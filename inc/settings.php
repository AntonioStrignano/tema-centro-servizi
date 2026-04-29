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
        'centro_servizi_render_settings_page',
        'dashicons-admin-tools',
        99
    );
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
        'merriweather' => ['label' => 'Merriweather (Google)', 'stack' => '"Merriweather", Georgia, serif', 'google_family' => 'Merriweather', 'weights' => [300, 400, 700, 900]],
        'playfair-display' => ['label' => 'Playfair Display (Google)', 'stack' => '"Playfair Display", Georgia, serif', 'google_family' => 'Playfair Display', 'weights' => [400, 500, 600, 700, 800, 900]],
        'raleway' => ['label' => 'Raleway (Google)', 'stack' => '"Raleway", Arial, sans-serif', 'google_family' => 'Raleway', 'weights' => [100, 200, 300, 400, 500, 600, 700, 800, 900]],
        'oswald' => ['label' => 'Oswald (Google)', 'stack' => '"Oswald", Arial, sans-serif', 'google_family' => 'Oswald', 'weights' => [200, 300, 400, 500, 600, 700]],
        'inter' => ['label' => 'Inter (Google)', 'stack' => '"Inter", Arial, sans-serif', 'google_family' => 'Inter', 'weights' => [100, 200, 300, 400, 500, 600, 700, 800, 900]],
        'work-sans' => ['label' => 'Work Sans (Google)', 'stack' => '"Work Sans", Arial, sans-serif', 'google_family' => 'Work Sans', 'weights' => [100, 200, 300, 400, 500, 600, 700, 800, 900]],
        'jost' => ['label' => 'Jost (Google)', 'stack' => '"Jost", Arial, sans-serif', 'google_family' => 'Jost', 'weights' => [100, 200, 300, 400, 500, 600, 700, 800, 900]],
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
    $factor = 1 + ($percent / 100);
    return centro_servizi_rgb_to_hex(
        (int) min(255, $rgb['r'] * $factor),
        (int) min(255, $rgb['g'] * $factor),
        (int) min(255, $rgb['b'] * $factor)
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

// ============================================================================
// RENDERING PAGINA IMPOSTAZIONI
// ============================================================================
function centro_servizi_render_settings_page(): void
{
    if (! current_user_can('manage_options')) {
        wp_die('Accesso negato.');
    }

    // Salvare i dati se il form è stato inviato
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['centro_servizi_nonce'])) {
        if (! wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['centro_servizi_nonce'])), 'centro_servizi_settings')) {
            wp_die('Verifica di sicurezza fallita.');
        }

        // COLORI (4 base)
        $color_main = sanitize_hex_color($_POST['color_main'] ?? '#007acc');
        $color_secondary = sanitize_hex_color($_POST['color_secondary'] ?? '#f0f0f0');
        $color_body = sanitize_hex_color($_POST['color_body'] ?? '#1f1f1f');
        $color_accent = sanitize_hex_color($_POST['color_accent'] ?? '#ff6b6b');

        update_option('centro_servizi_color_main', $color_main);
        update_option('centro_servizi_color_secondary', $color_secondary);
        update_option('centro_servizi_color_body', $color_body);
        update_option('centro_servizi_color_accent', $color_accent);

        // FONT PROFILES (corpo + H1-H4)
        $profiles = ['body', 'h1', 'h2', 'h3', 'h4'];
        $typography = [];

        foreach ($profiles as $profile) {
            $font_key = sanitize_text_field($_POST["font_${profile}"] ?? 'arial');
            $font_size = (int) ($_POST["size_${profile}"] ?? 16);
            $font_weight = (int) ($_POST["weight_${profile}"] ?? 400);
            $font_color = sanitize_hex_color($_POST["color_${profile}"] ?? '#1f1f1f');

            $font_size = max(10, min(72, $font_size));
            $font_weight = max(100, min(900, $font_weight));

            $typography[$profile] = [
                'font' => centro_servizi_sanitize_font_key($font_key, 'arial'),
                'size' => $font_size,
                'weight' => $font_weight,
                'color' => $font_color,
            ];
        }

        update_option('centro_servizi_typography', wp_json_encode($typography));

        // GOOGLE FONTS URL
        $google_fonts_url = centro_servizi_sanitize_google_fonts_url(sanitize_text_field($_POST['google_fonts_url'] ?? ''));
        update_option('centro_servizi_google_fonts_url', $google_fonts_url);

        // HOMEPAGE
        update_option('centro_servizi_homepage_title', sanitize_text_field($_POST['homepage_title'] ?? ''));
        update_option('centro_servizi_homepage_subtitle', sanitize_textarea_field($_POST['homepage_subtitle'] ?? ''));

        // CONTATTI (dinamici)
        $contacts = [];
        if (isset($_POST['contact_type']) && is_array($_POST['contact_type'])) {
            foreach ($_POST['contact_type'] as $index => $type) {
                $type = sanitize_text_field($type);
                $label = sanitize_text_field($_POST['contact_label'][$index] ?? '');
                $value = '';

                if ($type === 'email') {
                    $value = sanitize_email($_POST['contact_value'][$index] ?? '');
                } elseif ($type === 'phone') {
                    $value = sanitize_text_field($_POST['contact_value'][$index] ?? '');
                } elseif ($type === 'pec') {
                    $value = sanitize_email($_POST['contact_value'][$index] ?? '');
                } elseif ($type === 'address') {
                    $value = sanitize_textarea_field($_POST['contact_value'][$index] ?? '');
                } else {
                    $value = sanitize_text_field($_POST['contact_value'][$index] ?? '');
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
        update_option('centro_servizi_footer_text', sanitize_textarea_field($_POST['footer_text'] ?? ''));

        echo '<div class="notice notice-success"><p>Impostazioni salvate con successo!</p></div>';
    }

    // Carica valori correnti
    $color_main = get_option('centro_servizi_color_main', '#007acc');
    $color_secondary = get_option('centro_servizi_color_secondary', '#f0f0f0');
    $color_body = get_option('centro_servizi_color_body', '#1f1f1f');
    $color_accent = get_option('centro_servizi_color_accent', '#ff6b6b');

    $typography_json = get_option('centro_servizi_typography', '{"body":{"font":"arial","size":16,"weight":400,"color":"#1f1f1f"},"h1":{"font":"georgia","size":42,"weight":700,"color":"#1f1f1f"},"h2":{"font":"georgia","size":36,"weight":700,"color":"#1f1f1f"},"h3":{"font":"georgia","size":28,"weight":700,"color":"#1f1f1f"},"h4":{"font":"georgia","size":22,"weight":700,"color":"#1f1f1f"}}');
    $typography = json_decode($typography_json, true) ?: [];

    $google_fonts_url = get_option('centro_servizi_google_fonts_url', '');
    $homepage_title = get_option('centro_servizi_homepage_title', 'Centro Servizi');
    $homepage_subtitle = get_option('centro_servizi_homepage_subtitle', '');
    $contacts_json = get_option('centro_servizi_contacts', '[]');
    $contacts = json_decode($contacts_json, true) ?: [];
    $footer_text = get_option('centro_servizi_footer_text', '');

    $fonts = centro_servizi_get_font_catalog();
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
    ];
    ?>

    <div class="wrap">
        <h1>Impostazioni Sito</h1>

        <form method="post" class="centro-servizi-settings-form">
            <?php wp_nonce_field('centro_servizi_settings', 'centro_servizi_nonce'); ?>

            <!-- COLORI -->
            <div class="settings-section">
                <h2>🎨 Colori</h2>
                <p class="description">Imposta 4 colori base. Il tema genererà automaticamente sfumature chiare e scure.</p>
                <div class="color-grid">
                    <div class="color-item">
                        <label for="color_main">Colore Main</label>
                        <input type="color" id="color_main" name="color_main" value="<?php echo esc_attr($color_main); ?>" />
                        <small><?php echo esc_html($color_main); ?></small>
                    </div>
                    <div class="color-item">
                        <label for="color_secondary">Colore Secondary</label>
                        <input type="color" id="color_secondary" name="color_secondary" value="<?php echo esc_attr($color_secondary); ?>" />
                        <small><?php echo esc_html($color_secondary); ?></small>
                    </div>
                    <div class="color-item">
                        <label for="color_body">Colore Corpo</label>
                        <input type="color" id="color_body" name="color_body" value="<?php echo esc_attr($color_body); ?>" />
                        <small><?php echo esc_html($color_body); ?></small>
                    </div>
                    <div class="color-item">
                        <label for="color_accent">Colore Evidenza</label>
                        <input type="color" id="color_accent" name="color_accent" value="<?php echo esc_attr($color_accent); ?>" />
                        <small><?php echo esc_html($color_accent); ?></small>
                    </div>
                </div>
            </div>

            <!-- TYPOGRAPHY -->
            <div class="settings-section">
                <h2>🔤 Tipografia</h2>
                <p class="description">Configura font, size, weight e colore per ogni elemento.</p>

                <div class="typography-grid">
                    <?php foreach (['body', 'h1', 'h2', 'h3', 'h4'] as $profile): ?>
                        <div class="typography-card">
                            <h3><?php echo esc_html($heading_labels[$profile]); ?></h3>

                            <div class="form-group">
                                <label for="font_<?php echo $profile; ?>">Font</label>
                                <div class="font-picker-wrapper">
                                    <input type="hidden" id="font_<?php echo $profile; ?>" name="font_<?php echo $profile; ?>" value="<?php echo esc_attr($typography[$profile]['font'] ?? 'arial'); ?>" />
                                    <div class="font-picker-display" data-profile="<?php echo $profile; ?>">
                                        <?php echo esc_html($fonts[$typography[$profile]['font'] ?? 'arial']['label'] ?? 'Arial'); ?>
                                    </div>
                                    <div class="font-picker-dropdown" id="dropdown_<?php echo $profile; ?>" style="display: none;">
                                        <input type="text" class="font-search" placeholder="Cerca font..." data-profile="<?php echo $profile; ?>" />
                                        <div class="font-list">
                                            <?php foreach ($fonts as $key => $font): ?>
                                                <div class="font-item" data-value="<?php echo esc_attr($key); ?>" data-profile="<?php echo $profile; ?>" style="font-family: <?php echo esc_attr($font['stack']); ?>">
                                                    <?php echo esc_html($font['label']); ?>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="size_<?php echo $profile; ?>">Dimensione (px)</label>
                                <input type="number" id="size_<?php echo $profile; ?>" name="size_<?php echo $profile; ?>" value="<?php echo intval($typography[$profile]['size'] ?? 16); ?>" min="10" max="72" />
                            </div>

                            <div class="form-group">
                                <label for="weight_<?php echo $profile; ?>">Peso</label>
                                <select id="weight_<?php echo $profile; ?>" name="weight_<?php echo $profile; ?>">
                                    <?php $weights = [100, 200, 300, 400, 500, 600, 700, 800, 900]; ?>
                                    <?php foreach ($weights as $w): ?>
                                        <option value="<?php echo $w; ?>" <?php selected($typography[$profile]['weight'] ?? 400, $w); ?>>
                                            <?php echo $w; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="color_<?php echo $profile; ?>">Colore</label>
                                <input type="color" id="color_<?php echo $profile; ?>" name="color_<?php echo $profile; ?>" value="<?php echo esc_attr($typography[$profile]['color'] ?? '#1f1f1f'); ?>" />
                            </div>

                            <div class="preview-box" id="preview_<?php echo $profile; ?>">
                                <?php 
                                $font_stack = centro_servizi_get_font_stack_by_key($typography[$profile]['font'] ?? 'arial', 'arial');
                                $font_size = intval($typography[$profile]['size'] ?? 16);
                                $font_weight = intval($typography[$profile]['weight'] ?? 400);
                                $font_color = esc_attr($typography[$profile]['color'] ?? '#1f1f1f');
                                ?>
                                <div style="font-family: <?php echo esc_attr($font_stack); ?>; font-size: <?php echo intval($font_size); ?>px; font-weight: <?php echo intval($font_weight); ?>; color: <?php echo esc_attr($font_color); ?>;">
                                    <?php echo $profile === 'body' ? 'Anteprima testo corpo' : 'Titolo Anteprima'; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="form-group">
                    <label for="google_fonts_url">URL Google Fonts (opzionale)</label>
                    <input type="url" id="google_fonts_url" name="google_fonts_url" value="<?php echo esc_attr($google_fonts_url); ?>" class="regular-text code" placeholder="https://fonts.googleapis.com/css2?family=..." />
                    <p class="description">Se compilato, il tema carica questo URL al posto del caricamento automatico.</p>
                </div>
            </div>

            <!-- HOMEPAGE -->
            <div class="settings-section">
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
            <div class="settings-section">
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

            <!-- FOOTER -->
            <div class="settings-section">
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
    </div>

    <style>
        .centro-servizi-settings-form {
            background: white;
            padding: 20px;
            border-radius: 5px;
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
        document.addEventListener('DOMContentLoaded', function() {
            const fontCatalog = <?php echo wp_json_encode($fonts); ?>;
            const profiles = ['body', 'h1', 'h2', 'h3', 'h4'];

            // ===== FONT PICKER LOGIC =====
            profiles.forEach((profile) => {
                const display = document.querySelector(`.font-picker-display[data-profile="${profile}"]`);
                const dropdown = document.getElementById(`dropdown_${profile}`);
                const input = document.getElementById(`font_${profile}`);
                const search = dropdown.querySelector('.font-search');
                const items = dropdown.querySelectorAll('.font-item');

                // Mostra/nascondi dropdown
                display.addEventListener('click', () => {
                    dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
                    if (dropdown.style.display === 'block') {
                        search.focus();
                    }
                });

                // Cerca font
                search.addEventListener('input', (e) => {
                    const query = e.target.value.toLowerCase();
                    items.forEach((item) => {
                        const text = item.textContent.toLowerCase();
                        item.style.display = text.includes(query) ? 'block' : 'none';
                    });
                });

                // Seleziona font
                items.forEach((item) => {
                    item.addEventListener('click', () => {
                        const value = item.dataset.value;
                        const label = fontCatalog[value] ? fontCatalog[value].label : value;
                        input.value = value;
                        display.textContent = label;
                        dropdown.style.display = 'none';
                        updatePreview(profile);
                    });
                });

                // Marca elemento attivo
                const currentValue = input.value;
                items.forEach((item) => {
                    if (item.dataset.value === currentValue) {
                        item.classList.add('active');
                    }
                });

                // Aggiorna anteprima se cambiano size/weight/color
                ['size_', 'weight_', 'color_'].forEach((prefix) => {
                    const el = document.getElementById(`${prefix}${profile}`);
                    if (el) {
                        el.addEventListener('change', () => updatePreview(profile));
                        el.addEventListener('input', () => updatePreview(profile));
                    }
                });
            });

            function updatePreview(profile) {
                const preview = document.getElementById(`preview_${profile}`);
                const fontInput = document.getElementById(`font_${profile}`);
                const sizeInput = document.getElementById(`size_${profile}`);
                const weightInput = document.getElementById(`weight_${profile}`);
                const colorInput = document.getElementById(`color_${profile}`);

                if (!preview) return;

                const fontKey = fontInput.value;
                const fontStack = fontCatalog[fontKey] ? fontCatalog[fontKey].stack : 'Arial, sans-serif';
                const fontSize = sizeInput.value;
                const fontWeight = weightInput.value;
                const fontColor = colorInput.value;

                const div = preview.querySelector('div');
                if (div) {
                    div.style.fontFamily = fontStack;
                    div.style.fontSize = fontSize + 'px';
                    div.style.fontWeight = fontWeight;
                    div.style.color = fontColor;
                }
            }

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
        return;
    }

    // Auto-build URL from selected fonts
    $typography_json = get_option('centro_servizi_typography', '{}');
    $typography = json_decode($typography_json, true) ?: [];
    $font_catalog = centro_servizi_get_font_catalog();
    $families = [];

    foreach ($typography as $profile) {
        $font_key = (string) ($profile['font'] ?? 'arial');
        $family = (string) ($font_catalog[$font_key]['google_family'] ?? '');
        if ($family !== '' && ! in_array($family, $families)) {
            $families[] = $family;
        }
    }

    if (empty($families)) {
        return;
    }

    $params = array_map(fn ($f) => 'family=' . str_replace(' ', '+', $f), $families);
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

    $color_main = (string) get_option('centro_servizi_color_main', '#007acc');
    $color_secondary = (string) get_option('centro_servizi_color_secondary', '#f0f0f0');
    $color_body = (string) get_option('centro_servizi_color_body', '#1f1f1f');
    $color_accent = (string) get_option('centro_servizi_color_accent', '#ff6b6b');

    $typography_json = get_option('centro_servizi_typography', '{}');
    $typography = json_decode($typography_json, true) ?: [];
    $font_catalog = centro_servizi_get_font_catalog();

    echo "\n<style id=\"centro-servizi-dynamic-css\">\n";
    echo ":root {\n";
    echo "  --color-main: " . esc_html($color_main) . ";\n";
    echo "  --color-main-light: " . esc_html(centro_servizi_lighten_color($color_main, 20)) . ";\n";
    echo "  --color-main-dark: " . esc_html(centro_servizi_darken_color($color_main, 20)) . ";\n";
    echo "  --color-secondary: " . esc_html($color_secondary) . ";\n";
    echo "  --color-secondary-light: " . esc_html(centro_servizi_lighten_color($color_secondary, 20)) . ";\n";
    echo "  --color-secondary-dark: " . esc_html(centro_servizi_darken_color($color_secondary, 20)) . ";\n";
    echo "  --color-body: " . esc_html($color_body) . ";\n";
    echo "  --color-body-light: " . esc_html(centro_servizi_lighten_color($color_body, 20)) . ";\n";
    echo "  --color-body-dark: " . esc_html(centro_servizi_darken_color($color_body, 20)) . ";\n";
    echo "  --color-accent: " . esc_html($color_accent) . ";\n";
    echo "  --color-accent-light: " . esc_html(centro_servizi_lighten_color($color_accent, 20)) . ";\n";
    echo "  --color-accent-dark: " . esc_html(centro_servizi_darken_color($color_accent, 20)) . ";\n";
    echo "}\n\n";

    // Body
    $body = $typography['body'] ?? ['font' => 'arial', 'size' => 16, 'weight' => 400, 'color' => '#1f1f1f'];
    $body_stack = $font_catalog[$body['font']]['stack'] ?? 'Arial, sans-serif';
    echo 'body { font-family: ' . esc_html($body_stack) . '; font-size: ' . intval($body['size']) . 'px; font-weight: ' . intval($body['weight']) . '; color: ' . esc_html($body['color']) . "; }\n";

    // Headings
    foreach (['h1', 'h2', 'h3', 'h4'] as $tag) {
        $config = $typography[$tag] ?? ['font' => 'georgia', 'size' => 28, 'weight' => 700, 'color' => '#1f1f1f'];
        $stack = $font_catalog[$config['font']]['stack'] ?? 'Georgia, serif';
        echo $tag . ' { font-family: ' . esc_html($stack) . '; font-size: ' . intval($config['size']) . 'px; font-weight: ' . intval($config['weight']) . '; color: ' . esc_html($config['color']) . "; }\n";
    }

    echo "</style>\n";
}
