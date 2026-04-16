<?php
declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

add_action('add_meta_boxes', 'centro_servizi_register_native_meta_boxes');
add_action('save_post', 'centro_servizi_save_native_meta_boxes');
add_action('admin_enqueue_scripts', 'centro_servizi_enqueue_native_meta_media');
add_action('admin_footer', 'centro_servizi_print_native_meta_media_script');

function centro_servizi_register_native_meta_boxes(): void
{
    // Se ACF e' attivo, usiamo i gruppi campi gia definiti da ACF.
    if (function_exists('get_field')) {
        return;
    }

    add_meta_box(
        'centro-servizi-trasparenza-meta',
        'Dati documento trasparenza',
        'centro_servizi_render_trasparenza_meta_box',
        'trasparenza',
        'normal',
        'default'
    );

    add_meta_box(
        'centro-servizi-area-famiglie-meta',
        'Dati area famiglie',
        'centro_servizi_render_area_meta_box',
        'area-famiglie',
        'normal',
        'default'
    );

    add_meta_box(
        'centro-servizi-area-personale-meta',
        'Dati area personale',
        'centro_servizi_render_area_meta_box',
        'area-personale',
        'normal',
        'default'
    );
}

function centro_servizi_enqueue_native_meta_media(string $hook): void
{
    if (function_exists('get_field')) {
        return;
    }

    if ($hook !== 'post.php' && $hook !== 'post-new.php') {
        return;
    }

    $screen = get_current_screen();

    if (! $screen instanceof WP_Screen) {
        return;
    }

    if (! in_array($screen->post_type, ['trasparenza', 'area-famiglie', 'area-personale'], true)) {
        return;
    }

    wp_enqueue_media();
}

function centro_servizi_render_trasparenza_meta_box(WP_Post $post): void
{
    wp_nonce_field('centro_servizi_native_meta_save', 'centro_servizi_native_meta_nonce');

    $titolo = (string) get_post_meta($post->ID, 'titolo', true);
    $tag_anno = (string) get_post_meta($post->ID, 'tag_anno', true);
    $documento = get_post_meta($post->ID, 'documento', true);
    $documento_text = is_scalar($documento) ? (string) $documento : '';

    echo '<p><label for="centro_servizi_titolo"><strong>Titolo</strong></label><br />';
    echo '<input type="text" class="widefat" id="centro_servizi_titolo" name="centro_servizi_titolo" value="' . esc_attr($titolo) . '" /></p>';

    echo '<p><label for="centro_servizi_tag_anno"><strong>Tag anno</strong></label><br />';
    echo '<input type="text" class="widefat" id="centro_servizi_tag_anno" name="centro_servizi_tag_anno" value="' . esc_attr($tag_anno) . '" /></p>';

    centro_servizi_render_media_selector_field(
        'centro_servizi_documento',
        'centro_servizi_documento',
        $documento_text,
        'Documento'
    );
}

function centro_servizi_render_area_meta_box(WP_Post $post): void
{
    wp_nonce_field('centro_servizi_native_meta_save', 'centro_servizi_native_meta_nonce');

    $testo = (string) get_post_meta($post->ID, 'testo', true);
    $allegato = get_post_meta($post->ID, 'allegato', true);
    $allegato_text = is_scalar($allegato) ? (string) $allegato : '';

    echo '<p><label for="centro_servizi_testo"><strong>Testo</strong></label><br />';
    echo '<input type="text" class="widefat" id="centro_servizi_testo" name="centro_servizi_testo" value="' . esc_attr($testo) . '" /></p>';

    centro_servizi_render_media_selector_field(
        'centro_servizi_allegato',
        'centro_servizi_allegato',
        $allegato_text,
        'Allegato'
    );
}

function centro_servizi_render_media_selector_field(string $input_id, string $input_name, string $value, string $label): void
{
    $preview = centro_servizi_get_media_preview_data($value);

    echo '<p><label for="' . esc_attr($input_id) . '"><strong>' . esc_html($label) . '</strong> (ID attachment o URL file)</label><br />';
    echo '<input type="text" class="widefat" id="' . esc_attr($input_id) . '" name="' . esc_attr($input_name) . '" value="' . esc_attr($value) . '" />';
    echo '<span style="display:block; margin-top:8px;">';
    echo '<button type="button" class="button centro-servizi-open-media" data-target="' . esc_attr($input_id) . '">Scegli da Libreria Media</button> ';
    echo '<button type="button" class="button-link-delete centro-servizi-clear-media" data-target="' . esc_attr($input_id) . '" style="vertical-align:middle;">Svuota</button>';
    echo '</span>';

    echo '<span id="' . esc_attr($input_id) . '_preview" style="display:block; margin-top:10px; padding:10px; border:1px solid #dcdcde; border-radius:4px; background:#fff;">';

    if ($preview === []) {
        echo '<em>Nessun file selezionato.</em>';
    } else {
        if (! empty($preview['thumb_html'])) {
            echo '<div style="margin-bottom:8px;">' . wp_kses_post((string) $preview['thumb_html']) . '</div>';
        }

        echo '<div><strong>ID:</strong> ' . esc_html((string) ($preview['id'] ?? '-')) . '</div>';
        echo '<div><strong>Nome:</strong> ' . esc_html((string) ($preview['name'] ?? '-')) . '</div>';
        echo '<div><strong>URL:</strong> <a href="' . esc_url((string) ($preview['url'] ?? '')) . '" target="_blank" rel="noopener">' . esc_html((string) ($preview['url'] ?? '')) . '</a></div>';
        echo '<div><strong>Caricato il:</strong> ' . esc_html((string) ($preview['date'] ?? '-')) . '</div>';
        echo '<div><strong>Dimensione:</strong> ' . esc_html((string) ($preview['size'] ?? '-')) . '</div>';
    }

    echo '</span></p>';
}

