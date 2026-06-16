<?php
declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

add_action('pre_get_posts', 'centro_servizi_filter_search_post_types');
add_filter('posts_search', 'centro_servizi_extend_search_with_taxonomy_terms', 20, 2);

function centro_servizi_get_search_post_type_map(): array
{
    return [
        'page'           => 'Pagine',
        'trasparenza'    => 'Amministrazione Trasparente',
        'area-famiglie'  => 'Area Famiglie',
        'area-personale' => 'Area Personale',
    ];
}

function centro_servizi_get_search_allowed_post_types(): array
{
    return array_keys(centro_servizi_get_search_post_type_map());
}

function centro_servizi_get_search_selected_post_types(): array
{
    $allowed = centro_servizi_get_search_allowed_post_types();
    $raw = $_GET['post_type'] ?? [];

    if (is_string($raw) && $raw !== '') {
        $raw = [$raw];
    }

    if (! is_array($raw)) {
        return $allowed;
    }

    $selected = array_values(array_intersect($allowed, array_map('sanitize_key', $raw)));

    return $selected !== [] ? $selected : $allowed;
}

function centro_servizi_filter_search_post_types(WP_Query $query): void
{
    if (is_admin() || ! $query->is_main_query() || ! $query->is_search()) {
        return;
    }

    $query->set('post_type', centro_servizi_get_search_selected_post_types());
}

function centro_servizi_get_search_taxonomies(): array
{
    return [
        'contenutiammtrasp',
        'annoscolastico',
        'categoria-area-famiglia',
        'categoria-area-personale',
    ];
}

function centro_servizi_extend_search_with_taxonomy_terms(string $search, WP_Query $query): string
{
    if (is_admin() || ! $query->is_main_query() || ! $query->is_search()) {
        return $search;
    }

    global $wpdb;

    $raw_search = (string) $query->get('s');

    if ($raw_search === '') {
        return $search;
    }

    $search_terms = $query->get('search_terms');

    if (! is_array($search_terms) || $search_terms === []) {
        $search_terms = [trim($raw_search)];
    }

    $search_terms = array_values(array_filter(array_map(
        static fn($term): string => trim((string) $term),
        $search_terms
    )));

    if ($search_terms === []) {
        return $search;
    }

    $taxonomies = array_values(array_filter(
        centro_servizi_get_search_taxonomies(),
        static fn(string $taxonomy): bool => taxonomy_exists($taxonomy)
    ));

    if ($taxonomies === []) {
        return $search;
    }

    $escaped_taxonomies = array_map(
        static fn(string $taxonomy): string => "'" . esc_sql($taxonomy) . "'",
        $taxonomies
    );

    $taxonomy_sql = implode(', ', $escaped_taxonomies);
    $clauses = [];

    foreach ($search_terms as $term) {
        $like = '%' . $wpdb->esc_like($term) . '%';

        $clauses[] = $wpdb->prepare(
            "(
                {$wpdb->posts}.post_title LIKE %s
                OR {$wpdb->posts}.post_excerpt LIKE %s
                OR {$wpdb->posts}.post_content LIKE %s
                OR EXISTS (
                    SELECT 1
                    FROM {$wpdb->term_relationships} tr
                    INNER JOIN {$wpdb->term_taxonomy} tt
                        ON tt.term_taxonomy_id = tr.term_taxonomy_id
                    INNER JOIN {$wpdb->terms} t
                        ON t.term_id = tt.term_id
                    WHERE tr.object_id = {$wpdb->posts}.ID
                        AND tt.taxonomy IN ({$taxonomy_sql})
                        AND (
                            t.name LIKE %s
                            OR t.slug LIKE %s
                            OR tt.description LIKE %s
                        )
                )
            )",
            $like,
            $like,
            $like,
            $like,
            $like,
            $like
        );
    }

    if ($clauses === []) {
        return $search;
    }

    $search = ' AND (' . implode(' AND ', $clauses) . ')';

    if (! is_user_logged_in()) {
        $search .= " AND ({$wpdb->posts}.post_password = '')";
    }

    return $search;
}

