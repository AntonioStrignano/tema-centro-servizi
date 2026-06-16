<?php
declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

function centro_servizi_shorten_commit_subject(string $subject): string
{
    $max_length = 18;
    $length = function_exists('mb_strlen') ? mb_strlen($subject) : strlen($subject);

    if ($length <= $max_length) {
        return $subject;
    }

    $short = function_exists('mb_substr')
        ? mb_substr($subject, 0, $max_length)
        : substr($subject, 0, $max_length);

    return $short . '...';
}

function centro_servizi_get_release_info_defaults(): array
{
    return [
        'branch' => 'main',
        'commit_short' => '-',
        'commit_datetime' => '-',
    ];
}

function centro_servizi_parse_deploy_meta_file(string $meta_file): array
{
    $content = file_get_contents($meta_file);

    if (! is_string($content) || $content === '') {
        return [];
    }

    $keys = ['branch', 'commit_title', 'commit_hash', 'deployed_at'];
    $parsed = [];

    foreach ($keys as $key) {
        $pattern = "/'" . preg_quote($key, '/') . "'\\s*=>\\s*(['\"])((?:\\\\.|(?!\\1).)*)\\1/s";

        if (! preg_match($pattern, $content, $matches)) {
            continue;
        }

        $parsed[$key] = sanitize_text_field(stripcslashes((string) $matches[2]));
    }

    return $parsed;
}

function centro_servizi_get_release_info_meta(): array
{
    $meta = centro_servizi_get_release_info_defaults();
    $meta_file = get_template_directory() . '/assets/deploy-meta.php';

    if (! file_exists($meta_file) || ! is_readable($meta_file)) {
        return $meta;
    }

    $deploy_meta = centro_servizi_parse_deploy_meta_file($meta_file);

    if (isset($deploy_meta['branch']) && $deploy_meta['branch'] !== '') {
        $meta['branch'] = (string) $deploy_meta['branch'];
    }

    $commit_source = '';
    if (isset($deploy_meta['commit_title']) && $deploy_meta['commit_title'] !== '') {
        $commit_source = (string) $deploy_meta['commit_title'];
    } elseif (isset($deploy_meta['commit_hash']) && $deploy_meta['commit_hash'] !== '') {
        $commit_source = (string) $deploy_meta['commit_hash'];
    }

    if ($commit_source !== '') {
        $meta['commit_short'] = centro_servizi_shorten_commit_subject($commit_source);
    }

    if (isset($deploy_meta['deployed_at']) && $deploy_meta['deployed_at'] !== '') {
        $timestamp = strtotime((string) $deploy_meta['deployed_at']);

        if ($timestamp !== false && $timestamp > 0) {
            $meta['commit_datetime'] = wp_date('Y-m-d H:i', $timestamp);
        } else {
            $meta['commit_datetime'] = (string) $deploy_meta['deployed_at'];
        }
    }

    return $meta;
}

function centro_servizi_get_current_template_label(): string
{
    if (is_admin()) {
        return 'wp-admin';
    }

    global $template;

    if (! is_string($template) || $template === '') {
        return 'template non rilevato';
    }

    $normalized_template = wp_normalize_path($template);
    $normalized_theme_dir = wp_normalize_path(get_template_directory()) . '/';

    if (strpos($normalized_template, $normalized_theme_dir) === 0) {
        return substr($normalized_template, strlen($normalized_theme_dir));
    }

    return basename($normalized_template);
}

function centro_servizi_add_admin_bar_release_info($wp_admin_bar): void
{
    if (! ($wp_admin_bar instanceof WP_Admin_Bar)) {
        return;
    }

    if (! is_admin_bar_showing() || ! current_user_can('manage_options')) {
        return;
    }

    $meta = centro_servizi_get_release_info_meta();

    $template_label = centro_servizi_get_current_template_label();

    $title = sprintf(
        'Branch: %1$s | Commit: %2$s | Data: %3$s | Template: %4$s',
        $meta['branch'],
        $meta['commit_short'],
        $meta['commit_datetime'],
        $template_label
    );

    $wp_admin_bar->add_node([
        'id' => 'centro-servizi-release-info',
        'parent' => 'top-secondary',
        'title' => esc_html($title),
        'href' => false,
        'meta' => [
            'title' => 'Info tecniche tema',
        ],
    ]);
}

add_action('admin_bar_menu', 'centro_servizi_add_admin_bar_release_info', 999);

function centro_servizi_admin_get_terms_csv(int $post_id, string $taxonomy): string
{
    $terms = get_the_terms($post_id, $taxonomy);

    if (is_wp_error($terms) || empty($terms)) {
        return '—';
    }

    $names = array_map(
        static function ($term): string {
            return ($term instanceof WP_Term) ? $term->name : '';
        },
        $terms
    );

    $names = array_values(array_filter($names, static fn(string $name): bool => $name !== ''));

    return $names !== [] ? esc_html(implode(', ', $names)) : '—';
}

