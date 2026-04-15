<?php
declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

get_template_part('partials/header');
?>
<main class="site-main" id="contenuto-principale" role="main">
    <?php while (have_posts()) : the_post(); ?>
        <article <?php post_class('site-section'); ?>>
            <h1><?php the_title(); ?></h1>
            <?php the_content(); ?>
            <p><?php echo esc_html(centro_servizi_get_post_meta_text()); ?></p>
        </article>
    <?php endwhile; ?>
</main>
<?php
get_template_part('partials/footer');
