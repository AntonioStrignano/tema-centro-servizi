<?php
declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

// Compatibilita con PHP < 8 per funzioni stringa usate nel tema.
if (! function_exists('str_contains')) {
    function str_contains(string $haystack, string $needle): bool
    {
        if ($needle === '') {
            return true;
        }

        return strpos($haystack, $needle) !== false;
    }
}

if (! function_exists('str_starts_with')) {
    function str_starts_with(string $haystack, string $needle): bool
    {
        if ($needle === '') {
            return true;
        }

        return strpos($haystack, $needle) === 0;
    }
}

if (! function_exists('str_ends_with')) {
    function str_ends_with(string $haystack, string $needle): bool
    {
        if ($needle === '') {
            return true;
        }

        return substr($haystack, -strlen($needle)) === $needle;
    }
}

$centro_servizi_includes = [
    '/inc/setup.php',
    '/inc/enqueue.php',
    '/inc/settings.php',
    '/inc/debug.php',
    '/inc/cpt-attivita.php',
    '/inc/cpt-trasparenza.php',
    '/inc/cpt-area-famiglie.php',
    '/inc/cpt-area-personale.php',
    '/inc/taxonomies.php',
    '/inc/acf-fields.php',
    '/inc/meta-boxes.php',
    '/inc/accessibility.php',
    '/inc/admin.php',
];

foreach ($centro_servizi_includes as $centro_servizi_file) {
    $centro_servizi_path = get_template_directory() . $centro_servizi_file;

    if (file_exists($centro_servizi_path)) {
        require_once $centro_servizi_path;
    }
}

add_filter('template_include', 'centro_servizi_map_template_from_subdirectory', 99);

function centro_servizi_map_template_from_subdirectory(string $template): string
{
    if (is_404()) {
        $error_template = get_template_directory() . '/templates/404.php';

        if (file_exists($error_template)) {
            return $error_template;
        }
    }

    if (is_post_type_archive('attivita')) {
        $archive_template = get_template_directory() . '/templates/archive-attivita.php';

        if (file_exists($archive_template)) {
            return $archive_template;
        }
    }

    if (is_post_type_archive('trasparenza')) {
        $archive_template = get_template_directory() . '/templates/archive-trasparenza.php';

        if (file_exists($archive_template)) {
            return $archive_template;
        }
    }

    if (is_post_type_archive('area-famiglie')) {
        $archive_template = get_template_directory() . '/templates/archive-area-famiglie.php';

        if (file_exists($archive_template)) {
            return $archive_template;
        }
    }

    if (is_post_type_archive('area-personale')) {
        $archive_template = get_template_directory() . '/templates/archive-area-personale.php';

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

    $custom = get_template_directory() . '/templates/' . basename($template);

    if (file_exists($custom)) {
        return $custom;
    }

    return $template;
}
