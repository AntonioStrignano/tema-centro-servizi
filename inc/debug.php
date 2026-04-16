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

    $pdf_1 = isset($_GET['centro_seed_pdf_1']) ? absint((string) $_GET['centro_seed_pdf_1']) : 9;
    $pdf_2 = isset($_GET['centro_seed_pdf_2']) ? absint((string) $_GET['centro_seed_pdf_2']) : 10;
    $table_1 = isset($_GET['centro_seed_table_1']) ? absint((string) $_GET['centro_seed_table_1']) : 0;
    $table_2 = isset($_GET['centro_seed_table_2']) ? absint((string) $_GET['centro_seed_table_2']) : 0;

    $pdf_1_url = $pdf_1 > 0 ? (string) wp_get_attachment_url($pdf_1) : '';
    $pdf_2_url = $pdf_2 > 0 ? (string) wp_get_attachment_url($pdf_2) : '';

    $table_shortcode_1 = $table_1 > 0 ? sprintf('[table id=%d /]', $table_1) : '';
    $table_shortcode_2 = $table_2 > 0 ? sprintf('[table id=%d /]', $table_2) : '';

    $result_trasparenza = centro_servizi_seed_preview_transparency_posts([
        [
            'seed_key' => 'preview-trasparenza-1',
            'attachment_id' => $pdf_1,
            'school_year_name' => '2025/2026',
            'school_year_slug' => '2025-2026',
            'label_year' => '2025',
            'category_slug' => 'consuntivo',
            'post_title' => 'AT - Solo PDF',
            'titolo' => 'Solo PDF',
            'post_content' => '',
        ],
        [
            'seed_key' => 'preview-trasparenza-2',
            'attachment_id' => $pdf_2,
            'school_year_name' => '2026/2027',
            'school_year_slug' => '2026-2027',
            'label_year' => '2026',
            'category_slug' => 'preventivo',
            'post_title' => 'AT - PDF + testo',
            'titolo' => 'PDF + testo',
            'post_content' => 'Documento con allegato PDF e testo descrittivo.',
        ],
        [
            'seed_key' => 'preview-trasparenza-3',
            'school_year_name' => '2025/2026',
            'school_year_slug' => '2025-2026',
            'label_year' => '2025',
            'category_slug' => 'organizzazione',
            'post_title' => 'AT - Solo testo',
            'titolo' => 'Solo testo',
            'post_content' => 'Solo testo, senza allegato.',
        ],
        [
            'seed_key' => 'preview-trasparenza-4',
            'school_year_name' => '2026/2027',
            'school_year_slug' => '2026-2027',
            'label_year' => '2026',
            'category_slug' => 'verifiche-periodiche',
            'post_title' => 'AT - Tabella',
            'titolo' => 'Tabella',
            'post_content' => $table_shortcode_1 !== '' ? $table_shortcode_1 : 'Inserire tabella TablePress con parametro centro_seed_table_1.',
        ],
        [
            'seed_key' => 'preview-trasparenza-5',
            'school_year_name' => '2026/2027',
            'school_year_slug' => '2026-2027',
            'label_year' => '2026',
            'category_slug' => 'autorizzazioni',
            'post_title' => 'AT - Post vuoto/quasi vuoto',
            'titolo' => 'Quasi vuoto',
            'post_content' => '',
        ],
        [
            'seed_key' => 'preview-trasparenza-6',
            'attachment_id' => $pdf_1,
            'school_year_name' => '2025/2026',
            'school_year_slug' => '2025-2026',
            'label_year' => '2025',
            'category_slug' => 'moduli-iscrizione',
            'post_title' => 'AT - Piu PDF nello stesso post',
            'titolo' => 'Piu PDF',
            'post_content' => $pdf_2_url !== '' ? sprintf('Secondo allegato nel testo: <a href="%s" target="_blank" rel="noopener">Scarica secondo PDF</a>.', esc_url($pdf_2_url)) : 'Secondo allegato assente: manca URL del PDF 2.',
        ],
        [
            'seed_key' => 'preview-trasparenza-7',
            'document_value' => home_url('/wp-content/uploads/preview-non-pdf.docx'),
            'school_year_name' => '2025/2026',
            'school_year_slug' => '2025-2026',
            'label_year' => '2025',
            'category_slug' => 'normativa',
            'post_title' => 'AT - Allegato non PDF',
            'titolo' => 'Allegato non PDF',
            'post_content' => 'Caso errore redazionale: allegato non PDF.',
        ],
        [
            'seed_key' => 'preview-trasparenza-8',
            'school_year_name' => '2026/2027',
            'school_year_slug' => '2026-2027',
            'label_year' => '2026',
            'category_slug' => 'contributi-pubblici',
            'post_title' => 'AT - Tabella lunga/complessa',
            'titolo' => 'Tabella lunga/complessa',
            'post_content' => $table_shortcode_2 !== '' ? $table_shortcode_2 : 'Inserire tabella TablePress con parametro centro_seed_table_2.',
        ],
        [
            'seed_key' => 'preview-trasparenza-9',
            'school_year_name' => '2025/2026',
            'school_year_slug' => '2025-2026',
            'label_year' => '2025',
            'category_slug' => 'circolari-mim',
            'post_title' => 'AT - Testo con link esterni',
            'titolo' => 'Link esterni',
            'post_content' => 'Link esterno di prova: <a href="https://www.istruzione.it" target="_blank" rel="noopener">Ministero Istruzione</a>.',
        ],
    ]);

    $result_famiglie = centro_servizi_seed_preview_area_posts('area-famiglie', 'categoria-area-famiglia', [
        [
            'seed_key' => 'preview-famiglie-1',
            'term_slug' => 'avvisi',
            'post_title' => 'AF - Solo testo',
            'testo' => 'Solo testo',
            'post_content' => 'Comunicazione testuale senza allegato.',
        ],
        [
            'seed_key' => 'preview-famiglie-2',
            'term_slug' => 'moduli-iscrizione',
            'post_title' => 'AF - Testo + PDF',
            'testo' => 'Testo + PDF',
            'allegato_attachment_id' => $pdf_1,
            'post_content' => 'Testo introduttivo con allegato principale.',
        ],
        [
            'seed_key' => 'preview-famiglie-3',
            'term_slug' => 'carta-dei-servizi',
            'post_title' => 'AF - Solo PDF',
            'testo' => 'Solo PDF',
            'allegato_attachment_id' => $pdf_2,
            'post_content' => '',
        ],
        [
            'seed_key' => 'preview-famiglie-4',
            'term_slug' => 'privacy-e-informativa-genitori',
            'post_title' => 'AF - Solo link esterno',
            'testo' => 'Solo link esterno',
            'post_content' => 'Consulta il portale esterno: <a href="https://www.garanteprivacy.it" target="_blank" rel="noopener">Garante Privacy</a>.',
        ],
        [
            'seed_key' => 'preview-famiglie-5',
            'term_slug' => 'modulistica-somministrazione-farmaci',
            'post_title' => 'AF - Piu PDF + testo introduttivo',
            'testo' => 'Piu PDF + testo',
            'allegato_attachment_id' => $pdf_1,
            'post_content' => $pdf_2_url !== '' ? sprintf('Secondo PDF nel contenuto: <a href="%s" target="_blank" rel="noopener">Scarica PDF aggiuntivo</a>.', esc_url($pdf_2_url)) : 'Secondo PDF non disponibile.',
        ],
        [
            'seed_key' => 'preview-famiglie-6',
            'term_slug' => 'avvisi',
            'post_title' => 'AF - PDF mancante/non trovato',
            'testo' => 'PDF mancante',
            'allegato_attachment_id' => 999999,
            'post_content' => 'Questo caso serve per test gestione errori allegato.',
        ],
        [
            'seed_key' => 'preview-famiglie-7',
            'term_slug' => 'calendario-scolastico',
            'post_title' => 'AF - Contenuto molto lungo',
            'testo' => 'Contenuto lungo',
            'post_content' => centro_servizi_get_seed_long_text_block(),
        ],
        [
            'seed_key' => 'preview-famiglie-8',
            'term_slug' => 'privacy-e-informativa-genitori',
            'post_title' => 'AF - Dati sensibili da verificare',
            'testo' => 'Dati sensibili',
            'post_content' => 'ATTENZIONE TEST: questo contenuto include dati fittizi da verificare e oscurare in revisione.',
        ],
    ]);

    $result_personale = centro_servizi_seed_preview_area_posts('area-personale', 'categoria-area-personale', [
        [
            'seed_key' => 'preview-personale-1',
            'term_slug' => 'avvisi',
            'post_title' => 'AP - Solo testo',
            'testo' => 'Solo testo',
            'post_content' => 'Avviso interno in formato solo testo.',
        ],
        [
            'seed_key' => 'preview-personale-2',
            'term_slug' => 'modulistica',
            'post_title' => 'AP - Testo + PDF',
            'testo' => 'Testo + PDF',
            'allegato_attachment_id' => $pdf_1,
            'post_content' => 'Modulo interno con allegato PDF.',
        ],
        [
            'seed_key' => 'preview-personale-3',
            'term_slug' => 'regolamento-interno',
            'post_title' => 'AP - Solo PDF',
            'testo' => 'Solo PDF',
            'allegato_attachment_id' => $pdf_2,
            'post_content' => '',
        ],
        [
            'seed_key' => 'preview-personale-4',
            'term_slug' => 'formazione',
            'post_title' => 'AP - Solo link esterno',
            'testo' => 'Solo link esterno',
            'post_content' => 'Piattaforma formazione: <a href="https://www.scuola.istruzione.it" target="_blank" rel="noopener">Accesso esterno</a>.',
        ],
        [
            'seed_key' => 'preview-personale-5',
            'term_slug' => 'modulistica',
            'post_title' => 'AP - Piu PDF + testo introduttivo',
            'testo' => 'Piu PDF + testo',
            'allegato_attachment_id' => $pdf_2,
            'post_content' => $pdf_1_url !== '' ? sprintf('Secondo PDF nel contenuto: <a href="%s" target="_blank" rel="noopener">Scarica allegato aggiuntivo</a>.', esc_url($pdf_1_url)) : 'Secondo PDF non disponibile.',
        ],
        [
            'seed_key' => 'preview-personale-6',
            'term_slug' => 'avvisi',
            'post_title' => 'AP - PDF mancante/non trovato',
            'testo' => 'PDF mancante',
            'allegato_attachment_id' => 999999,
            'post_content' => 'Caso test per gestione allegato non disponibile.',
        ],
        [
            'seed_key' => 'preview-personale-7',
            'term_slug' => 'formazione',
            'post_title' => 'AP - Contenuto molto lungo',
            'testo' => 'Contenuto lungo',
            'post_content' => centro_servizi_get_seed_long_text_block(),
        ],
        [
            'seed_key' => 'preview-personale-8',
            'term_slug' => 'privacy-personale',
            'post_title' => 'AP - Dati sensibili da verificare',
            'testo' => 'Dati sensibili',
            'post_content' => 'ATTENZIONE TEST: contenuto con riferimenti sensibili fittizi per verifica checklist privacy.',
        ],
    ]);

    $summary = sprintf(
        'Trasparenza: %1$s | Area Famiglie: %2$s | Area Personale: %3$s',
        $result_trasparenza['message'],
        $result_famiglie['message'],
        $result_personale['message']
    );

    $ok = $result_trasparenza['ok'] || $result_famiglie['ok'] || $result_personale['ok'];

    $status = $ok ? 'ok' : 'error';
    $message = rawurlencode($summary);
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
        $document_value = isset($item['document_value']) && is_scalar($item['document_value']) ? trim((string) $item['document_value']) : '';
        $school_year_name = isset($item['school_year_name']) ? (string) $item['school_year_name'] : '';
        $school_year_slug = isset($item['school_year_slug']) ? (string) $item['school_year_slug'] : '';
        $label_year = isset($item['label_year']) ? (string) $item['label_year'] : '';
        $category_slug = isset($item['category_slug']) ? (string) $item['category_slug'] : '';

        if ($seed_key === '') {
            $errors[] = sprintf('Item %d: seed_key mancante.', $index + 1);
            continue;
        }

        if ($attachment_id > 0) {
            if (get_post_type($attachment_id) !== 'attachment') {
                $errors[] = sprintf('Item %d: attachment ID non valido (%d).', $index + 1, $attachment_id);
                continue;
            }

            $document_value = (string) $attachment_id;
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
            'post_title' => isset($item['post_title']) && is_string($item['post_title'])
                ? $item['post_title']
                : sprintf('Preview documento %s', $school_year_name),
            'post_content' => isset($item['post_content']) && is_string($item['post_content'])
                ? $item['post_content']
                : 'Contenuto demo generato dal tema per anteprima.',
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

        update_post_meta(
            $post_id,
            'titolo',
            isset($item['titolo']) && is_string($item['titolo']) && $item['titolo'] !== ''
                ? sanitize_text_field($item['titolo'])
                : sprintf('Documento preview %s', $school_year_name)
        );
        update_post_meta($post_id, 'tag_anno', $label_year);

        if ($document_value !== '') {
            if (ctype_digit($document_value)) {
                update_post_meta($post_id, 'documento', (int) $document_value);
            } else {
                update_post_meta($post_id, 'documento', esc_url_raw($document_value));
            }
        } else {
            delete_post_meta($post_id, 'documento');
        }

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

function centro_servizi_seed_preview_area_posts(string $post_type, string $taxonomy, array $items): array
{
    if (! post_type_exists($post_type) || ! taxonomy_exists($taxonomy)) {
        return [
            'ok' => false,
            'message' => 'CPT o tassonomia non disponibili.',
        ];
    }

    $created = 0;
    $updated = 0;
    $errors = [];

    foreach ($items as $index => $item) {
        $seed_key = isset($item['seed_key']) ? sanitize_key((string) $item['seed_key']) : '';
        $term_slug = isset($item['term_slug']) ? sanitize_title((string) $item['term_slug']) : '';
        $allegato_id = isset($item['allegato_attachment_id']) ? (int) $item['allegato_attachment_id'] : 0;
        $allegato_value = isset($item['allegato_value']) && is_scalar($item['allegato_value']) ? trim((string) $item['allegato_value']) : '';

        if ($seed_key === '' || $term_slug === '') {
            $errors[] = sprintf('Item %d: seed_key o categoria mancanti.', $index + 1);
            continue;
        }

        if ($allegato_id > 0) {
            if (get_post_type($allegato_id) !== 'attachment') {
                $errors[] = sprintf('Item %d: allegato ID non valido (%d).', $index + 1, $allegato_id);
                continue;
            }

            $allegato_value = (string) $allegato_id;
        }

        $term = get_term_by('slug', $term_slug, $taxonomy);

        if (! $term instanceof WP_Term) {
            $errors[] = sprintf('Item %d: categoria %s non trovata.', $index + 1, $term_slug);
            continue;
        }

        $existing = get_posts([
            'post_type' => $post_type,
            'post_status' => 'any',
            'numberposts' => 1,
            'fields' => 'ids',
            'meta_key' => '_centro_seed_preview_key',
            'meta_value' => $seed_key,
            'suppress_filters' => true,
        ]);

        $post_payload = [
            'post_type' => $post_type,
            'post_status' => 'publish',
            'post_title' => isset($item['post_title']) && is_string($item['post_title'])
                ? $item['post_title']
                : sprintf('Preview %s %d', $post_type, $index + 1),
            'post_content' => isset($item['post_content']) && is_string($item['post_content'])
                ? $item['post_content']
                : '',
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

        wp_set_object_terms($post_id, [$term->term_id], $taxonomy, false);

        update_post_meta(
            $post_id,
            'testo',
            isset($item['testo']) && is_string($item['testo'])
                ? sanitize_text_field($item['testo'])
                : ''
        );

        if ($allegato_value !== '') {
            if (ctype_digit($allegato_value)) {
                update_post_meta($post_id, 'allegato', (int) $allegato_value);
            } else {
                update_post_meta($post_id, 'allegato', esc_url_raw($allegato_value));
            }
        } else {
            delete_post_meta($post_id, 'allegato');
        }

        update_post_meta($post_id, '_testo', 'field_6579d01b052ac');
        update_post_meta($post_id, '_allegato', 'field_6579d07c052ad');
        update_post_meta($post_id, '_centro_seed_preview_key', $seed_key);
    }

    if ($errors === []) {
        return [
            'ok' => true,
            'message' => sprintf('Creati %1$d, aggiornati %2$d.', $created, $updated),
        ];
    }

    return [
        'ok' => ($created + $updated) > 0,
        'message' => sprintf('Creati %1$d, aggiornati %2$d. Errori: %3$s', $created, $updated, implode(' | ', $errors)),
    ];
}

function centro_servizi_get_seed_long_text_block(): string
{
    return 'Contenuto lungo di test per verificare leggibilita, spacing e navigazione su pagine dense. '
        . 'Questo testo viene ripetuto volutamente per simulare casi reali con molte informazioni operative e normative. '
        . 'Contenuto lungo di test per verificare leggibilita, spacing e navigazione su pagine dense. '
        . 'Questo testo viene ripetuto volutamente per simulare casi reali con molte informazioni operative e normative. '
        . 'Contenuto lungo di test per verificare leggibilita, spacing e navigazione su pagine dense. '
        . 'Questo testo viene ripetuto volutamente per simulare casi reali con molte informazioni operative e normative.';
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
