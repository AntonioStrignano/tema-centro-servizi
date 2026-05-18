<?php
declare(strict_types=1);

if (! function_exists('centro_servizi_archive_trasparenza_selected_slug')) {
function centro_servizi_archive_trasparenza_selected_slug(string $key): string
{
    if (! isset($_GET[$key])) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        return '';
    }

    return sanitize_text_field(wp_unslash((string) $_GET[$key])); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
}

function centro_servizi_archive_trasparenza_child_terms(int $parent_id): array
{
    $terms = get_terms([
        'taxonomy'   => 'contenutiammtrasp',
        'parent'     => $parent_id,
        'hide_empty' => false,
        'orderby'    => 'slug',
        'order'      => 'ASC',
    ]);

    return is_wp_error($terms) ? [] : $terms;
}

function centro_servizi_archive_trasparenza_category_blueprint(): array
{
    return [
        [
            'slug' => '01-documentaz-trasp',
            'legacy_slugs' => [],
            'children' => [
                ['slug' => 'circolari-mim'],
                ['slug' => 'convenzione-con-ambito'],
                ['slug' => 'normativa'],
                ['slug' => 'ptpct'],
            ],
        ],
        [
            'slug' => '02-organizzaz',
            'legacy_slugs' => ['02-organizzazione'],
            'children' => [
                ['slug' => 'organi-collegiali'],
                ['slug' => 'organigramma'],
                ['slug' => 'organizzazione'],
            ],
        ],
        [
            'slug' => '03-autorizzazioni',
            'legacy_slugs' => [],
            'children' => [
                ['slug' => 'convenzioni'],
                ['slug' => 'decreto-parita-scolastica'],
                ['slug' => 'patto-corresp'],
                ['slug' => 'autorizzazioni'],
            ],
        ],
        [
            'slug' => '04-personale',
            'legacy_slugs' => [],
            'children' => [
                ['slug' => 'ccnl'],
                ['slug' => 'costi-pers'],
                ['slug' => 'organico'],
                ['slug' => 'r-i-l'],
                ['slug' => 'tassi-ass'],
            ],
        ],
        [
            'slug' => '05-consul-e-collab',
            'legacy_slugs' => ['05-consulenti-e-collaboratori'],
            'children' => [
                ['slug' => 'consulenti-e-collaboratori-esterni', 'legacy_slugs' => ['consul-e-collab']],
                ['slug' => 'convenzioni-uni'],
            ],
        ],
        [
            'slug' => '06-bilanci',
            'legacy_slugs' => [],
            'children' => [
                ['slug' => 'consuntivo'],
                ['slug' => 'preventivo'],
                ['slug' => 'sociale'],
            ],
        ],
        [
            'slug' => '07-immobili',
            'legacy_slugs' => [],
            'children' => [
                ['slug' => 'immobile', 'legacy_slugs' => ['immobili']],
                ['slug' => 'planimetria'],
            ],
        ],
        [
            'slug' => '08-aiuti-economici',
            'legacy_slugs' => [],
            'children' => [
                ['slug' => 'agenzia-delle-entrate'],
                ['slug' => 'contributi-pubblici'],
                ['slug' => 'incentivi-per-occupazione', 'legacy_slugs' => ['incentivi-per-occupaz']],
            ],
        ],
        [
            'slug' => '09-orari-e-calendario',
            'legacy_slugs' => [],
            'children' => [
                ['slug' => 'calendario'],
                ['slug' => 'giornata-tipo'],
                ['slug' => 'orari-funz'],
            ],
        ],
        [
            'slug' => '10-iscrizioni',
            'legacy_slugs' => [],
            'children' => [
                ['slug' => 'moduli-iscriz', 'legacy_slugs' => ['iscrizioni']],
            ],
        ],
        [
            'slug' => '11-servizi-erogati',
            'legacy_slugs' => [],
            'children' => [
                ['slug' => 'carta-serv', 'legacy_slugs' => ['carta-servizi']],
                ['slug' => 'mensa'],
                ['slug' => 'offerta-formativa'],
                ['slug' => 'pai'],
                ['slug' => 'ptof'],
                ['slug' => 'rav'],
                ['slug' => 'regolamenti'],
                ['slug' => 'rette-fam', 'legacy_slugs' => ['rette-famiglie']],
                ['slug' => 'regolamento-interno-scuola'],
            ],
        ],
        [
            'slug' => '12-controlli-e-rilievi',
            'legacy_slugs' => [],
            'children' => [
                ['slug' => 'griglia-anac'],
                ['slug' => 'nomina'],
                ['slug' => 'snv'],
                ['slug' => 'verifiche-periodiche'],
            ],
        ],
    ];
}

