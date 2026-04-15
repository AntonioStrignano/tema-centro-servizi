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

    wp_enqueue_style(
        'centro-servizi-theme',
        get_stylesheet_uri(),
        [],
        wp_get_theme()->get('Version')
    );

    wp_enqueue_style(
        'centro-servizi-site',
        get_template_directory_uri() . '/assets/css/site.css',
        ['centro-servizi-theme'],
        wp_get_theme()->get('Version')
    );

    wp_enqueue_style(
        'centro-servizi-css-debug',
        get_template_directory_uri() . '/assets/css/css-debug.css',
        ['centro-servizi-site'],
        wp_get_theme()->get('Version')
    );

    if (centro_servizi_is_bureaucratic_context()) {
        wp_enqueue_style(
            'centro-servizi-burocratico',
            get_template_directory_uri() . '/assets/css/area-burocratica.css',
            ['centro-servizi-site'],
            wp_get_theme()->get('Version')
        );
    }
}
