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

function centro_servizi_get_legal_contact_chain(): array
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

    $legal_name = centro_servizi_get_legal_override(
        'legale_rappresentante',
        ['centro_servizi_legale_rappresentante'],
        ''
    );
    $legal_email = centro_servizi_get_legal_override(
        'email_legale_rappresentante',
        ['centro_servizi_email_legale_rappresentante'],
        ''
    );

    if ($dpo_name !== '' || $dpo_email !== '') {
        return [
            'label' => 'Responsabile della Protezione dei Dati (DPO)',
            'name' => $dpo_name,
            'email' => $dpo_email,
        ];
    }

    if ($legal_name !== '' || $legal_email !== '') {
        return [
            'label' => 'Legale rappresentante',
            'name' => $legal_name,
            'email' => $legal_email,
        ];
    }

    return [
        'label' => 'Referente privacy',
        'name' => 'Centro Servizi Scuole In Rete s.r.l.',
        'email' => 'direzione@scuoleinrete.it',
    ];
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
    $contact = centro_servizi_get_legal_contact_chain();
    $name = trim((string) ($contact['name'] ?? ''));
    $email = trim((string) ($contact['email'] ?? ''));
    $label = (string) ($contact['label'] ?? 'Referente privacy');

    if ($email !== '' && $name !== '') {
        return '<li><strong>' . esc_html($label) . ':</strong> ' . esc_html($name) . ' — <a href="mailto:' . esc_attr($email) . '">' . esc_html($email) . '</a></li>';
    }

    if ($email !== '') {
        return '<li><strong>' . esc_html($label) . ':</strong> <a href="mailto:' . esc_attr($email) . '">' . esc_html($email) . '</a></li>';
    }

    if ($name !== '') {
        return '<li><strong>' . esc_html($label) . ':</strong> ' . esc_html($name) . '</li>';
    }

    return '<li><strong>Referente privacy:</strong> Centro Servizi Scuole In Rete s.r.l. — <a href="mailto:direzione@scuoleinrete.it">direzione@scuoleinrete.it</a></li>';
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
    $contact = centro_servizi_get_legal_contact_chain();
    $name = trim((string) ($contact['name'] ?? ''));
    $email = trim((string) ($contact['email'] ?? ''));
    $label = (string) ($contact['label'] ?? 'Referente privacy');

    if ($email !== '' && $name !== '') {
        return '<li><strong>' . esc_html($label) . ':</strong> ' . esc_html($name) . ' — <a href="mailto:' . esc_attr($email) . '">' . esc_html($email) . '</a></li>';
    }

    if ($email !== '') {
        return '<li><strong>' . esc_html($label) . ':</strong> <a href="mailto:' . esc_attr($email) . '">' . esc_html($email) . '</a></li>';
    }

    if ($name !== '') {
        return '<li><strong>' . esc_html($label) . ':</strong> ' . esc_html($name) . '</li>';
    }

    return '<li><strong>Referente privacy:</strong> Centro Servizi Scuole In Rete s.r.l. — <a href="mailto:direzione@scuoleinrete.it">direzione@scuoleinrete.it</a></li>';
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