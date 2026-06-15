<?php
declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

add_action('init', 'centro_servizi_register_legal_shortcodes', 20);

function centro_servizi_register_legal_shortcodes(): void
{
    add_shortcode('centro_servizi_privacy_address', 'centro_servizi_shortcode_privacy_address');
    add_shortcode('centro_servizi_privacy_email', 'centro_servizi_shortcode_privacy_email');
    add_shortcode('centro_servizi_privacy_dpo', 'centro_servizi_shortcode_privacy_dpo');
    add_shortcode('centro_servizi_accessibilita_agid_link', 'centro_servizi_shortcode_accessibilita_agid_link');
    add_shortcode('centro_servizi_accessibilita_contact', 'centro_servizi_shortcode_accessibilita_contact');
    add_shortcode('centro_servizi_whistleblowing_link', 'centro_servizi_shortcode_whistleblowing_link');
    add_shortcode('centro_servizi_whistleblowing_responsabile', 'centro_servizi_shortcode_whistleblowing_responsabile');
}

function centro_servizi_get_legal_override(string $meta_key, array $option_keys = [], string $fallback = ''): string
{
    $post_id = (int) get_queried_object_id();

    if ($post_id > 0) {
        $meta_value = trim((string) get_post_meta($post_id, $meta_key, true));

        if ($meta_value !== '') {
            return $meta_value;
        }
    }

    foreach ($option_keys as $option_key) {
        $option_value = trim((string) get_option($option_key, ''));

        if ($option_value !== '') {
            return $option_value;
        }
    }

    return $fallback;
}

function centro_servizi_shortcode_privacy_address(): string
{
    $address = centro_servizi_get_legal_override(
        'legal_address',
        ['centro_servizi_legal_address'],
        '[indirizzo sede legale]'
    );

    return nl2br(esc_html($address));
}

function centro_servizi_shortcode_privacy_email(): string
{
    $email = centro_servizi_get_legal_override(
        'email_privacy',
        ['centro_servizi_email_privacy'],
        ''
    );

    if ($email === '') {
        $contact = centro_servizi_get_contact_by_type('email');
        if (is_array($contact) && isset($contact['value'])) {
            $email = trim((string) $contact['value']);
        }
    }

    if ($email === '') {
        return '[email contatto]';
    }

    return '<a href="mailto:' . esc_attr($email) . '">' . esc_html($email) . '</a>';
}

function centro_servizi_shortcode_privacy_dpo(): string
{
    $dpo_name = centro_servizi_get_legal_override(
        'dpo_nome',
        ['centro_servizi_dpo_nome'],
        ''
    );
    $dpo_email = centro_servizi_get_legal_override(
        'email_dpo',
        ['centro_servizi_email_dpo'],
        ''
    );
    $referente_privacy = centro_servizi_get_legal_override(
        'referente_privacy',
        ['centro_servizi_referente_privacy'],
        ''
    );

    if ($dpo_email !== '') {
        $label = $dpo_name !== '' ? esc_html($dpo_name) . ' — ' : '';
        return '<li><strong>Responsabile della Protezione dei Dati (DPO):</strong> ' . $label . '<a href="mailto:' . esc_attr($dpo_email) . '">' . esc_html($dpo_email) . '</a></li>';
    }

    if ($referente_privacy !== '') {
        return '<li><strong>Referente privacy:</strong> ' . esc_html($referente_privacy) . '</li>';
    }

    if ($dpo_name !== '') {
        return '<li><strong>Responsabile della Protezione dei Dati (DPO):</strong> ' . esc_html($dpo_name) . '</li>';
    }

    return '<li><strong>Referente privacy:</strong> [da completare]</li>';
}

function centro_servizi_shortcode_accessibilita_agid_link(): string
{
    $url = centro_servizi_get_legal_override(
        'url_dichiarazione_agid',
        ['centro_servizi_url_dichiarazione_agid'],
        'https://form.agid.gov.it'
    );

    return '<strong><a href="' . esc_url($url) . '" rel="noopener noreferrer">Vai alla dichiarazione di accessibilità pubblicata su AGID →</a></strong>';
}

function centro_servizi_shortcode_accessibilita_contact(): string
{
    $email = centro_servizi_get_legal_override(
        'email_dpo',
        ['centro_servizi_email_dpo', 'centro_servizi_email_privacy'],
        ''
    );

    if ($email === '') {
        $contact = centro_servizi_get_contact_by_type('email');
        if (is_array($contact) && isset($contact['value'])) {
            $email = trim((string) $contact['value']);
        }
    }

    if ($email === '') {
        return '<li>Email: [da completare]</li>';
    }

    return '<li>Email: <a href="mailto:' . esc_attr($email) . '">' . esc_html($email) . '</a></li>';
}

function centro_servizi_shortcode_whistleblowing_link(): string
{
    $url = centro_servizi_get_legal_override(
        'url_whistleblowing',
        ['centro_servizi_url_whistleblowing'],
        ''
    );

    if ($url === '') {
        return '<strong>[da completare: inserire URL piattaforma GlobaLeaks nelle impostazioni sito]</strong>';
    }

    return '<a href="' . esc_url($url) . '" rel="noopener noreferrer" target="_blank">Accedi al portale di segnalazione →</a>';
}

function centro_servizi_shortcode_whistleblowing_responsabile(): string
{
    $responsabile = centro_servizi_get_legal_override(
        'whistleblowing_responsabile',
        ['centro_servizi_whistleblowing_responsabile'],
        ''
    );

    if ($responsabile === '') {
        return '[da completare: nome o ufficio del responsabile del canale di segnalazione interna]';
    }

    return esc_html($responsabile);
}