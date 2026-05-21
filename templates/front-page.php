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
</head>
<body <?php body_class('bg-background-warm text-on-surface selection:bg-primary-fixed selection:text-on-primary-fixed'); ?>>
<?php wp_body_open(); ?>
<?php
$homepage_title = centro_servizi_get_homepage_title();
$homepage_subtitle = centro_servizi_get_homepage_subtitle();
$homepage_contacts = centro_servizi_get_homepage_contacts();
$homepage_map_embed_url = centro_servizi_get_homepage_map_embed_url();
$homepage_orari_document = centro_servizi_get_homepage_latest_trasparenza_document('orari-funz', 'Orari di funzionamento');
$homepage_calendar_document = centro_servizi_get_homepage_latest_trasparenza_document('calendario', 'Calendario scolastico');

$accessibility_page = get_page_by_path('dichiarazione-accessibilita');
$obiettivi_page = get_page_by_path('obiettivi-accessibilita');
$whistleblowing_url = trim((string) get_option('centro_servizi_url_whistleblowing', ''));
$footer_text = trim((string) get_option('centro_servizi_footer_text', ''));
$accessibility_feedback_url = trim((string) get_option('centro_servizi_accessibility_feedback_url', ''));

$legal_company_name = trim((string) get_option('centro_servizi_legal_company_name', ''));
$legal_address = trim((string) get_option('centro_servizi_legal_address', ''));
$legal_vat = trim((string) get_option('centro_servizi_legal_vat', ''));
$legal_fiscal_code = trim((string) get_option('centro_servizi_legal_fiscal_code', ''));
$legal_mecc = trim((string) get_option('centro_servizi_legal_mecc', ''));
$legal_rea = trim((string) get_option('centro_servizi_legal_rea', ''));

$contacts_raw = centro_servizi_get_contacts();
$contacts_map = [];
foreach ($contacts_raw as $contact) {
    $type = isset($contact['type']) ? (string) $contact['type'] : '';
    $value = isset($contact['value']) ? trim((string) $contact['value']) : '';
    if ($type === '' || $value === '' || isset($contacts_map[$type])) {
        continue;
    }
    $contacts_map[$type] = $value;
}

$company_display = $legal_company_name !== '' ? $legal_company_name : get_bloginfo('name');

$legal_chunks = [];
if ($legal_vat !== '') {
    $legal_chunks[] = 'P.IVA ' . $legal_vat;
}
if ($legal_fiscal_code !== '') {
    $legal_chunks[] = 'Cod. Fiscale ' . $legal_fiscal_code;
}
if ($legal_mecc !== '') {
    $legal_chunks[] = 'Cod. Mecc. ' . $legal_mecc;
}
if ($legal_rea !== '') {
    $legal_chunks[] = 'REA ' . $legal_rea;
}

$contact_chunks = [];
if (isset($contacts_map['address'])) {
    $contact_chunks[] = 'Indirizzo: ' . $contacts_map['address'];
}
if (isset($contacts_map['phone'])) {
    $contact_chunks[] = 'Tel: ' . $contacts_map['phone'];
}
if (isset($contacts_map['email'])) {
    $contact_chunks[] = 'Email: ' . antispambot($contacts_map['email']);
}
if (isset($contacts_map['pec'])) {
    $contact_chunks[] = 'PEC: ' . antispambot($contacts_map['pec']);
}

$feedback_url = $accessibility_feedback_url !== ''
    ? $accessibility_feedback_url
    : 'https://example.com/google-form-accessibilita';

