<?php
declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

// Registrare la pagina admin
add_action('admin_menu', 'centro_servizi_add_settings_page');
function centro_servizi_add_settings_page(): void
{
    add_menu_page(
        'Impostazioni Sito',
        'Impostazioni Sito',
        'manage_options',
        'centro-servizi-settings',
        'centro_servizi_render_settings_hub_page',
        'dashicons-admin-tools',
        99
    );

    add_submenu_page(
        'centro-servizi-settings',
        'Panoramica',
        'Panoramica',
        'manage_options',
        'centro-servizi-settings',
        'centro_servizi_render_settings_hub_page'
    );

    add_submenu_page(
        'centro-servizi-settings',
        'Stile Tema',
        'Stile Tema',
        'manage_options',
        'centro-servizi-settings-style',
        'centro_servizi_render_settings_style_page'
    );

    add_submenu_page(
        'centro-servizi-settings',
        'Contatti',
        'Contatti',
        'manage_options',
        'centro-servizi-settings-contatti',
        'centro_servizi_render_settings_contatti_page'
    );

    add_submenu_page(
        'centro-servizi-settings',
        'Normative e Privacy',
        'Normative e Privacy',
        'manage_options',
        'centro-servizi-settings-normative',
        'centro_servizi_render_settings_normative_page'
    );

    add_submenu_page(
        'centro-servizi-settings',
        'Area Legale',
        'Area Legale',
        'manage_options',
        'centro-servizi-settings-legale',
        'centro_servizi_render_settings_legale_page'
    );
}

function centro_servizi_get_settings_sections(): array
{
    return [
        'style' => [
            'title' => 'Stile Tema',
            'slug' => 'centro-servizi-settings-style',
            'description' => 'Colori, font, tipografia e contenuti hero della homepage.',
        ],
        'contatti' => [
            'title' => 'Contatti',
            'slug' => 'centro-servizi-settings-contatti',
            'description' => 'Recapiti pubblici, indirizzi e mappa della sede.',
        ],
        'normative' => [
            'title' => 'Normative e Privacy',
            'slug' => 'centro-servizi-settings-normative',
            'description' => 'Contatti privacy/DPO, whistleblowing e gestione pagine legali obbligatorie.',
        ],
        'legale' => [
            'title' => 'Area Legale',
            'slug' => 'centro-servizi-settings-legale',
            'description' => 'Dati societari e contenuti legali mostrati nel footer.',
        ],
    ];
}

function centro_servizi_get_settings_section_url(string $section): string
{
    $sections = centro_servizi_get_settings_sections();
    if (! isset($sections[$section])) {
        return admin_url('admin.php?page=centro-servizi-settings');
    }

    return admin_url('admin.php?page=' . $sections[$section]['slug']);
}

function centro_servizi_render_settings_hub_page(): void
{
    if (! current_user_can('manage_options')) {
        wp_die('Accesso negato.');
    }

    $sections = centro_servizi_get_settings_sections();
    ?>
    <div class="wrap">
        <h1>Impostazioni Sito</h1>
        <p>Area principale: usa le sotto-pagine per gestire ogni blocco in modo ordinato.</p>

        <div class="centro-servizi-settings-hub-grid">
            <?php foreach ($sections as $section): ?>
                <a class="centro-servizi-settings-hub-card" href="<?php echo esc_url(admin_url('admin.php?page=' . $section['slug'])); ?>">
                    <h2><?php echo esc_html($section['title']); ?></h2>
                    <p><?php echo esc_html($section['description']); ?></p>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <style>
        .centro-servizi-settings-hub-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 16px;
            margin-top: 16px;
            max-width: 1100px;
        }

        .centro-servizi-settings-hub-card {
            display: block;
            background: #fff;
            border: 1px solid #dcdcde;
            border-radius: 8px;
            padding: 18px;
            text-decoration: none;
            color: inherit;
            transition: border-color 0.18s ease, box-shadow 0.18s ease;
        }

        .centro-servizi-settings-hub-card:hover,
        .centro-servizi-settings-hub-card:focus {
            border-color: #2271b1;
            box-shadow: 0 0 0 1px #2271b1;
            outline: none;
        }

        .centro-servizi-settings-hub-card h2 {
            margin: 0 0 8px;
            font-size: 18px;
        }

        .centro-servizi-settings-hub-card p {
            margin: 0;
            color: #50575e;
        }
    </style>
    <?php
}

function centro_servizi_render_settings_style_page(): void
{
    centro_servizi_render_settings_page('style');
}

function centro_servizi_render_settings_contatti_page(): void
{
    centro_servizi_render_settings_page('contatti');
}

function centro_servizi_render_settings_normative_page(): void
{
    centro_servizi_render_settings_page('normative');
}

function centro_servizi_render_settings_legale_page(): void
{
    centro_servizi_render_settings_page('legale');
}

// ============================================================================
// CATALOGO FONT ESTESO CON WEIGHTS
// ============================================================================
function centro_servizi_get_font_catalog(): array
{
    return [
        'arial' => ['label' => 'Arial', 'stack' => 'Arial, Helvetica, sans-serif', 'google_family' => '', 'weights' => [400, 700]],
        'georgia' => ['label' => 'Georgia', 'stack' => 'Georgia, "Times New Roman", serif', 'google_family' => '', 'weights' => [400, 700]],
        'verdana' => ['label' => 'Verdana', 'stack' => 'Verdana, Geneva, sans-serif', 'google_family' => '', 'weights' => [400, 700]],
        'times-new-roman' => ['label' => 'Times New Roman', 'stack' => '"Times New Roman", Times, serif', 'google_family' => '', 'weights' => [400, 700]],
        'trebuchet' => ['label' => 'Trebuchet MS', 'stack' => '"Trebuchet MS", Tahoma, sans-serif', 'google_family' => '', 'weights' => [400, 700]],
        'courier' => ['label' => 'Courier New', 'stack' => '"Courier New", Courier, monospace', 'google_family' => '', 'weights' => [400, 700]],
        'garamond' => ['label' => 'Garamond', 'stack' => 'Garamond, "Times New Roman", serif', 'google_family' => '', 'weights' => [400, 700]],
        'tahoma' => ['label' => 'Tahoma', 'stack' => 'Tahoma, Geneva, sans-serif', 'google_family' => '', 'weights' => [400, 700]],
        'helvetica' => ['label' => 'Helvetica', 'stack' => 'Helvetica, Arial, sans-serif', 'google_family' => '', 'weights' => [400, 700]],
        'system-ui' => ['label' => 'System UI', 'stack' => 'system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif', 'google_family' => '', 'weights' => [400, 700]],
        'roboto' => ['label' => 'Roboto (Google)', 'stack' => '"Roboto", Arial, sans-serif', 'google_family' => 'Roboto', 'weights' => [100, 300, 400, 500, 700, 900]],
        'open-sans' => ['label' => 'Open Sans (Google)', 'stack' => '"Open Sans", Arial, sans-serif', 'google_family' => 'Open Sans', 'weights' => [300, 400, 500, 600, 700, 800]],
        'lato' => ['label' => 'Lato (Google)', 'stack' => '"Lato", Arial, sans-serif', 'google_family' => 'Lato', 'weights' => [100, 300, 400, 700, 900]],
        'montserrat' => ['label' => 'Montserrat (Google)', 'stack' => '"Montserrat", Arial, sans-serif', 'google_family' => 'Montserrat', 'weights' => [100, 200, 300, 400, 500, 600, 700, 800, 900]],
        'poppins' => ['label' => 'Poppins (Google)', 'stack' => '"Poppins", Arial, sans-serif', 'google_family' => 'Poppins', 'weights' => [100, 200, 300, 400, 500, 600, 700, 800, 900]],
        'nunito' => ['label' => 'Nunito (Google)', 'stack' => '"Nunito", Arial, sans-serif', 'google_family' => 'Nunito', 'weights' => [200, 300, 400, 500, 600, 700, 800, 900]],
        'source-sans-3' => ['label' => 'Source Sans 3 (Google)', 'stack' => '"Source Sans 3", Arial, sans-serif', 'google_family' => 'Source Sans 3', 'weights' => [200, 300, 400, 500, 600, 700, 900]],
        'bree-serif' => ['label' => 'Bree Serif (Google)', 'stack' => '"Bree Serif", Georgia, serif', 'google_family' => 'Bree Serif', 'weights' => [400, 700]],
        'croissant-one' => ['label' => 'Croissant One (Google)', 'stack' => '"Croissant One", Georgia, serif', 'google_family' => 'Croissant One', 'weights' => [400]],
        'merriweather' => ['label' => 'Merriweather (Google)', 'stack' => '"Merriweather", Georgia, serif', 'google_family' => 'Merriweather', 'weights' => [300, 400, 700, 900]],
        'playfair-display' => ['label' => 'Playfair Display (Google)', 'stack' => '"Playfair Display", Georgia, serif', 'google_family' => 'Playfair Display', 'weights' => [400, 500, 600, 700, 800, 900]],
        'raleway' => ['label' => 'Raleway (Google)', 'stack' => '"Raleway", Arial, sans-serif', 'google_family' => 'Raleway', 'weights' => [100, 200, 300, 400, 500, 600, 700, 800, 900]],
        'oswald' => ['label' => 'Oswald (Google)', 'stack' => '"Oswald", Arial, sans-serif', 'google_family' => 'Oswald', 'weights' => [200, 300, 400, 500, 600, 700]],
        'inter' => ['label' => 'Inter (Google)', 'stack' => '"Inter", Arial, sans-serif', 'google_family' => 'Inter', 'weights' => [100, 200, 300, 400, 500, 600, 700, 800, 900]],
        'work-sans' => ['label' => 'Work Sans (Google)', 'stack' => '"Work Sans", Arial, sans-serif', 'google_family' => 'Work Sans', 'weights' => [100, 200, 300, 400, 500, 600, 700, 800, 900]],
        'jost' => ['label' => 'Jost (Google)', 'stack' => '"Jost", Arial, sans-serif', 'google_family' => 'Jost', 'weights' => [100, 200, 300, 400, 500, 600, 700, 800, 900]],
        'nunito-sans' => ['label' => 'Nunito Sans (Google)', 'stack' => '"Nunito Sans", Arial, sans-serif', 'google_family' => 'Nunito Sans', 'weights' => [200, 300, 400, 500, 600, 700, 800, 900]],
        'fredoka' => ['label' => 'Fredoka (Google)', 'stack' => '"Fredoka", Arial, sans-serif', 'google_family' => 'Fredoka', 'weights' => [300, 400, 500, 600, 700]],
        'baloo-2' => ['label' => 'Baloo 2 (Google)', 'stack' => '"Baloo 2", Arial, sans-serif', 'google_family' => 'Baloo 2', 'weights' => [400, 500, 600, 700, 800]],
        'caveat' => ['label' => 'Caveat (Google)', 'stack' => '"Caveat", "Comic Sans MS", cursive', 'google_family' => 'Caveat', 'weights' => [400, 500, 600, 700]],
        'patrick-hand' => ['label' => 'Patrick Hand (Google)', 'stack' => '"Patrick Hand", cursive', 'google_family' => 'Patrick Hand', 'weights' => [400]],
        'quicksand' => ['label' => 'Quicksand (Google)', 'stack' => '"Quicksand", Arial, sans-serif', 'google_family' => 'Quicksand', 'weights' => [300, 400, 500, 600, 700]],
        'comfortaa' => ['label' => 'Comfortaa (Google)', 'stack' => '"Comfortaa", Arial, sans-serif', 'google_family' => 'Comfortaa', 'weights' => [300, 400, 500, 600, 700]],
        'lora' => ['label' => 'Lora (Google)', 'stack' => '"Lora", Georgia, serif', 'google_family' => 'Lora', 'weights' => [400, 500, 600, 700]],
        'amatic-sc' => ['label' => 'Amatic SC (Google)', 'stack' => '"Amatic SC", "Comic Sans MS", cursive', 'google_family' => 'Amatic SC', 'weights' => [400, 700]],
    ];
}

// ============================================================================
// UTILITY PER COLORI (SFUMATURE)
// ============================================================================
function centro_servizi_hex_to_rgb(string $hex): ?array
{
    $hex = ltrim($hex, '#');
    if (strlen($hex) === 6) {
        return [
            'r' => hexdec(substr($hex, 0, 2)),
            'g' => hexdec(substr($hex, 2, 2)),
            'b' => hexdec(substr($hex, 4, 2)),
        ];
    }
    return null;
}

function centro_servizi_rgb_to_hex(int $r, int $g, int $b): string
{
    return '#' . str_pad(dechex(max(0, min(255, $r))), 2, '0', STR_PAD_LEFT)
           . str_pad(dechex(max(0, min(255, $g))), 2, '0', STR_PAD_LEFT)
           . str_pad(dechex(max(0, min(255, $b))), 2, '0', STR_PAD_LEFT);
}

function centro_servizi_lighten_color(string $hex, int $percent = 20): string
{
    $rgb = centro_servizi_hex_to_rgb($hex);
    if (! $rgb) {
        return $hex;
    }

    $amount = max(0, min(100, $percent)) / 100;
    return centro_servizi_rgb_to_hex(
        (int) round($rgb['r'] + (255 - $rgb['r']) * $amount),
        (int) round($rgb['g'] + (255 - $rgb['g']) * $amount),
        (int) round($rgb['b'] + (255 - $rgb['b']) * $amount)
    );
}

function centro_servizi_darken_color(string $hex, int $percent = 20): string
{
    $rgb = centro_servizi_hex_to_rgb($hex);
    if (! $rgb) {
        return $hex;
    }
    $factor = 1 - ($percent / 100);
    return centro_servizi_rgb_to_hex(
        (int) max(0, $rgb['r'] * $factor),
        (int) max(0, $rgb['g'] * $factor),
        (int) max(0, $rgb['b'] * $factor)
    );
}

// ============================================================================
// FONT UTILITIES
// ============================================================================
function centro_servizi_sanitize_font_key(string $font_key, string $fallback = 'arial'): string
{
    $font_catalog = centro_servizi_get_font_catalog();
    return isset($font_catalog[$font_key]) ? $font_key : $fallback;
}

function centro_servizi_get_font_stack_by_key(string $font_key, string $fallback = 'arial'): string
{
    $font_catalog = centro_servizi_get_font_catalog();
    $safe_key = centro_servizi_sanitize_font_key($font_key, $fallback);
    return $font_catalog[$safe_key]['stack'];
}

