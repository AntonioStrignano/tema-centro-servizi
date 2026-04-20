<?php
declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

add_action('init', 'centro_servizi_register_taxonomies');
add_action('init', 'centro_servizi_seed_taxonomy_terms', 20);

function centro_servizi_register_taxonomies(): void
{
    register_taxonomy('anno-scol-attivita', ['attivita'], [
        'labels'            => [
            'name'          => 'Anno scolastico attivita',
            'singular_name' => 'Anno scolastico attivita',
        ],
        'public'            => true,
        'hierarchical'      => true,
        'show_admin_column' => true,
        'rewrite'           => ['slug' => 'anno-scol-attivita'],
    ]);

    register_taxonomy('sezioni', ['attivita'], [
        'labels'            => [
            'name'          => 'Sezioni',
            'singular_name' => 'Sezione',
        ],
        'public'            => true,
        'hierarchical'      => true,
        'show_admin_column' => true,
        'rewrite'           => ['slug' => 'sezioni'],
    ]);

    register_taxonomy('contenutiammtrasp', ['trasparenza'], [
        'labels'            => [
            'name'          => 'Contenuti Amm. Trasparente',
            'singular_name' => 'Contenuto Amm. Trasparente',
        ],
        'public'            => true,
        'hierarchical'      => true,
        'show_admin_column' => true,
        'rewrite'           => ['slug' => 'contenuti-amm-trasparente'],
    ]);

    register_taxonomy('annoscolastico', ['trasparenza'], [
        'labels'            => [
            'name'          => 'Anno scolastico',
            'singular_name' => 'Anno scolastico',
        ],
        'public'            => true,
        'hierarchical'      => true,
        'show_admin_column' => true,
        'rewrite'           => ['slug' => 'anno-scolastico'],
    ]);

    register_taxonomy('categoria-area-famiglia', ['area-famiglie'], [
        'labels'            => [
            'name'          => 'Categorie Area Famiglia',
            'singular_name' => 'Categoria Area Famiglia',
        ],
        'public'            => true,
        'hierarchical'      => true,
        'show_admin_column' => true,
        'rewrite'           => ['slug' => 'categoria-area-famiglia'],
    ]);

    register_taxonomy('categoria-area-personale', ['area-personale'], [
        'labels'            => [
            'name'          => 'Categorie Area Personale',
            'singular_name' => 'Categoria Area Personale',
        ],
        'public'            => true,
        'hierarchical'      => true,
        'show_admin_column' => true,
        'rewrite'           => ['slug' => 'categoria-area-personale'],
    ]);
}