$contact_cta_email = '';
$contact_cta_phone = '';
foreach ($homepage_contacts as $contact) {
    if (! is_array($contact)) {
        continue;
    }

    $type = (string) ($contact['type'] ?? '');
    $href = (string) ($contact['href'] ?? '');
    if ($href === '') {
        continue;
    }

    if ($contact_cta_email === '' && ($type === 'email' || $type === 'pec')) {
        $contact_cta_email = $href;
    }

    if ($contact_cta_phone === '' && ($type === 'phone' || $type === 'fax')) {
        $contact_cta_phone = $href;
    }
}
?>
<a class="sr-only focus:not-sr-only" href="#main-content">Salta al contenuto principale</a>
<!-- TopNavBar -->
<header class="sticky top-0 w-full z-50 bg-surface dark:bg-surface-container-lowest border-b border-border-subtle dark:border-outline-variant">
<div class="flex justify-between items-center h-20 px-4 md:px-gutter max-w-container-max mx-auto">
<div class="flex items-center gap-unit">
<span class="font-headline-sm text-headline-sm font-semibold text-primary dark:text-primary-fixed-dim"><?php echo esc_html($homepage_title); ?></span>
</div>
<nav class="hidden lg:flex items-center gap-8" aria-label="Navigazione principale">
<?php
wp_nav_menu([
    'theme_location' => 'primary',
    'container'      => false,
    'menu_class'     => 'stitch-menu',
    'fallback_cb'    => false,
]);
?>
</nav>
<div class="flex items-center gap-4">
<button type="button" class="p-2 text-on-surface-variant hover:text-primary transition-colors" aria-label="Cerca nel sito">
<span class="material-symbols-outlined" data-icon="search">search</span>
</button>
<button type="button" class="bg-tertiary text-on-tertiary px-6 py-2.5 rounded-full font-label-caps text-label-caps font-semibold hover:opacity-90 active:scale-[0.98] transition-all">
                    Contattaci
                </button>
</div>
</div>
</header>
<main id="main-content">
<!-- Hero Section -->
<section class="relative min-h-[870px] flex items-center overflow-hidden">
<div class="absolute inset-0 z-0">
<img class="w-full h-full object-cover" alt="Interno luminoso della scuola dell'infanzia" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCOEOdhKhmf4ocmIu2t4NkiFaoSjGU1DVVh_8a2u3UO_ocAQ-nCpHO412KgFFr0A1XLAP13MmZdldtGE30HBR5350iBoo3JtlsAUTmgVDffVsOln047IwDd_G3DIHv0PpuviOZqDXNqxtHZcu6bZIUjheQUlyq1zMs1_2LLK8Ob-YZP5R0VlirMvHEn0kz-lHoV21FaL6u-ogrIrt-H2VQFuo2MhvL2lL7WxNMNuieejtgv2U-NFTtEWjrsJHfzWZvkNzcI2Dt00K82"/>
<div class="absolute inset-0 bg-gradient-to-r from-background-warm/90 via-background-warm/40 to-transparent"></div>
</div>
<div class="relative z-10 px-4 md:px-gutter max-w-container-max mx-auto w-full">
<div class="max-w-2xl">
<span class="font-label-caps text-label-caps text-tertiary mb-4 block">SCUOLA DELL'INFANZIA</span>
<h1 class="font-display-lg text-display-lg-mobile md:text-display-lg text-primary mb-6 animate-fade-in"><?php echo esc_html($homepage_title); ?></h1>
<p class="font-body-lg text-body-lg text-on-surface-variant mb-0 max-w-lg"><?php echo esc_html($homepage_subtitle); ?></p>
</div>
</div>
</section>
<!-- Highlights Section (Replaced Didattica) -->
<section class="py-section-padding-mobile md:py-section-padding-desktop bg-surface overflow-hidden">
<div class="px-4 md:px-gutter max-w-container-max mx-auto">
<div class="flex flex-col lg:flex-row items-center gap-16 lg:gap-24">
<div class="w-full lg:w-1/2 relative">
<div class="aspect-[4/5] rounded-[3rem] overflow-hidden editorial-shadow relative z-10">
<img class="w-full h-full object-cover" alt="Insegnante con bambini in laboratorio all'aperto" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDCqdfqf86yBMLs89-SVbZojTGqtnPQBdOo5WmOYkCa9-Eh_5PHHCnswX62aYgIVX2mggL2yD0PYq6iFaJ06FmmXI_YotXP3h42k4NonnfE5pu4JQusKZ68f4Mva5GYjKMPJ8UnYrs-JZc1mQBSVljiRBtyS0cW74Ld46fiILH1U33mggccSPNrAJImJM_dtaF_BZ2jQwFiWeDNds22aesHu_wkCDbJwOsUU75suCBP4urpo9ZqS5G4fyk5NmmKq5fIXtU4EblIaKZd"/>
</div>
<!-- Decorative element -->
<div class="absolute -bottom-6 -right-6 w-32 h-32 bg-tertiary-fixed rounded-3xl -z-0 hidden md:block"></div>
<div class="absolute -top-6 -left-6 w-24 h-24 border-2 border-secondary/20 rounded-full -z-0 hidden md:block"></div>
</div>
<div class="w-full lg:w-1/2 space-y-8">
<div class="space-y-4">
<span class="font-label-caps text-label-caps text-secondary tracking-[0.2em] uppercase">Il nostro valore aggiunto</span>
<h2 class="font-display-lg text-headline-md md:text-headline-md lg:text-display-lg text-primary leading-tight">Esperienze uniche che ci distinguono.</h2>
</div>
<div class="space-y-6">
<p class="font-body-lg text-body-lg text-on-surface-variant italic border-l-4 border-tertiary pl-6">
                        "Non semplici lezioni, ma percorsi di scoperta pensati per nutrire il talento innato di ogni bambino."
                    </p>
