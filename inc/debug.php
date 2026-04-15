<?php
declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

function centro_servizi_is_bureaucratic_context(): bool
{
    return is_post_type_archive(['trasparenza', 'area-famiglie', 'area-personale'])
        || is_singular(['trasparenza', 'area-famiglie', 'area-personale'])
        || is_tax(['contenutiammtrasp', 'annoscolastico'])
        || is_page('amministrazione-trasparente');
}

function centro_servizi_get_debug_context(): array
{
    $template = centro_servizi_get_relative_theme_path(centro_servizi_get_current_template_path());
    $theme_timestamp = centro_servizi_get_theme_last_modified_timestamp();
    $deployed_at = $theme_timestamp > 0
        ? wp_date('d/m/Y H:i:s', $theme_timestamp)
        : 'non disponibile';

    return [
        'template' => $template !== '' ? $template : 'template non rilevato',
        'view_type' => centro_servizi_get_debug_view_type(),
        'object' => centro_servizi_get_debug_object_label(),
        'styles' => centro_servizi_get_enqueued_styles_debug(),
        'deployed_at' => $deployed_at,
    ];
}

function centro_servizi_get_asset_version(string $absolute_path): string
{
    $timestamp = file_exists($absolute_path) ? filemtime($absolute_path) : false;

    if (is_int($timestamp) && $timestamp > 0) {
        return (string) $timestamp;
    }

    return (string) wp_get_theme()->get('Version');
}

function centro_servizi_get_theme_last_modified_timestamp(): int
{
    static $latest_timestamp = null;

    if (is_int($latest_timestamp)) {
        return $latest_timestamp;
    }

    $latest_timestamp = 0;
    $theme_directory = get_template_directory();

    if (! is_dir($theme_directory)) {
        return 0;
    }

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($theme_directory, FilesystemIterator::SKIP_DOTS)
    );

    foreach ($iterator as $file_info) {
        if (! $file_info instanceof SplFileInfo || ! $file_info->isFile()) {
            continue;
        }

        $extension = strtolower($file_info->getExtension());

        if (! in_array($extension, ['php', 'css', 'js'], true)) {
            continue;
        }

        $pathname = wp_normalize_path($file_info->getPathname());

        if (str_contains($pathname, '/.git/') || str_contains($pathname, '/docs/')) {
            continue;
        }

        $modified = $file_info->getMTime();

        if ($modified > $latest_timestamp) {
            $latest_timestamp = $modified;
        }
    }

    return $latest_timestamp;
}

function centro_servizi_get_enqueued_styles_debug(): string
{
    global $wp_styles;

    if (! $wp_styles instanceof WP_Styles) {
        return 'nessuno rilevato';
    }

    $handles = [];

    foreach ($wp_styles->queue as $handle) {
        if (! is_string($handle) || ! str_starts_with($handle, 'centro-servizi-')) {
            continue;
        }

        $registered = $wp_styles->registered[$handle] ?? null;

        if (! $registered instanceof _WP_Dependency) {
            $handles[] = $handle;
            continue;
        }

        $version = is_string($registered->ver) ? $registered->ver : '';
        $handles[] = $version !== '' ? $handle . '@' . $version : $handle;
    }

    if ($handles === []) {
        return 'nessuno rilevato';
    }

    return implode(' | ', $handles);
}

function centro_servizi_get_debug_view_type(): string
{
    if (is_front_page()) {
        return 'front-page';
    }

    if (is_home()) {
        return 'home';
    }

    if (is_page()) {
        return 'page';
    }

    if (is_singular()) {
        return 'single-' . (string) get_post_type();
    }

    if (is_post_type_archive()) {
        $post_type_name = get_query_var('post_type');

        if (is_array($post_type_name)) {
            $post_type_name = reset($post_type_name);
        }

        return 'archive-' . (string) $post_type_name;
    }

    if (is_tax() || is_category() || is_tag()) {
        $term = get_queried_object();

        if ($term instanceof WP_Term) {
            return 'taxonomy-' . $term->taxonomy;
        }
    }

    if (is_search()) {
        return 'search';
    }

    if (is_404()) {
        return '404';
    }

    return 'generic';
}

function centro_servizi_get_debug_object_label(): string
{
    $object = get_queried_object();

    if ($object instanceof WP_Post) {
        return sprintf('post:%1$d slug:%2$s', $object->ID, $object->post_name);
    }

    if ($object instanceof WP_Term) {
        return sprintf('term:%1$s slug:%2$s', $object->taxonomy, $object->slug);
    }

    if ($object instanceof WP_Post_Type) {
        return 'post_type:' . $object->name;
    }

    if (is_search()) {
        return 'query:' . get_search_query();
    }

    return 'nessun oggetto specifico';
}

function centro_servizi_debug_field_label(string $field_name): string
{
    return sprintf(
        '<span class="debug-field-label">FIELD: %s</span>',
        esc_html($field_name)
    );
}

function centro_servizi_get_current_template_path(): string
{
    global $template;

    if (is_string($template) && $template !== '') {
        return $template;
    }

    return '';
}

function centro_servizi_get_relative_theme_path(string $path): string
{
    $theme_dir = wp_normalize_path(get_template_directory()) . '/';
    $path = wp_normalize_path($path);

    if ($path === '') {
        return '';
    }

    if (str_starts_with($path, $theme_dir)) {
        return substr($path, strlen($theme_dir));
    }

    return basename($path);
}
