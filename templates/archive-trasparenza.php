<?php
declare(strict_types=1);

get_template_part('partials/header');

// ── Filtri da GET ─────────────────────────────────────────────────────────────
$selected_anno = isset($_GET['anno']) ? sanitize_text_field(wp_unslash($_GET['anno'])) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
$selected_cat  = isset($_GET['cat'])  ? sanitize_text_field(wp_unslash($_GET['cat']))  : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

// ── Termini anno scolastico ───────────────────────────────────────────────────
$anni = get_terms([
    'taxonomy'   => 'annoscolastico',
    'hide_empty' => true,
    'orderby'    => 'slug',
    'order'      => 'DESC',
]);

// ── Termini categoria (gerarchici) ────────────────────────────────────────────
$cat_parents = get_terms([
    'taxonomy'   => 'contenutiammtrasp',
    'parent'     => 0,
    'hide_empty' => false,
    'orderby'    => 'slug',
    'order'      => 'ASC',
]);

// ── Query documenti ───────────────────────────────────────────────────────────
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
    'orderby'        => 'title',
    'order'          => 'ASC',
    'no_found_rows'  => true,
];

if (! empty($tax_query)) {
    $query_args['tax_query'] = $tax_query;
}

$documenti = new WP_Query($query_args);

$archive_url = get_post_type_archive_link('trasparenza');

?>
<main id="main">

    <?php get_template_part('partials/breadcrumb'); ?>

    <h1><?php post_type_archive_title(); ?></h1>

    <?php // ── Form filtri ────────────────────────────────────────────────── ?>
    <form method="get" action="<?php echo esc_url($archive_url); ?>" id="filtri-ammtrasp">

        <?php // ── Filtro anno scolastico ─────────────────────────────────── ?>
        <?php if (! is_wp_error($anni) && ! empty($anni)) : ?>
        <fieldset>
            <legend>Anno scolastico</legend>

            <label>
                <input type="radio" name="anno" value=""
                    <?php checked($selected_anno, ''); ?>
                    onchange="this.form.submit()">
                Tutti
            </label>

            <?php foreach ($anni as $anno) : ?>
            <label>
                <input type="radio" name="anno" value="<?php echo esc_attr($anno->slug); ?>"
                    <?php checked($selected_anno, $anno->slug); ?>
                    onchange="this.form.submit()">
                <?php echo esc_html($anno->name); ?>
            </label>
            <?php endforeach; ?>

        </fieldset>
        <?php endif; ?>

        <?php // ── Filtro categoria ───────────────────────────────────────── ?>
        <?php if (! is_wp_error($cat_parents) && ! empty($cat_parents)) : ?>
        <fieldset>
            <legend>Categoria</legend>

            <label>
                <input type="radio" name="cat" value=""
                    <?php checked($selected_cat, ''); ?>
                    onchange="this.form.submit()">
                Tutte
            </label>

            <?php foreach ($cat_parents as $parent) :
                $cat_children = get_terms([
                    'taxonomy'   => 'contenutiammtrasp',
                    'parent'     => $parent->term_id,
                    'hide_empty' => false,
                    'orderby'    => 'slug',
                    'order'      => 'ASC',
                ]);
            ?>

            <label>
                <input type="radio" name="cat" value="<?php echo esc_attr($parent->slug); ?>"
                    <?php checked($selected_cat, $parent->slug); ?>
                    onchange="this.form.submit()">
                <?php echo esc_html($parent->name); ?>
            </label>

            <?php if (! is_wp_error($cat_children) && ! empty($cat_children)) : ?>
            <?php foreach ($cat_children as $child) : ?>
            <label style="padding-left: 1.5em; display: block;">
                <input type="radio" name="cat" value="<?php echo esc_attr($child->slug); ?>"
                    <?php checked($selected_cat, $child->slug); ?>
                    onchange="this.form.submit()">
                <?php echo esc_html($child->name); ?>
            </label>
            <?php endforeach; ?>
            <?php endif; ?>

            <?php endforeach; ?>

        </fieldset>
        <?php endif; ?>

        <noscript>
            <button type="submit">Filtra</button>
        </noscript>

    </form>

    <?php // ── Cards documenti ────────────────────────────────────────────── ?>
    <?php if ($documenti->have_posts()) : ?>
    <ul>

        <?php while ($documenti->have_posts()) : $documenti->the_post(); ?>

        <?php
        $acf_titolo   = function_exists('get_field') ? get_field('titolo')    : '';
        $acf_tag_anno = function_exists('get_field') ? get_field('tag_anno')  : '';
        $acf_doc_url  = function_exists('get_field') ? get_field('documento') : '';
        $term_anni    = get_the_terms(get_the_ID(), 'annoscolastico');
        $term_cat     = get_the_terms(get_the_ID(), 'contenutiammtrasp');
        ?>

        <li>
            <article>

                <h2><?php the_title(); ?></h2>

                <?php if (! empty($acf_titolo)) : ?>
                <p><?php echo esc_html($acf_titolo); ?></p>
                <?php endif; ?>

                <?php if (! empty($acf_tag_anno)) : ?>
                <p><?php echo esc_html($acf_tag_anno); ?></p>
                <?php endif; ?>

                <?php if (! is_wp_error($term_anni) && ! empty($term_anni)) : ?>
                <p>
                    <?php
                    $nomi_anni = array_map(fn($t) => esc_html($t->name), $term_anni);
                    echo implode(', ', $nomi_anni);
                    ?>
                </p>
                <?php endif; ?>

                <?php if (! is_wp_error($term_cat) && ! empty($term_cat)) : ?>
                <p>
                    <?php
                    $nomi_cat = array_map(fn($t) => esc_html($t->name), $term_cat);
                    echo implode(', ', $nomi_cat);
                    ?>
                </p>
                <?php endif; ?>

                <?php if (! empty($acf_doc_url)) : ?>
                <p>
                    <a href="<?php echo esc_url($acf_doc_url); ?>" target="_blank" rel="noopener noreferrer">
                        Visualizza documento
                    </a>
                </p>
                <?php endif; ?>

            </article>
        </li>

        <?php endwhile; ?>
        <?php wp_reset_postdata(); ?>

    </ul>
    <?php else : ?>
    <p>Nessun documento trovato.</p>
    <?php endif; ?>

</main>

<?php get_template_part('partials/footer'); ?>