function centro_servizi_sanitize_google_fonts_url(string $url): string
{
    $clean_url = esc_url_raw(trim($url));
    if ($clean_url === '') {
        return '';
    }
    $parts = wp_parse_url($clean_url);
    if (! is_array($parts)) {
        return '';
    }
    $scheme = strtolower((string) ($parts['scheme'] ?? ''));
    $host = strtolower((string) ($parts['host'] ?? ''));
    $path = (string) ($parts['path'] ?? '');
    if ($scheme !== 'https' || $host !== 'fonts.googleapis.com' || strpos($path, '/css') !== 0) {
        return '';
    }
    return $clean_url;
}

function centro_servizi_get_typography_profiles(): array
{
    return ['body', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'links', 'buttons'];
}

function centro_servizi_get_typography_defaults(): array
{
    return [
        'body' => ['font_source' => 'catalog', 'font' => 'arial', 'custom_font' => '', 'size' => 16, 'size_unit' => 'px', 'weight' => 400, 'style' => 'normal', 'transform' => 'none', 'color_mode' => 'custom', 'color' => '#1f1f1f', 'color_palette' => 'body'],
        'h1' => ['font_source' => 'catalog', 'font' => 'georgia', 'custom_font' => '', 'size' => 42, 'size_unit' => 'px', 'weight' => 700, 'style' => 'normal', 'transform' => 'none', 'color_mode' => 'custom', 'color' => '#1f1f1f', 'color_palette' => 'body'],
        'h2' => ['font_source' => 'catalog', 'font' => 'georgia', 'custom_font' => '', 'size' => 36, 'size_unit' => 'px', 'weight' => 700, 'style' => 'normal', 'transform' => 'none', 'color_mode' => 'custom', 'color' => '#1f1f1f', 'color_palette' => 'body'],
        'h3' => ['font_source' => 'catalog', 'font' => 'georgia', 'custom_font' => '', 'size' => 28, 'size_unit' => 'px', 'weight' => 700, 'style' => 'normal', 'transform' => 'none', 'color_mode' => 'custom', 'color' => '#1f1f1f', 'color_palette' => 'body'],
        'h4' => ['font_source' => 'catalog', 'font' => 'georgia', 'custom_font' => '', 'size' => 22, 'size_unit' => 'px', 'weight' => 700, 'style' => 'normal', 'transform' => 'none', 'color_mode' => 'custom', 'color' => '#1f1f1f', 'color_palette' => 'body'],
        'h5' => ['font_source' => 'catalog', 'font' => 'georgia', 'custom_font' => '', 'size' => 20, 'size_unit' => 'px', 'weight' => 700, 'style' => 'normal', 'transform' => 'none', 'color_mode' => 'custom', 'color' => '#1f1f1f', 'color_palette' => 'body'],
        'h6' => ['font_source' => 'catalog', 'font' => 'georgia', 'custom_font' => '', 'size' => 18, 'size_unit' => 'px', 'weight' => 700, 'style' => 'normal', 'transform' => 'none', 'color_mode' => 'custom', 'color' => '#1f1f1f', 'color_palette' => 'body'],
        'links' => ['font_source' => 'catalog', 'font' => 'arial', 'custom_font' => '', 'size' => 16, 'size_unit' => 'px', 'weight' => 500, 'style' => 'normal', 'transform' => 'none', 'color_mode' => 'palette', 'color' => '#007acc', 'color_palette' => 'main'],
        'buttons' => ['font_source' => 'catalog', 'font' => 'arial', 'custom_font' => '', 'size' => 16, 'size_unit' => 'px', 'weight' => 600, 'style' => 'normal', 'transform' => 'none', 'color_mode' => 'palette', 'color' => '#ff6b6b', 'color_palette' => 'accent'],
    ];
}

function centro_servizi_get_palette_presets(): array
{
    return [
        // Palette principali richieste
        'giallo' => [
            'label' => 'Sole Giallo',
            'description' => 'Caldo, luminoso e accogliente per scuole con tono familiare.',
            'main' => '#8A5A00',
            'secondary' => '#FFF3C4',
            'body' => '#2A230F',
            'accent' => '#E4A000',
        ],
        'rosso' => [
            'label' => 'Rosso Civico',
            'description' => 'Istituzionale e vicino al mondo cooperativo e parrocchiale.',
            'main' => '#7A1F2B',
            'secondary' => '#F7D8DC',
            'body' => '#2F1A1D',
            'accent' => '#C7435A',
        ],
        'blu' => [
            'label' => 'Blu Fiducia',
            'description' => 'Affidabile, pulito e molto leggibile.',
            'main' => '#1F4E8C',
            'secondary' => '#DCE9F8',
            'body' => '#1A2433',
            'accent' => '#2F80ED',
        ],
        'verde' => [
            'label' => 'Verde Natura',
            'description' => 'Sereno e educativo, adatto all’infanzia.',
            'main' => '#2E6B4A',
            'secondary' => '#DDEFE5',
            'body' => '#1F2E26',
            'accent' => '#5BA06E',
        ],
        'lilla' => [
            'label' => 'Lilla Delicato',
            'description' => 'Morbido e contemporaneo, senza eccessi.',
            'main' => '#6C4A8A',
            'secondary' => '#EBDDFA',
            'body' => '#2D2435',
            'accent' => '#9B6FD6',
        ],
        'arancio' => [
            'label' => 'Arancio Energia',
            'description' => 'Vivace ma controllato, ideale per comunicazione attiva.',
            'main' => '#A24A1E',
            'secondary' => '#FDE3D5',
            'body' => '#352419',
            'accent' => '#E67A38',
        ],

        // Varianti curate per ampliare la libreria senza caos
        'ottanio' => [
            'label' => 'Ottanio Calmo',
            'description' => 'Serio ma moderno, adatto a cooperative strutturate.',
            'main' => '#1F5A63',
            'secondary' => '#D8EEF0',
            'body' => '#1C2B2E',
            'accent' => '#2E8A96',
        ],
        'salvia' => [
            'label' => 'Salvia Gentile',
            'description' => 'Naturale e rassicurante, ottimo per nido e infanzia.',
            'main' => '#4E6E5D',
            'secondary' => '#E5EFE9',
            'body' => '#24312A',
            'accent' => '#7BAA8F',
        ],
        'bordeaux' => [
            'label' => 'Bordeaux Istituzionale',
            'description' => 'Tradizionale e autorevole per enti religiosi.',
            'main' => '#6A1E3B',
            'secondary' => '#F2DCE6',
            'body' => '#2E1A23',
            'accent' => '#A83E67',
        ],
        'cielo' => [
            'label' => 'Cielo Aperto',
            'description' => 'Fresco e arioso, molto leggibile su siti informativi.',
            'main' => '#2A6DB0',
            'secondary' => '#DDEBFA',
            'body' => '#1B2838',
            'accent' => '#56A0F5',
        ],
        'pesca' => [
            'label' => 'Pesca Morbido',
            'description' => 'Accogliente e delicato, ideale per tono familiare.',
            'main' => '#A55A46',
            'secondary' => '#FBE5DD',
            'body' => '#352621',
            'accent' => '#E08A6D',
        ],
        'ardesia' => [
            'label' => 'Ardesia Pulita',
            'description' => 'Neutra elegante con accento caldo, sobria ma moderna.',
            'main' => '#35485A',
            'secondary' => '#E2E8EE',
            'body' => '#1E2329',
            'accent' => '#C37A48',
        ],
    ];
}

function centro_servizi_get_font_mood_presets(): array
{
    return [
        // Core moods
        'pulito' => [
            'label' => 'Pulito Contemporaneo',
            'description' => 'Chiaro e professionale, adatto a cooperative sociali.',
            'body_font' => 'source-sans-3',
            'heading_font' => 'montserrat',
            'label_font' => 'work-sans',
        ],
        'classico' => [
            'label' => 'Classico Serif',
            'description' => 'Con grazie evidenti, istituzionale e caldo per parrocchie e congregazioni.',
            'body_font' => 'merriweather',
            'heading_font' => 'playfair-display',
            'label_font' => 'source-sans-3',
        ],
        'giocoso' => [
            'label' => 'Giocoso Educativo',
            'description' => 'Amichevole e dinamico per scuole dell’infanzia paritarie.',
            'body_font' => 'nunito-sans',
            'heading_font' => 'fredoka',
            'label_font' => 'baloo-2',
        ],
        'lavagna' => [
            'label' => 'Lavagna Creativa',
            'description' => 'Tocco chalk/handwritten per sezioni più giocose in stile kindergarten.',
            'body_font' => 'nunito-sans',
            'heading_font' => 'patrick-hand',
            'label_font' => 'caveat',
        ],

        // Varianti orientate ai contesti reali
        'cooperativa-umana' => [
            'label' => 'Cooperativa Umana',
            'description' => 'Molto leggibile, caldo e contemporaneo.',
            'body_font' => 'open-sans',
            'heading_font' => 'jost',
            'label_font' => 'work-sans',
        ],
        'parrocchia-sobria' => [
            'label' => 'Parrocchia Sobria',
            'description' => 'Serif tradizionale con supporto sans per chiarezza.',
            'body_font' => 'lora',
            'heading_font' => 'merriweather',
            'label_font' => 'source-sans-3',
        ],
        'congregazione-editoriale' => [
            'label' => 'Congregazione Editoriale',
            'description' => 'Taglio editoriale più autorevole ma ancora accessibile.',
            'body_font' => 'merriweather',
            'heading_font' => 'playfair-display',
            'label_font' => 'raleway',
        ],
        'atelier-creativo' => [
            'label' => 'Atelier Creativo',
            'description' => 'Esprimente e giocoso per comunicazione laboratori.',
            'body_font' => 'nunito-sans',
            'heading_font' => 'comfortaa',
            'label_font' => 'baloo-2',
        ],
        'montessori-soft' => [
            'label' => 'Montessori Soft',
            'description' => 'Pulito, morbido e non urlato, adatto a tono pedagogico.',
            'body_font' => 'quicksand',
            'heading_font' => 'bree-serif',
            'label_font' => 'nunito',
        ],
        'chalk-festivo' => [
            'label' => 'Chalk Festivo',
            'description' => 'Più audace: titoli effetto lavagna e testi morbidi.',
            'body_font' => 'nunito-sans',
            'heading_font' => 'amatic-sc',
            'label_font' => 'patrick-hand',
        ],
        'friendly-istituzionale' => [
            'label' => 'Friendly Istituzionale',
            'description' => 'Bilanciato tra affidabilità e tono accogliente.',
            'body_font' => 'lato',
            'heading_font' => 'montserrat',
            'label_font' => 'quicksand',
        ],
    ];
}

function centro_servizi_build_typography_from_mood(string $mood): array
{
    $presets = centro_servizi_get_font_mood_presets();
    $safe_mood = isset($presets[$mood]) ? $mood : 'pulito';
    $preset = $presets[$safe_mood];

    $typography = centro_servizi_get_typography_defaults();
    $heading_profiles = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'];

    $typography['body']['font_source'] = 'catalog';
    $typography['body']['font'] = centro_servizi_sanitize_font_key((string) $preset['body_font'], 'source-sans-3');
    $typography['body']['weight'] = 400;
    $typography['body']['color_mode'] = 'palette';
    $typography['body']['color_palette'] = 'body';

    foreach ($heading_profiles as $profile) {
        $typography[$profile]['font_source'] = 'catalog';
        $typography[$profile]['font'] = centro_servizi_sanitize_font_key((string) $preset['heading_font'], 'montserrat');
        $typography[$profile]['weight'] = 700;
        $typography[$profile]['color_mode'] = 'palette';
        $typography[$profile]['color_palette'] = 'main';
    }

    $typography['links']['font_source'] = 'catalog';
    $typography['links']['font'] = centro_servizi_sanitize_font_key((string) $preset['label_font'], 'work-sans');
    $typography['links']['weight'] = 600;
    $typography['links']['color_mode'] = 'palette';
    $typography['links']['color_palette'] = 'main';

    $typography['buttons']['font_source'] = 'catalog';
    $typography['buttons']['font'] = centro_servizi_sanitize_font_key((string) $preset['label_font'], 'work-sans');
    $typography['buttons']['weight'] = 700;
    $typography['buttons']['color_mode'] = 'palette';
    $typography['buttons']['color_palette'] = 'accent';

    foreach ($typography as $profile => $config) {
        $typography[$profile]['custom_font'] = '';
        $typography[$profile]['style'] = 'normal';
        $typography[$profile]['transform'] = $profile === 'links' || $profile === 'buttons' ? 'uppercase' : 'none';
    }

    return $typography;
}

function centro_servizi_get_typography_size_units(): array
{
    return ['px' => 'px', 'rem' => 'rem', 'em' => 'em', '%' => '%'];
}

function centro_servizi_get_color_palette_choices(): array
{
    return [
        'main' => 'Main',
        'main-light' => 'Main chiaro',
        'main-dark' => 'Main scuro',
        'secondary' => 'Secondary',
        'secondary-light' => 'Secondary chiaro',
        'secondary-dark' => 'Secondary scuro',
        'body' => 'Body',
        'body-light' => 'Body chiaro',
        'body-dark' => 'Body scuro',
        'accent' => 'Accent',
        'accent-light' => 'Accent chiaro',
        'accent-dark' => 'Accent scuro',
    ];
}

function centro_servizi_get_typography_value(array $profile_config, string $key, $default)
{
    return array_key_exists($key, $profile_config) ? $profile_config[$key] : $default;
}

function centro_servizi_normalize_typography(array $typography): array
{
    $defaults = centro_servizi_get_typography_defaults();
    $normalized = [];

    foreach ($defaults as $profile => $default_config) {
        $raw = is_array($typography[$profile] ?? null) ? $typography[$profile] : [];
        $normalized[$profile] = array_merge($default_config, $raw);
    }

    return $normalized;
}

function centro_servizi_get_profile_font_stack(array $config, array $font_catalog): string
{
    $source = (string) centro_servizi_get_typography_value($config, 'font_source', 'catalog');
    if ($source === 'custom-google') {
        $custom_font = trim((string) centro_servizi_get_typography_value($config, 'custom_font', ''));
        if ($custom_font !== '') {
            return '"' . $custom_font . '", Arial, sans-serif';
        }
    }

    $font_key = (string) centro_servizi_get_typography_value($config, 'font', 'arial');
    $safe_key = centro_servizi_sanitize_font_key($font_key, 'arial');
    return (string) ($font_catalog[$safe_key]['stack'] ?? 'Arial, sans-serif');
}

