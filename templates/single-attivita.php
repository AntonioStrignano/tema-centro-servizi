<?php
declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

get_template_part('partials/header');
?>
<main class="site-main" id="contenuto-principale" role="main">
    <?php while (have_posts()) : the_post(); ?>
        <?php
        $post_id = get_the_ID();
        $school_year_terms = get_the_terms($post_id, 'anno-scol-attivita');
        $school_year_terms = is_wp_error($school_year_terms) || empty($school_year_terms) ? [] : $school_year_terms;

        $section_terms = get_the_terms($post_id, 'sezioni');
        $section_terms = is_wp_error($section_terms) || empty($section_terms) ? [] : $section_terms;

        $school_year_labels = array_map(static function (WP_Term $term): string {
            return $term->name;
        }, $school_year_terms);

        $section_labels = array_map(static function (WP_Term $term): string {
            return $term->name;
        }, $section_terms);
        ?>
        <article <?php post_class('site-section attivita-singola'); ?>>
            <header class="attivita-singola__header">
                <h1><?php the_title(); ?></h1>

                <dl class="attivita-singola__meta">
                    <?php if ($section_labels !== []) : ?>
                        <div>
                            <dt>Sezione</dt>
                            <dd><?php echo esc_html(implode(', ', $section_labels)); ?></dd>
                        </div>
                    <?php endif; ?>

                    <?php if ($school_year_labels !== []) : ?>
                        <div>
                            <dt>Anno scolastico</dt>
                            <dd><?php echo esc_html(implode(', ', $school_year_labels)); ?></dd>
                        </div>
                    <?php endif; ?>

                    <div>
                        <dt>Pubblicato</dt>
                        <dd><time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date('j F Y')); ?></time></dd>
                    </div>
                </dl>
            </header>

            <div class="attivita-singola__content">
                <?php the_content(); ?>
            </div>
        </article>
    <?php endwhile; ?>
</main>
<?php
get_template_part('partials/footer');