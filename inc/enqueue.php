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
}
