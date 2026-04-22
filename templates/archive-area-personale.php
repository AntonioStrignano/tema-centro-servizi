<?php
declare(strict_types=1);

function centro_servizi_archive_area_personale_selected_slug(string $key): string
{
    if (! isset($_GET[$key])) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        return '';
    }

    return sanitize_text_field(wp_unslash((string) $_GET[$key])); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
}

get_template_part('partials/header');

$selected_cat = centro_servizi_archive_area_personale_selected_slug('cat');

$categorie = get_terms([
    'taxonomy'   => 'categoria-area-personale',
    'hide_empty' => false,
    'orderby'    => 'slug',
    'order'      => 'ASC',
]);

$categorie = is_wp_error($categorie) ? [] : $categorie;

$query_args = [
    'post_type'      => 'area-personale',
    'posts_per_page' => -1,
    'orderby'        => 'date',
    'order'          => 'DESC',
    'no_found_rows'  => true,
];

if ($selected_cat !== '') {
    $query_args['tax_query'] = [[
        'taxonomy' => 'categoria-area-personale',
        'field'    => 'slug',
        'terms'    => $selected_cat,
    ]];
}

$contenuti = new WP_Query($query_args);

$archive_url = get_post_type_archive_link('area-personale');
$has_active_filters = ($selected_cat !== '');

?>
<main class="site-main" id="contenuto-principale" role="main">

    <h1><?php post_type_archive_title(); ?></h1>

    <form method="get" action="<?php echo esc_url($archive_url); ?>" id="filtri-area-personale">
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
        ?>
        <li>
            <?php get_template_part('partials/card-area-personale', null, ['post_id' => $post_id]); ?>
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
