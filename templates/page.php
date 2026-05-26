<?php
declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

get_template_part('partials/header');
?>
<main class="site-main page-basic" id="contenuto-principale" role="main">
    <section class="site-section page-basic__section">
        <div class="site-section__inner page-basic__inner">
            <?php while (have_posts()) : the_post(); ?>
                <article <?php post_class('page-basic__article'); ?>>
                    <header class="page-basic__header">
                        <h1 class="page-basic__title"><?php the_title(); ?></h1>
                    </header>

                    <?php if (is_page('contatti')) : ?>
                        <?php get_template_part('partials/page', 'contatti-recapiti'); ?>
                    <?php endif; ?>

                    <div class="entry-content page-basic__content">
                        <?php the_content(); ?>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>
    </section>
</main>
<?php
get_template_part('partials/footer');
