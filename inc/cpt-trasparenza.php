<?php
declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

add_action('init', 'centro_servizi_register_cpt_trasparenza');

function centro_servizi_register_cpt_trasparenza(): void
{
    register_post_type('trasparenza', [
        'labels' => [
            'name'               => 'Amministrazione Trasparente',
            'singular_name'      => 'Documento trasparenza',
            'add_new'            => 'Aggiungi nuovo',
            'add_new_item'       => 'Aggiungi nuovo documento',
            'edit_item'          => 'Modifica documento',
            'new_item'           => 'Nuovo documento',
            'view_item'          => 'Vedi documento',
            'search_items'       => 'Cerca documenti',
            'not_found'          => 'Nessun documento trovato',
            'not_found_in_trash' => 'Nessun documento nel cestino',
            'menu_name'          => 'Amm. Trasparente',
        ],
        'public'             => true,
        'publicly_queryable' => true,
        'has_archive'        => true,
        'menu_icon'          => 'dashicons-media-document',
        'rewrite'            => ['slug' => 'trasparenza'],
        'show_in_rest'       => false,
        'supports'           => ['title', 'editor'],
        'menu_position'      => 21,
    ]);
}