<p class="font-body-md text-body-md text-on-surface-variant">
                        Nella nostra scuola, la didattica supera i confini tradizionali. Integriamo l'educazione all'aperto, i linguaggi artistici e la sperimentazione tecnologica in un curriculum vivo, capace di adattarsi alle domande e alle curiosità di ogni piccolo esploratore.
                    </p>
</div>
<div class="pt-4">
<button type="button" class="bg-tertiary text-on-tertiary px-10 py-5 rounded-2xl font-headline-sm text-headline-sm hover:shadow-2xl hover:-translate-y-1 transition-all active:scale-95 flex items-center gap-3">
                        Scopri tutte le attività speciali
                        <span class="material-symbols-outlined" data-icon="arrow_outward">arrow_outward</span>
</button>
</div>
</div>
</div>
</div>
</section>
<!-- Orari e Calendario -->
<section class="py-section-padding-mobile md:py-section-padding-desktop">
<div class="px-4 md:px-gutter max-w-container-max mx-auto">
<div class="grid md:grid-cols-2 gap-12">
<div class="bg-primary p-12 rounded-[2rem] text-on-primary">
<div class="flex items-center gap-4 mb-8">
<span class="material-symbols-outlined text-4xl" data-icon="schedule">schedule</span>
<h2 class="font-headline-md text-headline-md text-on-primary">Orari di funzionamento</h2>
</div>
<p class="font-body-md text-body-md mb-8 opacity-90">Apri l'ultimo documento pubblicato sugli orari di funzionamento.</p>
<a class="flex items-center justify-between bg-white/10 hover:bg-white/20 p-4 rounded-xl transition-colors group" href="<?php echo esc_url($homepage_orari_document['url']); ?>">
<span class="font-semibold"><?php echo esc_html($homepage_orari_document['title']); ?></span>
<span class="material-symbols-outlined transition-transform group-hover:translate-x-1" data-icon="open_in_new">open_in_new</span>
</a>
<?php if ($homepage_orari_document['summary'] !== '') : ?>
<p class="font-body-sm text-body-sm mt-4 opacity-80"><?php echo esc_html($homepage_orari_document['summary']); ?></p>
<?php endif; ?>
</div>
<div class="bg-secondary p-12 rounded-[2rem] text-on-secondary">
<div class="flex items-center gap-4 mb-8">
<span class="material-symbols-outlined text-4xl" data-icon="calendar_month">calendar_month</span>
<h2 class="font-headline-md text-headline-md text-on-secondary">Calendario scolastico</h2>
</div>
<p class="font-body-md text-body-md mb-8 opacity-90">Consulta l'ultimo documento pubblicato sul calendario scolastico.</p>
<a class="flex items-center justify-between bg-white/10 hover:bg-white/20 p-4 rounded-xl transition-colors group" href="<?php echo esc_url($homepage_calendar_document['url']); ?>">
<span class="font-semibold"><?php echo esc_html($homepage_calendar_document['title']); ?></span>
<span class="material-symbols-outlined transition-transform group-hover:translate-x-1" data-icon="event_note">event_note</span>
</a>
<?php if ($homepage_calendar_document['summary'] !== '') : ?>
<p class="font-body-sm text-body-sm mt-4 opacity-80"><?php echo esc_html($homepage_calendar_document['summary']); ?></p>
<?php endif; ?>
</div>
</div>
</div>
</section>
<!-- Servizi principali -->
<section class="py-section-padding-mobile md:py-section-padding-desktop bg-surface-cream">
<div class="px-4 md:px-gutter max-w-container-max mx-auto">
<div class="text-center mb-16">
<span class="font-label-caps text-label-caps text-secondary mb-4 block">SERVIZI PER LE FAMIGLIE</span>
<h2 class="font-headline-md text-headline-md text-primary">Tutto a portata di clic.</h2>
</div>
<div class="grid grid-cols-2 gap-4 md:gap-6 md:grid-cols-4 max-w-5xl mx-auto">
<a class="block bg-white p-8 rounded-3xl text-center hover:shadow-xl transition-all border border-border-subtle group w-full h-full" href="#">
<span class="material-symbols-outlined text-4xl text-primary mb-4 block transition-transform group-hover:scale-110" data-icon="description">description</span>
<h4 class="font-label-caps text-label-caps text-primary">Modulistica</h4>
</a>
<a class="block bg-white p-8 rounded-3xl text-center hover:shadow-xl transition-all border border-border-subtle group w-full h-full" href="#">
<span class="material-symbols-outlined text-4xl text-primary mb-4 block transition-transform group-hover:scale-110" data-icon="notifications">notifications</span>
<h4 class="font-label-caps text-label-caps text-primary">Archivio Modulistica</h4>
</a>
<a class="block bg-white p-8 rounded-3xl text-center hover:shadow-xl transition-all border border-border-subtle group w-full h-full" href="#">
<span class="material-symbols-outlined text-4xl text-primary mb-4 block transition-transform group-hover:scale-110" data-icon="assignment_ind">assignment_ind</span>
<h4 class="font-label-caps text-label-caps text-primary">Archivio Comunicazioni</h4>
</a>
<a class="block bg-white p-8 rounded-3xl text-center hover:shadow-xl transition-all border border-border-subtle group w-full h-full" href="#">
<span class="material-symbols-outlined text-4xl text-primary mb-4 block transition-transform group-hover:scale-110" data-icon="diversity_1">diversity_1</span>
<h4 class="font-label-caps text-label-caps text-primary">Archivio Iscrizioni</h4>
</a>
</div>
</div>
</section>
<!-- Contatti rapidi -->
<section class="py-section-padding-mobile md:py-section-padding-desktop">
<div class="px-4 md:px-gutter max-w-container-max mx-auto">
<div class="bg-white rounded-[3rem] overflow-hidden editorial-shadow grid md:grid-cols-2">
<div class="p-12 md:p-20">
<span class="font-label-caps text-label-caps text-tertiary mb-4 block">CONTATTI</span>
<h2 class="font-headline-md text-headline-md text-primary mb-8">Siamo qui per te.</h2>
<?php if ($homepage_contacts !== []) : ?>
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-12">
<?php foreach ($homepage_contacts as $contact) : ?>
<div class="flex items-start gap-4 bg-surface-cream rounded-2xl p-4 h-full">
<span class="material-symbols-outlined text-secondary mt-1" data-icon="<?php echo esc_attr((string) $contact['icon']); ?>"><?php echo esc_html((string) $contact['icon']); ?></span>
<div>
<h4 class="font-semibold text-primary"><?php echo esc_html((string) $contact['label']); ?></h4>
<?php if ((string) $contact['href'] !== '') : ?>
<?php $is_external = ! empty($contact['external']); ?>
<p class="text-on-surface-variant break-words">
<a href="<?php echo esc_url((string) $contact['href']); ?>"<?php echo $is_external ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>>
<?php echo esc_html((string) $contact['value']); ?>
<?php if ($is_external) : ?><span class="sr-only">(apre in nuova finestra)</span><?php endif; ?>
</a>
</p>
<?php else : ?>
<p class="text-on-surface-variant break-words"><?php echo esc_html((string) $contact['value']); ?></p>
<?php endif; ?>
</div>
</div>
<?php endforeach; ?>
</div>
<?php else : ?>
<p class="text-on-surface-variant mb-12">I contatti saranno pubblicati a breve.</p>
<?php endif; ?>
<div class="flex flex-wrap gap-4">
<?php if ($contact_cta_email !== '') : ?>
<a href="<?php echo esc_url($contact_cta_email); ?>" class="bg-primary text-on-primary px-8 py-3 rounded-full font-label-caps text-label-caps font-semibold hover:opacity-90 transition-all">Scrivici</a>
<?php endif; ?>
<?php if ($contact_cta_phone !== '') : ?>
<a href="<?php echo esc_url($contact_cta_phone); ?>" class="border-2 border-primary text-primary px-8 py-3 rounded-full font-label-caps text-label-caps font-semibold hover:bg-primary/5 transition-all">Chiama ora</a>
<?php endif; ?>
</div>
</div>
<div class="relative min-h-[400px]">
<?php if ($homepage_map_embed_url !== '') : ?>
<iframe
class="w-full h-full object-cover"
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
<!-- Footer -->
<section class="relative py-section-padding-mobile md:py-section-padding-desktop overflow-hidden">
<div class="absolute inset-0 z-0">
<img alt="Partner Background" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCzRNDreMgYxqjNMVbORRy8sxSCTuYQVxw-n3EWp2khMpKqOGpbjlaFN7S6AxrhQYgB8FpYvRqntf_nFLqiJKs4XDdUJTwUyxsztqwxjdI-5Q0lfWveoH-9MFnxGK7cVtAsd8-STUIrKYhFFLlY8H8oomTTOObkf0grD-VgPR2WPXqm7vO-dZ-8uuoifaFderLgFTcCL1hBmibmxuqoaYDMgC1WHMYbMES1VUdFxEcwrS4-TpbSqCc0a7stG_7p-JiO-p0GSsWLw31n"/>
<div class="absolute inset-0 bg-background-warm/80 backdrop-blur-sm"></div>
</div>
<div class="relative z-10 px-4 md:px-gutter max-w-container-max mx-auto text-center">
<div class="max-w-3xl mx-auto space-y-8">
<div class="space-y-4">
<h2 class="font-display-lg text-headline-md md:text-display-lg text-primary">I Nostri Partner: Eccellenza nella Formazione</h2>
<p class="font-body-md text-body-md text-on-surface-variant">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. La nostra azienda partner supporta la scuola con servizi specializzati di alta qualità.
                </p>