function centro_servizi_get_profile_color_css(array $config): string
{
    $palette_choices = centro_servizi_get_color_palette_choices();
    $color_mode = (string) centro_servizi_get_typography_value($config, 'color_mode', 'custom');
    if ($color_mode === 'palette') {
        $token = (string) centro_servizi_get_typography_value($config, 'color_palette', 'body');
        if (isset($palette_choices[$token])) {
            return 'var(--color-' . $token . ')';
        }
    }

    $fallback = '#1f1f1f';
    $hex = sanitize_hex_color((string) centro_servizi_get_typography_value($config, 'color', $fallback));
    return $hex ?: $fallback;
}

// ============================================================================
// SEED PAGINE OBBLIGATORIE
// ============================================================================
add_action('admin_post_centro_servizi_seed_pages',   'centro_servizi_handle_seed_pages');
add_action('admin_post_centro_servizi_update_pages', 'centro_servizi_handle_update_pages');
add_action('admin_init', 'centro_servizi_migrate_legal_pages_to_dynamic_content', 20);

function centro_servizi_handle_seed_pages(): void
{
    centro_servizi_do_seed_pages(false);
}

function centro_servizi_handle_update_pages(): void
{
    centro_servizi_do_seed_pages(true);
}

function centro_servizi_migrate_legal_pages_to_dynamic_content(): void
{
    if (! current_user_can('manage_options')) {
        return;
    }

    if (get_option('centro_servizi_legal_pages_migrated_v2', '') === '1') {
        return;
    }

    $site_name = get_bloginfo('name');
    $site_url  = get_site_url();
    $seed_pages = centro_servizi_get_seed_pages($site_name, $site_url);

    foreach ($seed_pages as $page) {
        $slug = isset($page['slug']) ? (string) $page['slug'] : '';
        if ($slug === '') {
            continue;
        }

        $existing = get_page_by_path($slug);
        if (! $existing instanceof WP_Post) {
            continue;
        }

        centro_servizi_sync_legal_page_meta($existing->ID, $slug);

        $content = (string) ($existing->post_content ?? '');
        if (strpos($content, '[centro_servizi_') !== false) {
            continue;
        }

        $id = wp_update_post([
            'ID'           => $existing->ID,
            'post_content' => (string) ($page['content'] ?? ''),
        ], true);

        if (is_wp_error($id)) {
            return;
        }
    }

    update_option('centro_servizi_legal_pages_migrated_v2', '1', false);
}

function centro_servizi_sync_legal_page_meta(int $post_id, string $slug): void
{
    $contacts = centro_servizi_seed_load_contacts();
    $address  = isset($contacts['address']) ? (string) $contacts['address'] : '';
    $email    = isset($contacts['email']) ? (string) $contacts['email'] : '';

    $set_meta = static function (int $post_id, string $key, string $value): void {
        $value = trim($value);

        if ($value === '') {
            delete_post_meta($post_id, $key);
            return;
        }

        update_post_meta($post_id, $key, $value);
    };

    if ($slug === 'privacy-policy') {
        $set_meta($post_id, 'legal_address', (string) get_option('centro_servizi_legal_address', $address));
        $set_meta($post_id, 'email_privacy', (string) get_option('centro_servizi_email_privacy', $email));
        $set_meta($post_id, 'referente_privacy', (string) get_option('centro_servizi_referente_privacy', ''));
        $set_meta($post_id, 'dpo_nome', (string) get_option('centro_servizi_dpo_nome', ''));
        $set_meta($post_id, 'email_dpo', (string) get_option('centro_servizi_email_dpo', ''));
        return;
    }

    if ($slug === 'dichiarazione-accessibilita') {
        $set_meta($post_id, 'dpo_nome', (string) get_option('centro_servizi_dpo_nome', ''));
        $set_meta($post_id, 'email_dpo', (string) get_option('centro_servizi_email_dpo', $email));
        $set_meta($post_id, 'email_privacy', (string) get_option('centro_servizi_email_privacy', $email));
        $set_meta($post_id, 'referente_privacy', (string) get_option('centro_servizi_referente_privacy', ''));
        $set_meta($post_id, 'url_dichiarazione_agid', (string) get_option('centro_servizi_url_dichiarazione_agid', ''));
        return;
    }

    if ($slug === 'whistleblowing') {
        $set_meta($post_id, 'url_whistleblowing', (string) get_option('centro_servizi_url_whistleblowing', ''));
        $set_meta($post_id, 'whistleblowing_responsabile', (string) get_option('centro_servizi_whistleblowing_responsabile', ''));
    }
}

function centro_servizi_do_seed_pages(bool $overwrite): void
{
    if (! current_user_can('manage_options')) {
        wp_die('Accesso negato.');
    }

    if (
        ! isset($_POST['centro_servizi_seed_nonce']) ||
        ! wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['centro_servizi_seed_nonce'])), 'centro_servizi_seed_pages')
    ) {
        wp_die('Verifica di sicurezza fallita.');
    }

    $site_name = get_bloginfo('name');
    $site_url  = get_site_url();
    $results   = [];

    foreach (centro_servizi_get_seed_pages($site_name, $site_url) as $page) {
        $existing = get_page_by_path($page['slug']);

        if ($existing && ! $overwrite) {
            $results[] = sprintf(
                '⚠️ Già esistente (non modificata): <strong>%s</strong>',
                esc_html($page['title'])
            );
            continue;
        }

        if ($existing && $overwrite) {
            $id = wp_update_post([
                'ID'           => $existing->ID,
                'post_title'   => $page['title'],
                'post_content' => $page['content'],
            ], true);

            if (is_wp_error($id)) {
                $results[] = sprintf(
                    '❌ Errore aggiornando <strong>%s</strong>: %s',
                    esc_html($page['title']),
                    esc_html($id->get_error_message())
                );
            } else {
                $edit_url = get_edit_post_link($id, 'raw');
                $results[] = sprintf(
                    '🔄 Aggiornata: <a href="%s"><strong>%s</strong></a>',
                    esc_url((string) $edit_url),
                    esc_html($page['title'])
                );
            }
            continue;
        }

        // nuova pagina
        $id = wp_insert_post([
            'post_title'   => $page['title'],
            'post_name'    => $page['slug'],
            'post_content' => $page['content'],
            'post_status'  => 'draft',
            'post_type'    => 'page',
            'post_author'  => get_current_user_id(),
        ]);

        if (is_wp_error($id)) {
            $results[] = sprintf(
                '❌ Errore creando <strong>%s</strong>: %s',
                esc_html($page['title']),
                esc_html($id->get_error_message())
            );
        } else {
            $edit_url = get_edit_post_link($id, 'raw');
            $results[] = sprintf(
                '✅ Creata in bozza: <a href="%s"><strong>%s</strong></a>',
                esc_url((string) $edit_url),
                esc_html($page['title'])
            );
        }
    }

    set_transient('centro_servizi_seed_results_' . get_current_user_id(), $results, 120);
    wp_safe_redirect(admin_url('admin.php?page=centro-servizi-settings-normative'));
    exit;
}

/**
 * Legge i contatti salvati nelle impostazioni e li indicizza per tipo.
 * Restituisce array [ 'email' => 'info@...', 'phone' => '...', 'pec' => '...', 'address' => '...' ].
 * Se un tipo ha più voci, usa la prima.
 */
function centro_servizi_seed_load_contacts(): array
{
    $json = get_option('centro_servizi_contacts', '[]');
    $raw  = json_decode($json, true);
    if (! is_array($raw)) {
        return [];
    }
    $indexed = [];
    foreach ($raw as $contact) {
        $type  = (string) ($contact['type']  ?? '');
        $value = (string) ($contact['value'] ?? '');
        if ($type !== '' && $value !== '' && ! isset($indexed[$type])) {
            $indexed[$type] = $value;
        }
    }
    return $indexed;
}

function centro_servizi_get_seed_pages(string $site_name, string $site_url): array
{
    $contacts                     = centro_servizi_seed_load_contacts();
    $legale_rappresentante        = trim((string) get_option('centro_servizi_legale_rappresentante', ''));
    $dpo_nome_option              = trim((string) get_option('centro_servizi_dpo_nome', ''));
    $dpo_email_option             = trim((string) get_option('centro_servizi_email_dpo', ''));
    $email_privacy                = trim((string) get_option('centro_servizi_email_privacy', ''));
    $contact_email                = isset($contacts['email']) ? trim((string) $contacts['email']) : '';
    $dpo_nome                     = $dpo_nome_option !== '' ? $dpo_nome_option : $legale_rappresentante;
    $dpo_email                    = $dpo_email_option !== '' ? $dpo_email_option : ($email_privacy !== '' ? $email_privacy : $contact_email);
    $url_whistleblowing           = (string) get_option('centro_servizi_url_whistleblowing', '');
    $url_dichiarazione_agid       = (string) get_option('centro_servizi_url_dichiarazione_agid', '');
    $whistleblowing_responsabile  = (string) get_option('centro_servizi_whistleblowing_responsabile', '');

    return [
        [
            'title'   => 'Privacy Policy',
            'slug'    => 'privacy-policy',
            'content' => centro_servizi_seed_content_privacy($site_name, $site_url, $contacts, $dpo_nome, $dpo_email),
        ],
        [
            'title'   => 'Cookie Policy',
            'slug'    => 'cookie-policy',
            'content' => centro_servizi_seed_content_cookie($site_name),
        ],
        [
            'title'   => 'Contatti',
            'slug'    => 'contatti',
            'content' => centro_servizi_seed_content_contatti($site_name, $contacts),
        ],
        [
            'title'   => 'Dichiarazione di Accessibilità',
            'slug'    => 'dichiarazione-accessibilita',
            'content' => centro_servizi_seed_content_accessibilita($site_name, $site_url, $contacts, $dpo_nome, $dpo_email, $url_dichiarazione_agid),
        ],
        [
            'title'   => 'Segnalazioni Whistleblowing',
            'slug'    => 'whistleblowing',
            'content' => centro_servizi_seed_content_whistleblowing($site_name, $url_whistleblowing, $whistleblowing_responsabile),
        ],
        [
            'title'   => 'Obiettivi di Accessibilità',
            'slug'    => 'obiettivi-accessibilita',
            'content' => centro_servizi_seed_content_obiettivi($site_name),
        ],
    ];
}

function centro_servizi_seed_content_privacy(string $site_name, string $site_url, array $contacts = [], string $dpo_nome = '', string $email_dpo = ''): string
{
    $year    = (string) (int) date('Y');
    $address = isset($contacts['address']) ? esc_html($contacts['address']) : '[indirizzo sede legale]';
    $email   = isset($contacts['email'])   ? $contacts['email']             : '';

    $dpo_display = $dpo_nome !== '' ? esc_html($dpo_nome) . ' — ' : '';

    return '<p><em>Informativa ai sensi dell\'art. 13 del Regolamento UE 2016/679 (GDPR) — ' . esc_html($site_name) . ' — aggiornata al ' . esc_html($year) . '</em></p>

<h2>Titolare del trattamento</h2>
<p>' . esc_html($site_name) . '<br />
' . '[centro_servizi_privacy_address]' . '<br />
Email: [centro_servizi_privacy_email]</p>

<h2>Responsabile della Protezione dei Dati</h2>
<ul>
[centro_servizi_privacy_dpo]
</ul>

<h2>Quali dati raccogliamo e perché</h2>

<h3>Dati di navigazione (log del server)</h3>
<p>I sistemi informatici acquisiscono automaticamente alcuni dati la cui trasmissione è implicita nell\'uso di Internet (indirizzo IP, tipo di browser, pagine visitate, ora e data). Sono usati al solo fine di garantire il corretto funzionamento del sito e ricavare statistiche anonime aggregate. Non vengono associati a utenti identificati. <strong>Base giuridica:</strong> interesse legittimo (art. 6 par. 1 lett. f GDPR). <strong>Conservazione:</strong> massimo 30 giorni.</p>

<h3>Cookie analitici (Google Analytics 4)</h3>
<p>Utilizziamo Google Analytics 4 per raccogliere statistiche aggregate e anonimizzate sull\'utilizzo del sito (pagine visitate, durata, provenienza geografica approssimativa). I dati sono trasmessi a Google Ireland Ltd. (UE) e, per l\'elaborazione tecnica, a Google LLC (USA) nel rispetto delle Clausole Contrattuali Standard approvate dalla Commissione Europea. <strong>Base giuridica:</strong> consenso (art. 6 par. 1 lett. a GDPR), espresso tramite il banner cookie. Puoi revocare il consenso in qualsiasi momento dalla <a href="' . esc_url($site_url) . '/cookie-policy">Cookie Policy</a>. <strong>Conservazione:</strong> 14 mesi.</p>

<h3>Immagini pubblicate</h3>
<p>Il sito pubblica fotografie fornite dalla struttura scolastica. Le immagini in cui compaiono minori sono trattate nel rispetto delle indicazioni del Garante per la protezione dei dati personali. Il consenso al trattamento delle immagini è raccolto direttamente dalla scuola dai genitori o tutori legali, al di fuori di questo sito. Il sito non raccoglie direttamente dati personali tramite moduli online.</p>

<h2>A chi comunichiamo i dati</h2>
<ul>
<li><strong>Provider di hosting</strong> — server ubicato nell\'Unione Europea, per il funzionamento tecnico del sito.</li>
<li><strong>Google Ireland Ltd. / Google LLC</strong> — per Google Analytics 4, in base a DPA e Clausole Contrattuali Standard.</li>
</ul>
<p>I dati non vengono ceduti a terzi né utilizzati per finalità di profilazione o marketing.</p>

<h2>Trasferimenti extra-UE</h2>
<p>I dati analitici raccolti tramite Google Analytics 4 sono soggetti a trasferimento verso gli USA da parte di Google LLC. Il trasferimento avviene nel rispetto delle Clausole Contrattuali Standard (Decisione CE 2021/914) e dell\'EU-US Data Privacy Framework.</p>

<h2>I tuoi diritti</h2>
<p>Hai il diritto di: accedere ai tuoi dati personali; chiederne la rettifica o la cancellazione; opporti al trattamento; richiedere la limitazione; revocare il consenso in qualsiasi momento (senza pregiudicare la liceità del trattamento precedente); presentare reclamo al <a href="https://www.garanteprivacy.it" rel="noopener noreferrer">Garante per la protezione dei dati personali</a>.</p>
<p>Per esercitare i tuoi diritti: [centro_servizi_privacy_email]</p>

<h2>Modifiche all\'informativa</h2>
<p>Il titolare si riserva di aggiornare questa informativa. La versione vigente è sempre disponibile a questa pagina con la data di ultima modifica indicata in apertura.</p>';
}

