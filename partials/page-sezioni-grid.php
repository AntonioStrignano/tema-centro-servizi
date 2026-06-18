<?php
declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

$args = is_array($args ?? null) ? $args : [];
$items = isset($args['items']) && is_array($args['items']) ? $args['items'] : [];
$grid_class = isset($args['grid_class']) ? (string) $args['grid_class'] : '';
$item_class = isset($args['item_class']) ? (string) $args['item_class'] : '';
$figure_class = isset($args['figure_class']) ? (string) $args['figure_class'] : '';
$image_class = isset($args['image_class']) ? (string) $args['image_class'] : '';
$content_class = isset($args['content_class']) ? (string) $args['content_class'] : '';
$title_class = isset($args['title_class']) ? (string) $args['title_class'] : '';
$paragraph_class = isset($args['paragraph_class']) ? (string) $args['paragraph_class'] : '';

if ($items === [] || $grid_class === '' || $item_class === '') {
    return;
}
?>
<div class="<?php echo esc_attr($grid_class); ?>">
    <?php foreach ($items as $entry) : ?>
        <?php
        $titolo = isset($entry['titolo']) ? (string) $entry['titolo'] : '';
        $immagine = isset($entry['immagine']) && is_array($entry['immagine'])
            ? $entry['immagine']
            : null;
        $paragrafo = isset($entry['paragrafo']) ? (string) $entry['paragrafo'] : '';

        if ($titolo === '') {
            continue;
        }
        ?>
        <div class="<?php echo esc_attr($item_class); ?>">
            <?php if ($immagine !== null && isset($immagine['url'])) : ?>
                <figure class="<?php echo esc_attr($figure_class); ?>">
                    <img
                        src="<?php echo esc_url((string) $immagine['url']); ?>"
                        alt="<?php echo esc_attr($titolo); ?>"
                        class="<?php echo esc_attr($image_class); ?>"
                        loading="lazy"
                    />
                </figure>
            <?php endif; ?>

            <div class="<?php echo esc_attr($content_class); ?>">
                <h2 class="<?php echo esc_attr($title_class); ?>"><?php echo esc_html($titolo); ?></h2>

                <?php if ($paragrafo !== '') : ?>
                    <div class="<?php echo esc_attr($paragraph_class); ?>">
                        <?php echo wp_kses_post(wpautop($paragrafo)); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>