function centro_servizi_archive_trasparenza_find_term_by_slugs(array $terms, array $slugs): ?WP_Term
{
    foreach ($terms as $term) {
        if (! $term instanceof WP_Term) {
            continue;
        }

        if (in_array($term->slug, $slugs, true)) {
            return $term;
        }
    }

    return null;
}

function centro_servizi_archive_trasparenza_sort_terms_by_label(array $terms): array
{
    usort($terms, static function (WP_Term $left, WP_Term $right): int {
        return strcasecmp(
            centro_servizi_archive_trasparenza_term_display_name($left),
            centro_servizi_archive_trasparenza_term_display_name($right)
        );
    });

    return $terms;
}

function centro_servizi_archive_trasparenza_ordered_category_groups(array $all_terms): array
{
    $parents = [];
    $children_by_parent = [];

    foreach ($all_terms as $term) {
        if (! $term instanceof WP_Term) {
            continue;
        }

        if ((int) $term->parent === 0) {
            $parents[] = $term;
            continue;
        }

        if (! isset($children_by_parent[$term->parent])) {
            $children_by_parent[$term->parent] = [];
        }

        $children_by_parent[$term->parent][] = $term;
    }

    $groups = [];
    $used_parent_ids = [];
    $used_child_ids = [];

    foreach (centro_servizi_archive_trasparenza_category_blueprint() as $parent_config) {
        $parent_slugs = array_merge([$parent_config['slug']], $parent_config['legacy_slugs'] ?? []);
        $parent_term = centro_servizi_archive_trasparenza_find_term_by_slugs($parents, $parent_slugs);

        if (! $parent_term instanceof WP_Term) {
            continue;
        }

        $used_parent_ids[$parent_term->term_id] = true;
        $children = [];
        $actual_children = $children_by_parent[$parent_term->term_id] ?? [];

        foreach ($parent_config['children'] as $child_config) {
            $child_slugs = array_merge([$child_config['slug']], $child_config['legacy_slugs'] ?? []);
            $child_term = centro_servizi_archive_trasparenza_find_term_by_slugs($actual_children, $child_slugs);

            if (! $child_term instanceof WP_Term) {
                continue;
            }

            $children[] = $child_term;
            $used_child_ids[$child_term->term_id] = true;
        }

        $extra_children = [];
        foreach ($actual_children as $child_term) {
            if (! isset($used_child_ids[$child_term->term_id])) {
                $extra_children[] = $child_term;
            }
        }

        $groups[] = [
            'parent' => $parent_term,
            'children' => array_merge($children, centro_servizi_archive_trasparenza_sort_terms_by_label($extra_children)),
        ];
    }

    $extra_parents = [];
    foreach ($parents as $parent_term) {
        if (! isset($used_parent_ids[$parent_term->term_id])) {
            $extra_parents[] = $parent_term;
        }
    }

    foreach (centro_servizi_archive_trasparenza_sort_terms_by_label($extra_parents) as $parent_term) {
        $children = centro_servizi_archive_trasparenza_sort_terms_by_label($children_by_parent[$parent_term->term_id] ?? []);
        $groups[] = [
            'parent' => $parent_term,
            'children' => $children,
        ];
    }

    return $groups;
}

function centro_servizi_archive_trasparenza_assigned_terms(int $post_id): array
{
    $terms = get_the_terms($post_id, 'contenutiammtrasp');

    if (is_wp_error($terms) || empty($terms)) {
        return [];
    }

    return $terms;
}

