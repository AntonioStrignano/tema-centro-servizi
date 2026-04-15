<?php
declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

$template = get_template_directory() . '/templates/index.php';

if (file_exists($template)) {
    require $template;
    return;
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
<main id="contenuto" role="main">
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            <article <?php post_class('site-card'); ?>>
                <h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
                <?php the_excerpt(); ?>
            </article>
        <?php endwhile; ?>
    <?php else : ?>
        <p>Nessun contenuto disponibile.</p>
    <?php endif; ?>
</main>
<?php wp_footer(); ?>
</body>
</html>
