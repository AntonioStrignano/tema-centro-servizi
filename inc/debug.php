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
        'css_mode' => centro_servizi_get_css_loading_mode(),
        'styles' => centro_servizi_get_loaded_css_debug(),
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
    $wp_styles = wp_styles();

    if (! $wp_styles instanceof WP_Styles) {
        return 'nessuno rilevato';
    }

    $handles = [];

    $all_handles = array_unique(array_merge($wp_styles->queue, $wp_styles->done));

    foreach ($all_handles as $handle) {
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

function centro_servizi_read_css_file(string $absolute_path): string
{
    if (! file_exists($absolute_path) || ! is_readable($absolute_path)) {
        return '';
    }

    $contents = file_get_contents($absolute_path);

    if (! is_string($contents)) {
        return '';
    }

    return trim($contents);
}

function centro_servizi_get_loaded_css_debug(): string
{
    $loaded = [];

    foreach (centro_servizi_get_theme_stylesheets() as $stylesheet) {
        $loaded[] = sprintf(
            '%1$s@%2$s',
            $stylesheet['label'],
            $stylesheet['version']
        );
    }

    return $loaded === [] ? 'nessuno rilevato' : implode(' | ', $loaded);
}

function centro_servizi_get_theme_stylesheets(): array
{
    $stylesheets = [
        [
            'label' => 'style.css',
            'path' => get_stylesheet_directory() . '/style.css',
            'url' => get_stylesheet_uri(),
        ],
        [
            'label' => 'assets/css/site.css',
            'path' => get_template_directory() . '/assets/css/site.css',
            'url' => get_template_directory_uri() . '/assets/css/site.css',
        ],
        [
            'label' => 'assets/css/css-debug.css',
            'path' => get_template_directory() . '/assets/css/css-debug.css',
            'url' => get_template_directory_uri() . '/assets/css/css-debug.css',
        ],
    ];

    if (centro_servizi_is_bureaucratic_context()) {
        $stylesheets[] = [
            'label' => 'assets/css/area-burocratica.css',
            'path' => get_template_directory() . '/assets/css/area-burocratica.css',
            'url' => get_template_directory_uri() . '/assets/css/area-burocratica.css',
        ];
    }

    $resolved = [];

    foreach ($stylesheets as $stylesheet) {
        if (centro_servizi_read_css_file($stylesheet['path']) === '') {
            continue;
        }

        $stylesheet['version'] = centro_servizi_get_asset_version($stylesheet['path']);
        $stylesheet['href'] = add_query_arg('ver', $stylesheet['version'], $stylesheet['url']);
        $resolved[] = $stylesheet;
    }

    return $resolved;
}

function centro_servizi_get_theme_inline_css_bundle(): string
{
    $chunks = [];

    foreach (centro_servizi_get_theme_stylesheets() as $stylesheet) {
        $contents = centro_servizi_read_css_file($stylesheet['path']);

        if ($contents === '') {
            continue;
        }

        $chunks[] = sprintf(
            '/* %1$s @ %2$s */' . "\n" . '%3$s',
            $stylesheet['label'],
            $stylesheet['version'],
            $contents
        );
    }

    return implode("\n\n", $chunks);
}

function centro_servizi_get_css_loading_mode(): string
{
    return centro_servizi_get_theme_inline_css_bundle() !== ''
        ? 'inline+link'
        : 'link-only';
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

add_action('admin_init', 'centro_servizi_maybe_seed_preview_transparency_posts');
add_action('admin_notices', 'centro_servizi_seed_preview_notice');

function centro_servizi_maybe_seed_preview_transparency_posts(): void
{
    if (! is_admin() || ! current_user_can('manage_options')) {
        return;
    }

    if (! isset($_GET['centro_seed_preview']) || $_GET['centro_seed_preview'] !== '1') {
        return;
    }

    $result = centro_servizi_seed_preview_transparency_posts([
        [
            'seed_key' => 'preview-trasparenza-1',
            'attachment_id' => 9,
            'school_year_name' => '2025/2026',
            'school_year_slug' => '2025-2026',
            'label_year' => '2025',
            'category_slug' => 'consuntivo',
        ],
        [
            'seed_key' => 'preview-trasparenza-2',
            'attachment_id' => 10,
            'school_year_name' => '2026/2027',
            'school_year_slug' => '2026-2027',
            'label_year' => '2026',
            'category_slug' => 'preventivo',
        ],
    ]);

    $status = $result['ok'] ? 'ok' : 'error';
    $message = rawurlencode($result['message']);
    $redirect = add_query_arg([
        'centro_seed_preview_status' => $status,
        'centro_seed_preview_message' => $message,
    ], admin_url('edit.php?post_type=trasparenza'));

    wp_safe_redirect($redirect);
    exit;
}

function centro_servizi_seed_preview_transparency_posts(array $items): array
{
    if (! post_type_exists('trasparenza') || ! taxonomy_exists('contenutiammtrasp') || ! taxonomy_exists('annoscolastico')) {
        return [
            'ok' => false,
            'message' => 'CPT o tassonomie non disponibili per il seeding preview.',
        ];
    }

    $created = 0;
    $updated = 0;
    $errors = [];

    foreach ($items as $index => $item) {
        $seed_key = isset($item['seed_key']) ? sanitize_key((string) $item['seed_key']) : '';
        $attachment_id = isset($item['attachment_id']) ? (int) $item['attachment_id'] : 0;
        $school_year_name = isset($item['school_year_name']) ? (string) $item['school_year_name'] : '';
        $school_year_slug = isset($item['school_year_slug']) ? (string) $item['school_year_slug'] : '';
        $label_year = isset($item['label_year']) ? (string) $item['label_year'] : '';
        $category_slug = isset($item['category_slug']) ? (string) $item['category_slug'] : '';

        if ($seed_key === '') {
            $errors[] = sprintf('Item %d: seed_key mancante.', $index + 1);
            continue;
        }

        if ($attachment_id <= 0 || get_post_type($attachment_id) !== 'attachment') {
            $errors[] = sprintf('Item %d: attachment ID non valido (%d).', $index + 1, $attachment_id);
            continue;
        }

        if ($school_year_name === '' || $school_year_slug === '' || $category_slug === '') {
            $errors[] = sprintf('Item %d: dati anno/categoria incompleti.', $index + 1);
            continue;
        }

        $term_year = term_exists($school_year_slug, 'annoscolastico');

        if (! $term_year) {
            $term_year = wp_insert_term($school_year_name, 'annoscolastico', [
                'slug' => $school_year_slug,
            ]);
        }

        if (is_wp_error($term_year)) {
            $errors[] = sprintf('Item %d: errore creazione anno scolastico %s.', $index + 1, $school_year_slug);
            continue;
        }

        $year_term_id = is_array($term_year) ? (int) $term_year['term_id'] : (int) $term_year;
        $category_term = get_term_by('slug', $category_slug, 'contenutiammtrasp');

        if (! $category_term instanceof WP_Term) {
            $errors[] = sprintf('Item %d: categoria %s non trovata.', $index + 1, $category_slug);
            continue;
        }

        $existing = get_posts([
            'post_type' => 'trasparenza',
            'post_status' => 'any',
            'numberposts' => 1,
            'fields' => 'ids',
            'meta_key' => '_centro_seed_preview_key',
            'meta_value' => $seed_key,
            'suppress_filters' => true,
        ]);

        $post_payload = [
            'post_type' => 'trasparenza',
            'post_status' => 'publish',
            'post_title' => sprintf('Preview documento %s (media %d)', $school_year_name, $attachment_id),
            'post_content' => 'Contenuto demo generato dal tema per anteprima.',
        ];

        if ($existing !== []) {
            $post_payload['ID'] = (int) $existing[0];
            $post_id = wp_update_post($post_payload, true);

            if (is_wp_error($post_id) || ! is_int($post_id) || $post_id <= 0) {
                $errors[] = sprintf('Item %d: errore aggiornamento post preview.', $index + 1);
                continue;
            }

            $updated++;
        } else {
            $post_id = wp_insert_post($post_payload, true);

            if (is_wp_error($post_id) || ! is_int($post_id) || $post_id <= 0) {
                $errors[] = sprintf('Item %d: errore creazione post.', $index + 1);
                continue;
            }

            $created++;
        }

        wp_set_object_terms($post_id, [$category_term->term_id], 'contenutiammtrasp', false);
        wp_set_object_terms($post_id, [$year_term_id], 'annoscolastico', false);

        update_post_meta($post_id, 'titolo', sprintf('Documento preview %s', $school_year_name));
        update_post_meta($post_id, 'tag_anno', $label_year);
        update_post_meta($post_id, 'documento', $attachment_id);
        update_post_meta($post_id, '_titolo', 'field_6450c519a3b72');
        update_post_meta($post_id, '_tag_anno', 'field_6618d8794456d');
        update_post_meta($post_id, '_documento', 'field_644aa97b98744');
        update_post_meta($post_id, '_centro_seed_preview_key', $seed_key);
    }

    if ($errors === []) {
        return [
            'ok' => true,
            'message' => sprintf('Seeding preview completato: creati %1$d, aggiornati %2$d.', $created, $updated),
        ];
    }

    return [
        'ok' => ($created + $updated) > 0,
        'message' => sprintf('Creati %1$d, aggiornati %2$d. Errori: %3$s', $created, $updated, implode(' | ', $errors)),
    ];
}

function centro_servizi_seed_preview_notice(): void
{
    if (! is_admin() || ! current_user_can('manage_options')) {
        return;
    }

    if (! isset($_GET['centro_seed_preview_status'], $_GET['centro_seed_preview_message'])) {
        return;
    }

    $status = sanitize_key((string) $_GET['centro_seed_preview_status']);
    $message = sanitize_text_field(wp_unslash((string) $_GET['centro_seed_preview_message']));
    $class = $status === 'ok' ? 'notice notice-success' : 'notice notice-error';

    echo '<div class="' . esc_attr($class) . '"><p>' . esc_html($message) . '</p></div>';
}
