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
        'attivita'       => 'Attivita',
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
        'anno-scol-attivita',
        'sezioni',
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
    $clean = preg_replace('/^\d+[\s._-]*/u', '', trim($name));

    if (! is_string($clean)) {
        return trim($name);
    }

    $clean = trim($clean);

    return $clean !== '' ? $clean : trim($name);
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

function centro_servizi_get_search_result_context(int $post_id): array
{
    $post_type = get_post_type($post_id);

    if (! is_string($post_type) || $post_type === '') {
        return [];
    }

    $context_map = [];

    if ($post_type === 'trasparenza') {
        $context_map = [
            'Categoria' => centro_servizi_search_get_terms_labels($post_id, 'contenutiammtrasp'),
            'Anno scolastico' => centro_servizi_search_get_terms_labels($post_id, 'annoscolastico'),
        ];
    } elseif ($post_type === 'attivita') {
        $context_map = [
            'Sezione' => centro_servizi_search_get_terms_labels($post_id, 'sezioni'),
            'Anno scolastico' => centro_servizi_search_get_terms_labels($post_id, 'anno-scol-attivita'),
        ];
    } elseif ($post_type === 'area-famiglie') {
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