</div>
<div class="flex flex-wrap justify-center gap-4">
<span class="px-6 py-2 bg-secondary-container text-secondary rounded-full font-label-caps text-label-caps font-semibold">Formazione</span>
<span class="px-6 py-2 bg-primary-fixed text-primary rounded-full font-label-caps text-label-caps font-semibold">Consulenza</span>
<span class="px-6 py-2 bg-tertiary-fixed text-tertiary rounded-full font-label-caps text-label-caps font-semibold">Progettazione</span>
<span class="px-6 py-2 bg-surface-variant text-on-surface-variant rounded-full font-label-caps text-label-caps font-semibold">Comunicazione</span>
</div>
<div class="pt-4">
<button type="button" class="bg-primary text-on-primary px-10 py-5 rounded-2xl font-headline-sm text-headline-sm hover:shadow-2xl hover:-translate-y-1 transition-all active:scale-95">Visita il sito aziendale</button>
</div>
</div>
</div>
</section>
</main>
<footer class="bg-primary dark:bg-primary-container">
<div class="grid grid-cols-1 md:grid-cols-4 gap-gutter py-section-padding-mobile md:py-section-padding-desktop px-gutter max-w-container-max mx-auto text-on-primary">
<div class="md:col-span-2">
<span class="font-headline-sm text-headline-sm font-bold text-on-primary mb-6 block"><?php echo esc_html($company_display); ?></span>
<?php if ($legal_address !== '') : ?>
<p class="font-body-sm text-body-sm opacity-80 max-w-md mb-4"><?php echo nl2br(esc_html($legal_address)); ?></p>
<?php endif; ?>
<?php if ($footer_text !== '') : ?>
<p class="font-body-sm text-body-sm opacity-80 max-w-md mb-4"><?php echo nl2br(esc_html($footer_text)); ?></p>
<?php endif; ?>
<?php if (! empty($contact_chunks)) : ?>
<p class="font-body-sm text-body-sm opacity-80 max-w-md mb-4"><?php echo esc_html(implode(' | ', $contact_chunks)); ?></p>
<?php endif; ?>
<?php if (! empty($legal_chunks)) : ?>
<p class="font-body-sm text-body-sm opacity-80 max-w-md"><?php echo esc_html(implode(' | ', $legal_chunks)); ?></p>
<?php endif; ?>
</div>
<div>
<h4 class="font-label-caps text-label-caps mb-6 font-bold uppercase tracking-widest">Navigazione</h4>
<?php
wp_nav_menu([
    'theme_location' => 'primary',
    'container'      => false,
    'fallback_cb'    => 'wp_page_menu',
    'menu_class'     => 'space-y-4 font-body-sm text-body-sm footer-nav-menu',
]);
?>
</div>
<div>
<h4 class="font-label-caps text-label-caps mb-6 font-bold uppercase tracking-widest">Legale</h4>
<ul class="space-y-4 font-body-sm text-body-sm footer-legal-menu">
<li>
<a class="text-on-primary-container dark:text-on-primary-fixed-variant opacity-80 hover:opacity-100 hover:underline transition-opacity" href="<?php echo esc_url($feedback_url); ?>" target="_blank" rel="noopener noreferrer">
Feedback Accessibilita <span class="sr-only">(apre in nuova finestra)</span>
</a>
</li>
<li><a class="text-on-primary-container dark:text-on-primary-fixed-variant opacity-80 hover:opacity-100 hover:underline transition-opacity" href="<?php echo esc_url(home_url('/privacy-policy/')); ?>">Privacy Policy</a></li>
<li><a class="text-on-primary-container dark:text-on-primary-fixed-variant opacity-80 hover:opacity-100 hover:underline transition-opacity" href="<?php echo esc_url(home_url('/cookie-policy/')); ?>">Cookie Policy</a></li>
<li><a class="text-on-primary-container dark:text-on-primary-fixed-variant opacity-80 hover:opacity-100 hover:underline transition-opacity" href="<?php echo esc_url(get_post_type_archive_link('trasparenza') ?: home_url('/amministrazione-trasparente/')); ?>">Amministrazione Trasparente</a></li>
<?php if ($whistleblowing_url !== '') : ?>
<li>
<a class="text-on-primary-container dark:text-on-primary-fixed-variant opacity-80 hover:opacity-100 hover:underline transition-opacity" href="<?php echo esc_url($whistleblowing_url); ?>" target="_blank" rel="noopener noreferrer">
Whistleblowing <span class="sr-only">(apre in nuova finestra)</span>
</a>
</li>
<?php endif; ?>
<?php if ($accessibility_page instanceof WP_Post) : ?>
<li><a class="text-on-primary-container dark:text-on-primary-fixed-variant opacity-80 hover:opacity-100 hover:underline transition-opacity" href="<?php echo esc_url(get_permalink($accessibility_page)); ?>">Dichiarazione di Accessibilita</a></li>
<?php endif; ?>
<?php if ($obiettivi_page instanceof WP_Post) : ?>
<li><a class="text-on-primary-container dark:text-on-primary-fixed-variant opacity-80 hover:opacity-100 hover:underline transition-opacity" href="<?php echo esc_url(get_permalink($obiettivi_page)); ?>">Obiettivi di Accessibilita</a></li>
<?php endif; ?>
</ul>
</div>
</div>
<div class="border-t border-white/10 px-gutter max-w-container-max mx-auto py-8">
<p class="font-label-caps text-label-caps text-on-primary opacity-60 text-center">
<?php echo esc_html('© ' . gmdate('Y') . ' ' . get_bloginfo('name') . '. Tutti i diritti riservati.'); ?>
<?php if ($legal_mecc !== '') : ?> <?php echo esc_html(' Codice Meccanografico: ' . $legal_mecc . '.'); ?><?php endif; ?>
<?php if ($legal_vat !== '') : ?> <?php echo esc_html(' P.IVA ' . $legal_vat . '.'); ?><?php endif; ?>
</p>
</div>
</footer>
<?php wp_footer(); ?>
</body></html>