function centro_servizi_seed_content_cookie(string $site_name): string
{
    $year = (string) (int) date('Y');
    return '<p><em>Informativa sull\'uso dei cookie — ' . esc_html($site_name) . ' — aggiornata al ' . esc_html($year) . '</em></p>

<h2>Cosa sono i cookie</h2>
<p>I cookie sono piccoli file di testo salvati nel browser quando si visita un sito web. Permettono al sito di ricordare preferenze e di raccogliere informazioni sull\'utilizzo in forma anonima o aggregata.</p>

<h2>Cookie utilizzati da questo sito</h2>
<p>La tabella seguente elenca i cookie rilevati dal nostro sistema di gestione del consenso. I cookie analitici vengono attivati solo dopo consenso esplicito dell\'utente.</p>

[cookie_declaration]

<h2>Cookie tecnici (necessari)</h2>
<p>I cookie tecnici sono strettamente necessari al funzionamento del sito e non richiedono consenso. Includono il cookie del pannello di gestione del consenso (CookieYes) che memorizza le tue scelte.</p>

<h2>Cookie analitici — Google Analytics 4</h2>
<p>Utilizziamo Google Analytics 4 per raccogliere dati statistici in forma anonimizzata (pagine visitate, durata della visita, provenienza geografica approssimativa). Questi cookie vengono installati solo previo consenso esplicito.</p>
<p>Puoi revocare il consenso in qualsiasi momento tramite il pulsante qui sotto o la voce "Gestisci preferenze" nel footer:</p>

[cky-preference-link link_text="Modifica preferenze cookie"]

<h2>Cookie di terze parti</h2>
<p>Google Analytics 4 è gestito da Google Ireland Ltd. Per approfondire: <a href="https://policies.google.com/privacy" rel="noopener noreferrer">Privacy Policy di Google</a> · <a href="https://tools.google.com/dlpage/gaoptout" rel="noopener noreferrer">Strumento di opt-out da Google Analytics</a>.</p>

<h2>Come disabilitare i cookie dal browser</h2>
<p>Puoi anche impostare il browser per rifiutare tutti i cookie o per avvisarti quando viene inviato un cookie. Consulta le istruzioni specifiche del tuo browser (Chrome, Firefox, Safari, Edge).</p>';
}

function centro_servizi_seed_content_contatti(string $site_name, array $contacts = []): string
{
    $address = isset($contacts['address']) ? esc_html($contacts['address']) : '[da completare: indirizzo]';
    $phone   = isset($contacts['phone'])   ? esc_html($contacts['phone'])   : '[da completare: telefono]';
    $email   = $contacts['email'] ?? '';
    $pec     = $contacts['pec']   ?? '';

    $email_li = $email
        ? '<li><strong>Email:</strong> <a href="mailto:' . esc_attr($email) . '">' . esc_html($email) . '</a></li>'
        : '<li><strong>Email:</strong> [da completare]</li>';
    $pec_li = $pec
        ? '<li><strong>PEC:</strong> <a href="mailto:' . esc_attr($pec) . '">' . esc_html($pec) . '</a></li>'
        : '<li><strong>PEC:</strong> [da completare]</li>';

    return '<h2>Come contattarci</h2>
<p>Per informazioni, iscrizioni e comunicazioni con ' . esc_html($site_name) . ' puoi utilizzare i recapiti riportati di seguito.</p>

<h3>Sede</h3>
<p>' . $address . '</p>

<h3>Recapiti</h3>
<ul>
<li><strong>Telefono:</strong> ' . $phone . '</li>
' . $email_li . '
' . $pec_li . '
</ul>

<h3>Orari di segreteria</h3>
<p>[da completare]</p>

<h3>Come raggiungerci</h3>
<p>[da completare: indicazioni stradali o mappa embed]</p>';
}

function centro_servizi_seed_content_accessibilita(string $site_name, string $site_url, array $contacts = [], string $dpo_nome = '', string $email_dpo = '', string $url_dichiarazione_agid = ''): string
{
    $email       = $contacts['email'] ?? '';
    return '<p>Questa pagina descrive lo stato di conformità di <strong>' . esc_html($site_name) . '</strong> (' . esc_html($site_url) . ') rispetto ai requisiti di accessibilità previsti dalla Direttiva UE 2016/2102, dalla L. 4/2004 e dal D.Lgs. 106/2018.</p>

<h2>Dichiarazione ufficiale su AGID</h2>
<p>La dichiarazione di accessibilità ufficiale è compilata e pubblicata sul portale dell\'Agenzia per l\'Italia Digitale (AGID):</p>
<p>[centro_servizi_accessibilita_agid_link]</p>
<p><small>Per compilare o aggiornare la dichiarazione accedere a <a href="https://form.agid.gov.it" rel="noopener noreferrer">form.agid.gov.it</a> con SPID o CIE.</small></p>

<h2>Stato di conformità</h2>
<p>Il sito è <strong>parzialmente conforme</strong> alla norma EN 301 549 V3.2.1 (WCAG 2.1 livello AA), in ragione delle non conformità e deroghe elencate nella dichiarazione pubblicata su AGID.</p>

<h2>Feedback e contatti</h2>
<p>Hai riscontrato un problema di accessibilità o hai bisogno di un contenuto in formato alternativo?</p>
<ul>
<li>Soggetto responsabile: <strong>' . esc_html($site_name) . '</strong></li>
[centro_servizi_privacy_dpo]
[centro_servizi_accessibilita_contact]
</ul>

<h2>Procedura di attuazione</h2>
<p>In caso di risposta insoddisfacente entro 30 giorni è possibile rivolgersi al <a href="https://www.agid.gov.it/it/design-servizi/accessibilita/difensore-civico-digitale" rel="noopener noreferrer">Difensore Civico per il Digitale</a>.</p>';
}

function centro_servizi_seed_content_whistleblowing(string $site_name, string $url_whistleblowing = '', string $responsabile_canale = ''): string
{
    return '<p>' . esc_html($site_name) . ' garantisce canali sicuri e riservati per la segnalazione di condotte illecite, in conformità al D.Lgs. 10 marzo 2023, n. 24, che ha recepito la Direttiva UE 2019/1937.</p>

<h2>Come effettuare una segnalazione</h2>
<p>Le segnalazioni possono essere effettuate in forma <strong>riservata o anonima</strong> tramite la piattaforma sicura dedicata:</p>
<p>[centro_servizi_whistleblowing_link]</p>
<p>La piattaforma utilizza cifratura end-to-end e non registra dati identificativi del segnalante salvo quelli che sceglie volontariamente di fornire.</p>

<h2>Chi può segnalare</h2>
<p>Possono effettuare segnalazioni le persone che lavorano o hanno lavorato per ' . esc_html($site_name) . ': dipendenti, collaboratori, fornitori, tirocinanti, volontari e chiunque venga a conoscenza di possibili illeciti nell\'ambito della propria attività lavorativa.</p>

<h2>Cosa si può segnalare</h2>
<p>Violazioni di disposizioni normative nazionali o dell\'Unione Europea che ledono l\'interesse pubblico o l\'integrità dell\'organizzazione, di cui il segnalante sia venuto a conoscenza nel contesto lavorativo.</p>

<h2>Tutele garantite</h2>
<p>Il segnalante in buona fede è protetto da qualsiasi forma di ritorsione (licenziamento, demansionamento, sanzioni disciplinari, discriminazioni). L\'identità è tutelata e non può essere rivelata senza consenso. Le segnalazioni in mala fede, calunniose o diffamatorie non beneficiano di tali tutele.</p>

<h2>Trattamento dei dati personali</h2>
<p>I dati personali eventualmente presenti nella segnalazione sono trattati nel rispetto del GDPR (Reg. UE 2016/679). Base giuridica: obbligo legale (art. 6 par. 1 lett. c GDPR). Conservazione: non oltre 5 anni dalla comunicazione dell\'esito della segnalazione.</p>

<h2>Responsabile del canale interno</h2>
<p>[centro_servizi_whistleblowing_responsabile]</p>';
}

