<?php
declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

get_template_part('partials/header');

global $wp_query;

$search_term = trim((string) get_search_query());
$results = isset($wp_query->posts) && is_array($wp_query->posts) ? $wp_query->posts : [];
$grouped_results = function_exists('centro_servizi_group_search_posts')
    ? centro_servizi_group_search_posts($results)
    : [];
$total_results = $wp_query instanceof WP_Query ? (int) $wp_query->found_posts : 0;
$selected_post_types = function_exists('centro_servizi_get_search_selected_post_types')
    ? centro_servizi_get_search_selected_post_types()
    : [];
$available_post_types = function_exists('centro_servizi_get_search_post_type_map')
    ? centro_servizi_get_search_post_type_map()
    : [];
$has_custom_type_filter = $available_post_types !== [] && count($selected_post_types) < count($available_post_types);
?>
<main class="site-main search-page" id="contenuto-principale" role="main">
    <section class="site-section search-page__section">
        <div class="site-section__inner search-page__inner">
            <header class="search-page__header">
                <p class="search-page__eyebrow">Ricerca nel sito</p>
                <h1 class="search-page__title">Risultati ricerca</h1>
                <?php if ($search_term !== '') : ?>
                    <p class="search-page__summary"><?php echo esc_html($total_results); ?> risultati per &quot;<?php echo esc_html($search_term); ?>&quot;.</p>
                <?php else : ?>
                    <p class="search-page__summary">Inserisci un termine e filtra i tipi di contenuto da cercare.</p>
                <?php endif; ?>
            </header>

            <div class="search-page__layout">
                <aside class="search-page__sidebar" aria-label="Filtri ricerca">
                    <div class="search-page__panel">
                        <h2 class="search-page__panel-title">Affina la ricerca</h2>
                        <p class="search-page__panel-text">Usa il termine e i tipi di contenuto per restringere i risultati.</p>

                        <?php get_template_part('partials/search-form', null, ['context' => 'results', 'show_filters' => true, 'form_id_prefix' => 'results-search']); ?>

                        <?php if ($search_term !== '' || $has_custom_type_filter) : ?>
                            <div class="search-page__active-filters" aria-label="Filtri attivi">
                                <p class="search-page__active-filters-title">Filtri attivi</p>
                                <div class="search-page__chips">
                                    <?php if ($search_term !== '') : ?>
                                        <span class="search-page__chip">Testo: <?php echo esc_html($search_term); ?></span>
                                    <?php endif; ?>

                                    <?php if ($has_custom_type_filter) : ?>
                                        <?php foreach ($selected_post_types as $post_type) : ?>
                                            <span class="search-page__chip"><?php echo esc_html($available_post_types[$post_type] ?? $post_type); ?></span>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                                <p class="search-page__reset-wrap"><a class="search-page__reset" href="<?php echo esc_url(home_url('/')); ?>?s=">Azzera ricerca</a></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </aside>

                <div class="search-page__content">
                    <?php if ($total_results > 0 && $grouped_results !== []) : ?>
                        <div class="search-results" aria-live="polite">
                            <?php foreach ($grouped_results as $post_type => $posts) : ?>
                                <section class="search-results__group" aria-labelledby="search-group-<?php echo esc_attr($post_type); ?>">
                                    <header class="search-results__group-header">
                                        <div>
                                            <p class="search-results__group-kicker">Tipo contenuto</p>
                                            <h2 id="search-group-<?php echo esc_attr($post_type); ?>" class="search-results__group-title">
                                                <?php echo esc_html(centro_servizi_get_search_type_label((string) $post_type)); ?>
                                            </h2>
                                        </div>
                                        <p class="search-results__group-count"><?php echo esc_html((string) count($posts)); ?> risultati in questa pagina</p>
                                    </header>

                                    <div class="search-results__list">
                                        <?php foreach ($posts as $post) : ?>
                                            <?php if (! $post instanceof WP_Post) : ?>
                                                <?php continue; ?>
                                            <?php endif; ?>

                                            <?php
                                            $post_id = (int) $post->ID;
                                            $excerpt = centro_servizi_get_search_result_excerpt($post_id);
                                            $result_title = centro_servizi_get_search_result_title($post_id);
                                            $result_url = centro_servizi_get_search_result_url($post_id);
                                            $context_items = centro_servizi_get_search_result_context($post_id);
                                            ?>
                                            <article class="site-card search-result-card">
                                                <div class="search-result-card__meta-row">
                                                    <p class="search-result-card__type"><?php echo esc_html(centro_servizi_get_search_type_label((string) $post_type)); ?></p>
                                                    <p class="search-result-card__date">
                                                        <time datetime="<?php echo esc_attr(get_the_date('c', $post_id)); ?>"><?php echo esc_html(get_the_date('j F Y', $post_id)); ?></time>
                                                    </p>
                                                </div>
                                                <h3 class="search-result-card__title">
                                                    <a href="<?php echo esc_url($result_url); ?>"><?php echo esc_html($result_title); ?></a>
                                                </h3>
                                                <?php if ($context_items !== []) : ?>
                                                    <dl class="search-result-card__context" aria-label="Contesto contenuto">
                                                        <?php foreach ($context_items as $context_item) : ?>
                                                            <div class="search-result-card__context-row">
                                                                <dt><?php echo esc_html((string) $context_item['label']); ?></dt>
                                                                <dd><?php echo esc_html(implode(', ', (array) $context_item['values'])); ?></dd>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </dl>
                                                <?php endif; ?>
                                                <?php if ($excerpt !== '') : ?>
                                                    <p class="search-result-card__excerpt"><?php echo esc_html($excerpt); ?></p>
                                                <?php endif; ?>
                                                <p class="search-result-card__cta-wrap">
                                                    <a class="search-result-card__cta" href="<?php echo esc_url($result_url); ?>"><?php echo $post_type === 'trasparenza' ? 'Apri archivio filtrato' : 'Apri contenuto'; ?></a>
                                                </p>
                                            </article>
                                        <?php endforeach; ?>
                                    </div>
                                </section>
                            <?php endforeach; ?>
                        </div>

                        <?php get_template_part('partials/pagination', null, ['query' => $wp_query]); ?>
                    <?php elseif ($search_term !== '') : ?>
                        <div class="search-page__empty site-card">
                            <p class="search-page__empty-kicker">Nessuna corrispondenza</p>
                            <h2>Nessun risultato trovato</h2>
                            <p>Prova a cambiare termine di ricerca oppure amplia i tipi di contenuto selezionati.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
</main>
<?php
get_template_part('partials/footer');