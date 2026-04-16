<?php
declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

get_template_part('partials/header');
?>
<main class="site-main" id="contenuto-principale" role="main">
    <section class="site-section">
        <h1><?php echo is_home() ? 'Articoli' : 'Archivio'; ?></h1>

        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>
                <article <?php post_class('site-card'); ?>>
                    <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    <?php echo centro_servizi_render_custom_fields_preview(); ?>
                    <p><?php echo esc_html(centro_servizi_get_post_meta_text()); ?></p>
                    <?php the_excerpt(); ?>
                </article>
            <?php endwhile; ?>

            <?php the_posts_navigation(); ?>
        <?php else : ?>
            <p>Nessun contenuto disponibile.</p>
        <?php endif; ?>
    </section>
</main>
<?php
get_template_part('partials/footer');
