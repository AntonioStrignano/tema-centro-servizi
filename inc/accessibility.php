<?php
declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

add_filter('post_gallery', 'centro_servizi_render_attivita_gallery', 10, 3);

function centro_servizi_render_attivita_gallery($output, $attr, $instance): string
{
    if (! is_singular('attivita')) {
        return is_string($output) ? $output : '';
    }

    if (! is_array($attr)) {
        return is_string($output) ? $output : '';
    }

    $ids = [];

    if (isset($attr['ids']) && is_string($attr['ids']) && $attr['ids'] !== '') {
        $ids = array_values(array_filter(array_map('absint', explode(',', $attr['ids']))));
    }

    if ($ids === []) {
        return is_string($output) ? $output : '';
    }

    $html = '<div class="gallery gallery-columns-3">';

    foreach ($ids as $attachment_id) {
        $image_html = wp_get_attachment_image($attachment_id, 'large');

        if (! is_string($image_html) || $image_html === '') {
            continue;
        }

        $full_url = wp_get_attachment_url($attachment_id);

        if (! is_string($full_url) || $full_url === '') {
            continue;
        }

        $caption = wp_get_attachment_caption($attachment_id);

        $html .= '<figure class="gallery-item">';
        $html .= '<div class="gallery-icon">';
        $html .= '<a href="' . esc_url($full_url) . '" target="_blank" rel="noopener noreferrer">' . $image_html . '</a>';
        $html .= '</div>';

        if (is_string($caption) && $caption !== '') {
            $html .= '<figcaption class="wp-caption-text gallery-caption">' . esc_html($caption) . '</figcaption>';
        }

        $html .= '</figure>';
    }

    $html .= '</div>';

    return $html;
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

function centro_servizi_get_post_meta_string(int $post_id, string $meta_key): string
{
    $value = get_post_meta($post_id, $meta_key, true);

    if (! is_scalar($value)) {
        return '';
    }

    return trim((string) $value);
}

function centro_servizi_get_meta_file_link_data(int $post_id, string $meta_key): array
{
    $raw = get_post_meta($post_id, $meta_key, true);

    if (! is_scalar($raw)) {
        return [];
    }

    $raw_text = trim((string) $raw);

    if ($raw_text === '') {
        return [];
    }

    if (ctype_digit($raw_text)) {
        $attachment_id = (int) $raw_text;
        $url = wp_get_attachment_url($attachment_id);

        if (! is_string($url) || $url === '') {
            return [];
        }

        $filename = basename(get_attached_file($attachment_id) ?: $url);
        $extension = strtoupper((string) pathinfo($filename, PATHINFO_EXTENSION));
        $filesize = get_post_meta($attachment_id, '_wp_attachment_metadata', true);
        $size_text = '';

        if (is_array($filesize) && isset($filesize['filesize'])) {
            $size_text = size_format((int) $filesize['filesize']);
        }

        $details = array_filter([$extension, $size_text]);
        $label = $filename !== '' ? $filename : 'Documento';

        if ($details !== []) {
            $label .= ' (' . implode(', ', $details) . ')';
        }

        return [
            'url' => $url,
            'label' => $label,
        ];
    }

    if (filter_var($raw_text, FILTER_VALIDATE_URL) === false) {
        return [];
    }

    $filename = basename((string) wp_parse_url($raw_text, PHP_URL_PATH));
    $extension = strtoupper((string) pathinfo($filename, PATHINFO_EXTENSION));
    $label = $filename !== '' ? $filename : 'Documento esterno';

    if ($extension !== '') {
        $label .= ' (' . $extension . ')';
    }

    return [
        'url' => $raw_text,
        'label' => $label,
    ];
}

function centro_servizi_render_custom_fields_preview(?int $post_id = null): string
{
    $post_id = $post_id ?: get_the_ID();

    if (! $post_id) {
        return '';
    }

    $post_type = get_post_type($post_id);

    if ($post_type !== 'trasparenza' && $post_type !== 'area-famiglie' && $post_type !== 'area-personale') {
        return '';
    }

    $rows = [];

    if ($post_type === 'trasparenza') {
        $titolo = centro_servizi_get_post_meta_string($post_id, 'titolo');
        $tag_anno = centro_servizi_get_post_meta_string($post_id, 'tag_anno');
        $testo = centro_servizi_get_post_meta_string($post_id, 'testo');
        $allegato = centro_servizi_get_meta_file_link_data($post_id, 'allegato');
        $documento = centro_servizi_get_meta_file_link_data($post_id, 'documento');

        if ($titolo !== '') {
            $rows[] = '<p><strong>Titolo interno:</strong> ' . esc_html($titolo) . '</p>';
        }

        if ($tag_anno !== '') {
            $rows[] = '<p><strong>Tag anno:</strong> ' . esc_html($tag_anno) . '</p>';
        }

        if ($testo !== '') {
            $rows[] = '<p><strong>Sottotitolo:</strong> ' . esc_html($testo) . '</p>';
        }

        if ($allegato !== []) {
            $rows[] = '<p><a href="' . esc_url((string) $allegato['url']) . '" target="_blank" rel="noopener">Scarica allegato: ' . esc_html((string) $allegato['label']) . '</a></p>';
        }

        if ($documento !== []) {
            $rows[] = '<p><a href="' . esc_url((string) $documento['url']) . '" target="_blank" rel="noopener">Documento iframe: ' . esc_html((string) $documento['label']) . '</a></p>';
        }
    }

    if ($post_type === 'area-famiglie' || $post_type === 'area-personale') {
        $testo = centro_servizi_get_post_meta_string($post_id, 'testo');
        $allegato = centro_servizi_get_meta_file_link_data($post_id, 'allegato');

        if ($testo !== '') {
            $rows[] = '<p><strong>Testo custom:</strong> ' . esc_html($testo) . '</p>';
        }

        if ($allegato !== []) {
            $rows[] = '<p><a href="' . esc_url((string) $allegato['url']) . '" target="_blank" rel="noopener">Scarica: ' . esc_html((string) $allegato['label']) . '</a></p>';
        }
    }

    if ($rows === []) {
        return '';
    }

    return implode('', $rows);
}