function centro_servizi_seed_taxonomy_terms(): void
{
    if (! taxonomy_exists('contenutiammtrasp')) {
        return;
    }

    $structure = [
        '01 Documentazione Trasparente' => [
            'slug' => '01-documentaz-trasp',
            'children' => [
                'Circolari MIM' => 'circolari-mim',
                'Normativa' => 'normativa',
            ],
        ],
        '02 Organizzazione' => [
            'slug' => '02-organizzazione',
            'children' => [
                'Organi Collegiali' => 'organi-collegiali',
                'Organigramma' => 'organigramma',
                'Direzione e Segreteria' => 'organizzazione',
            ],
        ],
        '03 Autorizzazioni' => [
            'slug' => '03-autorizzazioni',
            'children' => [
                'Permessi e Autorizzazioni' => 'autorizzazioni',
                'Patto Corresponsabilita' => 'patto-corresp',
            ],
        ],
        '04 Personale' => [
            'slug' => '04-personale',
            'children' => [
                'CCNL' => 'ccnl',
                'Costi Personale' => 'costi-pers',
                'Organico' => 'organico',
                'Regolamento Interno Lavoratori' => 'r-i-l',
                'Tassi Assenza' => 'tassi-ass',
            ],
        ],
        '05 Consulenti e Collaboratori' => [
            'slug' => '05-consulenti-e-collaboratori',
            'children' => [
                'Consulenti e Collaboratori Esterni' => 'consul-e-collab',
            ],
        ],
        '06 Bilanci' => [
            'slug' => '06-bilanci',
            'children' => [
                'Bilancio Consuntivo' => 'consuntivo',
                'Bilancio Preventivo' => 'preventivo',
                'Bilancio Sociale' => 'sociale',
            ],
        ],
        '07 Immobili' => [
            'slug' => '07-immobili',
            'children' => [
                'Contratti fitto' => 'immobili',
            ],
        ],
        '08 Aiuti Economici' => [
            'slug' => '08-aiuti-economici',
            'children' => [
                'Contributi Pubblici' => 'contributi-pubblici',
                'Incentivi per Occupazione' => 'incentivi-per-occupaz',
            ],
        ],
        '09 Orari e Calendario' => [
            'slug' => '09-orari-e-calendario',
            'children' => [
                'Calendario Scolastico' => 'calendario',
                'Giornata Tipo' => 'giornata-tipo',
                'Orari Funzionamento' => 'orari-funz',
            ],
        ],
        '10 Iscrizioni' => [
            'slug' => '10-iscrizioni',
            'children' => [
                'Moduli Iscrizione' => 'iscrizioni',
            ],
        ],
        '11 Servizi Erogati' => [
            'slug' => '11-servizi-erogati',
            'children' => [
                'Carta Servizi' => 'carta-servizi',
                'PTOF' => 'ptof',
                'Regolamento Interno Scuola' => 'regolamento-interno-scuola',
                'Rette Famiglie' => 'rette-famiglie',
            ],
        ],
        '12 Controlli e Rilievi' => [
            'slug' => '12-controlli-e-rilievi',
            'children' => [
                'Verifiche Periodiche' => 'verifiche-periodiche',
            ],
        ],
    ];

    foreach ($structure as $parent_name => $config) {
        $parent_term = term_exists($config['slug'], 'contenutiammtrasp');

        if (! $parent_term) {
            $parent_term = wp_insert_term($parent_name, 'contenutiammtrasp', [
                'slug' => $config['slug'],
            ]);
        }

        if (is_wp_error($parent_term)) {
            continue;
        }

        $parent_id = is_array($parent_term) ? (int) $parent_term['term_id'] : (int) $parent_term;

        $parent_obj = get_term($parent_id, 'contenutiammtrasp');

        if ($parent_obj instanceof WP_Term && $parent_obj->name !== $parent_name) {
            wp_update_term($parent_id, 'contenutiammtrasp', [
                'name' => $parent_name,
            ]);
        }

        foreach ($config['children'] as $child_name => $child_slug) {
            $child_term = term_exists($child_slug, 'contenutiammtrasp');

            if (! $child_term) {
                wp_insert_term($child_name, 'contenutiammtrasp', [
                    'slug'   => $child_slug,
                    'parent' => $parent_id,
                ]);
                continue;
            }

            $child_id = is_array($child_term) ? (int) $child_term['term_id'] : (int) $child_term;
            $child_obj = get_term($child_id, 'contenutiammtrasp');

            if (! $child_obj instanceof WP_Term) {
                continue;
            }

            if ($child_obj->name !== $child_name || (int) $child_obj->parent !== $parent_id) {
                wp_update_term($child_id, 'contenutiammtrasp', [
                    'name'   => $child_name,
                    'parent' => $parent_id,
                ]);
            }
        }
    }

    centro_servizi_seed_flat_terms('categoria-area-famiglia', [
        'Avvisi' => 'avvisi',
        'Calendario scolastico' => 'calendario-scolastico',
        'Carta dei servizi' => 'carta-dei-servizi',
        'Moduli iscrizione' => 'moduli-iscrizione',
        'Modulistica somministrazione farmaci' => 'modulistica-somministrazione-farmaci',
        'Organi collegiali' => 'organi-collegiali',
        'Patto di corresponsabilita' => 'patto-di-corresponsabilita',
        'Privacy e informativa genitori' => 'privacy-e-informativa-genitori',
    ]);

    centro_servizi_seed_flat_terms('categoria-area-personale', [
        'Avvisi' => 'avvisi',
        'Formazione' => 'formazione',
        'Modulistica' => 'modulistica',
        'Privacy personale' => 'privacy-personale',
        'Regolamento interno' => 'regolamento-interno',
    ]);
}

function centro_servizi_seed_flat_terms(string $taxonomy, array $terms): void
{
    if (! taxonomy_exists($taxonomy)) {
        return;
    }

    foreach ($terms as $name => $slug) {
        if (term_exists($slug, $taxonomy)) {
            continue;
        }

        wp_insert_term($name, $taxonomy, [
            'slug' => $slug,
        ]);
    }
}
