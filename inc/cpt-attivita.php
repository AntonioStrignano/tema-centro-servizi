<?php
declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

add_action('init', 'centro_servizi_register_cpt_attivita');

function centro_servizi_register_cpt_attivita(): void
{
    register_post_type('attivita', [
        'labels' => [
            'name'               => 'Attivita',
            'singular_name'      => 'Attivita',
            'add_new'            => 'Aggiungi nuova',
            'add_new_item'       => 'Aggiungi nuova attivita',
            'edit_item'          => 'Modifica attivita',
            'new_item'           => 'Nuova attivita',
            'view_item'          => 'Vedi attivita',
            'search_items'       => 'Cerca attivita',
            'not_found'          => 'Nessuna attivita trovata',
            'not_found_in_trash' => 'Nessuna attivita nel cestino',
            'menu_name'          => 'Attivita',
        ],
        'public'             => true,
        'has_archive'        => true,
        'menu_icon'          => 'dashicons-calendar-alt',
        'rewrite'            => ['slug' => 'attivita'],
        'show_in_rest'       => false,
        'supports'           => ['title', 'editor', 'thumbnail', 'excerpt'],
        'menu_position'      => 20,
    ]);
}