function centro_servizi_seed_content_obiettivi(string $site_name): string
{
    $year = (string) (int) date('Y');
    return '<p>In conformità all\'art. 9-ter del D.Lgs. 82/2005 (Codice dell\'Amministrazione Digitale), ' . esc_html($site_name) . ' pubblica gli obiettivi annuali di accessibilità del proprio sito istituzionale.</p>

<h2>Obiettivi ' . esc_html($year) . '</h2>
<ul>
<li>[Inserire obiettivo 1 — es. completamento audit WCAG 2.1 AA]</li>
<li>[Inserire obiettivo 2 — es. formazione del personale sull\'accessibilità digitale]</li>
<li>[Inserire obiettivo 3 — es. revisione del contrasto cromatico della palette]</li>
</ul>

<h2>Stato di avanzamento</h2>
<p>[Descrivere lo stato attuale di raggiungimento degli obiettivi]</p>

<h2>Pubblicazione</h2>
<p>Data di pubblicazione: ' . esc_html($year) . '</p>';
}

// ============================================================================
// RENDERING PAGINA IMPOSTAZIONI
// ============================================================================
function centro_servizi_render_settings_page(string $active_section = 'style'): void
{
    if (! current_user_can('manage_options')) {
        wp_die('Accesso negato.');
    }

    $sections = centro_servizi_get_settings_sections();
    if (! isset($sections[$active_section])) {
        $active_section = 'style';
    }

    // Salvare i dati se il form è stato inviato
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['centro_servizi_nonce'])) {
        if (! wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['centro_servizi_nonce'])), 'centro_servizi_settings')) {
            wp_die('Verifica di sicurezza fallita.');
        }

        // Rimuove gli slash aggiunti automaticamente da WordPress (add_magic_quotes)
        $_post = wp_unslash($_POST);

        // PRESET VISIVI OBBLIGATORI (palette + tono font)
        $palette_presets = centro_servizi_get_palette_presets();
        $selected_palette = sanitize_key((string) ($_post['palette_preset'] ?? 'blu'));
        if (! isset($palette_presets[$selected_palette])) {
            $selected_palette = 'blu';
        }
        $palette = $palette_presets[$selected_palette];

        update_option('centro_servizi_palette_preset', $selected_palette);
        update_option('centro_servizi_color_main', (string) $palette['main']);
        update_option('centro_servizi_color_secondary', (string) $palette['secondary']);
        update_option('centro_servizi_color_body', (string) $palette['body']);
        update_option('centro_servizi_color_accent', (string) $palette['accent']);

        $font_moods = centro_servizi_get_font_mood_presets();
        $selected_font_mood = sanitize_key((string) ($_post['font_mood_preset'] ?? 'pulito'));
        if (! isset($font_moods[$selected_font_mood])) {
            $selected_font_mood = 'pulito';
        }

        update_option('centro_servizi_font_mood_preset', $selected_font_mood);
        update_option('centro_servizi_typography', wp_json_encode(centro_servizi_build_typography_from_mood($selected_font_mood)));
        update_option('centro_servizi_google_fonts_url', '');

        // HOMEPAGE
        update_option('centro_servizi_homepage_title', sanitize_text_field($_post['homepage_title'] ?? ''));
        update_option('centro_servizi_homepage_subtitle', sanitize_textarea_field($_post['homepage_subtitle'] ?? ''));

        // CONTATTI (dinamici)
        $contacts = [];
        if (isset($_post['contact_type']) && is_array($_post['contact_type'])) {
            foreach ($_post['contact_type'] as $index => $type) {
                $type = sanitize_text_field($type);
                $label = sanitize_text_field($_post['contact_label'][$index] ?? '');
                $value = '';

                if ($type === 'email') {
                    $value = sanitize_email($_post['contact_value'][$index] ?? '');
                } elseif ($type === 'phone') {
                    $value = sanitize_text_field($_post['contact_value'][$index] ?? '');
                } elseif ($type === 'pec') {
                    $value = sanitize_email($_post['contact_value'][$index] ?? '');
                } elseif ($type === 'address') {
                    $value = sanitize_textarea_field($_post['contact_value'][$index] ?? '');
                } else {
                    $value = sanitize_text_field($_post['contact_value'][$index] ?? '');
                }

                if (! empty($value)) {
                    $contacts[] = [
                        'type' => $type,
                        'label' => $label,
                        'value' => $value,
                    ];
                }
            }
        }
        update_option('centro_servizi_contacts', wp_json_encode($contacts));

        // FOOTER
        update_option('centro_servizi_footer_text', sanitize_textarea_field($_post['footer_text'] ?? ''));

        // DATI LEGALI FOOTER
        update_option('centro_servizi_legal_company_name', sanitize_text_field($_post['legal_company_name'] ?? ''));
        update_option('centro_servizi_legal_vat', sanitize_text_field($_post['legal_vat'] ?? ''));
        update_option('centro_servizi_legal_fiscal_code', sanitize_text_field($_post['legal_fiscal_code'] ?? ''));
        update_option('centro_servizi_legal_mecc', sanitize_text_field($_post['legal_mecc'] ?? ''));
        update_option('centro_servizi_legal_rea', sanitize_text_field($_post['legal_rea'] ?? ''));
        update_option('centro_servizi_legal_address', sanitize_textarea_field($_post['legal_address'] ?? ''));
        update_option('centro_servizi_accessibility_feedback_url', esc_url_raw(sanitize_text_field($_post['accessibility_feedback_url'] ?? '')));

        // MAPPA
        $maps_embed_url = esc_url_raw(sanitize_text_field($_post['maps_embed_url'] ?? ''));
        update_option('centro_servizi_maps_embed_url', $maps_embed_url);

        // PRIVACY & GDPR
        update_option('centro_servizi_legale_rappresentante', sanitize_text_field($_post['legale_rappresentante'] ?? ''));
        update_option('centro_servizi_dpo_nome', sanitize_text_field($_post['dpo_nome'] ?? ''));
        update_option('centro_servizi_email_dpo', sanitize_email($_post['email_dpo'] ?? ''));
        update_option('centro_servizi_email_privacy', sanitize_email($_post['email_privacy'] ?? ''));
        update_option('centro_servizi_pec_privacy', sanitize_email($_post['pec_privacy'] ?? ''));
        update_option('centro_servizi_referente_privacy', sanitize_text_field($_post['referente_privacy'] ?? ''));
        update_option('centro_servizi_url_dichiarazione_agid', esc_url_raw(sanitize_text_field($_post['url_dichiarazione_agid'] ?? '')));
        update_option('centro_servizi_whistleblowing_responsabile', sanitize_text_field($_post['whistleblowing_responsabile'] ?? ''));
        $url_wb = esc_url_raw(sanitize_text_field($_post['url_whistleblowing'] ?? ''));
        update_option('centro_servizi_url_whistleblowing', $url_wb);

        // SOCIAL MEDIA
        update_option('centro_servizi_social_facebook', esc_url_raw(sanitize_text_field($_post['social_facebook'] ?? '')));
        update_option('centro_servizi_social_instagram', esc_url_raw(sanitize_text_field($_post['social_instagram'] ?? '')));

        echo '<div class="notice notice-success"><p>Impostazioni salvate con successo!</p></div>';
    }

    // Carica valori correnti (pilotati da preset)
    $palette_presets = centro_servizi_get_palette_presets();
    $font_mood_presets = centro_servizi_get_font_mood_presets();

    $palette_preset = sanitize_key((string) get_option('centro_servizi_palette_preset', 'blu'));
    if (! isset($palette_presets[$palette_preset])) {
        $palette_preset = 'blu';
    }
    $active_palette = $palette_presets[$palette_preset];

    $font_mood_preset = sanitize_key((string) get_option('centro_servizi_font_mood_preset', 'pulito'));
    if (! isset($font_mood_presets[$font_mood_preset])) {
        $font_mood_preset = 'pulito';
    }

    $color_main = (string) get_option('centro_servizi_color_main', (string) $active_palette['main']);
    $color_secondary = (string) get_option('centro_servizi_color_secondary', (string) $active_palette['secondary']);
    $color_body = (string) get_option('centro_servizi_color_body', (string) $active_palette['body']);
    $color_accent = (string) get_option('centro_servizi_color_accent', (string) $active_palette['accent']);

    $typography_json = get_option('centro_servizi_typography', wp_json_encode(centro_servizi_get_typography_defaults()));
    $typography = centro_servizi_normalize_typography(json_decode($typography_json, true) ?: []);
    $profiles = centro_servizi_get_typography_profiles();
    $size_units = centro_servizi_get_typography_size_units();
    $palette_choices = centro_servizi_get_color_palette_choices();

    $google_fonts_url = '';
    $homepage_title = get_option('centro_servizi_homepage_title', 'Centro Servizi');
    $homepage_subtitle = get_option('centro_servizi_homepage_subtitle', '');
    $contacts_json = get_option('centro_servizi_contacts', '[]');
    $contacts = json_decode($contacts_json, true) ?: [];
    $footer_text = get_option('centro_servizi_footer_text', '');
    $legale_rappresentante = get_option('centro_servizi_legale_rappresentante', '');
    $dpo_nome = get_option('centro_servizi_dpo_nome', '');
    $email_dpo = get_option('centro_servizi_email_dpo', '');
    $email_privacy = get_option('centro_servizi_email_privacy', '');
    $pec_privacy = get_option('centro_servizi_pec_privacy', '');
    $referente_privacy = get_option('centro_servizi_referente_privacy', '');
    $url_dichiarazione_agid = get_option('centro_servizi_url_dichiarazione_agid', '');
    $whistleblowing_responsabile = get_option('centro_servizi_whistleblowing_responsabile', '');
    $url_whistleblowing = get_option('centro_servizi_url_whistleblowing', '');
    $maps_embed_url = get_option('centro_servizi_maps_embed_url', '');
    $legal_company_name = get_option('centro_servizi_legal_company_name', '');
    $legal_vat = get_option('centro_servizi_legal_vat', '');
    $legal_fiscal_code = get_option('centro_servizi_legal_fiscal_code', '');
    $legal_mecc = get_option('centro_servizi_legal_mecc', '');
    $legal_rea = get_option('centro_servizi_legal_rea', '');
    $legal_address = get_option('centro_servizi_legal_address', '');
    $accessibility_feedback_url = get_option('centro_servizi_accessibility_feedback_url', '');
    $social_facebook = get_option('centro_servizi_social_facebook', '');
    $social_instagram = get_option('centro_servizi_social_instagram', '');

    $fonts = centro_servizi_get_font_catalog();
    uasort($fonts, static function (array $a, array $b): int {
        return strcmp($a['label'], $b['label']);
    });

    $preview_font_families = [];
    foreach ($font_mood_presets as $preset) {
        foreach (['body_font', 'heading_font', 'label_font'] as $font_slot) {
            $font_key = centro_servizi_sanitize_font_key((string) ($preset[$font_slot] ?? 'arial'), 'arial');
            $family = trim((string) ($fonts[$font_key]['google_family'] ?? ''));
            if ($family !== '') {
                $preview_font_families[$family] = true;
            }
        }
    }

    $admin_preview_fonts_url = '';
    if ($preview_font_families !== []) {
        $font_params = [];
        foreach (array_keys($preview_font_families) as $family) {
            $encoded = str_replace('%20', '+', rawurlencode($family));
            $font_params[] = 'family=' . $encoded;
        }
        $font_params[] = 'display=swap';
        $admin_preview_fonts_url = 'https://fonts.googleapis.com/css2?' . implode('&', $font_params);
    }

    $contact_types = [
        'email' => 'Email',
        'phone' => 'Telefono',
        'pec' => 'PEC',
        'address' => 'Indirizzo',
        'fax' => 'Fax',
        'website' => 'Sito web',
        'social' => 'Social',
    ];

    $heading_labels = [
        'body' => 'Corpo testo',
        'h1' => 'Titolo H1',
        'h2' => 'Titolo H2',
        'h3' => 'Titolo H3',
        'h4' => 'Titolo H4',
        'h5' => 'Titolo H5',
        'h6' => 'Titolo H6',
        'links' => 'Link',
        'buttons' => 'Pulsanti',
    ];

    $palette_preview_map = [
        'main' => $color_main,
        'main-light' => centro_servizi_lighten_color($color_main, 55),
        'main-dark' => centro_servizi_darken_color($color_main, 20),
        'secondary' => $color_secondary,
        'secondary-light' => centro_servizi_lighten_color($color_secondary, 55),
        'secondary-dark' => centro_servizi_darken_color($color_secondary, 20),
        'body' => $color_body,
        'body-light' => centro_servizi_lighten_color($color_body, 55),
        'body-dark' => centro_servizi_darken_color($color_body, 20),
        'accent' => $color_accent,
        'accent-light' => centro_servizi_lighten_color($color_accent, 55),
        'accent-dark' => centro_servizi_darken_color($color_accent, 20),
    ];
    ?>

    <?php if ($admin_preview_fonts_url !== '') : ?>
        <link rel="stylesheet" href="<?php echo esc_url($admin_preview_fonts_url); ?>" />
    <?php endif; ?>

    <div class="wrap">
        <h1>Impostazioni Sito - <?php echo esc_html($sections[$active_section]['title']); ?></h1>

        <nav class="centro-servizi-settings-nav" aria-label="Sezioni impostazioni">
            <?php foreach ($sections as $section_key => $section): ?>
                <a
                    class="centro-servizi-settings-nav__link <?php echo $section_key === $active_section ? 'is-active' : ''; ?>"
                    href="<?php echo esc_url(admin_url('admin.php?page=' . $section['slug'])); ?>"
                >
                    <?php echo esc_html($section['title']); ?>
                </a>
            <?php endforeach; ?>
        </nav>
        <p class="description" style="margin: 8px 0 16px;"><?php echo esc_html($sections[$active_section]['description']); ?></p>

        <form method="post" class="centro-servizi-settings-form">
            <?php wp_nonce_field('centro_servizi_settings', 'centro_servizi_nonce'); ?>

            <div class="settings-section" data-settings-group="style">
                <h2>🎨 Template Palette</h2>
                <p class="description">La palette è vincolata a preset studiati per evitare combinazioni incoerenti.</p>
                <fieldset class="preset-chip-grid" aria-label="Anteprima palette disponibili">
                    <legend class="screen-reader-text">Seleziona una palette colore</legend>
                    <?php foreach ($palette_presets as $key => $preset): ?>
                        <label class="preset-chip preset-choice-card <?php echo $key === $palette_preset ? 'is-active' : ''; ?>">
                            <input
                                class="preset-choice-input"
                                type="radio"
                                name="palette_preset"
                                value="<?php echo esc_attr($key); ?>"
                                <?php checked($palette_preset, $key); ?>
                            />
                            <strong><?php echo esc_html($preset['label']); ?></strong>
                            <div class="preset-chip-swatches">
                                <span style="background: <?php echo esc_attr($preset['main']); ?>" title="Main"></span>
                                <span style="background: <?php echo esc_attr($preset['secondary']); ?>" title="Secondary"></span>
                                <span style="background: <?php echo esc_attr($preset['body']); ?>" title="Body"></span>
                                <span style="background: <?php echo esc_attr($preset['accent']); ?>" title="Accent"></span>
                            </div>
                            <small><?php echo esc_html($preset['description']); ?></small>
                        </label>
                    <?php endforeach; ?>
                </fieldset>
            </div>

            <div class="settings-section" data-settings-group="style">
                <h2>🔤 Gruppi Font (Mood)</h2>
                <p class="description">I font sono appaiati per tono comunicativo: pulito, classico o giocoso.</p>
                <fieldset class="mood-chip-grid" aria-label="Anteprima mood tipografici">
                    <legend class="screen-reader-text">Seleziona un mood tipografico</legend>
                    <?php foreach ($font_mood_presets as $key => $preset): ?>
                        <?php
                        $heading_key = centro_servizi_sanitize_font_key((string) ($preset['heading_font'] ?? 'montserrat'), 'montserrat');
                        $body_key = centro_servizi_sanitize_font_key((string) ($preset['body_font'] ?? 'source-sans-3'), 'source-sans-3');
                        $label_key = centro_servizi_sanitize_font_key((string) ($preset['label_font'] ?? 'work-sans'), 'work-sans');

                        $heading_stack = (string) ($fonts[$heading_key]['stack'] ?? 'Arial, sans-serif');
                        $body_stack = (string) ($fonts[$body_key]['stack'] ?? 'Arial, sans-serif');
                        $label_stack = (string) ($fonts[$label_key]['stack'] ?? 'Arial, sans-serif');
                        ?>
                        <label class="mood-chip preset-choice-card <?php echo $key === $font_mood_preset ? 'is-active' : ''; ?>">
                            <input
                                class="preset-choice-input"
                                type="radio"
                                name="font_mood_preset"
                                value="<?php echo esc_attr($key); ?>"
                                <?php checked($font_mood_preset, $key); ?>
                            />
                            <strong><?php echo esc_html($preset['label']); ?></strong>
                            <p class="mood-chip__title" style="font-family: <?php echo esc_attr($heading_stack); ?>;">Scuola dell'infanzia paritaria</p>
                            <p class="mood-chip__body" style="font-family: <?php echo esc_attr($body_stack); ?>;">Ambiente accogliente, crescita serena e relazione educativa.</p>
                            <span class="mood-chip__label" style="font-family: <?php echo esc_attr($label_stack); ?>;">ISCRIZIONI APERTE</span>
                            <small><?php echo esc_html($preset['description']); ?></small>
                        </label>
                    <?php endforeach; ?>
                </fieldset>
            </div>

            <!-- HOMEPAGE -->
            <div class="settings-section" data-settings-group="style">
                <h2>Homepage</h2>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="homepage_title">Titolo principale:</label></th>
                        <td>
                            <input type="text" id="homepage_title" name="homepage_title" value="<?php echo esc_attr($homepage_title); ?>" class="regular-text" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="homepage_subtitle">Sottotitolo:</label></th>
                        <td>
                            <textarea id="homepage_subtitle" name="homepage_subtitle" class="large-text" rows="3"><?php echo esc_textarea($homepage_subtitle); ?></textarea>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- CONTATTI (DINAMICI) -->
            <div class="settings-section" data-settings-group="contatti">
                <h2>Contatti</h2>
                <p class="description">Aggiungi più contatti (email, telefoni, indirizzi, PEC, ecc.)</p>

                <div id="contacts-container">
                    <?php if (! empty($contacts)): ?>
                        <?php foreach ($contacts as $index => $contact): ?>
                            <div class="contact-item" data-index="<?php echo $index; ?>">
                                <table class="form-table">
                                    <tr>
                                        <th scope="row"><label>Tipo:</label></th>
                                        <td>
                                            <select name="contact_type[]" class="contact-type" required>
                                                <option value="">-- Seleziona tipo --</option>
                                                <?php foreach ($contact_types as $value => $label): ?>
                                                    <option value="<?php echo esc_attr($value); ?>" <?php selected($contact['type'], $value); ?>>
                                                        <?php echo esc_html($label); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row"><label>Label (opzionale):</label></th>
                                        <td>
                                            <input type="text" name="contact_label[]" value="<?php echo esc_attr($contact['label']); ?>" placeholder="Es: 'Ufficio' o 'Centrale'" class="regular-text" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row"><label>Valore:</label></th>
                                        <td>
                                            <textarea name="contact_value[]" class="large-text" rows="2" required><?php echo esc_textarea($contact['value']); ?></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <button type="button" class="button button-secondary remove-contact">Rimuovi</button>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <button type="button" id="add-contact-btn" class="button button-primary">+ Aggiungi contatto</button>
            </div>

            <!-- MAPPA -->
            <div class="settings-section" data-settings-group="contatti">
                <h2>🗺️ Mappa sede</h2>
                <p class="description">Incolla l'URL src dell'embed ottenuto da <strong>Google Maps → Condividi → Incorpora una mappa → copia solo il valore src</strong>. Se lasci vuoto, la mappa viene costruita automaticamente dall'indirizzo.</p>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="maps_embed_url">URL embed mappa:</label></th>
                        <td>
                            <input type="url" id="maps_embed_url" name="maps_embed_url" value="<?php echo esc_attr($maps_embed_url); ?>" class="large-text" placeholder="https://www.google.com/maps/embed?pb=..." />
                            <p class="description">Solo il valore dell'attributo <code>src</code>, non l'intero tag <code>&lt;iframe&gt;</code>.</p>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- PRIVACY & GDPR -->
            <div class="settings-section" data-settings-group="normative">
                <h2>🔒 Privacy &amp; GDPR</h2>
                <p class="description">Dati usati per generare le pagine obbligatorie (Privacy Policy, Whistleblowing).</p>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="legale_rappresentante">Legale rappresentante:</label></th>
                        <td>
                            <input type="text" id="legale_rappresentante" name="legale_rappresentante" value="<?php echo esc_attr($legale_rappresentante); ?>" class="regular-text" placeholder="Nome e cognome" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="dpo_nome">DPO:</label></th>
                        <td>
                            <input type="text" id="dpo_nome" name="dpo_nome" value="<?php echo esc_attr($dpo_nome); ?>" class="regular-text" placeholder="Nome e cognome o ragione sociale" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="referente_privacy">Referente privacy:</label></th>
                        <td>
                            <input type="text" id="referente_privacy" name="referente_privacy" value="<?php echo esc_attr($referente_privacy); ?>" class="regular-text" placeholder="Nome e ruolo" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="email_privacy">Email privacy:</label></th>
                        <td>
                            <input type="email" id="email_privacy" name="email_privacy" value="<?php echo esc_attr($email_privacy); ?>" class="regular-text" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="pec_privacy">PEC privacy:</label></th>
                        <td>
                            <input type="email" id="pec_privacy" name="pec_privacy" value="<?php echo esc_attr($pec_privacy); ?>" class="regular-text" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="email_dpo">Mail DPO:</label></th>
                        <td>
                            <input type="email" id="email_dpo" name="email_dpo" value="<?php echo esc_attr($email_dpo); ?>" class="regular-text" />
                            <p class="description">Contatto del Responsabile della Protezione dei Dati. Appare nella Privacy Policy e nella Dichiarazione di Accessibilità.</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="url_dichiarazione_agid">URL dichiarazione AGID:</label></th>
                        <td>
                            <input type="url" id="url_dichiarazione_agid" name="url_dichiarazione_agid" value="<?php echo esc_attr($url_dichiarazione_agid); ?>" class="regular-text" placeholder="https://form.agid.gov.it/view/..." />
                            <p class="description">Link pubblico della dichiarazione ufficiale di accessibilità pubblicata su AGID.</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="url_whistleblowing">URL piattaforma Whistleblowing:</label></th>
                        <td>
                            <input type="url" id="url_whistleblowing" name="url_whistleblowing" value="<?php echo esc_attr($url_whistleblowing); ?>" class="regular-text" placeholder="https://segnalazioni.nomescuola.it" />
                            <p class="description">URL della piattaforma GlobaLeaks per le segnalazioni riservate.</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="whistleblowing_responsabile">Responsabile canale Whistleblowing:</label></th>
                        <td>
                            <input type="text" id="whistleblowing_responsabile" name="whistleblowing_responsabile" value="<?php echo esc_attr($whistleblowing_responsabile); ?>" class="regular-text" placeholder="Nome o ufficio competente" />
                        </td>
                    </tr>
                </table>
            </div>

            <div class="settings-section" data-settings-group="legale">
                <h2>📱 Social media</h2>
                <p class="description">Link ai profili social da mostrare nel footer o in altre sezioni del sito.</p>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="social_facebook">Facebook:</label></th>
                        <td>
                            <input type="url" id="social_facebook" name="social_facebook" value="<?php echo esc_attr($social_facebook); ?>" class="regular-text" placeholder="https://facebook.com/nomedelprofilo" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="social_instagram">Instagram:</label></th>
                        <td>
                            <input type="url" id="social_instagram" name="social_instagram" value="<?php echo esc_attr($social_instagram); ?>" class="regular-text" placeholder="https://instagram.com/nomedelprofilo" />
                        </td>
                    </tr>
                </table>
            </div>

            <div class="settings-section" data-settings-group="legale">
                <h2>Dati legali e aziendali</h2>
                <p class="description">Dati mostrati nel footer per anagrafica, codici e recapiti ufficiali.</p>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="legal_company_name">Ragione sociale:</label></th>
                        <td>
                            <input type="text" id="legal_company_name" name="legal_company_name" value="<?php echo esc_attr($legal_company_name); ?>" class="regular-text" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="legal_address">Sede legale (footer):</label></th>
                        <td>
                            <textarea id="legal_address" name="legal_address" class="large-text" rows="2"><?php echo esc_textarea($legal_address); ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="legal_vat">P.IVA:</label></th>
                        <td>
                            <input type="text" id="legal_vat" name="legal_vat" value="<?php echo esc_attr($legal_vat); ?>" class="regular-text" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="legal_fiscal_code">Codice fiscale:</label></th>
                        <td>
                            <input type="text" id="legal_fiscal_code" name="legal_fiscal_code" value="<?php echo esc_attr($legal_fiscal_code); ?>" class="regular-text" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="legal_mecc">Codice meccanografico:</label></th>
                        <td>
                            <input type="text" id="legal_mecc" name="legal_mecc" value="<?php echo esc_attr($legal_mecc); ?>" class="regular-text" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="legal_rea">REA:</label></th>
                        <td>
                            <input type="text" id="legal_rea" name="legal_rea" value="<?php echo esc_attr($legal_rea); ?>" class="regular-text" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="accessibility_feedback_url">URL segnalazione accessibilità:</label></th>
                        <td>
                            <input type="url" id="accessibility_feedback_url" name="accessibility_feedback_url" value="<?php echo esc_attr($accessibility_feedback_url); ?>" class="regular-text" placeholder="https://..." />
                            <p class="description">Link del pulsante "Segnala un problema di accessibilità" nel footer.</p>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- FOOTER -->
            <div class="settings-section" data-settings-group="legale">
                <h2>Footer</h2>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="footer_text">Testo footer:</label></th>
                        <td>
                            <textarea id="footer_text" name="footer_text" class="large-text" rows="3"><?php echo esc_textarea($footer_text); ?></textarea>
                            <p class="description">Testo personalizzato in fondo alla pagina</p>
                        </td>
                    </tr>
                </table>
            </div>

            <?php submit_button('Salva impostazioni'); ?>
        </form>

        <!-- SEZIONE PAGINE OBBLIGATORIE -->
        <?php
        $seed_results = get_transient('centro_servizi_seed_results_' . get_current_user_id());
        if ($seed_results) {
            delete_transient('centro_servizi_seed_results_' . get_current_user_id());
            echo '<div class="notice notice-info is-dismissible"><p><strong>Risultati creazione pagine:</strong></p><ul style="margin:4px 0 4px 20px;list-style:disc">';
            foreach ($seed_results as $result) {
                echo '<li>' . wp_kses($result, ['strong' => [], 'a' => ['href' => []]]) . '</li>';
            }
            echo '</ul></div>';
        }
        ?>
        <div class="settings-section" data-settings-group="normative">
            <h2>📄 Pagine obbligatorie</h2>
            <p class="description">Crea in <strong>bozza</strong> le pagine previste dalla normativa. Le pagine già esistenti non vengono sovrascritte né modificate.</p>
            <ul style="margin: 8px 0 16px 20px; list-style: disc;">
                <li><strong>Privacy Policy</strong> — GDPR art. 13</li>
                <li><strong>Cookie Policy</strong> — Provvedimento Garante 2021 + tabella CookieYes automatica</li>
                <li><strong>Contatti</strong></li>
                <li><strong>Dichiarazione di Accessibilità</strong> — L. 4/2004 + D.Lgs. 106/2018 (link a form.agid.gov.it)</li>
                <li><strong>Segnalazioni Whistleblowing</strong> — D.Lgs. 24/2023</li>
                <li><strong>Obiettivi di Accessibilità</strong> — CAD art. 9-ter</li>
            </ul>
            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" style="display:inline-block; margin-right: 8px;">
                <?php wp_nonce_field('centro_servizi_seed_pages', 'centro_servizi_seed_nonce'); ?>
                <input type="hidden" name="action" value="centro_servizi_seed_pages" />
                <?php submit_button('Crea pagine mancanti', 'secondary', 'seed_pages', false); ?>
            </form>
            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" style="display:inline-block;" onsubmit="return confirm('Questa operazione sovrascriverà il contenuto delle pagine esistenti. Continuare?');">
                <?php wp_nonce_field('centro_servizi_seed_pages', 'centro_servizi_seed_nonce'); ?>
                <input type="hidden" name="action" value="centro_servizi_update_pages" />
                <?php submit_button('Aggiorna tutte (sovrascrive)', 'delete', 'update_pages', false); ?>
            </form>
        </div>
    </div>

    <style>
        .centro-servizi-settings-nav {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin: 8px 0 12px;
        }

        .centro-servizi-settings-nav__link {
            display: inline-flex;
            align-items: center;
            padding: 7px 12px;
            background: #fff;
            border: 1px solid #dcdcde;
            border-radius: 999px;
            color: #1d2327;
            text-decoration: none;
        }

        .centro-servizi-settings-nav__link:hover,
        .centro-servizi-settings-nav__link:focus {
            border-color: #2271b1;
            color: #2271b1;
            outline: none;
        }

        .centro-servizi-settings-nav__link.is-active {
            border-color: #2271b1;
            background: #edf5ff;
            color: #0a4b78;
            font-weight: 600;
        }

        .centro-servizi-settings-form {
            background: white;
            padding: 20px;
            border-radius: 5px;
        }

        .settings-section[data-settings-group] {
            display: none;
        }

        .settings-section[data-settings-group="<?php echo esc_attr($active_section); ?>"] {
            display: block;
        }

        .settings-section {
            margin-top: 40px;
            padding-bottom: 20px;
            border-bottom: 1px solid #ddd;
        }

        .settings-section h2 {
            margin-top: 0;
            color: #333;
        }

        .settings-section > .description {
            margin: 0 0 15px;
            color: #666;
            font-style: italic;
        }

        /* COLOR GRID */
        .color-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .color-item {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .color-item label {
            font-weight: 600;
            font-size: 13px;
        }

        .color-item input[type="color"] {
            width: 100%;
            height: 80px;
            border: 2px solid #ddd;
            border-radius: 4px;
            cursor: pointer;
        }

        .color-item small {
            font-size: 11px;
            color: #666;
            font-family: monospace;
        }

        /* TYPOGRAPHY GRID */
        .typography-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .typography-card {
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 16px;
        }

        .typography-card h3 {
            margin: 0 0 16px;
            font-size: 14px;
            font-weight: 600;
            color: #333;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .form-group {
            margin-bottom: 12px;
        }

        .preset-chip-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 12px;
            margin-top: 12px;
            border: 0;
            padding: 0;
            margin-left: 0;
        }

        .mood-chip-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 12px;
            margin-top: 12px;
            border: 0;
            padding: 0;
            margin-left: 0;
        }

        .preset-chip {
            border: 1px solid #dcdcde;
            border-radius: 8px;
            padding: 10px;
            background: #fff;
        }

        .preset-choice-card {
            position: relative;
            display: block;
            cursor: pointer;
            transition: border-color 0.15s ease, box-shadow 0.15s ease, transform 0.15s ease;
        }

        .preset-choice-card:hover {
            border-color: #7aa7cc;
            transform: translateY(-1px);
        }

        .preset-choice-input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .preset-choice-card:has(.preset-choice-input:checked) {
            border-color: #2271b1;
            box-shadow: 0 0 0 1px #2271b1;
            background: #f7fbff;
        }

        .preset-choice-card:has(.preset-choice-input:focus-visible) {
            outline: 2px solid #2271b1;
            outline-offset: 2px;
        }

        .preset-chip.is-active {
            border-color: #2271b1;
            box-shadow: 0 0 0 1px #2271b1;
        }

        .preset-chip strong {
            display: block;
            margin-bottom: 8px;
            font-size: 12px;
        }

        .preset-chip small {
            display: block;
            margin-top: 8px;
            color: #50575e;
            font-size: 12px;
            line-height: 1.4;
        }

        .preset-chip-swatches {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 6px;
        }

        .preset-chip-swatches span {
            height: 20px;
            border-radius: 4px;
            border: 1px solid rgba(0, 0, 0, 0.08);
            display: block;
        }

        .mood-chip {
            border: 1px solid #dcdcde;
            border-radius: 8px;
            background: #fff;
            padding: 12px;
        }

        .mood-chip strong {
            display: block;
            margin-bottom: 8px;
            font-size: 13px;
        }

        .mood-chip__title {
            margin: 0 0 6px;
            font-size: 20px;
            line-height: 1.2;
            color: #1d2327;
        }

        .mood-chip__body {
            margin: 0 0 8px;
            font-size: 14px;
            line-height: 1.4;
            color: #50575e;
        }

        .mood-chip__label {
            display: inline-block;
            font-size: 12px;
            letter-spacing: 0.06em;
            padding: 2px 8px;
            border-radius: 999px;
            background: #edf5ff;
            color: #0a4b78;
            margin-bottom: 8px;
        }

        .mood-chip small {
            display: block;
            font-size: 12px;
            color: #50575e;
            line-height: 1.4;
        }

        .form-group label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 4px;
            color: #555;
        }

        .form-group input[type="text"],
        .form-group input[type="number"],
        .form-group select,
        .form-group input[type="color"] {
            width: 100%;
            padding: 6px 8px;
            border: 1px solid #ddd;
            border-radius: 3px;
            font-size: 13px;
        }

        .form-group input[type="color"] {
            height: 40px;
            padding: 2px;
        }

        .inline-controls {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 8px;
        }

        .font-mode-block,
        .color-mode-block {
            margin-top: 8px;
        }

        .font-mode-block .description,
        .color-mode-block .description {
            margin: 6px 0 0;
            font-style: normal;
        }

        /* FONT PICKER */
        .font-picker-wrapper {
            position: relative;
        }

        .font-picker-display {
            padding: 8px 12px;
            background: white;
            border: 1px solid #ddd;
            border-radius: 3px;
            cursor: pointer;
            font-size: 13px;
            user-select: none;
        }

        .font-picker-display:hover {
            border-color: #999;
        }

        .font-picker-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #ddd;
            border-radius: 3px;
            margin-top: 2px;
            z-index: 1000;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .font-search {
            width: 100% !important;
            padding: 8px !important;
            border: none !important;
            border-bottom: 1px solid #ddd !important;
            border-radius: 3px 3px 0 0 !important;
            font-size: 12px !important;
        }

        .font-list {
            max-height: 250px;
            overflow-y: auto;
        }

        .font-item {
            padding: 8px 12px;
            cursor: pointer;
            font-size: 13px;
            border-bottom: 1px solid #f0f0f0;
            transition: background 0.15s;
        }

        .font-item:hover {
            background: #f0f0f0;
        }

        .font-item.active {
            background: #e3f2fd;
            font-weight: 600;
        }

        /* PREVIEW */
        .preview-box {
            margin-top: 12px;
            padding: 12px;
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 3px;
            min-height: 50px;
            display: flex;
            align-items: center;
        }

        .preview-box div {
            width: 100%;
        }

        /* CONTACT ITEMS */
        .contact-item {
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 15px;
        }

        .contact-item .form-table {
            margin: 0;
        }

        .contact-item .form-table td,
        .contact-item .form-table th {
            padding: 10px 0;
        }

        #add-contact-btn {
            margin-top: 15px;
        }

        .form-table input[type="email"],
        .form-table input[type="text"],
        .form-table textarea {
            width: 100%;
            max-width: 500px;
        }
    </style>

    <script>
        function preloadGoogleFont(fontFamily) {
            if (!fontFamily) return;
            const fontName = fontFamily.replace(/ /g, '+');
            const linkId = `gfont-${fontName}`;
            if (document.getElementById(linkId)) return;
            const link = document.createElement('link');
            link.id = linkId;
            link.rel = 'stylesheet';
            link.href = `https://fonts.googleapis.com/css2?family=${fontName}&display=swap`;
            document.head.appendChild(link);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const fontCatalog = <?php echo wp_json_encode($fonts); ?>;
            const profiles = <?php echo wp_json_encode($profiles); ?>;
            const paletteKeys = <?php echo wp_json_encode(array_keys($palette_choices)); ?>;

            // Preload font attualmente selezionati per ogni profilo
            profiles.forEach((profile) => {
                const fontInput = document.getElementById(`font_${profile}`);
                const customFontInput = document.getElementById(`custom_font_${profile}`);
                const fontSourceInput = document.getElementById(`font_source_${profile}`);
                if (fontSourceInput && fontSourceInput.value === 'custom-google' && customFontInput) {
                    const customFont = customFontInput.value.trim();
                    if (customFont) preloadGoogleFont(customFont);
                } else if (fontInput && fontCatalog[fontInput.value]) {
                    const googleFamily = fontCatalog[fontInput.value].google_family;
                    if (googleFamily) preloadGoogleFont(googleFamily);
                }
            });

            // ===== FONT PICKER LOGIC =====
            profiles.forEach((profile) => {
                const display = document.querySelector(`.font-picker-display[data-profile="${profile}"]`);
                const dropdown = document.getElementById(`dropdown_${profile}`);
                const input = document.getElementById(`font_${profile}`);
                const search = dropdown ? dropdown.querySelector('.font-search') : null;
                const items = dropdown ? dropdown.querySelectorAll('.font-item') : [];

                const fontSourceSelect = document.getElementById(`font_source_${profile}`);
                const customFontInput = document.getElementById(`custom_font_${profile}`);
                const colorModeSelect = document.getElementById(`color_mode_${profile}`);

                const catalogBlock = document.querySelector(`.font-mode-catalog[data-profile="${profile}"]`);
                const customBlock = document.querySelector(`.font-mode-custom[data-profile="${profile}"]`);
                const colorCustomBlock = document.querySelector(`.color-mode-custom[data-profile="${profile}"]`);
                const colorPaletteBlock = document.querySelector(`.color-mode-palette[data-profile="${profile}"]`);

                function toggleFontSourceBlocks() {
                    const source = fontSourceSelect ? fontSourceSelect.value : 'catalog';
                    if (catalogBlock) {
                        catalogBlock.style.display = source === 'catalog' ? 'block' : 'none';
                    }
                    if (customBlock) {
                        customBlock.style.display = source === 'custom-google' ? 'block' : 'none';
                    }
                }

                function toggleColorModeBlocks() {
                    const mode = colorModeSelect ? colorModeSelect.value : 'custom';
                    if (colorCustomBlock) {
                        colorCustomBlock.style.display = mode === 'custom' ? 'block' : 'none';
                    }
                    if (colorPaletteBlock) {
                        colorPaletteBlock.style.display = mode === 'palette' ? 'block' : 'none';
                    }
                }

                // Mostra/nascondi dropdown
                if (display && dropdown && search) {
                    display.addEventListener('click', () => {
                        dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
                        if (dropdown.style.display === 'block') {
                            search.focus();
                        }
                    });
                }

                // Cerca font
                if (search) {
                    search.addEventListener('input', (e) => {
                        const query = e.target.value.toLowerCase();
                        items.forEach((item) => {
                            const text = item.textContent.toLowerCase();
                            item.style.display = text.includes(query) ? 'block' : 'none';
                        });
                    });
                }

                // Seleziona font
                items.forEach((item) => {
                    item.addEventListener('click', () => {
                        const value = item.dataset.value;
                        const label = fontCatalog[value] ? fontCatalog[value].label : value;
                        const fontFamily = fontCatalog[value] ? fontCatalog[value].google_family : '';
                        input.value = value;
                        display.textContent = label;
                        dropdown.style.display = 'none';
                        if (fontFamily) {
                            preloadGoogleFont(fontFamily);
                        }
                        updatePreview(profile);
                    });
                });

                // Marca elemento attivo
                const currentValue = input ? input.value : '';
                items.forEach((item) => {
                    if (item.dataset.value === currentValue) {
                        item.classList.add('active');
                    }
                });

                ['size_', 'size_unit_', 'weight_', 'style_', 'transform_', 'color_', 'color_palette_'].forEach((prefix) => {
                    const el = document.getElementById(`${prefix}${profile}`);
                    if (el) {
                        el.addEventListener('change', () => updatePreview(profile));
                        el.addEventListener('input', () => updatePreview(profile));
                    }
                });

                if (fontSourceSelect) {
                    fontSourceSelect.addEventListener('change', () => {
                        toggleFontSourceBlocks();
                        updatePreview(profile);
                    });
                }
                if (customFontInput) {
                    customFontInput.addEventListener('change', () => updatePreview(profile));
                    customFontInput.addEventListener('input', () => updatePreview(profile));
                }
                if (colorModeSelect) {
                    colorModeSelect.addEventListener('change', () => {
                        toggleColorModeBlocks();
                        updatePreview(profile);
                    });
                }

                toggleFontSourceBlocks();
                toggleColorModeBlocks();
                updatePreview(profile);
            });

            function hexToRgb(hex) {
                if (!hex || typeof hex !== 'string') return null;
                const normalized = hex.replace('#', '');
                if (normalized.length !== 6) return null;
                const value = parseInt(normalized, 16);
                if (Number.isNaN(value)) return null;
                return {
                    r: (value >> 16) & 255,
                    g: (value >> 8) & 255,
                    b: value & 255
                };
            }

            function rgbToHex(rgb) {
                const clamp = (n) => Math.max(0, Math.min(255, Math.round(n)));
                const toHex = (n) => clamp(n).toString(16).padStart(2, '0');
                return '#' + toHex(rgb.r) + toHex(rgb.g) + toHex(rgb.b);
            }

            function applyLightness(hex, percent, mode) {
                const rgb = hexToRgb(hex);
                if (!rgb) return '#1f1f1f';
                const amount = Math.max(0, Math.min(100, percent)) / 100;
                const factor = mode === 'lighten' ? (1 - amount) : (1 - amount);
                return rgbToHex({
                    r: mode === 'lighten' ? (rgb.r + (255 - rgb.r) * amount) : (rgb.r * factor),
                    g: mode === 'lighten' ? (rgb.g + (255 - rgb.g) * amount) : (rgb.g * factor),
                    b: mode === 'lighten' ? (rgb.b + (255 - rgb.b) * amount) : (rgb.b * factor)
                });
            }

            function getPaletteColors() {
                const main = document.getElementById('color_main')?.value || '#007acc';
                const secondary = document.getElementById('color_secondary')?.value || '#f0f0f0';
                const body = document.getElementById('color_body')?.value || '#1f1f1f';
                const accent = document.getElementById('color_accent')?.value || '#ff6b6b';

                return {
                    'main': main,
                    'main-light': applyLightness(main, 55, 'lighten'),
                    'main-dark': applyLightness(main, 20, 'darken'),
                    'secondary': secondary,
                    'secondary-light': applyLightness(secondary, 55, 'lighten'),
                    'secondary-dark': applyLightness(secondary, 20, 'darken'),
                    'body': body,
                    'body-light': applyLightness(body, 55, 'lighten'),
                    'body-dark': applyLightness(body, 20, 'darken'),
                    'accent': accent,
                    'accent-light': applyLightness(accent, 55, 'lighten'),
                    'accent-dark': applyLightness(accent, 20, 'darken')
                };
            }

            function updatePreview(profile) {
                const preview = document.getElementById(`preview_${profile}`);
                const fontInput = document.getElementById(`font_${profile}`);
                const fontSourceInput = document.getElementById(`font_source_${profile}`);
                const customFontInput = document.getElementById(`custom_font_${profile}`);
                const sizeInput = document.getElementById(`size_${profile}`);
                const sizeUnitInput = document.getElementById(`size_unit_${profile}`);
                const weightInput = document.getElementById(`weight_${profile}`);
                const styleInput = document.getElementById(`style_${profile}`);
                const transformInput = document.getElementById(`transform_${profile}`);
                const colorModeInput = document.getElementById(`color_mode_${profile}`);
                const colorPaletteInput = document.getElementById(`color_palette_${profile}`);
                const colorInput = document.getElementById(`color_${profile}`);

                if (!preview) return;

                const fontSource = fontSourceInput ? fontSourceInput.value : 'catalog';
                const customFont = customFontInput ? customFontInput.value.trim() : '';
                const fontKey = fontInput ? fontInput.value : 'arial';
                
                if (fontSource === 'custom-google' && customFont) {
                    preloadGoogleFont(customFont);
                } else if (fontCatalog[fontKey] && fontCatalog[fontKey].google_family) {
                    preloadGoogleFont(fontCatalog[fontKey].google_family);
                }
                
                const fontStack = (fontSource === 'custom-google' && customFont)
                    ? `"${customFont}", Arial, sans-serif`
                    : (fontCatalog[fontKey] ? fontCatalog[fontKey].stack : 'Arial, sans-serif');
                const fontSize = sizeInput ? sizeInput.value : '16';
                const fontSizeUnit = sizeUnitInput ? sizeUnitInput.value : 'px';
                const fontWeight = weightInput ? weightInput.value : '400';
                const fontStyle = styleInput ? styleInput.value : 'normal';
                const fontTransform = transformInput ? transformInput.value : 'none';

                const paletteColors = getPaletteColors();
                const colorMode = colorModeInput ? colorModeInput.value : 'custom';
                const selectedPalette = colorPaletteInput ? colorPaletteInput.value : 'body';
                const fallbackColor = colorInput ? colorInput.value : '#1f1f1f';
                const fontColor = colorMode === 'palette' && paletteKeys.includes(selectedPalette)
                    ? (paletteColors[selectedPalette] || '#1f1f1f')
                    : fallbackColor;

                const div = preview.querySelector('div');
                if (div) {
                    div.style.fontFamily = fontStack;
                    div.style.fontSize = fontSize + fontSizeUnit;
                    div.style.fontWeight = fontWeight;
                    div.style.fontStyle = fontStyle;
                    div.style.textTransform = fontTransform;
                    div.style.color = fontColor;
                }
            }

            ['color_main', 'color_secondary', 'color_body', 'color_accent'].forEach((id) => {
                const input = document.getElementById(id);
                if (!input) return;
                input.addEventListener('input', () => profiles.forEach((profile) => updatePreview(profile)));
                input.addEventListener('change', () => profiles.forEach((profile) => updatePreview(profile)));
            });

            // ===== CONTATTI LOGIC =====
            const contactsContainer = document.getElementById('contacts-container');
            const addBtn = document.getElementById('add-contact-btn');
            const contactTypes = <?php echo wp_json_encode($contact_types); ?>;

            addBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const newIndex = Math.max(0, ...Array.from(
                    document.querySelectorAll('.contact-item')
                ).map(el => parseInt(el.dataset.index) || 0)) + 1;

                const typeOptions = Object.entries(contactTypes).map(([value, label]) =>
                    `<option value="${value}">${label}</option>`
                ).join('');

                const html = `
                    <div class="contact-item" data-index="${newIndex}">
                        <table class="form-table">
                            <tr>
                                <th scope="row"><label>Tipo:</label></th>
                                <td>
                                    <select name="contact_type[]" class="contact-type" required>
                                        <option value="">-- Seleziona tipo --</option>
                                        ${typeOptions}
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label>Label (opzionale):</label></th>
                                <td>
                                    <input type="text" name="contact_label[]" placeholder="Es: 'Ufficio' o 'Centrale'" class="regular-text" />
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label>Valore:</label></th>
                                <td>
                                    <textarea name="contact_value[]" class="large-text" rows="2" required></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <button type="button" class="button button-secondary remove-contact">Rimuovi</button>
                                </td>
                            </tr>
                        </table>
                    </div>
                `;

                contactsContainer.insertAdjacentHTML('beforeend', html);
                attachRemoveListener(contactsContainer.lastElementChild.querySelector('.remove-contact'));
            });

            function attachRemoveListener(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    this.closest('.contact-item').remove();
                });
            }

            document.querySelectorAll('.remove-contact').forEach(attachRemoveListener);
        });
    </script>

    <hr style="margin: 3rem 0;" />
    <div style="background: #f9f9f9; padding: 1.5rem; border-radius: 4px; border-left: 4px solid #007acc;">
        <h3 style="margin-top: 0; color: #333;">🐛 Debug: Dati Tipografia Salvati</h3>
        <p style="margin: 0 0 1rem; color: #666; font-size: 0.9rem;">Verifica che i font selezionati siano salvati correttamente nel database.</p>
        <pre style="margin: 0; padding: 1rem; background: white; border: 1px solid #ddd; border-radius: 3px; overflow-x: auto; font-size: 0.8rem; max-height: 300px; overflow-y: auto;">
