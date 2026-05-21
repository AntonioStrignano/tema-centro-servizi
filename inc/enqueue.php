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

    if (! is_front_page()) {
        return;
    }

    $theme_dir = get_template_directory();
    $theme_uri = get_template_directory_uri();

    wp_enqueue_style(
        'centro-servizi-material-symbols',
        'https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap',
        [],
        null
    );

    wp_enqueue_style(
        'centro-servizi-stitch-layout',
        $theme_uri . '/assets/css/stitch-layout.css',
            ['centro-servizi-material-symbols'],
        (string) filemtime($theme_dir . '/assets/css/stitch-layout.css')
    );

    wp_enqueue_style(
        'centro-servizi-stitch-header-footer',
        $theme_uri . '/assets/css/stitch-header-footer.css',
        ['centro-servizi-stitch-layout'],
        (string) filemtime($theme_dir . '/assets/css/stitch-header-footer.css')
    );

    wp_enqueue_style(
        'centro-servizi-stitch-homepage',
        $theme_uri . '/assets/css/stitch-homepage.css',
        ['centro-servizi-stitch-header-footer'],
        (string) filemtime($theme_dir . '/assets/css/stitch-homepage.css')
    );

    wp_enqueue_script(
        'centro-servizi-stitch-homepage',
        $theme_uri . '/assets/js/stitch-homepage.js',
        [],
        (string) filemtime($theme_dir . '/assets/js/stitch-homepage.js'),
        true
    );
}
