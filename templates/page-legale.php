<?php
declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

get_template_part('partials/header');
?>
<main class="site-main pagina-legale-sober" id="contenuto-principale" role="main">
    <section class="site-section">
        <div class="site-section__inner">
            <?php while (have_posts()) : the_post(); ?>
                <article <?php post_class('pagina-legale-sober__article'); ?>>
                    <h1 class="pagina-legale-sober__title"><?php the_title(); ?></h1>
                    <div class="entry-content pagina-legale-sober__content">
                        <?php the_content(); ?>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>
    </section>
</main>
<?php
get_template_part('partials/footer');
