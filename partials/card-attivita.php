<?php
declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

$post_id = isset($args['post_id']) ? (int) $args['post_id'] : get_the_ID();

if (! $post_id) {
    return;
}

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
<article <?php post_class('site-card attivita-card', $post_id); ?>>
    <a class="attivita-card__link" href="<?php echo esc_url(get_permalink($post_id)); ?>">
        <?php if (has_post_thumbnail($post_id)) : ?>
            <div class="attivita-card__media">
                <?php echo get_the_post_thumbnail($post_id, 'card-thumbnail', ['class' => 'attivita-card__image']); ?>
            </div>
        <?php endif; ?>

        <div class="attivita-card__content">
            <h2 class="attivita-card__title"><?php echo esc_html(get_the_title($post_id)); ?></h2>

            <dl class="attivita-card__meta">
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
                    <dd><time datetime="<?php echo esc_attr(get_the_date('c', $post_id)); ?>"><?php echo esc_html(get_the_date('j F Y', $post_id)); ?></time></dd>
                </div>
            </dl>
        </div>
    </a>
</article>