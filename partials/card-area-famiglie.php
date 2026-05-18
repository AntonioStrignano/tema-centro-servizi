<?php
declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

$post_id = isset($args['post_id']) ? (int) $args['post_id'] : get_the_ID();

if (! $post_id) {
    return;
}

if (isset($args['termine_display']) && $args['termine_display'] instanceof WP_Term) {
    $termine_display = $args['termine_display'];
} else {
    $terms = get_the_terms($post_id, 'categoria-area-famiglia');
    $terms = is_wp_error($terms) || empty($terms) ? [] : $terms;
    if ($terms !== []) {
        usort($terms, static function (WP_Term $left, WP_Term $right): int {
            return strcmp($left->slug, $right->slug);
        });
    }
    $termine_display = $terms !== [] && $terms[0] instanceof WP_Term ? $terms[0] : null;
}

$testo     = centro_servizi_get_post_meta_string($post_id, 'testo');
$allegato  = centro_servizi_get_meta_file_link_data($post_id, 'allegato');
$contenuto = trim((string) get_post_field('post_content', $post_id));
?>
<article class="site-card trasparenza-card">
    <h2 class="trasparenza-card__title"><?php echo esc_html(get_the_title($post_id)); ?></h2>

    <?php if ($termine_display instanceof WP_Term) : ?>
    <p class="trasparenza-card__subtitle"><strong><?php echo esc_html($termine_display->name); ?></strong></p>
    <?php endif; ?>

    <?php if ($testo !== '') : ?>
    <p class="trasparenza-card__text"><?php echo esc_html($testo); ?></p>
    <?php endif; ?>

    <?php if ($allegato !== []) : ?>
    <p class="trasparenza-card__file">
        <a href="<?php echo esc_url((string) $allegato['url']); ?>" target="_blank" rel="noopener noreferrer">
            <?php echo esc_html((string) $allegato['label']); ?> <span class="sr-only">(apre in nuova finestra)</span>
        </a>
    </p>
    <?php endif; ?>

    <?php if ($contenuto !== '') : ?>
    <div class="trasparenza-card__content"><?php echo apply_filters('the_content', $contenuto); ?></div>
    <?php endif; ?>

    <div class="trasparenza-card__meta">
        <p>Pubblicato il <?php echo esc_html(get_the_date('j F Y', $post_id)); ?></p>
        <p>Ultima modifica <?php echo esc_html(get_the_modified_date('j F Y', $post_id)); ?></p>
    </div>
</article>