function centro_servizi_get_media_preview_data(string $value): array
{
    $value = trim($value);

    if ($value === '') {
        return [];
    }

    if (ctype_digit($value)) {
        $attachment_id = (int) $value;

        if (get_post_type($attachment_id) !== 'attachment') {
            return [];
        }

        $url = (string) wp_get_attachment_url($attachment_id);

        if ($url === '') {
            return [];
        }

        $filename = basename((string) get_attached_file($attachment_id));

        if ($filename === '') {
            $filename = basename((string) wp_parse_url($url, PHP_URL_PATH));
        }

        $date = get_the_date('d/m/Y H:i', $attachment_id);
        $size = '';
        $filepath = get_attached_file($attachment_id);

        if (is_string($filepath) && $filepath !== '' && file_exists($filepath)) {
            $bytes = filesize($filepath);

            if (is_int($bytes) && $bytes > 0) {
                $size = size_format($bytes);
            }
        }

        $thumb_html = '';

        if (wp_attachment_is_image($attachment_id)) {
            $thumb_html = (string) wp_get_attachment_image(
                $attachment_id,
                'thumbnail',
                false,
                ['style' => 'max-width:120px;height:auto;border:1px solid #dcdcde;border-radius:4px;']
            );
        } else {
            $icon = wp_mime_type_icon($attachment_id);

            if (is_string($icon) && $icon !== '') {
                $thumb_html = '<img src="' . esc_url($icon) . '" alt="" style="max-width:48px;height:auto;" />';
            }
        }

        return [
            'id' => (string) $attachment_id,
            'name' => $filename !== '' ? $filename : '-',
            'url' => $url,
            'date' => $date !== '' ? (string) $date : '-',
            'size' => $size !== '' ? $size : '-',
            'thumb_html' => $thumb_html,
        ];
    }

    if (filter_var($value, FILTER_VALIDATE_URL) === false) {
        return [];
    }

    $filename = basename((string) wp_parse_url($value, PHP_URL_PATH));
    $extension = strtoupper((string) pathinfo($filename, PATHINFO_EXTENSION));
    $name = $filename !== '' ? $filename : 'Documento esterno';

    if ($extension !== '') {
        $name .= ' (' . $extension . ')';
    }

    return [
        'id' => '-',
        'name' => $name,
        'url' => $value,
        'date' => '-',
        'size' => '-',
        'thumb_html' => '',
    ];
}

function centro_servizi_save_native_meta_boxes(int $post_id): void
{
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (wp_is_post_revision($post_id)) {
        return;
    }

    if (! isset($_POST['centro_servizi_native_meta_nonce']) || ! wp_verify_nonce(sanitize_text_field(wp_unslash((string) $_POST['centro_servizi_native_meta_nonce'])), 'centro_servizi_native_meta_save')) {
        return;
    }

    if (! current_user_can('edit_post', $post_id)) {
        return;
    }

    $post_type = get_post_type($post_id);

    if ($post_type === 'trasparenza') {
        centro_servizi_update_meta_text($post_id, 'titolo', $_POST['centro_servizi_titolo'] ?? '');
        centro_servizi_update_meta_text($post_id, 'tag_anno', $_POST['centro_servizi_tag_anno'] ?? '');
        centro_servizi_update_meta_attachment($post_id, 'documento', $_POST['centro_servizi_documento'] ?? '');

        return;
    }

    if ($post_type === 'area-famiglie' || $post_type === 'area-personale') {
        centro_servizi_update_meta_text($post_id, 'testo', $_POST['centro_servizi_testo'] ?? '');
        centro_servizi_update_meta_attachment($post_id, 'allegato', $_POST['centro_servizi_allegato'] ?? '');
    }
}

function centro_servizi_update_meta_text(int $post_id, string $meta_key, mixed $raw): void
{
    $value = sanitize_text_field(wp_unslash((string) $raw));

    if ($value === '') {
        delete_post_meta($post_id, $meta_key);
        return;
    }

    update_post_meta($post_id, $meta_key, $value);
}

