<?php
declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

get_template_part('partials/header');
?>
<main class="site-main pagina-legale-sober" id="contenuto-principale" role="main">
    <?php while (have_posts()) : the_post(); ?>
        <?php
        $modified_timestamp = (int) get_post_modified_time('U', true);
        $modified_iso       = get_post_modified_time(DATE_W3C, true) ?: '';
        $modified_display   = $modified_timestamp > 0
            ? wp_date('d/m/Y', $modified_timestamp)
            : '';
        ?>
        <article <?php post_class('site-section pagina-legale-sober__article'); ?>>
            <header class="pagina-legale-sober__header">
                <h1 class="pagina-legale-sober__title"><?php the_title(); ?></h1>
                <?php if ($modified_display !== '') : ?>
                    <p class="pagina-legale-sober__meta">
                        <strong><?php esc_html_e('Ultimo aggiornamento:', 'tema-centro-servizi'); ?></strong>
                        <time datetime="<?php echo esc_attr($modified_iso); ?>"><?php echo esc_html($modified_display); ?></time>
                    </p>
                <?php endif; ?>
            </header>

            <div class="entry-content pagina-legale-sober__content">
                <?php the_content(); ?>
            </div>
        </article>
    <?php endwhile; ?>
</main>
<?php
get_template_part('partials/footer');