function centro_servizi_archive_trasparenza_term_display_name(WP_Term $term): string
{
    $aliases = [
        'immobili' => 'Contratti fitto',
        'immobile' => 'Contratti fitto',
        'organizzazione' => 'Direzione e segreteria',
        'autorizzazioni' => 'Permessi e autorizzazioni',
    ];

    if (isset($aliases[$term->slug])) {
        return $aliases[$term->slug];
    }

    return centro_servizi_archive_trasparenza_clean_term_name($term->name);
}

function centro_servizi_archive_trasparenza_display_term(array $terms): ?WP_Term
{
    if ($terms === []) {
        return null;
    }

    usort($terms, static function (WP_Term $left, WP_Term $right): int {
        $left_depth = count(get_ancestors($left->term_id, 'contenutiammtrasp', 'taxonomy'));
        $right_depth = count(get_ancestors($right->term_id, 'contenutiammtrasp', 'taxonomy'));

        if ($left_depth !== $right_depth) {
            return $right_depth <=> $left_depth;
        }

        return strcmp($left->slug, $right->slug);
    });

    return $terms[0] instanceof WP_Term ? $terms[0] : null;
}

function centro_servizi_archive_trasparenza_term_label(?WP_Term $term): string
{
    if (! $term instanceof WP_Term) {
        return '';
    }

    if ($term->parent === 0) {
        return centro_servizi_archive_trasparenza_clean_term_name($term->name);
    }

    $parents = get_ancestors($term->term_id, 'contenutiammtrasp', 'taxonomy');
    $labels = [];

    foreach (array_reverse($parents) as $parent_id) {
        $parent_term = get_term($parent_id, 'contenutiammtrasp');

        if ($parent_term instanceof WP_Term) {
            $labels[] = centro_servizi_archive_trasparenza_term_display_name($parent_term);
        }
    }

    $labels[] = centro_servizi_archive_trasparenza_term_display_name($term);

    return implode(' / ', $labels);
}

function centro_servizi_archive_trasparenza_clean_term_name(string $name): string
{
    $clean = preg_replace('/^\s*(?:\d+\s*[\.)\-_:]?\s*|[-–—]+\s*)+/u', '', $name);

    if (! is_string($clean)) {
        return trim($name);
    }

    $clean = trim($clean);

    return $clean !== '' ? $clean : trim($name);
}
function centro_servizi_archive_trasparenza_title(?WP_Term $term, string $tag_anno, string $fallback): string
{
    $parts = array_filter([
        centro_servizi_archive_trasparenza_term_label($term),
        trim($tag_anno),
    ]);

    if ($parts === []) {
        return $fallback;
    }

    return implode(' - ', $parts);
}

function centro_servizi_archive_trasparenza_file_data(int $post_id): array
{
    $allegato = centro_servizi_get_meta_file_link_data($post_id, 'allegato');

    if ($allegato !== []) {
        return $allegato;
    }

    return centro_servizi_get_meta_file_link_data($post_id, 'documento');
}
}

get_template_part('partials/header');

$selected_anno = centro_servizi_archive_trasparenza_selected_slug('anno');
$selected_cat = centro_servizi_archive_trasparenza_selected_slug('cat');
$selected_search = centro_servizi_archive_trasparenza_selected_slug('q');

$anni = get_terms([
    'taxonomy'   => 'annoscolastico',
    'hide_empty' => false,
    'orderby'    => 'slug',
    'order'      => 'ASC',
]);

$anni = is_wp_error($anni) ? [] : $anni;

$all_categories = get_terms([
    'taxonomy'   => 'contenutiammtrasp',
    'hide_empty' => false,
]);

$all_categories = is_wp_error($all_categories) ? [] : $all_categories;
$category_groups = centro_servizi_archive_trasparenza_ordered_category_groups($all_categories);

$tax_query = [];

if ($selected_anno !== '') {
    $tax_query[] = [
        'taxonomy' => 'annoscolastico',
        'field'    => 'slug',
        'terms'    => $selected_anno,
    ];
}

if ($selected_cat !== '') {
    $tax_query[] = [
        'taxonomy'         => 'contenutiammtrasp',
        'field'            => 'slug',
        'terms'            => $selected_cat,
        'include_children' => true,
    ];
}

