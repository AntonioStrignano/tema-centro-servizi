<?php
declare(strict_types=1);

if (! function_exists('centro_servizi_archive_area_famiglie_selected_slug')) {
function centro_servizi_archive_area_famiglie_selected_slug(string $key): string
{
    if (! isset($_GET[$key])) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        return '';
    }

    return sanitize_text_field(wp_unslash((string) $_GET[$key])); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
}

function centro_servizi_archive_area_famiglie_clean_term_name(string $name): string
{
    $clean = preg_replace('/^\s*(?:\d+\s*[\.\)\-_:]?\s*|[-\u2013\u2014]+\s*)+/u', '', $name);

    if (! is_string($clean)) {
        return trim($name);
    }

    $clean = trim($clean);

    return $clean !== '' ? $clean : trim($name);
}

function centro_servizi_archive_area_famiglie_term_display_name(WP_Term $term): string
{
    return centro_servizi_archive_area_famiglie_clean_term_name($term->name);
}

function centro_servizi_archive_area_famiglie_category_groups(array $all_terms): array
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

        $children_by_parent[$term->parent][] = $term;
    }

    usort($parents, static function (WP_Term $a, WP_Term $b): int {
        return strcmp($a->slug, $b->slug);
    });

    $groups = [];

    foreach ($parents as $parent_term) {
        $children = $children_by_parent[$parent_term->term_id] ?? [];
        usort($children, static function (WP_Term $a, WP_Term $b): int {
            return strcmp($a->slug, $b->slug);
        });

        $groups[] = [
            'parent'   => $parent_term,
            'children' => $children,
        ];
    }

    return $groups;
}
} // end function_exists

get_template_part('partials/header');

$selected_cat    = centro_servizi_archive_area_famiglie_selected_slug('cat');
$selected_search = centro_servizi_archive_area_famiglie_selected_slug('q');

$all_categories  = get_terms([
    'taxonomy'   => 'categoria-area-famiglia',
    'hide_empty' => false,
]);

$all_categories  = is_wp_error($all_categories) ? [] : $all_categories;
$category_groups = centro_servizi_archive_area_famiglie_category_groups($all_categories);

$tax_query = [];

if ($selected_cat !== '') {
    $tax_query[] = [
        'taxonomy'         => 'categoria-area-famiglia',
        'field'            => 'slug',
        'terms'            => $selected_cat,
        'include_children' => true,
    ];
}

