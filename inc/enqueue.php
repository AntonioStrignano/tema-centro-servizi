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

    wp_enqueue_style(
        'centro-servizi-site',
        get_template_directory_uri() . '/assets/css/site.css',
        ['centro-servizi-theme'],
        $site_version
    );

    wp_enqueue_style(
        'centro-servizi-css-debug',
        get_template_directory_uri() . '/assets/css/css-debug.css',
        ['centro-servizi-site'],
        $debug_version
    );

    if (centro_servizi_is_bureaucratic_context()) {
        wp_enqueue_style(
            'centro-servizi-burocratico',
            get_template_directory_uri() . '/assets/css/area-burocratica.css',
            ['centro-servizi-site'],
            $bureaucratic_version
        );
    }
}
