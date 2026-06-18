<?php
declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}
?>
<!DOCTYPE html>
<html class="scroll-smooth" lang="it"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Scuola dell'Infanzia | Scuola Aperta</title>
<?php wp_head(); ?>
<?php foreach (centro_servizi_get_theme_stylesheets() as $stylesheet) : ?>
<link rel="stylesheet" id="<?php echo esc_attr(sanitize_title($stylesheet['label'])); ?>-css" href="<?php echo esc_url($stylesheet['href']); ?>" media="all"/>
<?php endforeach; ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<?php
$homepage_title = centro_servizi_get_homepage_title();
$homepage_subtitle = centro_servizi_get_homepage_subtitle();
$homepage_contacts = centro_servizi_get_homepage_contacts();
$homepage_map_embed_url = centro_servizi_get_homepage_map_embed_url();
$homepage_trasparenza_archive_url = (string) get_post_type_archive_link('trasparenza');
if ($homepage_trasparenza_archive_url === '') {
  $homepage_trasparenza_archive_url = (string) home_url('/trasparenza/');
}

$homepage_orari_archive_url = (string) add_query_arg('cat', 'orari-funz', $homepage_trasparenza_archive_url);
$homepage_calendar_archive_url = (string) add_query_arg('cat', 'calendario', $homepage_trasparenza_archive_url);
$homepage_area_famiglie_archive_url = (string) get_post_type_archive_link('area-famiglie');
if ($homepage_area_famiglie_archive_url === '') {
  $homepage_area_famiglie_archive_url = (string) home_url('/area-famiglie/');
}

$homepage_moduli_iscrizione_url = (string) add_query_arg('cat', 'moduli-iscrizione', $homepage_area_famiglie_archive_url);
$homepage_attivita_archive_url = (string) get_post_type_archive_link('attivita');
if ($homepage_attivita_archive_url === '') {
  $homepage_attivita_archive_url = (string) home_url('/attivita/');
}

$contacts_page = get_page_by_path('contatti');
$contact_page_url = $contacts_page instanceof WP_Post
  ? (string) get_permalink($contacts_page)
  : (string) home_url('/contatti/');
?>
<?php get_template_part('partials/skip-links'); ?>

<?php get_template_part('partials/chrome-header'); ?>
<main id="contenuto-principale" role="main">

<!-- Hero -->
<section class="hp-hero" aria-label="Benvenuti">
  <div class="hp-hero__bg" aria-hidden="true">
    <img class="hp-hero__bg-img" alt="" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCOEOdhKhmf4ocmIu2t4NkiFaoSjGU1DVVh_8a2u3UO_ocAQ-nCpHO412KgFFr0A1XLAP13MmZdldtGE30HBR5350iBoo3JtlsAUTmgVDffVsOln047IwDd_G3DIHv0PpuviOZqDXNqxtHZcu6bZIUjheQUlyq1zMs1_2LLK8Ob-YZP5R0VlirMvHEn0kz-lHoV21FaL6u-ogrIrt-H2VQFuo2MhvL2lL7WxNMNuieejtgv2U-NFTtEWjrsJHfzWZvkNzcI2Dt00K82" />
    <div class="hp-hero__overlay"></div>
  </div>
  <div class="hp-container hp-hero__content">
    <div class="hp-hero__text">
      <h1 class="hp-hero__title"><?php echo esc_html($homepage_title); ?></h1>
      <p class="hp-hero__subtitle"><?php echo esc_html($homepage_subtitle); ?></p>
    </div>
    <div class="hp-hero__logo" aria-hidden="true">
      <svg class="hp-hero__logo-svg" viewBox="0 0 160 160" role="img" aria-label="Logo della scuola">
        <rect x="8" y="8" width="144" height="144" rx="36" fill="#ffffff"/>
        <circle cx="80" cy="60" r="28" fill="#e8f2ef"/>
        <path d="M48 102L80 74L112 102V122H90V106H70V122H48Z" fill="#003342"/>
        <path d="M46 42H114" stroke="#436555" stroke-width="8" stroke-linecap="round"/>
        <path d="M56 30H104" stroke="#581a01" stroke-width="6" stroke-linecap="round"/>
      </svg>
    </div>
  </div>
