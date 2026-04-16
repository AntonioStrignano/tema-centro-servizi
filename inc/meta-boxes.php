<?php
declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

add_action('add_meta_boxes', 'centro_servizi_register_native_meta_boxes');
add_action('save_post', 'centro_servizi_save_native_meta_boxes');

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

    echo '<p><label for="centro_servizi_documento"><strong>Documento</strong> (ID attachment o URL file)</label><br />';
    echo '<input type="text" class="widefat" id="centro_servizi_documento" name="centro_servizi_documento" value="' . esc_attr($documento_text) . '" /></p>';
}

function centro_servizi_render_area_meta_box(WP_Post $post): void
{
    wp_nonce_field('centro_servizi_native_meta_save', 'centro_servizi_native_meta_nonce');

    $testo = (string) get_post_meta($post->ID, 'testo', true);
    $allegato = get_post_meta($post->ID, 'allegato', true);
    $allegato_text = is_scalar($allegato) ? (string) $allegato : '';

    echo '<p><label for="centro_servizi_testo"><strong>Testo</strong></label><br />';
    echo '<input type="text" class="widefat" id="centro_servizi_testo" name="centro_servizi_testo" value="' . esc_attr($testo) . '" /></p>';

    echo '<p><label for="centro_servizi_allegato"><strong>Allegato</strong> (ID attachment o URL file)</label><br />';
    echo '<input type="text" class="widefat" id="centro_servizi_allegato" name="centro_servizi_allegato" value="' . esc_attr($allegato_text) . '" /></p>';
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
