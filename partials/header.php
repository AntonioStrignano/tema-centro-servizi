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
    <?php $inline_css_bundle = centro_servizi_get_theme_inline_css_bundle(); ?>
    <?php if ($inline_css_bundle !== '') : ?>
        <style id="centro-servizi-inline-css">
<?php echo $inline_css_bundle; ?>
        </style>
    <?php endif; ?>
    <?php foreach (centro_servizi_get_theme_stylesheets() as $stylesheet) : ?>
        <link rel="stylesheet" id="<?php echo esc_attr(sanitize_title($stylesheet['label'])); ?>-css" href="<?php echo esc_url($stylesheet['href']); ?>" media="all">
    <?php endforeach; ?>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<?php $debug_context = centro_servizi_get_debug_context(); ?>
<?php get_template_part('partials/skip-links'); ?>
<aside class="debug-bar debug-bar-top" aria-label="Informazioni debug">
    <p><strong>Template:</strong> <?php echo esc_html($debug_context['template']); ?> <span class="debug-separator">|</span> <strong>Deploy:</strong> <?php echo esc_html($debug_context['deployed_at']); ?> <span class="debug-separator">|</span> <strong>Commit:</strong> <?php echo esc_html($debug_context['commit_title']); ?></p>
</aside>
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
