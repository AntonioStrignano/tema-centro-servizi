<?php
declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

$centro_servizi_includes = [
    '/inc/setup.php',
    '/inc/theme-assets.php',
    '/inc/enqueue.php',
    '/inc/settings.php',
    '/inc/homepage.php',
    '/inc/cpt-attivita.php',
    '/inc/cpt-trasparenza.php',
    '/inc/cpt-area-famiglie.php',
    '/inc/cpt-area-personale.php',
    '/inc/taxonomies.php',
    '/inc/acf-fields.php',
    '/inc/meta-boxes.php',
    '/inc/accessibility.php',
    '/inc/admin.php',
    '/inc/search.php',
];

foreach ($centro_servizi_includes as $centro_servizi_file) {
    $centro_servizi_path = get_template_directory() . $centro_servizi_file;

    if (file_exists($centro_servizi_path)) {
        require_once $centro_servizi_path;
    }
}

add_filter('template_include', 'centro_servizi_map_template_from_subdirectory', 99);

function centro_servizi_get_legal_page_slugs(): array
{
    return [
        'privacy-policy',
        'cookie-policy',
        'dichiarazione-accessibilita',
        'whistleblowing',
        'obiettivi-accessibilita',
        'amministrazione-trasparente',
        'la-nostra-scuola',
    ];
}

function centro_servizi_map_template_from_subdirectory(string $template): string
{
    if (is_front_page()) {
        $front_page_template = get_template_directory() . '/templates/front-page.php';

        if (file_exists($front_page_template)) {
            return $front_page_template;
        }
    }

    if (is_404()) {
        $error_template = get_template_directory() . '/templates/404.php';

        if (file_exists($error_template)) {
            return $error_template;
        }
    }

    if (is_search()) {
        $search_template = get_template_directory() . '/templates/search.php';

        if (file_exists($search_template)) {
            return $search_template;
        }
    }

    if (is_post_type_archive('attivita')) {
        $archive_template = get_template_directory() . '/templates/archive-attivita.php';

        if (file_exists($archive_template)) {
            return $archive_template;
        }
    }

    if (is_post_type_archive('trasparenza')) {
        $archive_template = get_template_directory() . '/templates/archive-area-burocratica-common.php';

        if (file_exists($archive_template)) {
            return $archive_template;
        }
    }

    if (is_post_type_archive('area-famiglie')) {
        $archive_template = get_template_directory() . '/templates/archive-area-burocratica-common.php';

        if (file_exists($archive_template)) {
            return $archive_template;
        }
    }

    if (is_post_type_archive('area-personale')) {
        $archive_template = get_template_directory() . '/templates/archive-area-burocratica-common.php';

        if (file_exists($archive_template)) {
            return $archive_template;
        }
    }

    if (is_singular('attivita')) {
        $single_template = get_template_directory() . '/templates/single-attivita.php';

        if (file_exists($single_template)) {
            return $single_template;
        }
    }

    if (is_singular(['area-famiglie', 'area-personale'])) {
        $single_template = get_template_directory() . '/templates/single-area-burocratica.php';

        if (file_exists($single_template)) {
            return $single_template;
        }
    }

    // Pagine legali: template unico pulito
    if (is_page()) {
        $slug            = (string) get_post_field('post_name', get_queried_object_id());
        $legal_template  = get_template_directory() . '/templates/page-legale.php';
        $legal_page_slug = in_array($slug, centro_servizi_get_legal_page_slugs(), true);

        if ($legal_page_slug && file_exists($legal_template)) {
            return $legal_template;
        }
    }

    // Pagine con template slug-specifico in templates/page-{slug}.php
    if (is_page()) {
        $slug          = (string) get_post_field('post_name', get_queried_object_id());
        $slug_template = get_template_directory() . '/templates/page-' . $slug . '.php';

        if (file_exists($slug_template)) {
            return $slug_template;
        }

        $page_template = get_template_directory() . '/templates/page.php';

        if (file_exists($page_template)) {
            return $page_template;
        }
    }

    $custom = get_template_directory() . '/templates/' . basename($template);

    if (file_exists($custom)) {
        return $custom;
    }

    return $template;
}
