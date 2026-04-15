<?php
declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

add_action('acf/init', 'centro_servizi_register_acf_fields');

function centro_servizi_register_acf_fields(): void
{
    if (! function_exists('acf_add_local_field_group')) {
        return;
    }

    acf_add_local_field_group([
        'key' => 'group_centro_servizi_trasparenza',
        'title' => 'Dati documento trasparenza',
        'fields' => [
            [
                'key' => 'field_centro_servizi_titolo',
                'label' => 'Titolo',
                'name' => 'titolo',
                'type' => 'text',
            ],
            [
                'key' => 'field_centro_servizi_tag_anno',
                'label' => 'Tag anno',
                'name' => 'tag_anno',
                'type' => 'text',
            ],
            [
                'key' => 'field_centro_servizi_documento',
                'label' => 'Documento',
                'name' => 'documento',
                'type' => 'file',
                'return_format' => 'array',
                'library' => 'all',
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'trasparenza',
                ],
            ],
        ],
    ]);

    foreach (['area-famiglie', 'area-personale'] as $post_type) {
        acf_add_local_field_group([
            'key' => 'group_centro_servizi_' . str_replace('-', '_', $post_type),
            'title' => $post_type === 'area-famiglie' ? 'Dati area famiglie' : 'Dati area personale',
            'fields' => [
                [
                    'key' => 'field_' . str_replace('-', '_', $post_type) . '_testo',
                    'label' => 'Testo',
                    'name' => 'testo',
                    'type' => 'text',
                ],
                [
                    'key' => 'field_' . str_replace('-', '_', $post_type) . '_allegato',
                    'label' => 'Allegato',
                    'name' => 'allegato',
                    'type' => 'file',
                    'return_format' => 'array',
                    'library' => 'all',
                ],
            ],
            'location' => [
                [
                    [
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => $post_type,
                    ],
                ],
            ],
        ]);
    }

    acf_add_local_field_group([
        'key' => 'group_centro_servizi_contatti',
        'title' => 'Dati contatti',
        'fields' => [
            [
                'key' => 'field_centro_servizi_indirizzo_sede',
                'label' => 'Indirizzo sede',
                'name' => 'indirizzo_sede',
                'type' => 'text',
            ],
            [
                'key' => 'field_centro_servizi_cap_citta_provincia',
                'label' => 'CAP / Citta / Provincia',
                'name' => 'cap_citta_provincia',
                'type' => 'text',
            ],
            [
                'key' => 'field_centro_servizi_telefono',
                'label' => 'Telefono',
                'name' => 'telefono',
                'type' => 'text',
            ],
            [
                'key' => 'field_centro_servizi_email',
                'label' => 'Email',
                'name' => 'email',
                'type' => 'email',
            ],
            [
                'key' => 'field_centro_servizi_pec',
                'label' => 'PEC',
                'name' => 'pec',
                'type' => 'email',
            ],
            [
                'key' => 'field_centro_servizi_codice_fiscale_piva',
                'label' => 'Codice fiscale / P.IVA',
                'name' => 'codice_fiscale_piva',
                'type' => 'text',
            ],
            [
                'key' => 'field_centro_servizi_codice_meccanografico',
                'label' => 'Codice meccanografico',
                'name' => 'codice_meccanografico',
                'type' => 'text',
            ],
            [
                'key' => 'field_centro_servizi_google_maps_embed_url',
                'label' => 'Google Maps embed URL',
                'name' => 'google_maps_embed_url',
                'type' => 'url',
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'page',
                ],
            ],
        ],
    ]);
}
