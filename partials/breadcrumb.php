<?php
declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

$items = [
    [
        'label' => 'Home',
        'url'   => home_url('/'),
    ],
];

if (is_home() || is_front_page()) {
    $items[] = [
        'label' => get_bloginfo('name'),
        'url'   => '',
    ];
} elseif (is_page()) {
    $ancestors = array_reverse(get_post_ancestors(get_the_ID()));

    foreach ($ancestors as $ancestor_id) {
        $items[] = [
            'label' => get_the_title($ancestor_id),
            'url'   => get_permalink($ancestor_id),
        ];
    }

    $items[] = [
        'label' => get_the_title(),
        'url'   => '',
    ];
} elseif (is_singular()) {
    $post_type = get_post_type_object((string) get_post_type());

    if ($post_type && $post_type->has_archive) {
        $items[] = [
            'label' => $post_type->labels->name,
            'url'   => get_post_type_archive_link($post_type->name),
        ];
    }

    $items[] = [
        'label' => get_the_title(),
        'url'   => '',
    ];
} elseif (is_post_type_archive()) {
    $post_type_name = get_query_var('post_type');

    if (is_array($post_type_name)) {
        $post_type_name = reset($post_type_name);
    }

    $post_type = get_post_type_object((string) $post_type_name);

    if ($post_type) {
        $items[] = [
            'label' => $post_type->labels->name,
            'url'   => '',
        ];
    }
} elseif (is_archive()) {
    $items[] = [
        'label' => post_type_archive_title('', false),
        'url'   => '',
    ];
} elseif (is_search()) {
    $items[] = [
        'label' => 'Risultati ricerca',
        'url'   => '',
    ];
} elseif (is_404()) {
    $items[] = [
        'label' => 'Pagina non trovata',
        'url'   => '',
    ];
}

if (count($items) < 2) {
    return;
}
?>
<nav class="site-breadcrumb" aria-label="Breadcrumb">
    <ol class="breadcrumb">
        <?php foreach ($items as $index => $item) : ?>
            <?php $is_last = $index === array_key_last($items); ?>
            <li>
                <?php if (! $is_last && $item['url']) : ?>
                    <a href="<?php echo esc_url($item['url']); ?>"><?php echo esc_html($item['label']); ?></a>
                <?php else : ?>
                    <span aria-current="page"><?php echo esc_html($item['label']); ?></span>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ol>
</nav>
