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

    if (is_page() && function_exists('centro_servizi_get_legal_page_slugs')) {
        $slug = (string) get_post_field('post_name', get_queried_object_id());

        if (in_array($slug, centro_servizi_get_legal_page_slugs(), true)) {
            wp_enqueue_style(
                'centro-servizi-roboto-legal',
                'https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap',
                [],
                null
            );
        }
    }
}