function centro_servizi_get_search_type_label(string $post_type): string
{
    $map = centro_servizi_get_search_post_type_map();

    return $map[$post_type] ?? ucfirst(str_replace('-', ' ', $post_type));
}

function centro_servizi_group_search_posts(array $posts): array
{
    $grouped = [];

    foreach ($posts as $post) {
        if (! $post instanceof WP_Post) {
            continue;
        }

        $post_type = get_post_type($post);

        if (! is_string($post_type) || $post_type === '') {
            continue;
        }

        if (! isset($grouped[$post_type])) {
            $grouped[$post_type] = [];
        }

        $grouped[$post_type][] = $post;
    }

    $ordered = [];

    foreach (centro_servizi_get_search_allowed_post_types() as $post_type) {
        if (isset($grouped[$post_type])) {
            $ordered[$post_type] = $grouped[$post_type];
        }
    }

    foreach ($grouped as $post_type => $items) {
        if (! isset($ordered[$post_type])) {
            $ordered[$post_type] = $items;
        }
    }

    return $ordered;
}

function centro_servizi_get_search_result_excerpt(int $post_id): string
{
    $excerpt = trim((string) get_the_excerpt($post_id));

    if ($excerpt !== '') {
        return $excerpt;
    }

    $content = trim(wp_strip_all_tags((string) get_post_field('post_content', $post_id)));

    if ($content === '') {
        return '';
    }

    return wp_trim_words($content, 28, '...');
}

function centro_servizi_search_clean_term_name(string $name): string
{
    $trimmed_name = trim($name);

    // Preserva etichette di anni scolastici come 2025/2026 o 2025-2026.
    if (preg_match('/^\d{4}\s*[\/-]\s*\d{4}$/u', $trimmed_name) === 1) {
        return $trimmed_name;
    }

    $clean = preg_replace('/^\d+[\s._-]*/u', '', $trimmed_name);

    if (! is_string($clean)) {
        return $trimmed_name;
    }

    $clean = trim($clean);

    return $clean !== '' ? $clean : $trimmed_name;
}

function centro_servizi_search_get_term_display_name(WP_Term $term): string
{
    if ($term->taxonomy === 'contenutiammtrasp') {
        $aliases = [
            'immobili' => 'Contratti fitto',
            'immobile' => 'Contratti fitto',
            'organizzazione' => 'Direzione e segreteria',
            'autorizzazioni' => 'Permessi e autorizzazioni',
        ];

        if (isset($aliases[$term->slug])) {
            return $aliases[$term->slug];
        }
    }

    return centro_servizi_search_clean_term_name($term->name);
}

function centro_servizi_search_get_terms_labels(int $post_id, string $taxonomy): array
{
    $terms = get_the_terms($post_id, $taxonomy);

    if (is_wp_error($terms) || empty($terms)) {
        return [];
    }

    $labels = array_values(array_filter(array_map(
        static function ($term): string {
            return $term instanceof WP_Term
                ? centro_servizi_search_get_term_display_name($term)
                : '';
        },
        $terms
    )));

    $labels = array_values(array_unique($labels));

    natcasesort($labels);

    return array_values($labels);
}

function centro_servizi_search_get_first_term_slug(int $post_id, string $taxonomy): string
{
    $terms = get_the_terms($post_id, $taxonomy);

    if (is_wp_error($terms) || empty($terms)) {
        return '';
    }

    usort($terms, static function (WP_Term $left, WP_Term $right): int {
        return strcmp($left->slug, $right->slug);
    });

    return $terms[0] instanceof WP_Term ? (string) $terms[0]->slug : '';
}

function centro_servizi_search_get_trasparenza_assigned_terms(int $post_id): array
{
    $terms = get_the_terms($post_id, 'contenutiammtrasp');

    if (is_wp_error($terms) || empty($terms)) {
        return [];
    }

    return $terms;
}

