<?php
declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

add_action('init', 'centro_servizi_register_cpt_area_famiglie');

function centro_servizi_register_cpt_area_famiglie(): void
{
    register_post_type('area-famiglie', [
        'labels' => [
            'name'               => 'Area Famiglie',
            'singular_name'      => 'Contenuto area famiglie',
            'add_new'            => 'Aggiungi nuovo',
            'add_new_item'       => 'Aggiungi nuovo contenuto',
            'edit_item'          => 'Modifica contenuto',
            'new_item'           => 'Nuovo contenuto',
            'view_item'          => 'Vedi contenuto',
            'search_items'       => 'Cerca contenuti',
            'not_found'          => 'Nessun contenuto trovato',
            'not_found_in_trash' => 'Nessun contenuto nel cestino',
            'menu_name'          => 'Area Famiglie',
        ],
        'public'        => true,
        'has_archive'   => true,
        'menu_icon'     => 'dashicons-groups',
        'rewrite'       => ['slug' => 'area-famiglie'],
        'show_in_rest'  => false,
        'supports'      => ['title', 'editor'],
        'menu_position' => 22,
    ]);
}
