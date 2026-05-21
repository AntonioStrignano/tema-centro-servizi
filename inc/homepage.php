<?php
declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

function centro_servizi_get_homepage_title(): string
{
    $title = trim((string) get_option('centro_servizi_homepage_title', ''));

    return $title !== '' ? $title : get_bloginfo('name');
}

function centro_servizi_get_homepage_subtitle(): string
{
    $subtitle = trim((string) get_option('centro_servizi_homepage_subtitle', ''));

    return $subtitle !== '' ? $subtitle : get_bloginfo('description');
}

function centro_servizi_get_homepage_map_embed_url(): string
{
    $maps_embed_url = trim((string) get_option('centro_servizi_maps_embed_url', ''));

    if ($maps_embed_url !== '') {
        return $maps_embed_url;
    }

    $address_contact = centro_servizi_get_contact_by_type('address');
    $address_value = is_array($address_contact) ? trim((string) ($address_contact['value'] ?? '')) : '';

    if ($address_value === '') {
        return '';
    }

    return 'https://www.google.com/maps?q=' . rawurlencode($address_value) . '&z=15&output=embed';
}

function centro_servizi_get_homepage_contacts(): array
{
    $contacts = centro_servizi_get_contacts();
    if ($contacts === []) {
        return [];
    }

    $icon_map = [
        'address' => 'location_on',
        'phone'   => 'call',
        'fax'     => 'call',
        'email'   => 'mail',
        'pec'     => 'mail',
        'website' => 'language',
        'social'  => 'share',
    ];

    $normalized = [];

    foreach ($contacts as $contact) {
        if (! is_array($contact)) {
            continue;
        }

        $type = trim((string) ($contact['type'] ?? ''));
        $value = trim((string) ($contact['value'] ?? ''));

        if ($type === '' || $value === '') {
            continue;
        }

        $label = trim((string) ($contact['label'] ?? ''));
        if ($label === '') {
            $label = match ($type) {
                'address' => 'Sede Centrale',
                'phone'   => 'Telefono',
                'fax'     => 'Fax',
                'email'   => 'Email',
                'pec'     => 'PEC',
                'website' => 'Sito web',
                'social'  => 'Social',
                default   => ucfirst($type),
            };
        }

        $href = '';
        $external = false;

        if ($type === 'email' || $type === 'pec') {
            $href = 'mailto:' . antispambot($value);
        } elseif ($type === 'phone' || $type === 'fax') {
            $href = 'tel:' . preg_replace('/[^+\d]/', '', $value);
        } elseif ($type === 'website' || $type === 'social') {
            $href = $value;
            $external = true;
        }

        $normalized[] = [
            'type'     => $type,
            'label'    => $label,
            'value'    => $value,
            'href'     => $href,
            'external' => $external,
            'icon'     => $icon_map[$type] ?? 'info',
        ];
    }

    return $normalized;
}

function centro_servizi_get_homepage_latest_trasparenza_document(string $term_slug, string $fallback_label): array
{
    $fallback_url = get_post_type_archive_link('trasparenza');
    if (! is_string($fallback_url) || $fallback_url === '') {
        $fallback_url = home_url('/trasparenza/');
    }

    $document = [
        'title'   => $fallback_label,
        'url'     => $fallback_url,
        'summary' => '',
    ];

    $posts = get_posts([
        'post_type'      => 'trasparenza',
        'posts_per_page' => 1,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'no_found_rows'  => true,
        'tax_query'      => [[
            'taxonomy'         => 'contenutiammtrasp',
            'field'            => 'slug',
            'terms'            => $term_slug,
            'include_children' => true,
        ]],
    ]);

    if ($posts === [] || ! isset($posts[0]) || ! $posts[0] instanceof WP_Post) {
        return $document;
    }

    $post = $posts[0];
    $content = trim(wp_strip_all_tags((string) get_post_field('post_content', $post->ID)));

    $document['title'] = get_the_title($post);
    $document['url'] = get_permalink($post);
    $document['summary'] = $content !== '' ? wp_trim_words($content, 16) : '';

    return $document;
}