if (count($tax_query) > 1) {
    $tax_query['relation'] = 'AND';
}

$query_args = [
    'post_type'      => 'trasparenza',
    'posts_per_page' => -1,
    'orderby'        => 'date',
    'order'          => 'DESC',
    'no_found_rows'  => true,
];

if (! empty($tax_query)) {
    $query_args['tax_query'] = $tax_query;
}

if ($selected_search !== '') {
    $query_args['s'] = $selected_search;
}

// Expand text search to also match taxonomy term names (categories + years).
// This lets users find documents by typing e.g. "contributi" to surface docs
// in the "Contributi Pubblici" category even when post content is minimal.
if ($selected_search !== '') {
    $matching_terms = get_terms([
        'taxonomy'   => ['contenutiammtrasp', 'annoscolastico'],
        'hide_empty' => false,
        'search'     => $selected_search,
    ]);

    if (! is_wp_error($matching_terms) && ! empty($matching_terms)) {
        $matched_term_ids = array_map(static fn(WP_Term $t): int => $t->term_id, $matching_terms);

        $term_post_ids = get_posts([
            'post_type'      => 'trasparenza',
            'posts_per_page' => -1,
            'fields'         => 'ids',
            'no_found_rows'  => true,
            'tax_query'      => [[
                'taxonomy' => ['contenutiammtrasp', 'annoscolastico'],
                'field'    => 'term_id',
                'terms'    => $matched_term_ids,
                'operator' => 'IN',
            ]],
        ]);

        if (! empty($term_post_ids)) {
            $text_args           = $query_args;
            $text_args['fields'] = 'ids';
            $text_post_ids       = get_posts($text_args);

            $merged_ids = array_values(array_unique(array_merge(
                array_map('intval', (array) $text_post_ids),
                array_map('intval', (array) $term_post_ids)
            )));

            $query_args = [
                'post_type'      => 'trasparenza',
                'posts_per_page' => -1,
                'orderby'        => 'date',
                'order'          => 'DESC',
                'no_found_rows'  => true,
                'post__in'       => ! empty($merged_ids) ? $merged_ids : [0],
            ];
        }
    }
}

$documenti = new WP_Query($query_args);

$archive_url = get_post_type_archive_link('trasparenza');
$has_active_filters = ($selected_anno !== '' || $selected_cat !== '' || $selected_search !== '');

