<?php
declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

add_action('admin_init', 'centro_servizi_ensure_attivita_page');
add_action('admin_menu', 'centro_servizi_register_attivita_admin_menu');

function centro_servizi_get_attivita_page(): ?WP_Post
{
    $page = get_page_by_path('attivita');

    return $page instanceof WP_Post ? $page : null;
}

function centro_servizi_ensure_attivita_page(): void
{
    if (! current_user_can('manage_options')) {
        return;
    }

    if (centro_servizi_get_attivita_page() instanceof WP_Post) {
        return;
    }

    $author_id = (int) get_current_user_id();
    if ($author_id <= 0) {
        $author_id = 1;
    }

    $page_id = wp_insert_post([
        'post_title'   => 'Attività',
        'post_name'    => 'attivita',
        'post_content' => '',
        'post_status'  => 'draft',
        'post_type'    => 'page',
        'post_author'  => $author_id,
    ], true);

    if (! is_wp_error($page_id) && (int) $page_id > 0) {
        update_post_meta((int) $page_id, '_wp_page_template', '');
    }
}

function centro_servizi_get_attivita_edit_url(): string
{
    $page = centro_servizi_get_attivita_page();

    if ($page instanceof WP_Post) {
        $edit_url = get_edit_post_link($page->ID, 'raw');
        if (is_string($edit_url) && $edit_url !== '') {
            return $edit_url;
        }
    }

    return admin_url('post-new.php?post_type=page');
}

function centro_servizi_register_attivita_admin_menu(): void
{
    add_menu_page(
        'Attività',
        'Attività',
        'edit_pages',
        'centro-servizi-attivita',
        'centro_servizi_render_attivita_admin_redirect',
        'dashicons-format-gallery',
        22
    );
}

function centro_servizi_render_attivita_admin_redirect(): void
{
    if (! current_user_can('edit_pages')) {
        wp_die('Accesso negato.');
    }

    $edit_url = centro_servizi_get_attivita_edit_url();

    if (is_admin()) {
        wp_safe_redirect($edit_url);
        exit;
    }
}

function centro_servizi_get_attivita_image_alt(array $image, string $section_title, int $index = 0): string
{
    $alt = trim((string) ($image['alt'] ?? ''));

    if ($alt !== '') {
        return $alt;
    }

    $section_title = trim(wp_strip_all_tags($section_title));

    if ($section_title !== '') {
        return 'bambini che fanno attività scolastica: ' . $section_title;
    }

    return 'bambini che fanno attività scolastica';
}
