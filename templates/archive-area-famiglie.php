<?php
declare(strict_types=1);

function centro_servizi_archive_area_famiglie_selected_slug(string $key): string
{
    if (! isset($_GET[$key])) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        return '';
    }

    return sanitize_text_field(wp_unslash((string) $_GET[$key])); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
}

function centro_servizi_archive_area_famiglie_assigned_terms(int $post_id): array
{
    $terms = get_the_terms($post_id, 'categoria-area-famiglia');

    if (is_wp_error($terms) || empty($terms)) {
        return [];
    }

    return $terms;
}

function centro_servizi_archive_area_famiglie_display_term(array $terms): ?WP_Term
{
    if ($terms === []) {
        return null;
    }

    usort($terms, static function (WP_Term $left, WP_Term $right): int {
        return strcmp($left->slug, $right->slug);
    });

    return $terms[0] instanceof WP_Term ? $terms[0] : null;
}

function centro_servizi_archive_area_famiglie_file_data(int $post_id): array
{
    return centro_servizi_get_meta_file_link_data($post_id, 'allegato');
}

get_template_part('partials/header');

$selected_cat = centro_servizi_archive_area_famiglie_selected_slug('cat');

$categorie = get_terms([
    'taxonomy'   => 'categoria-area-famiglia',
    'hide_empty' => false,
    'orderby'    => 'slug',
    'order'      => 'ASC',
]);

$categorie = is_wp_error($categorie) ? [] : $categorie;

$query_args = [
    'post_type'      => 'area-famiglie',
    'posts_per_page' => -1,
    'orderby'        => 'date',
    'order'          => 'DESC',
    'no_found_rows'  => true,
];

if ($selected_cat !== '') {
    $query_args['tax_query'] = [[
        'taxonomy' => 'categoria-area-famiglia',
        'field'    => 'slug',
        'terms'    => $selected_cat,
    ]];
}

$contenuti = new WP_Query($query_args);

$archive_url = get_post_type_archive_link('area-famiglie');
$has_active_filters = ($selected_cat !== '');

?>
<main class="site-main" id="contenuto-principale" role="main">

    <h1><?php post_type_archive_title(); ?></h1>

    <form method="get" action="<?php echo esc_url($archive_url); ?>" id="filtri-area-famiglie">
        <fieldset>
            <legend>Categoria</legend>

            <label>
                <input type="radio" name="cat" value=""
                    <?php checked($selected_cat, ''); ?>
                    onchange="this.form.submit()">
                Tutte
            </label>

            <?php foreach ($categorie as $categoria) : ?>
            <label style="display: block;">
                <input type="radio" name="cat" value="<?php echo esc_attr($categoria->slug); ?>"
                    <?php checked($selected_cat, $categoria->slug); ?>
                    onchange="this.form.submit()">
                <?php echo esc_html($categoria->name); ?>
            </label>
            <?php endforeach; ?>

            <?php if ($categorie === []) : ?>
            <p>Nessuna categoria disponibile.</p>
            <?php endif; ?>

        </fieldset>

        <noscript>
            <button type="submit">Filtra</button>
        </noscript>

    </form>

    <?php if ($contenuti->post_count > 0) : ?>
    <ul>
        <?php foreach ($contenuti->posts as $contenuto_post) : ?>
        <?php
        setup_postdata($contenuto_post);
        $post_id = (int) $contenuto_post->ID;
        $testo = centro_servizi_get_post_meta_string($post_id, 'testo');
        $allegato = centro_servizi_archive_area_famiglie_file_data($post_id);
        $termine_display = centro_servizi_archive_area_famiglie_display_term(centro_servizi_archive_area_famiglie_assigned_terms($post_id));
        $contenuto = trim((string) get_post_field('post_content', $post_id));
        ?>
        <li>
            <article>
                <h2><?php echo esc_html(get_the_title($post_id)); ?></h2>

                <?php if ($termine_display instanceof WP_Term) : ?>
                <p><strong><?php echo esc_html($termine_display->name); ?></strong></p>
                <?php endif; ?>

                <?php if ($testo !== '') : ?>
                <p><?php echo esc_html($testo); ?></p>
                <?php endif; ?>

                <?php if ($allegato !== []) : ?>
                <p>
                    <a href="<?php echo esc_url((string) $allegato['url']); ?>" target="_blank" rel="noopener noreferrer">
                        <?php echo esc_html((string) $allegato['label']); ?> <span class="sr-only">(apre in nuova finestra)</span>
                    </a>
                </p>
                <?php endif; ?>

                <?php if ($contenuto !== '') : ?>
                <div><?php echo apply_filters('the_content', $contenuto); ?></div>
                <?php endif; ?>

                <div style="margin-top: 1.5em; padding-top: 1em; border-top: 1px solid #ccc;">
                    <p style="margin: 0.25em 0;">Pubblicato il <?php echo esc_html(get_the_date('j F Y', $post_id)); ?></p>
                    <p style="margin: 0.25em 0;">Ultima modifica <?php echo esc_html(get_the_modified_date('j F Y', $post_id)); ?></p>
                </div>
            </article>
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

    <?php wp_reset_postdata(); ?>

</main>

<?php get_template_part('partials/footer'); ?>