</section>
<!-- Highlights (valore aggiunto) -->
<section class="hp-highlights hp-section">
  <div class="hp-container">
    <div class="hp-highlights__inner">
      <div class="hp-highlights__image">
        <div class="hp-highlights__image-crop">
          <img alt="Insegnante con bambini in laboratorio all'aperto" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDCqdfqf86yBMLs89-SVbZojTGqtnPQBdOo5WmOYkCa9-Eh_5PHHCnswX62aYgIVX2mggL2yD0PYq6iFaJ06FmmXI_YotXP3h42k4NonnfE5pu4JQusKZ68f4Mva5GYjKMPJ8UnYrs-JZc1mQBSVljiRBtyS0cW74Ld46fiILH1U33mggccSPNrAJImJM_dtaF_BZ2jQwFiWeDNds22aesHu_wkCDbJwOsUU75suCBP4urpo9ZqS5G4fyk5NmmKq5fIXtU4EblIaKZd" />
        </div>
        <div class="hp-highlights__deco-a" aria-hidden="true"></div>
        <div class="hp-highlights__deco-b" aria-hidden="true"></div>
      </div>
      <div class="hp-highlights__text">
        <span class="hp-kicker">Il nostro valore aggiunto</span>
        <h2 class="hp-highlights__title">Esperienze uniche che ci distinguono.</h2>
        <p class="hp-highlights__quote">"Non semplici lezioni, ma percorsi di scoperta pensati per nutrire il talento innato di ogni bambino."</p>
        <p class="hp-highlights__body">Nella nostra scuola, la didattica supera i confini tradizionali. Integriamo l'educazione all'aperto, i linguaggi artistici e la sperimentazione tecnologica in un curriculum vivo, capace di adattarsi alle domande e alle curiosità di ogni piccolo esploratore.</p>
        <a href="<?php echo esc_url($contact_page_url); ?>" class="btn btn--tertiary btn--lg">
          Contattaci
          <span class="material-symbols-outlined" aria-hidden="true">arrow_outward</span>
        </a>
      </div>
    </div>
  </div>
</section>

<!-- Orari e Calendario -->
<section class="hp-docs hp-section">
  <div class="hp-container">
    <div class="hp-docs__grid">
      <div class="hp-docs__card hp-docs__card--primary">
        <div class="hp-docs__card-header">
          <span class="material-symbols-outlined hp-docs__icon" aria-hidden="true">schedule</span>
          <h2 class="hp-docs__title">Orari di funzionamento</h2>
        </div>
        <p class="hp-docs__desc">Apri l'archivio Amministrazione Trasparente filtrato sugli orari di funzionamento.</p>
        <a class="hp-docs__link" href="<?php echo esc_url($homepage_orari_archive_url); ?>">
          <span>Vai alla sezione Orari di funzionamento</span>
          <span class="material-symbols-outlined" aria-hidden="true">open_in_new</span>
        </a>
        <p class="hp-docs__summary">Trovi tutti i documenti pubblicati nella categoria dedicata.</p>
      </div>
      <div class="hp-docs__card hp-docs__card--secondary">
        <div class="hp-docs__card-header">
          <span class="material-symbols-outlined hp-docs__icon" aria-hidden="true">calendar_month</span>
          <h2 class="hp-docs__title">Calendario scolastico</h2>
        </div>
        <p class="hp-docs__desc">Apri l'archivio Amministrazione Trasparente filtrato sul calendario scolastico.</p>
        <a class="hp-docs__link" href="<?php echo esc_url($homepage_calendar_archive_url); ?>">
          <span>Vai alla sezione Calendario scolastico</span>
          <span class="material-symbols-outlined" aria-hidden="true">event_note</span>
        </a>
        <p class="hp-docs__summary">Trovi tutti i documenti pubblicati nella categoria dedicata.</p>
      </div>
    </div>
  </div>
</section>

<!-- Servizi principali -->
<section class="hp-servizi hp-section">
  <div class="hp-container">
    <header class="hp-servizi__header">
      <span class="hp-kicker">SERVIZI PER LE FAMIGLIE</span>
      <h2 class="hp-servizi__title">Tutto a portata di clic.</h2>
    </header>
    <div class="hp-servizi__grid">
      <a class="hp-servizi__card" href="<?php echo esc_url($homepage_moduli_iscrizione_url); ?>">
        <span class="material-symbols-outlined hp-servizi__icon" aria-hidden="true">assignment</span>
        <h4 class="hp-servizi__label">Moduli iscrizione</h4>
      </a>
      <a class="hp-servizi__card" href="<?php echo esc_url($homepage_area_famiglie_archive_url); ?>">
        <span class="material-symbols-outlined hp-servizi__icon" aria-hidden="true">groups</span>
        <h4 class="hp-servizi__label">Area famiglie</h4>
      </a>
      <a class="hp-servizi__card" href="<?php echo esc_url($homepage_attivita_archive_url); ?>">
        <span class="material-symbols-outlined hp-servizi__icon" aria-hidden="true">celebration</span>
        <h4 class="hp-servizi__label">Attivita</h4>
      </a>
    </div>
  </div>
