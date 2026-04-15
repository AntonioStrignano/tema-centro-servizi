<?php
declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

add_action('init', 'centro_servizi_register_cpt_area_personale');

function centro_servizi_register_cpt_area_personale(): void
{
    register_post_type('area-personale', [
        'labels' => [
            'name'               => 'Area Personale',
            'singular_name'      => 'Contenuto area personale',
            'add_new'            => 'Aggiungi nuovo',
            'add_new_item'       => 'Aggiungi nuovo contenuto',
            'edit_item'          => 'Modifica contenuto',
            'new_item'           => 'Nuovo contenuto',
            'view_item'          => 'Vedi contenuto',
            'search_items'       => 'Cerca contenuti',
            'not_found'          => 'Nessun contenuto trovato',
            'not_found_in_trash' => 'Nessun contenuto nel cestino',
            'menu_name'          => 'Area Personale',
        ],
        'public'        => true,
        'has_archive'   => true,
        'menu_icon'     => 'dashicons-businessperson',
        'rewrite'       => ['slug' => 'area-personale'],
        'show_in_rest'  => false,
        'supports'      => ['title', 'editor', 'excerpt'],
        'menu_position' => 23,
    ]);
}
