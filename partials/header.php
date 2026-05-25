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
    <?php foreach (centro_servizi_get_theme_stylesheets() as $stylesheet) : ?>
        <link rel="stylesheet" id="<?php echo esc_attr(sanitize_title($stylesheet['label'])); ?>-css" href="<?php echo esc_url($stylesheet['href']); ?>" media="all">
    <?php endforeach; ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<?php get_template_part('partials/skip-links'); ?>
<?php if (function_exists('centro_servizi_is_bureaucratic_context') && centro_servizi_is_bureaucratic_context()) : ?>
<header class="hp-header" role="banner">
    <div class="hp-header__inner">
        <a class="hp-logo" href="<?php echo esc_url(home_url('/')); ?>" aria-label="<?php echo esc_attr(get_bloginfo('name')); ?>">
            <svg class="hp-logo__svg" viewBox="0 0 120 120" role="img" aria-hidden="true" focusable="false">
                <circle cx="60" cy="60" r="56" fill="#e8f2ef"/>
                <circle cx="60" cy="60" r="44" fill="#ffffff"/>
                <path d="M34 70L60 42L86 70V86H66V72H54V86H34Z" fill="#003342"/>
                <path d="M32 30H88" stroke="#436555" stroke-width="8" stroke-linecap="round"/>
                <path d="M40 20H80" stroke="#581a01" stroke-width="6" stroke-linecap="round"/>
            </svg>
        </a>

        <nav class="hp-header__nav" aria-label="Navigazione principale">
            <?php
            wp_nav_menu([
                'theme_location' => 'primary',
                'container'      => false,
                'menu_class'     => 'hp-nav-menu',
                'fallback_cb'    => false,
            ]);
            ?>
        </nav>

        <div class="hp-header__actions">
            <a class="btn btn--tertiary" href="<?php echo esc_url(home_url('/contatti/')); ?>">Contattaci</a>
        </div>
    </div>
</header>
<?php else : ?>
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
<?php endif; ?>
<?php get_template_part('partials/breadcrumb'); ?>
