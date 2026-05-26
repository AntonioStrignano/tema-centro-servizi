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
<?php get_template_part('partials/chrome-header'); ?>
<?php if (! is_front_page()) : ?>
    <?php get_template_part('partials/breadcrumb'); ?>
<?php endif; ?>
