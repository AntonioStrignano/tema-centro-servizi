<?php
declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

add_action('wp_enqueue_scripts', 'centro_servizi_enqueue_assets');

function centro_servizi_enqueue_assets(): void
{
    if (! is_admin()) {
        wp_deregister_script('jquery');
    }

    $theme_version = centro_servizi_get_asset_version(get_stylesheet_directory() . '/style.css');
    $site_version = centro_servizi_get_asset_version(get_template_directory() . '/assets/css/site.css');
    $debug_version = centro_servizi_get_asset_version(get_template_directory() . '/assets/css/css-debug.css');
    $bureaucratic_version = centro_servizi_get_asset_version(get_template_directory() . '/assets/css/area-burocratica.css');

    wp_enqueue_style(
        'centro-servizi-theme',
        get_stylesheet_uri(),
        [],
        $theme_version
    );

    $css_bundle = [];

    $site_css = centro_servizi_read_css_file(get_template_directory() . '/assets/css/site.css');

    if ($site_css !== '') {
        $css_bundle[] = $site_css;
    }

    $debug_css = centro_servizi_read_css_file(get_template_directory() . '/assets/css/css-debug.css');

    if ($debug_css !== '') {
        $css_bundle[] = $debug_css;
    }

    if (centro_servizi_is_bureaucratic_context()) {
        $bureaucratic_css = centro_servizi_read_css_file(get_template_directory() . '/assets/css/area-burocratica.css');

        if ($bureaucratic_css !== '') {
            $css_bundle[] = $bureaucratic_css;
        }
    }

    if ($css_bundle !== []) {
        wp_add_inline_style('centro-servizi-theme', implode("\n\n", $css_bundle));
    }
}
