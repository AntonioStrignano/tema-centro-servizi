<?php
declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

get_template_part('partials/header');

$homepage_title = trim((string) get_option('centro_servizi_homepage_title', ''));
$homepage_subtitle = trim((string) get_option('centro_servizi_homepage_subtitle', ''));
$contacts_json = (string) get_option('centro_servizi_contacts', '[]');
$contacts_raw = json_decode($contacts_json, true);
$contacts_raw = is_array($contacts_raw) ? $contacts_raw : [];

$contacts_by_type = [];
foreach ($contacts_raw as $contact) {
    $type = isset($contact['type']) ? (string) $contact['type'] : '';
    $value = isset($contact['value']) ? trim((string) $contact['value']) : '';

    if ($type === '' || $value === '' || isset($contacts_by_type[$type])) {
        continue;
    }

    $contacts_by_type[$type] = $value;
}

$title = $homepage_title !== '' ? $homepage_title : get_bloginfo('name');
$subtitle = $homepage_subtitle;

$attivita_archive_url = get_post_type_archive_link('attivita') ?: home_url('/attivita/');
$trasparenza_archive_url = get_post_type_archive_link('trasparenza') ?: home_url('/amministrazione-trasparente/');
$area_famiglie_archive_url = get_post_type_archive_link('area-famiglie') ?: home_url('/area-famiglie/');
$area_personale_archive_url = get_post_type_archive_link('area-personale') ?: home_url('/area-personale/');
$contatti_page_url = home_url('/contatti/');

$attivita_in_evidenza = new WP_Query([
    'post_type'      => 'attivita',
    'posts_per_page' => 3,
    'orderby'        => 'date',
    'order'          => 'DESC',
    'no_found_rows'  => true,
]);

$documenti_recenti = new WP_Query([
    'post_type'      => 'trasparenza',
    'posts_per_page' => 5,
    'orderby'        => 'date',
    'order'          => 'DESC',
    'no_found_rows'  => true,
]);
?>
<main class="site-main home-main" id="contenuto-principale" role="main">
    <section class="site-section home-hero" aria-labelledby="home-hero-title">
        <p class="home-hero__kicker">Scuola paritaria d'infanzia</p>
        <h1 id="home-hero-title" class="home-hero__title"><?php echo esc_html($title); ?></h1>

        <?php if ($subtitle !== '') : ?>
            <p class="home-hero__subtitle"><?php echo esc_html($subtitle); ?></p>
        <?php endif; ?>

        <div class="home-hero__actions">
            <a class="home-button" href="<?php echo esc_url($attivita_archive_url); ?>">Scopri le attivita</a>
            <a class="home-button home-button--ghost" href="<?php echo esc_url($contatti_page_url); ?>">Contattaci</a>
        </div>
    </section>

    <?php while (have_posts()) : the_post(); ?>
        <?php $content = trim((string) get_the_content()); ?>
        <?php if ($content !== '') : ?>
            <section class="site-section home-intro home-divider" aria-labelledby="home-intro-title">
                <h2 id="home-intro-title">Chi siamo</h2>
                <div class="entry-content">
                    <?php the_content(); ?>
                </div>
            </section>
        <?php endif; ?>
    <?php endwhile; ?>

    <section class="site-section home-services home-divider home-divider--double" aria-labelledby="home-services-title">
        <h2 id="home-services-title">Aree e servizi</h2>
        <div class="home-services__grid">
            <article class="home-service-card">
                <h3>Area famiglie</h3>
                <p>Comunicazioni, circolari e documenti dedicati alle famiglie.</p>
                <p><a href="<?php echo esc_url($area_famiglie_archive_url); ?>">Vai all'area famiglie</a></p>
            </article>
            <article class="home-service-card">
                <h3>Area personale</h3>
                <p>Materiali e documentazione riservata al personale scolastico.</p>
                <p><a href="<?php echo esc_url($area_personale_archive_url); ?>">Vai all'area personale</a></p>
            </article>
            <article class="home-service-card">
                <h3>Amministrazione trasparente</h3>
                <p>Documenti obbligatori, aggiornamenti e pubblicazioni normative.</p>
                <p><a href="<?php echo esc_url($trasparenza_archive_url); ?>">Apri la sezione trasparenza</a></p>
            </article>
        </div>
    </section>

    <section class="site-section home-attivita home-divider" aria-labelledby="home-attivita-title">
        <div class="home-section-head">
            <h2 id="home-attivita-title">Attivita recenti</h2>
            <a href="<?php echo esc_url($attivita_archive_url); ?>">Vedi tutte</a>
        </div>

        <?php if ($attivita_in_evidenza->have_posts()) : ?>
            <div class="home-attivita__grid">
                <?php foreach ($attivita_in_evidenza->posts as $attivita_post) : ?>
                    <?php get_template_part('partials/card-attivita', null, ['post_id' => (int) $attivita_post->ID]); ?>
                <?php endforeach; ?>
            </div>
        <?php else : ?>
            <p>Nessuna attivita pubblicata al momento.</p>
        <?php endif; ?>
    </section>

    <section class="site-section home-trasparenza home-divider home-divider--double" aria-labelledby="home-trasparenza-title">
        <div class="home-section-head">
            <h2 id="home-trasparenza-title">Documenti in evidenza</h2>
            <a href="<?php echo esc_url($trasparenza_archive_url); ?>">Vedi tutti</a>
        </div>

        <?php if ($documenti_recenti->have_posts()) : ?>
            <ul class="home-trasparenza__list">
                <?php foreach ($documenti_recenti->posts as $documento_post) : ?>
                    <?php
                    $documento_id = (int) $documento_post->ID;
                    $documento_titolo = get_the_title($documento_id);
                    $documento_data = get_the_date('d/m/Y', $documento_id);
                    ?>
                    <li>
                        <a href="<?php echo esc_url($trasparenza_archive_url); ?>">
                            <?php echo esc_html($documento_titolo); ?>
                        </a>
                        <span><?php echo esc_html($documento_data); ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else : ?>
            <p>Nessun documento disponibile in questo momento.</p>
        <?php endif; ?>
    </section>

    <section class="site-section home-contacts home-divider" aria-labelledby="home-contacts-title">
        <h2 id="home-contacts-title">Contatti rapidi</h2>
        <div class="home-contacts__grid">
            <?php if (isset($contacts_by_type['phone'])) : ?>
                <article>
                    <h3>Telefono</h3>
                    <p><a href="tel:<?php echo esc_attr((string) preg_replace('/[^+\d]/', '', $contacts_by_type['phone'])); ?>"><?php echo esc_html($contacts_by_type['phone']); ?></a></p>
                </article>
            <?php endif; ?>

            <?php if (isset($contacts_by_type['email'])) : ?>
                <article>
                    <h3>Email</h3>
                    <p><a href="mailto:<?php echo esc_attr(antispambot($contacts_by_type['email'])); ?>"><?php echo esc_html(antispambot($contacts_by_type['email'])); ?></a></p>
                </article>
            <?php endif; ?>

            <?php if (isset($contacts_by_type['address'])) : ?>
                <article>
                    <h3>Sede</h3>
                    <p><?php echo nl2br(esc_html($contacts_by_type['address'])); ?></p>
                </article>
            <?php endif; ?>
        </div>
        <p><a href="<?php echo esc_url($contatti_page_url); ?>">Apri la pagina contatti completa</a></p>
    </section>

    <?php wp_reset_postdata(); ?>
</main>
<?php
get_template_part('partials/footer');