?>
<main class="site-main trasparenza-archive" id="contenuto-principale" role="main">
    <section class="site-section">
        <div class="site-section__inner">
            <header class="trasparenza-archive__header">
                <h1 class="trasparenza-archive__title"><?php post_type_archive_title(); ?></h1>
                <p class="trasparenza-archive__intro">Consulta i documenti filtrando per anno scolastico, categoria o parola chiave.</p>
            </header>

            <form method="get" action="<?php echo esc_url($archive_url); ?>" class="trasparenza-archive__years-form" aria-label="Filtro per anno scolastico">
                <?php if ($selected_cat !== '') : ?>
                    <input type="hidden" name="cat" value="<?php echo esc_attr($selected_cat); ?>">
                <?php endif; ?>
                <?php if ($selected_search !== '') : ?>
                    <input type="hidden" name="q" value="<?php echo esc_attr($selected_search); ?>">
                <?php endif; ?>

                <fieldset class="trasparenza-archive__years">
                    <legend class="sr-only">Anno scolastico</legend>

                    <label class="trasparenza-archive__year-option">
                        <input type="radio" name="anno" value="" <?php checked($selected_anno, ''); ?>>
                        <span>Tutti gli anni</span>
                    </label>

                    <?php foreach ($anni as $anno) : ?>
                        <label class="trasparenza-archive__year-option">
                            <input type="radio" name="anno" value="<?php echo esc_attr($anno->slug); ?>" <?php checked($selected_anno, $anno->slug); ?>>
                            <span><?php echo esc_html($anno->name); ?></span>
                        </label>
                    <?php endforeach; ?>
                </fieldset>

                <noscript>
                    <button type="submit">Applica anno</button>
                </noscript>
            </form>

            <div class="trasparenza-archive__layout">
                <aside class="trasparenza-archive__sidebar" aria-label="Filtri archivio trasparenza">
                    <form method="get" action="<?php echo esc_url($archive_url); ?>" class="trasparenza-filters">
                        <?php if ($selected_anno !== '') : ?>
                            <input type="hidden" name="anno" value="<?php echo esc_attr($selected_anno); ?>">
                        <?php endif; ?>

                        <div class="trasparenza-filters__search">
                            <label for="trasparenza-q">Cerca nei documenti</label>
                            <input type="search" id="trasparenza-q" name="q" value="<?php echo esc_attr($selected_search); ?>" placeholder="Cerca documento...">
                        </div>

                        <fieldset class="trasparenza-filters__fieldset">
                            <legend>Categoria</legend>

                            <label class="trasparenza-filters__option trasparenza-filters__option--all">
                                <input type="radio" name="cat" value="" <?php checked($selected_cat, ''); ?>>
                                <span>Tutte le categorie</span>
                            </label>

                            <?php if ($category_groups !== []) : ?>
                                <ul class="trasparenza-filters__category-list">
                                    <?php foreach ($category_groups as $group) : ?>
                                        <?php
                                        $parent = $group['parent'];
                                        $children = $group['children'];
                                        if (! $parent instanceof WP_Term) {
                                            continue;
                                        }
                                        ?>
                                        <li class="trasparenza-filters__category-group">
                                            <div class="trasparenza-filters__option trasparenza-filters__option--parent trasparenza-filters__category-parent">
                                                <span><strong><?php echo esc_html(centro_servizi_archive_trasparenza_term_display_name($parent)); ?></strong></span>
                                            </div>

                                            <?php if ($children !== []) : ?>
                                                <ul class="trasparenza-filters__category-children">
                                                    <?php foreach ($children as $child) : ?>
                                                        <?php if ($child instanceof WP_Term) : ?>
                                                            <li>
                                                                <label class="trasparenza-filters__option trasparenza-filters__option--child">
                                                                    <input type="radio" name="cat" value="<?php echo esc_attr($child->slug); ?>" <?php checked($selected_cat, $child->slug); ?>>
                                                                    <span><?php echo esc_html(centro_servizi_archive_trasparenza_term_display_name($child)); ?></span>
                                                                </label>
                                                            </li>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php endif; ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else : ?>
                                <p>Nessuna categoria disponibile.</p>
                            <?php endif; ?>
                        </fieldset>

                        <?php if ($has_active_filters) : ?>
                        <div class="trasparenza-filters__actions">
                            <a href="<?php echo esc_url($archive_url); ?>">Reset filtri</a>
                        </div>
                        <?php endif; ?>
                    </form>
                </aside>

                <div class="trasparenza-archive__results">
                    <p class="trasparenza-archive__summary"><?php echo esc_html(sprintf(_n('%d documento trovato', '%d documenti trovati', $documenti->post_count, 'tema-centro-servizi'), $documenti->post_count)); ?></p>

    <?php if ($documenti->post_count > 0) : ?>
    <ul class="trasparenza-archive__list">
        <?php foreach ($documenti->posts as $documento_post) : ?>
        <?php
        setup_postdata($documento_post);
        $post_id = (int) $documento_post->ID;
        $titolo_custom = centro_servizi_get_post_meta_string($post_id, 'titolo');
        $testo = centro_servizi_get_post_meta_string($post_id, 'testo');
        $tag_anno = centro_servizi_get_post_meta_string($post_id, 'tag_anno');
        $allegato = centro_servizi_archive_trasparenza_file_data($post_id);
        $termine_display = centro_servizi_archive_trasparenza_display_term(centro_servizi_archive_trasparenza_assigned_terms($post_id));
        $contenuto = trim((string) get_post_field('post_content', $post_id));
        ?>
        <li class="trasparenza-archive__item">
            <?php
            get_template_part('partials/card-trasparenza', null, [
                'post_id' => $post_id,
                'termine_display' => $termine_display,
                'tag_anno' => $tag_anno,
                'titolo_custom' => $titolo_custom,
                'testo' => $testo,
                'allegato' => $allegato,
                'contenuto' => $contenuto,
            ]);
            ?>
        </li>
        <?php endforeach; ?>
    </ul>
    <?php endif; ?>

    <?php if ($documenti->post_count === 0) : ?>
    <p>Nessun documento trovato con i filtri correnti.</p>
    <?php if ($has_active_filters) : ?>
    <p><a href="<?php echo esc_url($archive_url); ?>">Reset filtri</a></p>
    <?php endif; ?>
    <?php endif; ?>

                </div>
            </div>

    <?php wp_reset_postdata(); ?>

        </div>
    </section>
