<?php
declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

$post_id = isset($args['post_id']) ? (int) $args['post_id'] : get_the_ID();

if (! $post_id) {
    return;
}

$termine_display = isset($args['termine_display']) && $args['termine_display'] instanceof WP_Term
    ? $args['termine_display']
    : null;
$tag_anno = isset($args['tag_anno']) && is_string($args['tag_anno'])
    ? trim($args['tag_anno'])
    : '';
$titolo_custom = isset($args['titolo_custom']) && is_string($args['titolo_custom'])
    ? trim($args['titolo_custom'])
    : '';
$testo = isset($args['testo']) && is_string($args['testo'])
    ? trim($args['testo'])
    : '';
$allegato = isset($args['allegato']) && is_array($args['allegato'])
    ? $args['allegato']
    : [];
$contenuto = isset($args['contenuto']) && is_string($args['contenuto'])
    ? trim($args['contenuto'])
    : '';

$titolo_card = centro_servizi_archive_trasparenza_title($termine_display, $tag_anno, get_the_title($post_id));
?>
<article>
    <h2><?php echo esc_html($titolo_card); ?></h2>

    <?php if ($titolo_custom !== '') : ?>
    <p><strong><?php echo esc_html($titolo_custom); ?></strong></p>
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