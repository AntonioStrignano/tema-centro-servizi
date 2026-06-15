<?php
declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

get_template_part('partials/header');
?>
<main class="site-main burocratica-singola" id="contenuto-principale" role="main">
    <section class="site-section">
        <div class="site-section__inner">
            <?php while (have_posts()) : the_post(); ?>
                <?php
                $post_id = get_the_ID();
                $post_type = get_post_type($post_id);
                $taxonomy = $post_type === 'area-personale' ? 'categoria-area-personale' : 'categoria-area-famiglia';
                $terms = get_the_terms($post_id, $taxonomy);
                $terms = is_wp_error($terms) || empty($terms) ? [] : $terms;

                if ($terms !== []) {
                    usort($terms, static function (WP_Term $left, WP_Term $right): int {
                        return strcmp($left->slug, $right->slug);
                    });
                }

                $display_term = $terms !== [] && $terms[0] instanceof WP_Term ? $terms[0] : null;
                $intro_text = centro_servizi_get_post_meta_string($post_id, 'testo');
                $attachment = centro_servizi_get_meta_file_link_data($post_id, 'allegato');
                ?>
                <article <?php post_class('burocratica-singola__article'); ?>>
                    <header class="burocratica-singola__header">
                        <p class="burocratica-singola__eyebrow"><?php echo esc_html(get_post_type_object($post_type)->labels->singular_name ?? 'Contenuto'); ?></p>
                        <h1 class="burocratica-singola__title"><?php the_title(); ?></h1>

                        <?php if ($display_term instanceof WP_Term) : ?>
                            <p class="burocratica-singola__subtitle"><?php echo esc_html($display_term->name); ?></p>
                        <?php endif; ?>
                    </header>

                    <div class="burocratica-singola__card">
                        <?php if ($intro_text !== '') : ?>
                            <p class="burocratica-singola__intro"><?php echo esc_html($intro_text); ?></p>
                        <?php endif; ?>

                        <?php if ($attachment !== []) : ?>
                            <p class="burocratica-singola__file">
                                <a href="<?php echo esc_url((string) $attachment['url']); ?>" target="_blank" rel="noopener noreferrer">
                                    <?php echo esc_html((string) $attachment['label']); ?> <span class="sr-only">(apre in nuova finestra)</span>
                                </a>
                            </p>
                        <?php endif; ?>

                        <div class="entry-content burocratica-singola__content">
                            <?php the_content(); ?>
                        </div>

                        <div class="burocratica-singola__meta">
                            <p>Pubblicato il <?php echo esc_html(get_the_date('j F Y', $post_id)); ?></p>
                            <p>Ultima modifica <?php echo esc_html(get_the_modified_date('j F Y', $post_id)); ?></p>
                        </div>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>
    </section>
</main>
<?php
get_template_part('partials/footer');