<?php
$current_typo = get_option('centro_servizi_typography', '{}');
$typo_array = json_decode($current_typo, true) ?: [];
echo 'Profili tipografia: ' . json_encode($typo_array, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n\n";

$current_gf_url = get_option('centro_servizi_google_fonts_url', '');
echo 'URL Custom Google Fonts: ' . ($current_gf_url ?: '(vuoto)') . "\n";
?>
        </pre>
    </div>
    <?php
}

// ============================================================================
// HELPER FUNCTIONS
// ============================================================================
function centro_servizi_get_setting(string $key, $default = ''): mixed
{
    return get_option('centro_servizi_' . $key, $default);
}

function centro_servizi_get_contacts(): array
{
    $contacts_json = get_option('centro_servizi_contacts', '[]');
    return json_decode($contacts_json, true) ?: [];
}

function centro_servizi_get_contacts_by_type(string $type): array
{
    return array_filter(centro_servizi_get_contacts(), function($contact) use ($type) {
        return $contact['type'] === $type;
    });
}

function centro_servizi_get_contact_by_type(string $type): ?array
{
    $contacts = centro_servizi_get_contacts_by_type($type);
    return ! empty($contacts) ? reset($contacts) : null;
}

function centro_servizi_is_excluded_from_dynamic_theme(): bool
{
    if (is_admin()) {
        return true;
    }

    // Keep bureaucratic pages isolated from the global dynamic selectors.
    if (function_exists('centro_servizi_is_bureaucratic_context') && centro_servizi_is_bureaucratic_context()) {
        return true;
    }

    if (is_page_template('templates/page-legale.php')) {
        return true;
    }

    return is_page([
        'privacy-policy',
        'cookie-policy',
        'dichiarazione-accessibilita',
        'obiettivi-accessibilita',
        'whistleblowing',
    ]);
}

