<?php
declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

function centro_servizi_get_skip_links(): array
{
    return [
        '#contenuto-principale' => 'Vai al contenuto principale',
        '#navigazione-principale' => 'Vai alla navigazione',
        '#footer-sito' => 'Vai al footer',
    ];
}

function centro_servizi_get_post_meta_text(?int $post_id = null): string
{
    $post_id = $post_id ?: get_the_ID();

    if (! $post_id) {
        return '';
    }

    $published = get_the_date('d/m/Y', $post_id);
    $modified = get_the_modified_date('d/m/Y', $post_id);

    return sprintf(
        'Pubblicato: %1$s | Modificato: %2$s',
        $published,
        $modified
    );
}

function centro_servizi_get_file_label(array $file, string $prefix = 'Scarica'): string
{
    $name = '';

    if (! empty($file['title'])) {
        $name = (string) $file['title'];
    } elseif (! empty($file['filename'])) {
        $name = (string) $file['filename'];
    }

    $extension = ! empty($file['filename'])
        ? strtoupper((string) pathinfo((string) $file['filename'], PATHINFO_EXTENSION))
        : '';

    $size = ! empty($file['filesize'])
        ? size_format((int) $file['filesize'])
        : '';

    $details = array_filter([$extension, $size]);

    if ($details === []) {
        return trim($prefix . ': ' . $name);
    }

    return sprintf('%1$s: %2$s (%3$s)', $prefix, $name, implode(', ', $details));
}
