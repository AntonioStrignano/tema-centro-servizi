<?php
declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

if (! isset($centro_post_type, $centro_taxonomy, $centro_card_partial, $centro_sidebar_aria_label, $centro_search_input_id)) {
    if (is_post_type_archive('area-famiglie')) {
        $centro_post_type = 'area-famiglie';
        $centro_taxonomy = 'categoria-area-famiglia';
        $centro_card_partial = 'partials/card-area-famiglie';
        $centro_sidebar_aria_label = 'Filtri archivio area famiglie';
        $centro_search_input_id = 'area-famiglie-q';
    } elseif (is_post_type_archive('area-personale')) {
        $centro_post_type = 'area-personale';
        $centro_taxonomy = 'categoria-area-personale';
        $centro_card_partial = 'partials/card-area-personale';
        $centro_sidebar_aria_label = 'Filtri archivio area personale';
        $centro_search_input_id = 'area-personale-q';
    } else {
        return;
    }
}

if (! is_string($centro_post_type) || $centro_post_type === '') {
    return;
}

if (! is_string($centro_taxonomy) || $centro_taxonomy === '') {
    return;
}

if (! is_string($centro_card_partial) || $centro_card_partial === '') {
    return;
}

if (! is_string($centro_sidebar_aria_label) || $centro_sidebar_aria_label === '') {
    return;
}

if (! is_string($centro_search_input_id) || $centro_search_input_id === '') {
    return;
}

if (! function_exists('centro_servizi_archive_burocratica_selected_slug')) {
function centro_servizi_archive_burocratica_selected_slug(string $key): string
{
    if (! isset($_GET[$key])) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        return '';
    }

    return sanitize_text_field(wp_unslash((string) $_GET[$key])); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
}
}

get_template_part('partials/header');

$selected_cat    = centro_servizi_archive_burocratica_selected_slug('cat');
$selected_search = centro_servizi_archive_burocratica_selected_slug('q');

$all_categories = get_terms([
    'taxonomy'   => $centro_taxonomy,
    'hide_empty' => false,
    'orderby'    => 'name',
    'order'      => 'ASC',
]);

$all_categories = is_wp_error($all_categories) ? [] : $all_categories;

$tax_query = [];

if ($selected_cat !== '') {
    $tax_query[] = [
        'taxonomy' => $centro_taxonomy,
        'field'    => 'slug',
        'terms'    => $selected_cat,
    ];
}

$query_args = [
    'post_type'      => $centro_post_type,
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
        'taxonomy'   => $centro_taxonomy,
        'hide_empty' => false,
        'search'     => $selected_search,
    ]);

    if (! is_wp_error($matching_terms) && ! empty($matching_terms)) {
        $matched_term_ids = array_values(array_unique(array_map(
            static fn(WP_Term $t): int => $t->term_id,
            array_filter($matching_terms, static fn($t): bool => $t instanceof WP_Term)
        )));

        $term_post_ids = get_posts([
            'post_type'      => $centro_post_type,
            'posts_per_page' => -1,
            'fields'         => 'ids',
            'no_found_rows'  => true,
            'tax_query'      => [[
                'taxonomy'         => $centro_taxonomy,
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
                'post_type'      => $centro_post_type,
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
$archive_url        = get_post_type_archive_link($centro_post_type);
$has_active_filters = ($selected_cat !== '' || $selected_search !== '');

?>
<main class="site-main trasparenza-archive burocratica-archive" id="contenuto-principale" role="main">
    <section class="site-section">
        <div class="site-section__inner">
            <header class="trasparenza-archive__header">
                <h1 class="trasparenza-archive__title"><?php post_type_archive_title(); ?></h1>
                <p class="trasparenza-archive__intro">Consulta i documenti filtrando per categoria o parola chiave.</p>
            </header>

            <div class="trasparenza-archive__layout">
                <aside class="trasparenza-archive__sidebar" aria-label="<?php echo esc_attr($centro_sidebar_aria_label); ?>">
                    <form method="get" action="<?php echo esc_url((string) $archive_url); ?>" class="trasparenza-filters burocratica-filters">

                        <div class="trasparenza-filters__search">
                            <label for="<?php echo esc_attr($centro_search_input_id); ?>">Cerca</label>
                            <input type="search" id="<?php echo esc_attr($centro_search_input_id); ?>" name="q" value="<?php echo esc_attr($selected_search); ?>" placeholder="Cerca documento...">
                        </div>

                        <fieldset class="trasparenza-filters__fieldset">
                            <legend>Categoria</legend>

                            <label class="trasparenza-filters__option trasparenza-filters__option--all">
                                <input type="radio" name="cat" value="" <?php checked($selected_cat, ''); ?>>
                                <span>Tutte le categorie</span>
                            </label>

                            <?php if ($all_categories !== []) : ?>
                                <ul class="trasparenza-filters__category-list">
                                    <?php foreach ($all_categories as $categoria) : ?>
                                        <?php if ($categoria instanceof WP_Term) : ?>
                                            <li>
                                                <label class="trasparenza-filters__option">
                                                    <input type="radio" name="cat" value="<?php echo esc_attr($categoria->slug); ?>" <?php checked($selected_cat, $categoria->slug); ?>>
                                                    <span><?php echo esc_html($categoria->name); ?></span>
                                                </label>
                                            </li>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else : ?>
                                <p>Nessuna categoria disponibile.</p>
                            <?php endif; ?>
                        </fieldset>

                        <?php if ($has_active_filters) : ?>
                        <div class="trasparenza-filters__actions">
                            <a href="<?php echo esc_url((string) $archive_url); ?>">Reset filtri</a>
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
        $terms_raw   = get_the_terms($post_id, $centro_taxonomy);
        $terms_raw   = is_wp_error($terms_raw) || empty($terms_raw) ? [] : $terms_raw;
        usort($terms_raw, static function (WP_Term $a, WP_Term $b): int { return strcmp($a->slug, $b->slug); });
        $termine_display = $terms_raw !== [] && $terms_raw[0] instanceof WP_Term ? $terms_raw[0] : null;
        ?>
        <li class="trasparenza-archive__item">
            <?php
            get_template_part($centro_card_partial, null, [
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
    <p><a href="<?php echo esc_url((string) $archive_url); ?>">Reset filtri</a></p>
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