function centro_servizi_search_get_trasparenza_display_term(int $post_id): ?WP_Term
{
    $terms = centro_servizi_search_get_trasparenza_assigned_terms($post_id);

    if ($terms === []) {
        return null;
    }

    usort($terms, static function (WP_Term $left, WP_Term $right): int {
        $left_depth = count(get_ancestors($left->term_id, 'contenutiammtrasp', 'taxonomy'));
        $right_depth = count(get_ancestors($right->term_id, 'contenutiammtrasp', 'taxonomy'));

        if ($left_depth !== $right_depth) {
            return $right_depth <=> $left_depth;
        }

        return strcmp($left->slug, $right->slug);
    });

    return $terms[0] instanceof WP_Term ? $terms[0] : null;
}

function centro_servizi_search_get_trasparenza_term_label(?WP_Term $term): string
{
    if (! $term instanceof WP_Term) {
        return '';
    }

    if ($term->parent === 0) {
        return centro_servizi_search_clean_term_name($term->name);
    }

    $parents = get_ancestors($term->term_id, 'contenutiammtrasp', 'taxonomy');
    $labels = [];

    foreach (array_reverse($parents) as $parent_id) {
        $parent_term = get_term($parent_id, 'contenutiammtrasp');

        if ($parent_term instanceof WP_Term) {
            $labels[] = centro_servizi_search_get_term_display_name($parent_term);
        }
    }

    $labels[] = centro_servizi_search_get_term_display_name($term);

    return implode(' / ', $labels);
}

function centro_servizi_get_search_result_title(int $post_id): string
{
    $post_type = get_post_type($post_id);
    $fallback = get_the_title($post_id);

    if ($post_type !== 'trasparenza') {
        return $fallback;
    }

    $display_term = centro_servizi_search_get_trasparenza_display_term($post_id);
    $tag_anno = trim(centro_servizi_get_post_meta_string($post_id, 'tag_anno'));
    $parts = array_filter([
        centro_servizi_search_get_trasparenza_term_label($display_term),
        $tag_anno,
    ]);

    return $parts !== [] ? implode(' - ', $parts) : $fallback;
}

function centro_servizi_get_search_result_url(int $post_id): string
{
    $post_type = get_post_type($post_id);

    if (! is_string($post_type) || $post_type === '') {
        return (string) get_permalink($post_id);
    }

    if ($post_type !== 'trasparenza') {
        return (string) get_permalink($post_id);
    }

    $archive_url = get_post_type_archive_link('trasparenza');

    if (! is_string($archive_url) || $archive_url === '') {
        return (string) home_url('/trasparenza/');
    }

    $display_term = centro_servizi_search_get_trasparenza_display_term($post_id);
    $selected_cat = $display_term instanceof WP_Term ? (string) $display_term->slug : '';
    $selected_year = centro_servizi_search_get_first_term_slug($post_id, 'annoscolastico');
    $query_args = [];

    if ($selected_cat !== '') {
        $query_args['cat'] = $selected_cat;
    }

    if ($selected_year !== '') {
        $query_args['anno'] = $selected_year;
    }

    return $query_args !== [] ? (string) add_query_arg($query_args, $archive_url) : $archive_url;
}

function centro_servizi_get_search_result_context(int $post_id): array
{
    $post_type = get_post_type($post_id);

    if (! is_string($post_type) || $post_type === '') {
        return [];
    }

    $context_map = [];

    if ($post_type === 'area-famiglie') {
        $context_map = [
            'Categoria' => centro_servizi_search_get_terms_labels($post_id, 'categoria-area-famiglia'),
        ];
    } elseif ($post_type === 'area-personale') {
        $context_map = [
            'Categoria' => centro_servizi_search_get_terms_labels($post_id, 'categoria-area-personale'),
        ];
    }

    $items = [];

    foreach ($context_map as $label => $values) {
        if ($values === []) {
            continue;
        }

        $items[] = [
            'label' => $label,
            'values' => $values,
        ];
    }

    return $items;
}