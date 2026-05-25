<?php
declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

/**
 * Dati statici rapidi mostrati nella admin bar.
 * Aggiorna qui branch/commit/data quando fai un rilascio.
 */
function centro_servizi_get_admin_debug_static_meta(): array
{
    return [
        'branch' => 'chore/theme-risanamento',
        'commit_short' => 'manuale',
        'commit_datetime' => '2026-05-25 00:00',
    ];
}

function centro_servizi_get_current_template_label(): string
{
    if (is_admin()) {
        return 'wp-admin';
    }

    global $template;

    if (! is_string($template) || $template === '') {
        return 'template non rilevato';
    }

    $normalized_template = wp_normalize_path($template);
    $normalized_theme_dir = wp_normalize_path(get_template_directory()) . '/';

    if (str_starts_with($normalized_template, $normalized_theme_dir)) {
        return substr($normalized_template, strlen($normalized_theme_dir));
    }

    return basename($normalized_template);
}

function centro_servizi_add_admin_bar_release_info(WP_Admin_Bar $wp_admin_bar): void
{
    if (! is_admin_bar_showing() || ! current_user_can('manage_options')) {
        return;
    }

    $meta = centro_servizi_get_admin_debug_static_meta();
    $template_label = centro_servizi_get_current_template_label();

    $title = sprintf(
        'Branch: %1$s | Commit: %2$s | Data: %3$s | Template: %4$s',
        $meta['branch'],
        $meta['commit_short'],
        $meta['commit_datetime'],
        $template_label
    );

    $wp_admin_bar->add_node([
        'id' => 'centro-servizi-release-info',
        'parent' => 'top-secondary',
        'title' => esc_html($title),
        'href' => false,
        'meta' => [
            'title' => 'Info tecniche tema',
        ],
    ]);
}

add_action('admin_bar_menu', 'centro_servizi_add_admin_bar_release_info', 999);
