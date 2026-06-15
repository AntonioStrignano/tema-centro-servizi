<?php
declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

$pagination_query = $args['query'] ?? null;

if (! $pagination_query instanceof WP_Query) {
    global $wp_query;
    $pagination_query = $wp_query;
}

if (! $pagination_query instanceof WP_Query || $pagination_query->max_num_pages <= 1) {
    return;
}

$current = max(
    1,
    (int) $pagination_query->get('paged'),
    (int) get_query_var('paged'),
    (int) get_query_var('page')
);
$total = (int) $pagination_query->max_num_pages;
$request_uri = isset($_SERVER['REQUEST_URI']) ? wp_unslash((string) $_SERVER['REQUEST_URI']) : '/';
$current_url = home_url($request_uri);
$base = remove_query_arg('paged', $current_url);
$base = add_query_arg('paged', '%#%', $base);

$paginate_args = [
    'base' => $base,
    'format' => '',
    'current' => $current,
    'total' => $total,
    'type' => 'array',
    'prev_text' => '← Precedente',
    'next_text' => 'Successiva →',
    'before_page_number' => '<span class="sr-only">Pagina </span>',
];

$pages = paginate_links($paginate_args);

if (empty($pages)) {
    return;
}
?>
<nav class="pagination-nav" aria-label="Navigazione pagine">
    <ul class="pagination">
        <?php foreach ($pages as $page) : ?>
            <li>
                <?php echo wp_kses_post($page); ?>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>
