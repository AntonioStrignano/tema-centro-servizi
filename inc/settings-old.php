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

function centro_servizi_get_font_catalog(): array
{
    return [
        'arial' => [
            'label' => 'Arial',
            'stack' => 'Arial, Helvetica, sans-serif',
            'google_family' => '',
        ],
        'georgia' => [
            'label' => 'Georgia',
            'stack' => 'Georgia, "Times New Roman", serif',
            'google_family' => '',
        ],
        'verdana' => [
            'label' => 'Verdana',
            'stack' => 'Verdana, Geneva, sans-serif',
            'google_family' => '',
        ],
        'times-new-roman' => [
            'label' => 'Times New Roman',
            'stack' => '"Times New Roman", Times, serif',
            'google_family' => '',
        ],
        'trebuchet' => [
            'label' => 'Trebuchet MS',
            'stack' => '"Trebuchet MS", Tahoma, sans-serif',
            'google_family' => '',
        ],
        'courier' => [
            'label' => 'Courier New',
            'stack' => '"Courier New", Courier, monospace',
            'google_family' => '',
        ],
        'garamond' => [
            'label' => 'Garamond',
            'stack' => 'Garamond, "Times New Roman", serif',
            'google_family' => '',
        ],
        'tahoma' => [
            'label' => 'Tahoma',
            'stack' => 'Tahoma, Geneva, sans-serif',
            'google_family' => '',
        ],
        'helvetica' => [
            'label' => 'Helvetica',
            'stack' => 'Helvetica, Arial, sans-serif',
            'google_family' => '',
        ],
        'system-ui' => [
            'label' => 'System UI',
            'stack' => 'system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif',
            'google_family' => '',
        ],
        'roboto' => [
            'label' => 'Roboto (Google Fonts)',
            'stack' => '"Roboto", Arial, sans-serif',
            'google_family' => 'Roboto',
        ],
        'open-sans' => [
            'label' => 'Open Sans (Google Fonts)',
            'stack' => '"Open Sans", Arial, sans-serif',
            'google_family' => 'Open Sans',
        ],
        'lato' => [
            'label' => 'Lato (Google Fonts)',
            'stack' => '"Lato", Arial, sans-serif',
            'google_family' => 'Lato',
        ],
        'montserrat' => [
            'label' => 'Montserrat (Google Fonts)',
            'stack' => '"Montserrat", Arial, sans-serif',
            'google_family' => 'Montserrat',
        ],
        'poppins' => [
            'label' => 'Poppins (Google Fonts)',
            'stack' => '"Poppins", Arial, sans-serif',
            'google_family' => 'Poppins',
        ],
        'nunito' => [
            'label' => 'Nunito (Google Fonts)',
            'stack' => '"Nunito", Arial, sans-serif',
            'google_family' => 'Nunito',
        ],
        'source-sans-3' => [
            'label' => 'Source Sans 3 (Google Fonts)',
            'stack' => '"Source Sans 3", Arial, sans-serif',
            'google_family' => 'Source Sans 3',
        ],
        'merriweather' => [
            'label' => 'Merriweather (Google Fonts)',
            'stack' => '"Merriweather", Georgia, serif',
            'google_family' => 'Merriweather',
        ],
        'playfair-display' => [
            'label' => 'Playfair Display (Google Fonts)',
            'stack' => '"Playfair Display", Georgia, serif',
            'google_family' => 'Playfair Display',
        ],
        'raleway' => [
            'label' => 'Raleway (Google Fonts)',
            'stack' => '"Raleway", Arial, sans-serif',
            'google_family' => 'Raleway',
        ],
        'oswald' => [
            'label' => 'Oswald (Google Fonts)',
            'stack' => '"Oswald", Arial, sans-serif',
            'google_family' => 'Oswald',
        ],
    ];
}

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

    if ($scheme !== 'https') {
        return '';
    }

    if ($host !== 'fonts.googleapis.com') {
        return '';
    }

    if (strpos($path, '/css') !== 0) {
        return '';
    }

    return $clean_url;
}

