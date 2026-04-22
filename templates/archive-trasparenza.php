<?php
declare(strict_types=1);

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
    $clean = preg_replace('/^\s*\d+\s*[\.)\-_:]?\s*/u', '', $name);

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

get_template_part('partials/header');

$selected_anno = centro_servizi_archive_trasparenza_selected_slug('anno');
$selected_cat = centro_servizi_archive_trasparenza_selected_slug('cat');

$anni = get_terms([
    'taxonomy'   => 'annoscolastico',
    'hide_empty' => false,
    'orderby'    => 'slug',
    'order'      => 'DESC',
]);

$anni = is_wp_error($anni) ? [] : $anni;

$cat_parents = get_terms([
    'taxonomy'   => 'contenutiammtrasp',
    'parent'     => 0,
    'hide_empty' => false,
    'orderby'    => 'slug',
    'order'      => 'ASC',
]);

$cat_parents = is_wp_error($cat_parents) ? [] : $cat_parents;

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

$documenti = new WP_Query($query_args);

$archive_url = get_post_type_archive_link('trasparenza');
$has_active_filters = ($selected_anno !== '' || $selected_cat !== '');

?>
<main class="site-main" id="contenuto-principale" role="main">

    <h1><?php post_type_archive_title(); ?></h1>

    <p>
        <a href="<?php echo esc_url(home_url('/wp-sitemap.xml')); ?>" target="_blank" rel="noopener noreferrer">
            Sitemap del sito <span class="sr-only">(apre in nuova finestra)</span>
        </a>
    </p>

    <form method="get" action="<?php echo esc_url($archive_url); ?>" id="filtri-ammtrasp">
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

            <?php if ($anni === []) : ?>
            <p>Nessun anno scolastico disponibile.</p>
            <?php endif; ?>

        </fieldset>

        <fieldset>
            <legend>Categoria</legend>

            <label>
                <input type="radio" name="cat" value=""
                    <?php checked($selected_cat, ''); ?>
                    onchange="this.form.submit()">
                Tutte
            </label>

            <?php foreach ($cat_parents as $parent) : ?>
            <?php $cat_children = centro_servizi_archive_trasparenza_child_terms((int) $parent->term_id); ?>

            <label style="display: block;">
                <input type="radio" name="cat" value="<?php echo esc_attr($parent->slug); ?>"
                    <?php checked($selected_cat, $parent->slug); ?>
                    onchange="this.form.submit()">
                <?php echo esc_html(centro_servizi_archive_trasparenza_term_display_name($parent)); ?>
            </label>

            <?php foreach ($cat_children as $child) : ?>
            <label style="padding-left: 1.5em; display: block;">
                <input type="radio" name="cat" value="<?php echo esc_attr($child->slug); ?>"
                    <?php checked($selected_cat, $child->slug); ?>
                    onchange="this.form.submit()">
                <?php echo esc_html(centro_servizi_archive_trasparenza_term_display_name($child)); ?>
            </label>
            <?php endforeach; ?>
            <?php endforeach; ?>

            <?php if ($cat_parents === []) : ?>
            <p>Nessuna categoria disponibile.</p>
            <?php endif; ?>

        </fieldset>

        <noscript>
            <button type="submit">Filtra</button>
        </noscript>

    </form>

    <?php if ($documenti->post_count > 0) : ?>
    <ul>
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
        <li>
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

    <?php wp_reset_postdata(); ?>

</main>

<?php get_template_part('partials/footer'); ?>