</main>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var yearForm = document.querySelector('.trasparenza-archive__years-form');
    var filterForm = document.querySelector('.trasparenza-filters');
    var resultsContainer = document.querySelector('.trasparenza-archive__results');

    if (!yearForm || !filterForm || !resultsContainer) {
        return;
    }

    var activeRequestId = 0;

    function getSelectedValue(selector) {
        var checked = document.querySelector(selector + ':checked');
        return checked ? checked.value : '';
    }

    function getToggleableInput(target) {
        if (!target || !target.closest) {
            return null;
        }

        if (target.matches && target.matches('input[name="anno"], input[name="cat"]')) {
            return target;
        }

        var label = target.closest('label');

        if (!label) {
            return null;
        }

        return label.querySelector('input[name="anno"], input[name="cat"]');
    }

    function buildUrl() {
        var url = new URL(window.location.href);
        var anno = getSelectedValue('input[name="anno"]');
        var cat = getSelectedValue('input[name="cat"]');
        var searchInput = filterForm.querySelector('input[name="q"]');
        var q = searchInput ? searchInput.value.trim() : '';

        url.searchParams.delete('anno');
        url.searchParams.delete('cat');
        url.searchParams.delete('q');

        if (anno !== '') {
            url.searchParams.set('anno', anno);
        }
        if (cat !== '') {
            url.searchParams.set('cat', cat);
        }
        if (q !== '') {
            url.searchParams.set('q', q);
        }

        return url;
    }

    function refreshResults() {
        activeRequestId += 1;
        var requestId = activeRequestId;
        var url = buildUrl();
        var previousScrollTop = resultsContainer.scrollTop;

        fetch(url.toString(), {
            credentials: 'same-origin',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(function (response) {
                return response.text();
            })
            .then(function (html) {
                if (requestId !== activeRequestId) {
                    return;
                }

                var parser = new DOMParser();
                var doc = parser.parseFromString(html, 'text/html');
                var newResults = doc.querySelector('.trasparenza-archive__results');

                if (!newResults) {
                    return;
                }

                resultsContainer.innerHTML = newResults.innerHTML;
                resultsContainer.scrollTop = previousScrollTop;
                history.replaceState({}, '', url.pathname + url.search);
            })
            .catch(function () {
                if (requestId !== activeRequestId) {
                    return;
                }

                window.location.href = url.toString();
            });
    }

    // Radio deselection: memorizza lo stato sul label stesso al mousedown,
    // così ogni label ha il proprio stato e non c'è variabile condivisa.
    function setupRadioToggle(form) {
        form.addEventListener('mousedown', function (event) {
            var label = event.target.closest('label');
            if (!label) { return; }
            var input = label.querySelector('input[name="anno"], input[name="cat"]');
            if (!input) { return; }
            label.dataset.prevChecked = input.checked ? '1' : '0';
        });

        form.addEventListener('click', function (event) {
            var label = event.target.closest('label');
            if (!label) { return; }
            var input = label.querySelector('input[name="anno"], input[name="cat"]');
            if (!input) { return; }

            if (label.dataset.prevChecked === '1') {
                event.preventDefault();
                input.checked = false;
                refreshResults();
            }
        });
    }

    setupRadioToggle(yearForm);
    setupRadioToggle(filterForm);

    var autoInputs = document.querySelectorAll('input[name="anno"], input[name="cat"]');
    for (var index = 0; index < autoInputs.length; index += 1) {
        autoInputs[index].addEventListener('change', refreshResults);
    }

    filterForm.addEventListener('submit', function (event) {
        event.preventDefault();
        refreshResults();
    });
});
</script>

<?php get_template_part('partials/footer'); ?>
