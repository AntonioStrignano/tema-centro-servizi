<?php
declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

$post_id = isset($args['post_id']) ? (int) $args['post_id'] : get_the_ID();

if (! $post_id) {
    return;
}

$terms = get_the_terms($post_id, 'categoria-area-famiglia');
$terms = is_wp_error($terms) || empty($terms) ? [] : $terms;

if ($terms !== []) {
    usort($terms, static function (WP_Term $left, WP_Term $right): int {
        return strcmp($left->slug, $right->slug);
    });
}

$termine_display = $terms !== [] && $terms[0] instanceof WP_Term ? $terms[0] : null;
$testo = centro_servizi_get_post_meta_string($post_id, 'testo');
$allegato = centro_servizi_get_meta_file_link_data($post_id, 'allegato');
$contenuto = trim((string) get_post_field('post_content', $post_id));
?>
<article>
    <h2><?php echo esc_html(get_the_title($post_id)); ?></h2>

    <?php if ($termine_display instanceof WP_Term) : ?>
    <p><strong><?php echo esc_html($termine_display->name); ?></strong></p>
    <?php endif; ?>

    <?php if ($testo !== '') : ?>
    <p><?php echo esc_html($testo); ?></p>
    <?php endif; ?>

    <?php if ($allegato !== []) : ?>
    <p>
        <a href="<?php echo esc_url((string) $allegato['url']); ?>" target="_blank" rel="noopener noreferrer">
            <?php echo esc_html((string) $allegato['label']); ?> <span class="sr-only">(apre in nuova finestra)</span>
        </a>
    </p>
    <?php endif; ?>

    <?php if ($contenuto !== '') : ?>
    <div><?php echo apply_filters('the_content', $contenuto); ?></div>
    <?php endif; ?>

    <div style="margin-top: 1.5em; padding-top: 1em; border-top: 1px solid #ccc;">
        <p style="margin: 0.25em 0;">Pubblicato il <?php echo esc_html(get_the_date('j F Y', $post_id)); ?></p>
        <p style="margin: 0.25em 0;">Ultima modifica <?php echo esc_html(get_the_modified_date('j F Y', $post_id)); ?></p>
    </div>
</article>