// Rendere la pagina
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

        // COLORI
        update_option('centro_servizi_color_primary', sanitize_hex_color($_POST['color_primary'] ?? ''));
        update_option('centro_servizi_color_secondary', sanitize_hex_color($_POST['color_secondary'] ?? ''));
        update_option('centro_servizi_color_accent', sanitize_hex_color($_POST['color_accent'] ?? ''));

        // FONT
        $font_body = centro_servizi_sanitize_font_key(sanitize_text_field($_POST['font_body'] ?? ''), 'arial');
        $font_headings = centro_servizi_sanitize_font_key(sanitize_text_field($_POST['font_headings'] ?? ''), 'georgia');
        $google_fonts_url = centro_servizi_sanitize_google_fonts_url(sanitize_text_field($_POST['google_fonts_url'] ?? ''));

        update_option('centro_servizi_font_body', $font_body);
        update_option('centro_servizi_font_headings', $font_headings);
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

                // Sanificare il valore in base al tipo
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

    $color_primary = get_option('centro_servizi_color_primary', '#007acc');
    $color_secondary = get_option('centro_servizi_color_secondary', '#f0f0f0');
    $color_accent = get_option('centro_servizi_color_accent', '#ff6b6b');
    $font_body = centro_servizi_sanitize_font_key((string) get_option('centro_servizi_font_body', 'arial'), 'arial');
    $font_headings = centro_servizi_sanitize_font_key((string) get_option('centro_servizi_font_headings', 'georgia'), 'georgia');
    $google_fonts_url = centro_servizi_sanitize_google_fonts_url((string) get_option('centro_servizi_google_fonts_url', ''));
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
    ?>

    <div class="wrap">
        <h1>Impostazioni Sito</h1>

        <form method="post" class="centro-servizi-settings-form">
            <?php wp_nonce_field('centro_servizi_settings', 'centro_servizi_nonce'); ?>

            <!-- COLORI -->
            <div class="settings-section">
                <h2>🎨 Colori</h2>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="color_primary">Colore primario:</label></th>
                        <td>
                            <input type="color" id="color_primary" name="color_primary" value="<?php echo esc_attr($color_primary); ?>" />
                            <p class="description">Usato per bottoni, link principali, accenti</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="color_secondary">Colore secondario:</label></th>
                        <td>
                            <input type="color" id="color_secondary" name="color_secondary" value="<?php echo esc_attr($color_secondary); ?>" />
                            <p class="description">Usato per sfondi, bordi leggeri</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="color_accent">Colore accento:</label></th>
                        <td>
                            <input type="color" id="color_accent" name="color_accent" value="<?php echo esc_attr($color_accent); ?>" />
                            <p class="description">Usato per evidenziare elementi importanti</p>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- FONT -->
            <div class="settings-section">
                <h2>🔤 Font</h2>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="font_body">Font corpo testo:</label></th>
                        <td>
                            <select id="font_body" name="font_body">
                                <?php foreach ($fonts as $value => $font_data): ?>
                                    <option value="<?php echo esc_attr($value); ?>" <?php selected($font_body, $value); ?>>
                                        <?php echo esc_html($font_data['label']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="font_headings">Font titoli (h1, h2, ...):</label></th>
                        <td>
                            <select id="font_headings" name="font_headings">
                                <?php foreach ($fonts as $value => $font_data): ?>
                                    <option value="<?php echo esc_attr($value); ?>" <?php selected($font_headings, $value); ?>>
                                        <?php echo esc_html($font_data['label']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="google_fonts_url">URL stylesheet Google Fonts (opzionale):</label></th>
                        <td>
                            <input type="url" id="google_fonts_url" name="google_fonts_url" value="<?php echo esc_attr($google_fonts_url); ?>" class="regular-text code" placeholder="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" />
                            <p class="description">Se valorizzato, il tema carica questo URL al posto del caricamento automatico dei font Google selezionati.</p>
                        </td>
                    </tr>
                </table>

                <div class="font-preview-box" id="font-preview-box">
                    <p class="font-preview-label">Anteprima corpo testo</p>
                    <p class="font-preview-body" id="font-preview-body">
                        Questo e un testo di esempio per verificare leggibilita, contrasto e resa tipografica del contenuto principale.
                    </p>

                    <p class="font-preview-label">Anteprima titoli</p>
                    <h3 class="font-preview-heading" id="font-preview-heading">Titolo esempio H3</h3>
                    <h4 class="font-preview-heading" id="font-preview-heading-small">Sottotitolo esempio H4</h4>
                </div>
            </div>

            <!-- HOMEPAGE -->
            <div class="settings-section">
                <h2>🏠 Homepage</h2>
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
                <h2>📞 Contatti</h2>
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
                <h2>👣 Footer</h2>
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

        .form-table input[type="color"] {
            width: 60px;
            height: 50px;
            cursor: pointer;
            border: 1px solid #ddd;
            border-radius: 3px;
        }

        .form-table select,
        .form-table input[type="email"],
        .form-table input[type="text"],
        .form-table textarea {
            width: 100%;
            max-width: 500px;
        }

        .contact-item {
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 15px;
        }

        .font-preview-box {
            margin-top: 20px;
            max-width: 760px;
            background: #f7f7f7;
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 16px;
        }

        .font-preview-label {
            margin: 0 0 8px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #555;
        }

        .font-preview-body {
            margin: 0 0 20px;
            font-size: 16px;
            line-height: 1.6;
            color: #1f1f1f;
        }

        .font-preview-heading {
            margin: 0 0 8px;
            color: #111;
            line-height: 1.25;
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
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const contactsContainer = document.getElementById('contacts-container');
            const addBtn = document.getElementById('add-contact-btn');
            const contactTypes = <?php echo wp_json_encode($contact_types); ?>;
            const fontCatalog = <?php echo wp_json_encode($fonts); ?>;
            const bodySelect = document.getElementById('font_body');
            const headingsSelect = document.getElementById('font_headings');
            const googleFontsUrlInput = document.getElementById('google_fonts_url');
            const previewBody = document.getElementById('font-preview-body');
            const previewHeading = document.getElementById('font-preview-heading');
            const previewHeadingSmall = document.getElementById('font-preview-heading-small');

            function getStackByKey(key, fallback) {
                if (fontCatalog[key] && fontCatalog[key].stack) {
                    return fontCatalog[key].stack;
                }

                return fontCatalog[fallback] ? fontCatalog[fallback].stack : 'Arial, Helvetica, sans-serif';
            }

            function ensurePreviewFontLink(href) {
                const linkId = 'centro-servizi-font-preview-link';
                let linkEl = document.getElementById(linkId);

                if (!href) {
                    if (linkEl) {
                        linkEl.remove();
                    }
                    return;
                }

                if (!linkEl) {
                    linkEl = document.createElement('link');
                    linkEl.id = linkId;
                    linkEl.rel = 'stylesheet';
                    document.head.appendChild(linkEl);
                }

                linkEl.href = href;
            }

            function buildAutoGoogleFontsUrl() {
                const selectedKeys = [bodySelect.value, headingsSelect.value];
                const families = [];

                selectedKeys.forEach((key) => {
                    const family = fontCatalog[key] && fontCatalog[key].google_family
                        ? String(fontCatalog[key].google_family)
                        : '';

                    if (family && !families.includes(family)) {
                        families.push(family);
                    }
                });

                if (!families.length) {
                    return '';
                }

                const params = families
                    .map((family) => `family=${family.replace(/\s+/g, '+')}`)
                    .join('&');

                return `https://fonts.googleapis.com/css2?${params}&display=swap`;
            }

            function sanitizePreviewGoogleFontsUrl(raw) {
                const value = String(raw || '').trim();

                if (!value) {
                    return '';
                }

                try {
                    const parsed = new URL(value);
                    const host = parsed.hostname.toLowerCase();

                    if (parsed.protocol !== 'https:' || host !== 'fonts.googleapis.com') {
                        return '';
                    }

                    if (!parsed.pathname.startsWith('/css')) {
                        return '';
                    }

                    return parsed.toString();
                } catch (error) {
                    return '';
                }
            }

            function refreshFontPreview() {
                if (!previewBody || !previewHeading || !previewHeadingSmall || !bodySelect || !headingsSelect) {
                    return;
                }

                const bodyStack = getStackByKey(bodySelect.value, 'arial');
                const headingsStack = getStackByKey(headingsSelect.value, 'georgia');

                previewBody.style.fontFamily = bodyStack;
                previewHeading.style.fontFamily = headingsStack;
                previewHeadingSmall.style.fontFamily = headingsStack;

                const customUrl = sanitizePreviewGoogleFontsUrl(googleFontsUrlInput ? googleFontsUrlInput.value : '');
                if (customUrl) {
                    ensurePreviewFontLink(customUrl);
                    return;
                }

                ensurePreviewFontLink(buildAutoGoogleFontsUrl());
            }

            if (bodySelect) {
                bodySelect.addEventListener('change', refreshFontPreview);
            }

            if (headingsSelect) {
                headingsSelect.addEventListener('change', refreshFontPreview);
            }

            if (googleFontsUrlInput) {
                googleFontsUrlInput.addEventListener('input', refreshFontPreview);
                googleFontsUrlInput.addEventListener('change', refreshFontPreview);
            }

            refreshFontPreview();

            // Aggiungi nuovo contatto
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

            // Rimuovi contatto
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

// Helper: Recuperare le impostazioni nel frontend
function centro_servizi_get_setting(string $key, $default = ''): mixed
{
    return get_option('centro_servizi_' . $key, $default);
}

// Helper: Recuperare tutti i contatti
function centro_servizi_get_contacts(): array
{
    $contacts_json = get_option('centro_servizi_contacts', '[]');
    return json_decode($contacts_json, true) ?: [];
}

// Helper: Recuperare contatti filtrati per tipo
function centro_servizi_get_contacts_by_type(string $type): array
{
    return array_filter(centro_servizi_get_contacts(), function($contact) use ($type) {
        return $contact['type'] === $type;
    });
}

// Helper: Recuperare il primo contatto di un tipo
function centro_servizi_get_contact_by_type(string $type): ?array
{
    $contacts = centro_servizi_get_contacts_by_type($type);
    return ! empty($contacts) ? reset($contacts) : null;
}

add_action('wp_enqueue_scripts', 'centro_servizi_enqueue_selected_google_fonts', 20);
function centro_servizi_enqueue_selected_google_fonts(): void
{
    if (is_admin()) {
        return;
    }

    $custom_google_fonts_url = centro_servizi_sanitize_google_fonts_url((string) get_option('centro_servizi_google_fonts_url', ''));
    if ($custom_google_fonts_url !== '') {
        wp_enqueue_style('centro-servizi-google-fonts-custom', $custom_google_fonts_url, [], null);
        return;
    }

    $font_catalog = centro_servizi_get_font_catalog();
    $font_body = centro_servizi_sanitize_font_key((string) get_option('centro_servizi_font_body', 'arial'), 'arial');
    $font_headings = centro_servizi_sanitize_font_key((string) get_option('centro_servizi_font_headings', 'georgia'), 'georgia');

    $google_families = [];
    foreach ([$font_body, $font_headings] as $font_key) {
        $family = (string) ($font_catalog[$font_key]['google_family'] ?? '');
        if ($family !== '') {
            $google_families[$family] = true;
        }
    }

    if (empty($google_families)) {
        return;
    }

    $params = [];
    foreach (array_keys($google_families) as $family) {
        $params[] = 'family=' . str_replace(' ', '+', $family);
    }
    $params[] = 'display=swap';

    $google_fonts_url = 'https://fonts.googleapis.com/css2?' . implode('&', $params);

    wp_enqueue_style('centro-servizi-google-fonts-auto', $google_fonts_url, [], null);
}

add_action('wp_head', 'centro_servizi_print_dynamic_font_css', 30);
function centro_servizi_print_dynamic_font_css(): void
{
    if (is_admin()) {
        return;
    }

    $font_body = centro_servizi_sanitize_font_key((string) get_option('centro_servizi_font_body', 'arial'), 'arial');
    $font_headings = centro_servizi_sanitize_font_key((string) get_option('centro_servizi_font_headings', 'georgia'), 'georgia');

    $body_stack = centro_servizi_get_font_stack_by_key($font_body, 'arial');
    $headings_stack = centro_servizi_get_font_stack_by_key($font_headings, 'georgia');

    echo "\n<style id=\"centro-servizi-dynamic-fonts\">\n";
    echo 'body, button, input, select, textarea { font-family: ' . esc_html($body_stack) . '; }' . "\n";
    echo 'h1, h2, h3, h4, h5, h6 { font-family: ' . esc_html($headings_stack) . '; }' . "\n";
    echo "</style>\n";
}
