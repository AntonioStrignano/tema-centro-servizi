<?php
declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<?php get_template_part('partials/skip-links'); ?>
<header class="site-header" id="top" role="banner">
    <div class="site-branding">
        <p><a href="<?php echo esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a></p>
        <p><?php bloginfo('description'); ?></p>
    </div>

    <nav class="site-navigation" id="navigazione-principale" role="navigation" aria-label="Menu principale">
        <?php
        wp_nav_menu([
            'theme_location' => 'primary',
            'container'      => false,
            'fallback_cb'    => 'wp_page_menu',
            'menu_class'     => 'menu',
        ]);
        ?>
    </nav>

    <div class="site-search">
        <?php get_search_form(); ?>
    </div>
</header>
<?php get_template_part('partials/breadcrumb'); ?>
