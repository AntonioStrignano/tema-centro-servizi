<?php
declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

global $wp_query;

if ($wp_query->max_num_pages <= 1) {
    return;
}

$current = max(1, get_query_var('paged'));
$total = $wp_query->max_num_pages;
$base = get_pagenum_link(1);
$format = (strpos($base, '?') ? '&' : '?') . 'paged=%#%';

$paginate_args = [
    'base' => $base,
    'format' => $format,
    'current' => $current,
    'total' => $total,
    'type' => 'array',
    'prev_text' => '← Precedente',
    'next_text' => 'Successiva →',
    'before_page_number' => '<span class="screen-reader-text">Pagina </span>',
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