// ============================================================================
// GOOGLE FONTS & DYNAMIC CSS (FRONTEND)
// ============================================================================
add_action('wp_enqueue_scripts', 'centro_servizi_enqueue_google_fonts', 20);
function centro_servizi_enqueue_google_fonts(): void
{
    if (is_admin()) {
        return;
    }

    $custom_url = centro_servizi_sanitize_google_fonts_url((string) get_option('centro_servizi_google_fonts_url', ''));
    if ($custom_url !== '') {
        wp_enqueue_style('centro-servizi-google-fonts-custom', $custom_url, [], null);
    }

    // Auto-build URL from selected fonts
    $typography_json = get_option('centro_servizi_typography', '{}');
    $typography = centro_servizi_normalize_typography(json_decode($typography_json, true) ?: []);
    $font_catalog = centro_servizi_get_font_catalog();
    $family_weights = [];

    foreach ($typography as $profile_config) {
        $source = (string) centro_servizi_get_typography_value($profile_config, 'font_source', 'catalog');
        $weight = (int) centro_servizi_get_typography_value($profile_config, 'weight', 400);
        $family = '';

        if ($source === 'custom-google') {
            $family = trim((string) centro_servizi_get_typography_value($profile_config, 'custom_font', ''));
        } else {
            $font_key = (string) centro_servizi_get_typography_value($profile_config, 'font', 'arial');
            $safe_key = centro_servizi_sanitize_font_key($font_key, 'arial');
            $family = (string) ($font_catalog[$safe_key]['google_family'] ?? '');
        }

        if ($family === '') {
            continue;
        }

        if (! isset($family_weights[$family])) {
            $family_weights[$family] = [];
        }
        $family_weights[$family][(string) max(100, min(900, $weight))] = true;
    }

    if (empty($family_weights)) {
        return;
    }

    $params = [];
    foreach ($family_weights as $family => $weights) {
        $encoded_family = str_replace('%20', '+', rawurlencode($family));
        $weight_values = array_keys($weights);
        sort($weight_values, SORT_NUMERIC);
        if (! empty($weight_values)) {
            $params[] = 'family=' . $encoded_family . ':wght@' . implode(';', $weight_values);
        } else {
            $params[] = 'family=' . $encoded_family;
        }
    }
    $params[] = 'display=swap';
    $url = 'https://fonts.googleapis.com/css2?' . implode('&', $params);

    wp_enqueue_style('centro-servizi-google-fonts-auto', $url, [], null);
}

