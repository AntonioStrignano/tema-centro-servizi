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
?>
<main class="site-main search-page" id="contenuto-principale" role="main">
    <section class="site-section search-page__section">
        <div class="site-section__inner search-page__inner">
            <header class="search-page__header">
                <h1 class="search-page__title">Risultati ricerca</h1>
                <?php if ($search_term !== '') : ?>
                    <p class="search-page__summary"><?php echo esc_html($total_results); ?> risultati per &quot;<?php echo esc_html($search_term); ?>&quot;.</p>
                <?php else : ?>
                    <p class="search-page__summary">Inserisci un termine e filtra i tipi di contenuto da cercare.</p>
                <?php endif; ?>
            </header>

            <?php get_template_part('partials/search-form', null, ['context' => 'results', 'show_filters' => true, 'form_id_prefix' => 'results-search']); ?>

            <?php if ($total_results > 0 && $grouped_results !== []) : ?>
                <div class="search-results" aria-live="polite">
                    <?php foreach ($grouped_results as $post_type => $posts) : ?>
                        <section class="search-results__group" aria-labelledby="search-group-<?php echo esc_attr($post_type); ?>">
                            <header class="search-results__group-header">
                                <h2 id="search-group-<?php echo esc_attr($post_type); ?>" class="search-results__group-title">
                                    <?php echo esc_html(centro_servizi_get_search_type_label((string) $post_type)); ?>
                                </h2>
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
                                    ?>
                                    <article class="site-card search-result-card">
                                        <p class="search-result-card__type"><?php echo esc_html(centro_servizi_get_search_type_label((string) $post_type)); ?></p>
                                        <h3 class="search-result-card__title">
                                            <a href="<?php echo esc_url(get_permalink($post_id)); ?>"><?php echo esc_html(get_the_title($post_id)); ?></a>
                                        </h3>
                                        <p class="search-result-card__date">
                                            <time datetime="<?php echo esc_attr(get_the_date('c', $post_id)); ?>"><?php echo esc_html(get_the_date('j F Y', $post_id)); ?></time>
                                        </p>
                                        <?php if ($excerpt !== '') : ?>
                                            <p class="search-result-card__excerpt"><?php echo esc_html($excerpt); ?></p>
                                        <?php endif; ?>
                                    </article>
                                <?php endforeach; ?>
                            </div>
                        </section>
                    <?php endforeach; ?>
                </div>

                <?php get_template_part('partials/pagination', null, ['query' => $wp_query]); ?>
            <?php elseif ($search_term !== '') : ?>
                <div class="search-page__empty site-card">
                    <h2>Nessun risultato trovato</h2>
                    <p>Prova a cambiare termine di ricerca oppure amplia i tipi di contenuto selezionati.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>
<?php
get_template_part('partials/footer');