$query_args = [
    'post_type'      => 'area-famiglie',
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

if ($selected_search !== '') {
    $matching_terms = get_terms([
        'taxonomy'   => 'categoria-area-famiglia',
        'hide_empty' => false,
        'search'     => $selected_search,
    ]);

    if (! is_wp_error($matching_terms) && ! empty($matching_terms)) {
        $matched_term_ids = array_values(array_unique(array_map(
            static fn(WP_Term $t): int => $t->term_id,
            array_filter($matching_terms, static fn($t): bool => $t instanceof WP_Term)
        )));

        $term_post_ids = get_posts([
            'post_type'      => 'area-famiglie',
            'posts_per_page' => -1,
            'fields'         => 'ids',
            'no_found_rows'  => true,
            'tax_query'      => [[
                'taxonomy'         => 'categoria-area-famiglia',
                'field'            => 'term_id',
                'terms'            => $matched_term_ids,
                'operator'         => 'IN',
                'include_children' => true,
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
                'post_type'      => 'area-famiglie',
                'posts_per_page' => -1,
                'orderby'        => 'date',
                'order'          => 'DESC',
                'no_found_rows'  => true,
                'post__in'       => ! empty($merged_ids) ? $merged_ids : [0],
            ];
        }
    }
}

$contenuti          = new WP_Query($query_args);
$archive_url        = get_post_type_archive_link('area-famiglie');
$has_active_filters = ($selected_cat !== '' || $selected_search !== '');

?>
<main class="site-main trasparenza-archive" id="contenuto-principale" role="main">
    <section class="site-section">
        <div class="site-section__inner">
            <header class="trasparenza-archive__header">
                <h1 class="trasparenza-archive__title"><?php post_type_archive_title(); ?></h1>
                <p class="trasparenza-archive__intro">Consulta i documenti filtrando per categoria o parola chiave.</p>
            </header>

            <div class="trasparenza-archive__layout">
                <aside class="trasparenza-archive__sidebar" aria-label="Filtri archivio area famiglie">
                    <form method="get" action="<?php echo esc_url($archive_url); ?>" class="trasparenza-filters">

                        <div class="trasparenza-filters__search">
                            <label for="area-famiglie-q">Cerca</label>
                            <input type="search" id="area-famiglie-q" name="q" value="<?php echo esc_attr($selected_search); ?>" placeholder="Cerca documento...">
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
                                        $parent   = $group['parent'];
                                        $children = $group['children'];
                                        if (! $parent instanceof WP_Term) {
                                            continue;
                                        }
                                        ?>
                                        <li class="trasparenza-filters__category-group">
                                            <div class="trasparenza-filters__option trasparenza-filters__option--parent trasparenza-filters__category-parent">
                                                <span><strong><?php echo esc_html(centro_servizi_archive_area_famiglie_term_display_name($parent)); ?></strong></span>
                                            </div>

                                            <?php if ($children !== []) : ?>
                                                <ul class="trasparenza-filters__category-children">
                                                    <?php foreach ($children as $child) : ?>
                                                        <?php if ($child instanceof WP_Term) : ?>
                                                            <li>
                                                                <label class="trasparenza-filters__option trasparenza-filters__option--child">
                                                                    <input type="radio" name="cat" value="<?php echo esc_attr($child->slug); ?>" <?php checked($selected_cat, $child->slug); ?>>
                                                                    <span><?php echo esc_html(centro_servizi_archive_area_famiglie_term_display_name($child)); ?></span>
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
                    <p class="trasparenza-archive__summary"><?php echo esc_html(sprintf(_n('%d documento trovato', '%d documenti trovati', $contenuti->post_count, 'tema-centro-servizi'), $contenuti->post_count)); ?></p>

    <?php if ($contenuti->post_count > 0) : ?>
    <ul class="trasparenza-archive__list">
        <?php foreach ($contenuti->posts as $contenuto_post) : ?>
        <?php
        setup_postdata($contenuto_post);
        $post_id     = (int) $contenuto_post->ID;
        $terms_raw   = get_the_terms($post_id, 'categoria-area-famiglia');
        $terms_raw   = is_wp_error($terms_raw) || empty($terms_raw) ? [] : $terms_raw;
        usort($terms_raw, static function (WP_Term $a, WP_Term $b): int { return strcmp($a->slug, $b->slug); });
        $termine_display = $terms_raw !== [] && $terms_raw[0] instanceof WP_Term ? $terms_raw[0] : null;
        ?>
        <li class="trasparenza-archive__item">
            <?php
            get_template_part('partials/card-area-famiglie', null, [
                'post_id'         => $post_id,
                'termine_display' => $termine_display,
            ]);
            ?>
        </li>
        <?php endforeach; ?>
    </ul>
    <?php endif; ?>

    <?php if ($contenuti->post_count === 0) : ?>
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
    var filterForm       = document.querySelector('.trasparenza-filters');
    var sidebarContainer = document.querySelector('.trasparenza-archive__sidebar');
    var resultsContainer = document.querySelector('.trasparenza-archive__results');

    if (!filterForm || !resultsContainer) {
        return;
    }

    var activeRequestId = 0;

    function getSelectedValue(selector) {
        var checked = document.querySelector(selector + ':checked');
        return checked ? checked.value : '';
    }

    function buildUrl() {
        var url         = new URL(window.location.href);
        var cat         = getSelectedValue('input[name="cat"]');
        var searchInput = filterForm.querySelector('input[name="q"]');
        var q           = searchInput ? searchInput.value.trim() : '';

        url.searchParams.delete('cat');
        url.searchParams.delete('q');

        if (cat !== '') { url.searchParams.set('cat', cat); }
        if (q !== '')   { url.searchParams.set('q', q); }

        return url;
    }

    function updateScrollHintState(container) {
        if (!container) { return; }
        var scrollableDistance = container.scrollHeight - container.clientHeight;
        var hasOverflow  = scrollableDistance > 2;
        var isAtBottom   = !hasOverflow || (container.scrollTop >= scrollableDistance - 2);
        container.classList.toggle('has-scroll-hint', hasOverflow);
        container.classList.toggle('is-at-bottom', isAtBottom);
        if (!hasOverflow) { container.classList.remove('is-at-bottom'); }
    }

    function refreshResults() {
        activeRequestId += 1;
        var requestId        = activeRequestId;
        var url              = buildUrl();
        var previousScrollTop = resultsContainer.scrollTop;

        fetch(url.toString(), {
            credentials: 'same-origin',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
            .then(function (response) { return response.text(); })
            .then(function (html) {
                if (requestId !== activeRequestId) { return; }
                var parser     = new DOMParser();
                var doc        = parser.parseFromString(html, 'text/html');
                var newResults = doc.querySelector('.trasparenza-archive__results');
                if (!newResults) { return; }
                resultsContainer.innerHTML  = newResults.innerHTML;
                resultsContainer.scrollTop  = previousScrollTop;
                history.replaceState({}, '', url.pathname + url.search);
                updateScrollHintState(resultsContainer);
            })
            .catch(function () {
                if (requestId !== activeRequestId) { return; }
                window.location.href = url.toString();
            });
    }

    function setupRadioToggle(form) {
        form.addEventListener('mousedown', function (event) {
            var label = event.target.closest('label');
            if (!label) { return; }
            var input = label.querySelector('input[name="cat"]');
            if (!input) { return; }
            label.dataset.prevChecked = input.checked ? '1' : '0';
        });

        form.addEventListener('click', function (event) {
            var label = event.target.closest('label');
            if (!label) { return; }
            var input = label.querySelector('input[name="cat"]');
            if (!input) { return; }
            if (label.dataset.prevChecked === '1') {
                event.preventDefault();
                input.checked = false;
                refreshResults();
            }
        });
    }

    setupRadioToggle(filterForm);

    var autoInputs = document.querySelectorAll('input[name="cat"]');
    for (var index = 0; index < autoInputs.length; index += 1) {
        autoInputs[index].addEventListener('change', refreshResults);
    }

    filterForm.addEventListener('submit', function (event) {
        event.preventDefault();
        refreshResults();
    });

    if (sidebarContainer) {
        sidebarContainer.addEventListener('scroll', function () { updateScrollHintState(sidebarContainer); });
    }
    resultsContainer.addEventListener('scroll', function () { updateScrollHintState(resultsContainer); });
    window.addEventListener('resize', function () {
        updateScrollHintState(sidebarContainer);
        updateScrollHintState(resultsContainer);
    });
    updateScrollHintState(sidebarContainer);
    updateScrollHintState(resultsContainer);
});
</script>

<?php get_template_part('partials/footer'); ?>