add_action('wp_head', 'centro_servizi_print_dynamic_css', 30);
function centro_servizi_print_dynamic_css(): void
{
    if (is_admin()) {
        return;
    }

    $is_excluded_from_dynamic_theme = centro_servizi_is_excluded_from_dynamic_theme();

    $color_main = (string) get_option('centro_servizi_color_main', '#007acc');
    $color_secondary = (string) get_option('centro_servizi_color_secondary', '#f0f0f0');
    $color_body = (string) get_option('centro_servizi_color_body', '#1f1f1f');
    $color_accent = (string) get_option('centro_servizi_color_accent', '#ff6b6b');

    $typography_json = get_option('centro_servizi_typography', '{}');
    $typography = centro_servizi_normalize_typography(json_decode($typography_json, true) ?: []);
    $font_catalog = centro_servizi_get_font_catalog();
    $computed_font_stacks = [];

    $body_stack = centro_servizi_get_profile_font_stack(is_array($typography['body'] ?? null) ? $typography['body'] : [], $font_catalog);
    $h1_stack = centro_servizi_get_profile_font_stack(is_array($typography['h1'] ?? null) ? $typography['h1'] : [], $font_catalog);
    $label_stack = centro_servizi_get_profile_font_stack(is_array($typography['links'] ?? null) ? $typography['links'] : [], $font_catalog);

    $body_stack = preg_replace('/[^A-Za-z0-9\s,\"\'\-]/', '', $body_stack) ?: 'Arial, sans-serif';
    $h1_stack = preg_replace('/[^A-Za-z0-9\s,\"\'\-]/', '', $h1_stack) ?: $body_stack;
    $label_stack = preg_replace('/[^A-Za-z0-9\s,\"\'\-]/', '', $label_stack) ?: $body_stack;

    $stitch_bg_warm = centro_servizi_lighten_color($color_secondary, 68);
    $stitch_surface = centro_servizi_lighten_color($color_secondary, 80);
    $stitch_surface_cream = centro_servizi_lighten_color($color_secondary, 72);
    $stitch_text_muted = centro_servizi_darken_color($color_body, 25);
    $stitch_border_subtle = centro_servizi_lighten_color($color_body, 78);

    echo "\n<style id=\"centro-servizi-dynamic-css\">\n";
    echo ":root {\n";
    echo "  --color-main: " . esc_html($color_main) . ";\n";
    echo "  --color-main-light: " . esc_html(centro_servizi_lighten_color($color_main, 55)) . ";\n";
    echo "  --color-main-dark: " . esc_html(centro_servizi_darken_color($color_main, 20)) . ";\n";
    echo "  --color-secondary: " . esc_html($color_secondary) . ";\n";
    echo "  --color-secondary-light: " . esc_html(centro_servizi_lighten_color($color_secondary, 55)) . ";\n";
    echo "  --color-secondary-dark: " . esc_html(centro_servizi_darken_color($color_secondary, 20)) . ";\n";
    echo "  --color-body: " . esc_html($color_body) . ";\n";
    echo "  --color-body-light: " . esc_html(centro_servizi_lighten_color($color_body, 55)) . ";\n";
    echo "  --color-body-dark: " . esc_html(centro_servizi_darken_color($color_body, 20)) . ";\n";
    echo "  --color-accent: " . esc_html($color_accent) . ";\n";
    echo "  --color-accent-light: " . esc_html(centro_servizi_lighten_color($color_accent, 55)) . ";\n";
    echo "  --color-accent-dark: " . esc_html(centro_servizi_darken_color($color_accent, 20)) . ";\n";
    echo "  --font-body-family: " . $body_stack . ";\n";
    echo "  --font-heading-family: " . $h1_stack . ";\n";
    echo "  --font-label-family: " . $label_stack . ";\n";
    echo "  --theme-color-primary: var(--color-main);\n";
    echo "  --theme-color-secondary: var(--color-secondary);\n";
    echo "  --theme-color-text: var(--color-body);\n";
    echo "  --theme-color-accent: var(--color-accent);\n";
    echo "  --theme-font-body: var(--font-body-family);\n";
    echo "  --theme-font-heading: var(--font-heading-family);\n";
    echo "  --theme-font-label: var(--font-label-family);\n";
    echo "  --stitch-primary: var(--color-main, #003342);\n";
    echo "  --stitch-secondary: var(--color-main-dark, #436555);\n";
    echo "  --stitch-tertiary: var(--color-accent-dark, #581a01);\n";
    echo "  --stitch-bg-warm: " . esc_html($stitch_bg_warm) . ";\n";
    echo "  --stitch-surface: " . esc_html($stitch_surface) . ";\n";
    echo "  --stitch-surface-cream: " . esc_html($stitch_surface_cream) . ";\n";
    echo "  --stitch-text: var(--color-body, #191c1d);\n";
    echo "  --stitch-text-muted: " . esc_html($stitch_text_muted) . ";\n";
    echo "  --stitch-border-subtle: " . esc_html($stitch_border_subtle) . ";\n";
    echo "}\n\n";

    if ($is_excluded_from_dynamic_theme) {
        echo "</style>\n";
        return;
    }

    $selector_map = [
        'body' => 'body',
        'h1' => 'h1',
        'h2' => 'h2',
        'h3' => 'h3',
        'h4' => 'h4',
        'h5' => 'h5',
        'h6' => 'h6',
        // Limit links/buttons typography to content area to keep header/footer stable across contexts.
        'links' => '.site-main a',
        'buttons' => '.site-main button, .site-main .button, .site-main input[type="button"], .site-main input[type="submit"], .site-main .wp-element-button, .site-main .wp-block-button__link',
    ];

    foreach ($selector_map as $profile => $selector) {
        $config = is_array($typography[$profile] ?? null) ? $typography[$profile] : [];
        $font_stack = centro_servizi_get_profile_font_stack($config, $font_catalog);
        $safe_font_stack = preg_replace('/[^A-Za-z0-9\s,\"\'\-]/', '', $font_stack);
        $safe_font_stack = is_string($safe_font_stack) && $safe_font_stack !== '' ? $safe_font_stack : 'Arial, sans-serif';
        $computed_font_stacks[$profile] = $font_stack;
        $font_size = (float) centro_servizi_get_typography_value($config, 'size', 16);
        $size_unit = (string) centro_servizi_get_typography_value($config, 'size_unit', 'px');
        $font_weight = (int) centro_servizi_get_typography_value($config, 'weight', 400);
        $font_style = (string) centro_servizi_get_typography_value($config, 'style', 'normal');
        $font_transform = (string) centro_servizi_get_typography_value($config, 'transform', 'none');
        $font_color = centro_servizi_get_profile_color_css($config);

        if (! in_array($size_unit, ['px', 'rem', 'em', '%'], true)) {
            $size_unit = 'px';
        }
        if (! in_array($font_style, ['normal', 'italic', 'oblique'], true)) {
            $font_style = 'normal';
        }
        if (! in_array($font_transform, ['none', 'uppercase', 'lowercase', 'capitalize'], true)) {
            $font_transform = 'none';
        }

        echo $selector . ' { '
            . 'font-family: ' . $safe_font_stack . '; '
            . 'font-size: ' . esc_html((string) $font_size . $size_unit) . '; '
            . 'font-weight: ' . intval($font_weight) . '; '
            . 'font-style: ' . esc_html($font_style) . '; '
            . 'text-transform: ' . esc_html($font_transform) . '; '
            . 'color: ' . esc_html($font_color) . '; '
            . "}\n";
    }

    // Homepage: assicura applicazione esplicita dei font scelti anche sui blocchi vetrina custom.
    $home_body_stack = (string) ($computed_font_stacks['body'] ?? 'Arial, sans-serif');
    $home_h1_stack = (string) ($computed_font_stacks['h1'] ?? $home_body_stack);
    $home_h2_stack = (string) ($computed_font_stacks['h2'] ?? $home_h1_stack);
    $home_h3_stack = (string) ($computed_font_stacks['h3'] ?? $home_h2_stack);
    $home_links_stack = (string) ($computed_font_stacks['links'] ?? $home_body_stack);
    $home_buttons_stack = (string) ($computed_font_stacks['buttons'] ?? $home_body_stack);

    $home_body_stack = preg_replace('/[^A-Za-z0-9\s,\"\'\-]/', '', $home_body_stack) ?: 'Arial, sans-serif';
    $home_h1_stack = preg_replace('/[^A-Za-z0-9\s,\"\'\-]/', '', $home_h1_stack) ?: $home_body_stack;
    $home_h2_stack = preg_replace('/[^A-Za-z0-9\s,\"\'\-]/', '', $home_h2_stack) ?: $home_h1_stack;
    $home_h3_stack = preg_replace('/[^A-Za-z0-9\s,\"\'\-]/', '', $home_h3_stack) ?: $home_h2_stack;
    $home_links_stack = preg_replace('/[^A-Za-z0-9\s,\"\'\-]/', '', $home_links_stack) ?: $home_body_stack;
    $home_buttons_stack = preg_replace('/[^A-Za-z0-9\s,\"\'\-]/', '', $home_buttons_stack) ?: $home_body_stack;

    echo ".home-vitrine, .home-vitrine p, .home-vitrine li, .home-vitrine span { font-family: " . $home_body_stack . "; }\n";
    echo ".home-vitrine h1, .home-vitrine .home-vitrine__title { font-family: " . $home_h1_stack . "; }\n";
    echo ".home-vitrine h2, .home-vitrine .home-vitrine__section-title { font-family: " . $home_h2_stack . "; }\n";
    echo ".home-vitrine h3, .home-vitrine .home-vitrine__contact-card h3, .home-vitrine .home-vitrine__calendar-card h3, .home-vitrine .home-vitrine__highlight-content h3 { font-family: " . $home_h3_stack . "; }\n";
    echo ".home-vitrine a, .home-vitrine .home-vitrine__text-link { font-family: " . $home_links_stack . "; }\n";
    echo ".home-vitrine .home-vitrine__button { font-family: " . $home_buttons_stack . "; }\n";

    echo "</style>\n";
}
