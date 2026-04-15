<?php
declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

add_action('after_setup_theme', 'centro_servizi_theme_setup');
add_action('init', 'centro_servizi_disable_comments_support', 20);
add_filter('comments_open', '__return_false', 20, 2);
add_filter('pings_open', '__return_false', 20, 2);

function centro_servizi_theme_setup(): void
{
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('automatic-feed-links');
    add_theme_support('html5', [
        'search-form',
        'gallery',
        'caption',
        'style',
        'script',
    ]);

    register_nav_menus([
        'primary' => 'Menu principale',
        'footer'  => 'Menu footer',
    ]);

    add_image_size('card-thumbnail', 400, 300, true);
    add_image_size('gallery-medium', 800, 600, true);
    add_image_size('hero-banner', 1200, 400, true);
}

function centro_servizi_disable_comments_support(): void
{
    $post_types = get_post_types_by_support(['comments']);

    foreach ($post_types as $post_type) {
        remove_post_type_support($post_type, 'comments');
        remove_post_type_support($post_type, 'trackbacks');
    }
}