function centro_servizi_update_meta_attachment(int $post_id, string $meta_key, mixed $raw): void
{
    $value = trim(wp_unslash((string) $raw));

    if ($value === '') {
        delete_post_meta($post_id, $meta_key);
        return;
    }

    if (ctype_digit($value)) {
        update_post_meta($post_id, $meta_key, (int) $value);
        return;
    }

    update_post_meta($post_id, $meta_key, esc_url_raw($value));
}

function centro_servizi_print_native_meta_media_script(): void
{
    if (function_exists('get_field')) {
        return;
    }

    $screen = get_current_screen();

    if (! $screen instanceof WP_Screen) {
        return;
    }

    if ($screen->base !== 'post' || ! in_array($screen->post_type, ['trasparenza', 'area-famiglie', 'area-personale'], true)) {
        return;
    }
    ?>
    <script>
        (function () {
            function escapeHtml(value) {
                return String(value)
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            }

            function formatBytes(bytes) {
                if (!Number.isFinite(bytes) || bytes <= 0) {
                    return '-';
                }

                var units = ['B', 'KB', 'MB', 'GB'];
                var i = 0;
                var value = bytes;

                while (value >= 1024 && i < units.length - 1) {
                    value /= 1024;
                    i += 1;
                }

                return value.toFixed(i === 0 ? 0 : 1) + ' ' + units[i];
            }

            function getInput(targetId) {
                return document.getElementById(targetId);
            }

            function renderPreview(targetId, data) {
                var box = document.getElementById(targetId + '_preview');

                if (!box) {
                    return;
                }

                if (!data) {
                    box.innerHTML = '<em>Nessun file selezionato.</em>';
                    return;
                }

                var id = data.id ? String(data.id) : '-';
                var name = data.filename || data.title || '-';
                var url = data.url || '';
                var date = data.dateFormatted || data.date || '-';
                var size = data.filesizeHumanReadable || formatBytes(Number(data.filesizeInBytes || 0));
                var thumb = '';

                if (data.type === 'image') {
                    if (data.sizes && data.sizes.thumbnail && data.sizes.thumbnail.url) {
                        thumb = '<img src="' + escapeHtml(data.sizes.thumbnail.url) + '" alt="" style="max-width:120px;height:auto;border:1px solid #dcdcde;border-radius:4px;" />';
                    } else if (url) {
                        thumb = '<img src="' + escapeHtml(url) + '" alt="" style="max-width:120px;height:auto;border:1px solid #dcdcde;border-radius:4px;" />';
                    }
                } else if (data.icon) {
                    thumb = '<img src="' + escapeHtml(data.icon) + '" alt="" style="max-width:48px;height:auto;" />';
                }

                var html = '';

                if (thumb) {
                    html += '<div style="margin-bottom:8px;">' + thumb + '</div>';
                }

                html += '<div><strong>ID:</strong> ' + escapeHtml(id) + '</div>';
                html += '<div><strong>Nome:</strong> ' + escapeHtml(name) + '</div>';

                if (url) {
                    html += '<div><strong>URL:</strong> <a href="' + escapeHtml(url) + '" target="_blank" rel="noopener">' + escapeHtml(url) + '</a></div>';
                } else {
                    html += '<div><strong>URL:</strong> -</div>';
                }

                html += '<div><strong>Caricato il:</strong> ' + escapeHtml(date || '-') + '</div>';
                html += '<div><strong>Dimensione:</strong> ' + escapeHtml(size || '-') + '</div>';

                box.innerHTML = html;
            }

            document.addEventListener('click', function (event) {
                var openButton = event.target.closest('.centro-servizi-open-media');

                if (openButton) {
                    event.preventDefault();

                    var targetId = openButton.getAttribute('data-target');
                    var input = getInput(targetId);

                    if (!input || typeof wp === 'undefined' || !wp.media) {
                        return;
                    }

                    var frame = wp.media({
                        title: 'Seleziona un file',
                        button: { text: 'Usa questo file' },
                        library: { type: '' },
                        multiple: false
                    });

                    frame.on('select', function () {
                        var selection = frame.state().get('selection').first();

                        if (!selection) {
                            return;
                        }

                        var attachment = selection.toJSON();

                        if (attachment && attachment.id) {
                            input.value = String(attachment.id);
                            renderPreview(targetId, attachment);
                        }
                    });

                    frame.open();
                    return;
                }

                var clearButton = event.target.closest('.centro-servizi-clear-media');

                if (clearButton) {
                    event.preventDefault();

                    var clearTarget = clearButton.getAttribute('data-target');
                    var clearInput = getInput(clearTarget);

                    if (clearInput) {
                        clearInput.value = '';
                        renderPreview(clearTarget, null);
                    }
                }
            });
        })();
    </script>
    <?php
}