</section>

<!-- Contatti rapidi -->
<section class="hp-contatti hp-section">
  <div class="hp-container">
    <div class="hp-contatti__panel">
      <div class="hp-contatti__info">
        <span class="hp-kicker hp-kicker--tertiary">CONTATTI</span>
        <h2 class="hp-contatti__title">Siamo qui per te.</h2>
        <?php if ($homepage_contacts !== []) : ?>
        <ul class="stitch-contact-list" role="list">
          <?php foreach ($homepage_contacts as $contact) : ?>
          <li class="stitch-contact-list__item">
            <span class="material-symbols-outlined" aria-hidden="true"><?php echo esc_html((string) $contact['icon']); ?></span>
            <div>
              <h4><?php echo esc_html((string) $contact['label']); ?></h4>
              <?php if ((string) $contact['href'] !== '') : ?>
              <?php $is_external = ! empty($contact['external']); ?>
              <p>
                <a href="<?php echo esc_url((string) $contact['href']); ?>"<?php echo $is_external ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>>
                  <?php echo esc_html((string) $contact['value']); ?>
                  <?php if ($is_external) : ?><span class="sr-only">(apre in nuova finestra)</span><?php endif; ?>
                </a>
              </p>
              <?php else : ?>
              <p><?php echo esc_html((string) $contact['value']); ?></p>
              <?php endif; ?>
            </div>
          </li>
          <?php endforeach; ?>
        </ul>
        <?php else : ?>
        <p class="hp-contatti__empty">I contatti saranno pubblicati a breve.</p>
        <?php endif; ?>
        <div class="hp-contatti__ctas">
          <a href="<?php echo esc_url($contact_page_url); ?>" class="btn btn--primary">Contattaci</a>
        </div>
      </div>
      <div class="hp-contatti__map">
        <?php if ($homepage_map_embed_url !== '') : ?>
        <iframe
          src="<?php echo esc_url($homepage_map_embed_url); ?>"
          title="Mappa sede"
          loading="lazy"
          referrerpolicy="no-referrer-when-downgrade"
          allowfullscreen></iframe>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<!-- Partner -->
<section class="hp-partner hp-section">
  <div class="hp-partner__bg" aria-hidden="true">
    <img alt="" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCzRNDreMgYxqjNMVbORRy8sxSCTuYQVxw-n3EWp2khMpKqOGpbjlaFN7S6AxrhQYgB8FpYvRqntf_nFLqiJKs4XDdUJTwUyxsztqwxjdI-5Q0lfWveoH-9MFnxGK7cVtAsd8-STUIrKYhFFLlY8H8oomTTOObkf0grD-VgPR2WPXqm7vO-dZ-8uuoifaFderLgFTcCL1hBmibmxuqoaYDMgC1WHMYbMES1VUdFxEcwrS4-TpbSqCc0a7stG_7p-JiO-p0GSsWLw31n" />
    <div class="hp-partner__overlay"></div>
  </div>
  <div class="hp-container">
    <div class="hp-partner__content">
      <h2 class="hp-partner__title">I Nostri Partner: Eccellenza nella Formazione</h2>
      <p class="hp-partner__body">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. La nostra azienda partner supporta la scuola con servizi specializzati di alta qualità.</p>
      <div class="hp-partner__tags">
        <span class="hp-partner__tag hp-partner__tag--b">Formazione</span>
        <span class="hp-partner__tag hp-partner__tag--a">Consulenza</span>
        <span class="hp-partner__tag hp-partner__tag--c">Progettazione</span>
        <span class="hp-partner__tag hp-partner__tag--n">Comunicazione</span>
      </div>
      <div class="hp-partner__ctas">
        <a href="<?php echo esc_url($contact_page_url); ?>" class="btn btn--primary btn--lg">Contattaci</a>
      </div>
    </div>
  </div>
</section>
<?php get_template_part('partials/footer'); ?>