function centro_servizi_admin_resolve_attachment_filename($raw): string
{
    if (is_array($raw)) {
        if (isset($raw['filename']) && is_string($raw['filename']) && $raw['filename'] !== '') {
            return $raw['filename'];
        }

        if (isset($raw['url']) && is_string($raw['url']) && $raw['url'] !== '') {
            $path = (string) parse_url($raw['url'], PHP_URL_PATH);
            return $path !== '' ? wp_basename($path) : '—';
        }
    }

    if (is_numeric($raw)) {
        $file_path = get_attached_file((int) $raw);

        if (is_string($file_path) && $file_path !== '') {
            return wp_basename($file_path);
        }

        $url = wp_get_attachment_url((int) $raw);

        if (is_string($url) && $url !== '') {
            $path = (string) parse_url($url, PHP_URL_PATH);
            return $path !== '' ? wp_basename($path) : '—';
        }
    }

    if (is_string($raw) && $raw !== '') {
        $decoded = maybe_unserialize($raw);

        if (is_array($decoded)) {
            return centro_servizi_admin_resolve_attachment_filename($decoded);
        }

        if (is_numeric($decoded)) {
            return centro_servizi_admin_resolve_attachment_filename((int) $decoded);
        }

        if (filter_var($raw, FILTER_VALIDATE_URL)) {
            $path = (string) parse_url($raw, PHP_URL_PATH);
            return $path !== '' ? wp_basename($path) : '—';
        }

        return $raw;
    }

    return '—';
}

function centro_servizi_admin_get_file_name_for_post(int $post_id, array $meta_keys): string
{
    foreach ($meta_keys as $meta_key) {
        $raw = get_post_meta($post_id, $meta_key, true);

        if ($raw === '' || $raw === null) {
            continue;
        }

        $resolved = centro_servizi_admin_resolve_attachment_filename($raw);

        if ($resolved !== '—' && $resolved !== '') {
            return esc_html($resolved);
        }
    }

    return '—';
}

function centro_servizi_admin_columns_trasparenza(array $columns): array
{
    return [
        'cb' => $columns['cb'] ?? '<input type="checkbox" />',
        'title' => $columns['title'] ?? 'Titolo',
        'categoria' => 'Categoria',
        'anno_scolastico' => 'Anno scolastico',
        'nome_allegato' => 'Nome allegato',
        'date' => $columns['date'] ?? 'Data',
        'ultima_modifica' => 'Ultima modifica',
    ];
}

function centro_servizi_admin_columns_area_common(array $columns): array
{
    return [
        'cb' => $columns['cb'] ?? '<input type="checkbox" />',
        'title' => $columns['title'] ?? 'Titolo',
        'categoria' => 'Categoria',
        'nome_allegato' => 'Nome allegato',
        'date' => $columns['date'] ?? 'Data',
        'ultima_modifica' => 'Ultima modifica',
    ];
}

function centro_servizi_admin_render_column_trasparenza(string $column, int $post_id): void
{
    if ($column === 'categoria') {
        echo centro_servizi_admin_get_terms_csv($post_id, 'contenutiammtrasp');
        return;
    }

    if ($column === 'anno_scolastico') {
        echo centro_servizi_admin_get_terms_csv($post_id, 'annoscolastico');
        return;
    }

    if ($column === 'nome_allegato') {
        echo centro_servizi_admin_get_file_name_for_post($post_id, ['documento', 'allegato']);
        return;
    }

    if ($column === 'ultima_modifica') {
        echo esc_html(get_the_modified_date('d/m/Y H:i', $post_id));
    }
}

function centro_servizi_admin_render_column_area_famiglie(string $column, int $post_id): void
{
    if ($column === 'categoria') {
        echo centro_servizi_admin_get_terms_csv($post_id, 'categoria-area-famiglia');
        return;
    }

    if ($column === 'nome_allegato') {
        echo centro_servizi_admin_get_file_name_for_post($post_id, ['allegato']);
        return;
    }

    if ($column === 'ultima_modifica') {
        echo esc_html(get_the_modified_date('d/m/Y H:i', $post_id));
    }
}

function centro_servizi_admin_render_column_area_personale(string $column, int $post_id): void
{
    if ($column === 'categoria') {
        echo centro_servizi_admin_get_terms_csv($post_id, 'categoria-area-personale');
        return;
    }

    if ($column === 'nome_allegato') {
        echo centro_servizi_admin_get_file_name_for_post($post_id, ['allegato']);
        return;
    }

    if ($column === 'ultima_modifica') {
        echo esc_html(get_the_modified_date('d/m/Y H:i', $post_id));
    }
}

add_filter('manage_trasparenza_posts_columns', 'centro_servizi_admin_columns_trasparenza');
add_filter('manage_area-famiglie_posts_columns', 'centro_servizi_admin_columns_area_common');
add_filter('manage_area-personale_posts_columns', 'centro_servizi_admin_columns_area_common');

add_action('manage_trasparenza_posts_custom_column', 'centro_servizi_admin_render_column_trasparenza', 10, 2);
add_action('manage_area-famiglie_posts_custom_column', 'centro_servizi_admin_render_column_area_famiglie', 10, 2);
add_action('manage_area-personale_posts_custom_column', 'centro_servizi_admin_render_column_area_personale', 10, 2);
