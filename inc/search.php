<?php
declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

add_action('pre_get_posts', 'centro_servizi_filter_search_post_types');

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