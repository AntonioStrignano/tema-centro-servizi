<?php
declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

add_action('wp_enqueue_scripts', 'centro_servizi_enqueue_assets');

function centro_servizi_enqueue_assets(): void
{
    $is_attivita_page = is_page('attivita');

    if (! is_admin() && ! $is_attivita_page) {
        wp_deregister_script('jquery');
    }

    $theme_dir = get_template_directory();
    $theme_uri = get_template_directory_uri();

    wp_enqueue_script(
        'centro-servizi-site-header',
        $theme_uri . '/assets/js/site-header.js',
        [],
        (string) filemtime($theme_dir . '/assets/js/site-header.js'),
        true
    );

    if ($is_attivita_page) {
        wp_enqueue_style('thickbox');
        wp_enqueue_script('thickbox');
        wp_enqueue_script(
            'centro-servizi-attivita-lightbox',
            $theme_uri . '/assets/js/attivita-lightbox.js',
            ['jquery', 'thickbox'],
            (string) filemtime($theme_dir . '/assets/js/attivita-lightbox.js'),
            true
        );
    }

    $is_bureaucratic_context = function_exists('centro_servizi_is_bureaucratic_context')
        && centro_servizi_is_bureaucratic_context();

    if (! is_front_page() || $is_bureaucratic_context) {
        return;
    }

    wp_enqueue_style(
        'centro-servizi-material-symbols',
        'https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap',
        [],
        null
    );

}
