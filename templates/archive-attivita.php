<?php
declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

function centro_servizi_archive_attivita_selected_slug(string $key): string
{
    if (! isset($_GET[$key])) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        return '';
    }

    return sanitize_text_field(wp_unslash((string) $_GET[$key])); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
}

get_template_part('partials/header');

$selected_year = centro_servizi_archive_attivita_selected_slug('anno');
$selected_section = centro_servizi_archive_attivita_selected_slug('sezione');

$school_years = get_terms([
    'taxonomy'   => 'anno-scol-attivita',
    'hide_empty' => false,
    'orderby'    => 'slug',
    'order'      => 'DESC',
]);

$school_years = is_wp_error($school_years) ? [] : $school_years;

$sections = get_terms([
    'taxonomy'   => 'sezioni',
    'hide_empty' => false,
    'orderby'    => 'slug',
    'order'      => 'ASC',
]);

$sections = is_wp_error($sections) ? [] : $sections;

$tax_query = [];

if ($selected_year !== '') {
    $tax_query[] = [
        'taxonomy' => 'anno-scol-attivita',
        'field'    => 'slug',
        'terms'    => $selected_year,
    ];
}

if ($selected_section !== '') {
    $tax_query[] = [
        'taxonomy' => 'sezioni',
        'field'    => 'slug',
        'terms'    => $selected_section,
    ];
}

if (count($tax_query) > 1) {
    $tax_query['relation'] = 'AND';
}

$query_args = [
    'post_type'      => 'attivita',
    'posts_per_page' => -1,
    'orderby'        => 'date',
    'order'          => 'DESC',
    'no_found_rows'  => true,
];

if ($tax_query !== []) {
    $query_args['tax_query'] = $tax_query;
}

$attivita = new WP_Query($query_args);
$archive_url = get_post_type_archive_link('attivita');
$has_active_filters = ($selected_year !== '' || $selected_section !== '');
?>
<main class="site-main" id="contenuto-principale" role="main">
    <section class="site-section attivita-archive">
        <h1><?php post_type_archive_title(); ?></h1>

        <form method="get" action="<?php echo esc_url($archive_url); ?>" class="attivita-archive__filters" id="filtri-attivita">
            <fieldset>
                <legend>Anno scolastico</legend>

                <label>
                    <input type="radio" name="anno" value=""
                        <?php checked($selected_year, ''); ?>
                        onchange="this.form.submit()">
                    Tutti
                </label>

                <?php foreach ($school_years as $school_year) : ?>
                    <label>
                        <input type="radio" name="anno" value="<?php echo esc_attr($school_year->slug); ?>"
                            <?php checked($selected_year, $school_year->slug); ?>
                            onchange="this.form.submit()">
                        <?php echo esc_html($school_year->name); ?>
                    </label>
                <?php endforeach; ?>
            </fieldset>

            <fieldset>
                <legend>Sezione</legend>

                <label>
                    <input type="radio" name="sezione" value=""
                        <?php checked($selected_section, ''); ?>
                        onchange="this.form.submit()">
                    Tutte
                </label>

                <?php foreach ($sections as $section) : ?>
                    <label>
                        <input type="radio" name="sezione" value="<?php echo esc_attr($section->slug); ?>"
                            <?php checked($selected_section, $section->slug); ?>
                            onchange="this.form.submit()">
                        <?php echo esc_html($section->name); ?>
                    </label>
                <?php endforeach; ?>
            </fieldset>

            <noscript>
                <button type="submit">Filtra</button>
            </noscript>
        </form>

        <?php if ($attivita->post_count > 0) : ?>
            <div class="attivita-archive__grid">
                <?php foreach ($attivita->posts as $attivita_post) : ?>
                    <?php get_template_part('partials/card-attivita', null, ['post_id' => (int) $attivita_post->ID]); ?>
                <?php endforeach; ?>
            </div>
        <?php else : ?>
            <p>Nessuna attivita disponibile con i filtri correnti.</p>
            <?php if ($has_active_filters) : ?>
                <p><a href="<?php echo esc_url($archive_url); ?>">Reset filtri</a></p>
            <?php endif; ?>
        <?php endif; ?>

        <?php wp_reset_postdata(); ?>
    </section>
</main>
<?php
get_template_part('partials/footer');