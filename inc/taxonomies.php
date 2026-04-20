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
        [
            'name' => '01 Documentazione Trasparente',
            'slug' => '01-documentaz-trasp',
            'children' => [
                ['name' => 'Circolari MIM', 'slug' => 'circolari-mim'],
                ['name' => 'Convenzione con Ambito', 'slug' => 'convenzione-con-ambito'],
                ['name' => 'Normativa', 'slug' => 'normativa'],
                ['name' => 'PTPCT', 'slug' => 'ptpct'],
            ],
        ],
        [
            'name' => '02 Organizzazione',
            'slug' => '02-organizzaz',
            'legacy_slugs' => ['02-organizzazione'],
            'children' => [
                ['name' => 'Organi Collegiali', 'slug' => 'organi-collegiali'],
                ['name' => 'Organigramma', 'slug' => 'organigramma'],
                ['name' => 'Direzione e Segreteria', 'slug' => 'organizzazione'],
            ],
        ],
        [
            'name' => '03 Autorizzazioni',
            'slug' => '03-autorizzazioni',
            'children' => [
                ['name' => 'Convenzioni', 'slug' => 'convenzioni'],
                ['name' => 'Decreto Parita Scolastica', 'slug' => 'decreto-parita-scolastica'],
                ['name' => 'Patto Corresponsabilita', 'slug' => 'patto-corresp'],
                ['name' => 'Permessi e Autorizzazioni', 'slug' => 'autorizzazioni'],
            ],
        ],
        [
            'name' => '04 Personale',
            'slug' => '04-personale',
            'children' => [
                ['name' => 'CCNL', 'slug' => 'ccnl'],
                ['name' => 'Costi Personale', 'slug' => 'costi-pers'],
                ['name' => 'Organico', 'slug' => 'organico'],
                ['name' => 'Regolamento Interno Lavoratori', 'slug' => 'r-i-l'],
                ['name' => 'Tassi Assenza', 'slug' => 'tassi-ass'],
            ],
        ],
        [
            'name' => '05 Consulenti e Collaboratori',
            'slug' => '05-consul-e-collab',
            'legacy_slugs' => ['05-consulenti-e-collaboratori'],
            'children' => [
                [
                    'name' => 'Consulenti e Collaboratori Esterni',
                    'slug' => 'consulenti-e-collaboratori-esterni',
                    'legacy_slugs' => ['consul-e-collab'],
                ],
                ['name' => 'Convenzioni Universita', 'slug' => 'convenzioni-uni'],
            ],
        ],
        [
            'name' => '06 Bilanci',
            'slug' => '06-bilanci',
            'children' => [
                ['name' => 'Bilancio Consuntivo', 'slug' => 'consuntivo'],
                ['name' => 'Bilancio Preventivo', 'slug' => 'preventivo'],
                ['name' => 'Bilancio Sociale', 'slug' => 'sociale'],
            ],
        ],
        [
            'name' => '07 Immobili',
            'slug' => '07-immobili',
            'children' => [
                [
                    'name' => 'Contratti fitto',
                    'slug' => 'immobile',
                    'legacy_slugs' => ['immobili'],
                ],
                ['name' => 'Planimetria', 'slug' => 'planimetria'],
            ],
        ],
        [
            'name' => '08 Aiuti Economici',
            'slug' => '08-aiuti-economici',
            'children' => [
                ['name' => 'Contributi Pubblici', 'slug' => 'contributi-pubblici'],
                [
                    'name' => 'Incentivi per Occupazione',
                    'slug' => 'incentivi-per-occupazione',
                    'legacy_slugs' => ['incentivi-per-occupaz'],
                ],
            ],
        ],
        [
            'name' => '09 Orari e Calendario',
            'slug' => '09-orari-e-calendario',
            'children' => [
                ['name' => 'Calendario Scolastico', 'slug' => 'calendario'],
                ['name' => 'Giornata Tipo', 'slug' => 'giornata-tipo'],
                ['name' => 'Orari Funzionamento', 'slug' => 'orari-funz'],
            ],
        ],
        [
            'name' => '10 Iscrizioni',
            'slug' => '10-iscrizioni',
            'children' => [
                [
                    'name' => 'Moduli Iscrizione',
                    'slug' => 'moduli-iscriz',
                    'legacy_slugs' => ['iscrizioni'],
                ],
            ],
        ],
        [
            'name' => '11 Servizi Erogati',
            'slug' => '11-servizi-erogati',
            'children' => [
                [
                    'name' => 'Carta Servizi',
                    'slug' => 'carta-serv',
                    'legacy_slugs' => ['carta-servizi'],
                ],
                ['name' => 'Mensa', 'slug' => 'mensa'],
                ['name' => 'Offerta Formativa', 'slug' => 'offerta-formativa'],
                ['name' => 'PAI', 'slug' => 'pai'],
                ['name' => 'PTOF', 'slug' => 'ptof'],
                ['name' => 'RAV', 'slug' => 'rav'],
                ['name' => 'Regolamenti', 'slug' => 'regolamenti'],
                [
                    'name' => 'Rette Famiglie',
                    'slug' => 'rette-fam',
                    'legacy_slugs' => ['rette-famiglie'],
                ],
                [
                    'name' => 'Regolamento Interno Scuola',
                    'slug' => 'regolamento-interno-scuola',
                ],
            ],
        ],
        [
            'name' => '12 Controlli e Rilievi',
            'slug' => '12-controlli-e-rilievi',
            'children' => [
                ['name' => 'Griglia ANAC', 'slug' => 'griglia-anac'],
                ['name' => 'Nomina', 'slug' => 'nomina'],
                ['name' => 'SNV', 'slug' => 'snv'],
                ['name' => 'Verifiche Periodiche', 'slug' => 'verifiche-periodiche'],
            ],
        ],
    ];

    foreach ($structure as $parent_config) {
        $parent_name = (string) $parent_config['name'];
        $parent_slug = (string) $parent_config['slug'];
        $parent_legacy_slugs = isset($parent_config['legacy_slugs']) && is_array($parent_config['legacy_slugs'])
            ? $parent_config['legacy_slugs']
            : [];

        $parent_term = centro_servizi_find_term_by_slugs('contenutiammtrasp', array_merge([$parent_slug], $parent_legacy_slugs));

        if (! $parent_term) {
            $parent_term = wp_insert_term($parent_name, 'contenutiammtrasp', [
                'slug' => $parent_slug,
            ]);
        }

        if (is_wp_error($parent_term)) {
            continue;
        }

        $parent_id = is_array($parent_term) ? (int) $parent_term['term_id'] : (int) $parent_term;

        $parent_obj = get_term($parent_id, 'contenutiammtrasp');

        if ($parent_obj instanceof WP_Term && (
            $parent_obj->name !== $parent_name
            || $parent_obj->slug !== $parent_slug
            || (int) $parent_obj->parent !== 0
        )) {
            wp_update_term($parent_id, 'contenutiammtrasp', [
                'name'   => $parent_name,
                'slug'   => $parent_slug,
                'parent' => 0,
            ]);
        }

        $children = isset($parent_config['children']) && is_array($parent_config['children'])
            ? $parent_config['children']
            : [];

        foreach ($children as $child_config) {
            if (! is_array($child_config)) {
                continue;
            }

            $child_name = isset($child_config['name']) ? (string) $child_config['name'] : '';
            $child_slug = isset($child_config['slug']) ? (string) $child_config['slug'] : '';

            if ($child_name === '' || $child_slug === '') {
                continue;
            }

            $child_legacy_slugs = isset($child_config['legacy_slugs']) && is_array($child_config['legacy_slugs'])
                ? $child_config['legacy_slugs']
                : [];

            $child_term = centro_servizi_find_term_by_slugs('contenutiammtrasp', array_merge([$child_slug], $child_legacy_slugs));

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

            if ($child_obj->name !== $child_name || $child_obj->slug !== $child_slug || (int) $child_obj->parent !== $parent_id) {
                wp_update_term($child_id, 'contenutiammtrasp', [
                    'name'   => $child_name,
                    'slug'   => $child_slug,
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

function centro_servizi_find_term_by_slugs(string $taxonomy, array $slugs)
{
    foreach ($slugs as $slug) {
        if (! is_string($slug) || $slug === '') {
            continue;
        }

        $term = term_exists($slug, $taxonomy);

        if ($term) {
            return $term;
        }
    }

    return false;
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
