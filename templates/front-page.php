<?php
declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

$homepage_title_option = trim((string) get_option('centro_servizi_homepage_title', ''));
$homepage_subtitle_option = trim((string) get_option('centro_servizi_homepage_subtitle', ''));

$homepage_title = $homepage_title_option !== ''
    ? $homepage_title_option
    : get_bloginfo('name');

$homepage_subtitle = $homepage_subtitle_option !== ''
    ? $homepage_subtitle_option
    : get_bloginfo('description');
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
    'fallback_cb'    => 'wp_page_menu',
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
<h1 class="font-display-lg text-display-lg-mobile md:text-display-lg text-primary mb-6 animate-fade-in">
                        <?php echo esc_html($homepage_title); ?>
                    </h1>
<p class="font-body-lg text-body-lg text-on-surface-variant mb-10 max-w-lg">
                        <?php echo esc_html($homepage_subtitle); ?>
                    </p>
<div class="flex flex-col sm:flex-row gap-4">
<button type="button" class="bg-tertiary text-on-tertiary px-8 py-4 rounded-xl font-headline-sm text-headline-sm hover:shadow-lg transition-all active:scale-95">
                            Prenota un colloquio
                        </button>
<button type="button" class="border-2 border-secondary text-secondary px-8 py-4 rounded-xl font-headline-sm text-headline-sm hover:bg-secondary/5 transition-all">
                            Scopri la scuola
                        </button>
</div>
</div>
</div>
</section>
<!-- Chi Siamo / Valori -->
<section class="py-section-padding-mobile md:py-section-padding-desktop bg-surface-cream">
<div class="px-4 md:px-gutter max-w-container-max mx-auto">
<div class="grid lg:grid-cols-2 gap-16 items-center md:grid-cols-4 justify-items-center max-w-5xl mx-auto">
<div>
<span class="font-label-caps text-label-caps text-tertiary mb-4 block">CHI SIAMO</span>
<h2 class="font-headline-md text-headline-md text-primary mb-6">Educhiamo con amore, guidiamo con esperienza.</h2>
<p class="font-body-md text-body-md text-on-surface-variant">
                            La nostra scuola paritaria è un luogo di crescita armoniosa dove la curiosità naturale dei bambini viene alimentata attraverso il gioco, l'esplorazione e la relazione. Crediamo in un'educazione che valorizzi l'unicità di ogni individuo in un contesto comunitario solido.
                        </p>
</div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:grid-cols-4 justify-items-center max-w-5xl mx-auto">
<div class="bg-white p-8 rounded-2xl editorial-shadow text-center">
<div class="w-12 h-12 bg-secondary-container rounded-full flex items-center justify-center mx-auto mb-4 text-secondary">
<span class="material-symbols-outlined" data-icon="volunteer_activism">volunteer_activism</span>
</div>
<h3 class="font-headline-sm text-headline-sm text-primary mb-2">Accoglienza</h3>
<p class="font-body-sm text-body-sm text-on-surface-variant">Un clima familiare e sereno.</p>
</div>
<div class="bg-white p-8 rounded-2xl editorial-shadow text-center">
<div class="w-12 h-12 bg-primary-fixed rounded-full flex items-center justify-center mx-auto mb-4 text-primary">
<span class="material-symbols-outlined" data-icon="trending_up">trending_up</span>
</div>
<h3 class="font-headline-sm text-headline-sm text-primary mb-2">Crescita</h3>
<p class="font-body-sm text-body-sm text-on-surface-variant">Sviluppo cognitivo e motorio.</p>
</div>
<div class="bg-white p-8 rounded-2xl editorial-shadow text-center">
<div class="w-12 h-12 bg-tertiary-fixed rounded-full flex items-center justify-center mx-auto mb-4 text-tertiary">
<span class="material-symbols-outlined" data-icon="groups">groups</span>
</div>
<h3 class="font-headline-sm text-headline-sm text-primary mb-2">Comunità</h3>
<p class="font-body-sm text-body-sm text-on-surface-variant">Condivisione con le famiglie.</p>
</div>
</div>
</div>
</div>
</section>
<!-- La nostra scuola: Mini Gallery -->
<section class="py-section-padding-mobile md:py-section-padding-desktop">
<div class="px-4 md:px-gutter max-w-container-max mx-auto">
<div class="flex justify-between items-end mb-12">
<div>
<span class="font-label-caps text-label-caps text-secondary mb-4 block">GLI SPAZI</span>
<h2 class="font-headline-md text-headline-md text-primary">Un ambiente su misura per i piccoli.</h2>
</div>
<a class="hidden md:flex items-center gap-2 text-secondary font-semibold hover:underline group" href="#">
                        Scopri la scuola 
                        <span class="material-symbols-outlined transition-transform group-hover:translate-x-1" data-icon="arrow_forward">arrow_forward</span>
</a>
</div>
<div class="grid grid-cols-1 md:grid-cols-4 gap-6">
<div class="space-y-4">
<div class="aspect-[4/5] rounded-3xl overflow-hidden">
<img class="w-full h-full object-cover hover:scale-105 transition-transform duration-500" alt="Aula polifunzionale" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCdh5na1rpTmHcMqxT0pqpJreVVjX2XS1eSsDT7NPTYV6FYXCWHTAD25SD4ql1gj2yO8oY5Q1TtRDy3NoTJorVUeC8eraSQwzkpUC79-6ln1_Zbo2WjM0ylhQGn0e3ksBzHAdeU2hJ9lhIckBTZTC7Mo0qBLG2k9FLLb8yZK7ZqbyFJHJ-peO3oR9zlQgh1ooYlsJ7ab0UAtdayfFRSqcz2TH4shcg-IP8r64HU6IlVPicTqBYL7rkvXeRkDNWLK7wZ7jngL0XjH394"/>
</div>
<p class="font-label-caps text-label-caps text-primary px-2">Aula polifunzionale</p>
</div>
<div class="space-y-4 md:mt-12">
<div class="aspect-[4/5] rounded-3xl overflow-hidden">
<img class="w-full h-full object-cover hover:scale-105 transition-transform duration-500" alt="Giardino esterno" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDHNMPLe6BN65k37pNZ9tykZJzzu88rx4kZymtD_mPGqkNc9mtps3I4PbyXYx5tkh2ZTktBf6wpT0BozIpAdm3tM5ddJxhrGNhwbKS6uLmZkGTzzVE-upi0eFp2Q7UidHrB1XBu_movCtJcSgA3_T6SFgKmVKjPrO3X0X5Q0JD-IPe-HH2xObuJ8LZT8yDp8_qHys7HEigViRe-mDuXNiwYpgjXKh5Ywia0YmbYlo7UmJZI1nCa3vXVYehnfxGcAGQuKpGZSxpb4htF"/>
</div>
<p class="font-label-caps text-label-caps text-primary px-2">Giardino esterno</p>
</div>
<div class="space-y-4">
<div class="aspect-[4/5] rounded-3xl overflow-hidden">
<img class="w-full h-full object-cover hover:scale-105 transition-transform duration-500" alt="Laboratorio creativo" src="https://lh3.googleusercontent.com/aida-public/AB6AXuA9GcGYrJD0DtSRKZMua6hvH8-4mLi-ukSuWbR1nzK_VFSkYCKW8QHI6xNev3qEZXYHifk1STgoG5ct0hejF8bfmOO4s_Z-wMzJmJTSyqFYHypFMIgRBBeYwrrcvCxuC7iZHlbgGBIx0zJMibFGvHEeHOz8gMuxfOiSxkpkwcuZvnU0SeOb9OOnjLgyu48j7TEjKgAiF4LTLWoAGn-QZ17tBNtFrCTcno0ZIm9DgBRJnWL3oq_UvV5AGxNjNyxWM3GRz3SDZX-OU9Ka"/>
</div>
<p class="font-label-caps text-label-caps text-primary px-2">Laboratorio creativo</p>
</div>
<div class="space-y-4 md:mt-12">
<div class="aspect-[4/5] rounded-3xl overflow-hidden">
<img class="w-full h-full object-cover hover:scale-105 transition-transform duration-500" alt="Area riposo" src="https://lh3.googleusercontent.com/aida-public/AB6AXuD10J3r7ipd-PbdzTFMszODPK5OvOk3zBrDnE84zhOiCd2Ic9811PXrB-KXz6qqJI77FRROa1p0olJmdQK2-lP4XsZknsrkZtsu1d_MtQJfjQ24zaSQlLJO6kmyD0CN-GSHkbdfXtB5o7cGkTKZI3EvIw_fISB5ssz7ETbAl1r5AvANwa84qnuFXuNYW7xVUuhmSIp1SSHUNbKPDtM60YKyBlZAac3ZNlQBqLblRff9QS5FZR-4YDajX9mC0dNaizCtpFM5MEVpzwWq"/>
</div>
<p class="font-label-caps text-label-caps text-primary px-2">Area riposo</p>
</div>
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
<h2 class="font-headline-md text-headline-md">Orari di funzionamento</h2>
</div>
<ul class="space-y-6 font-body-md text-body-md border-l-2 border-primary-fixed/30 pl-6">
<li>
<span class="block font-bold">Ingresso:</span>
<span class="opacity-90">Dalle 08:00 alle 09:00</span>
</li>
<li>
<span class="block font-bold">Uscita intermedia:</span>
<span class="opacity-90">Dalle 13:00 alle 13:30</span>
</li>
<li>
<span class="block font-bold">Uscita finale:</span>
<span class="opacity-90">Dalle 15:45 alle 16:30</span>
</li>
</ul>
</div>
<div class="bg-secondary p-12 rounded-[2rem] text-on-secondary">
<div class="flex items-center gap-4 mb-8">
<span class="material-symbols-outlined text-4xl" data-icon="calendar_month">calendar_month</span>
<h2 class="font-headline-md text-headline-md">Calendario scolastico</h2>
</div>
<p class="font-body-md text-body-md mb-8 opacity-90">Consulta le festività, i periodi di chiusura e le date degli eventi speciali previsti per l'anno scolastico in corso.</p>
<div class="space-y-4">
<a class="flex items-center justify-between bg-white/10 hover:bg-white/20 p-4 rounded-xl transition-colors group" href="#">
<span class="font-semibold">Scarica Calendario 2024/25</span>
<span class="material-symbols-outlined transition-transform group-hover:translate-y-1" data-icon="download">download</span>
</a>
<a class="flex items-center justify-between bg-white/10 hover:bg-white/20 p-4 rounded-xl transition-colors group" href="#">
<span class="font-semibold">Eventi e Recite</span>
<span class="material-symbols-outlined transition-transform group-hover:translate-x-1" data-icon="event_note">event_note</span>
</a>
</div>
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
<div class="grid grid-cols-2 gap-4 md:gap-6 md:grid-cols-4 justify-items-center max-w-5xl mx-auto">
<a class="bg-white p-8 rounded-3xl text-center hover:shadow-xl transition-all border border-border-subtle group" href="#">
<span class="material-symbols-outlined text-4xl text-primary mb-4 block transition-transform group-hover:scale-110" data-icon="description">description</span>
<h4 class="font-label-caps text-label-caps text-primary">Modulistica</h4>
</a>
<a class="bg-white p-8 rounded-3xl text-center hover:shadow-xl transition-all border border-border-subtle group" href="#">
<span class="material-symbols-outlined text-4xl text-primary mb-4 block transition-transform group-hover:scale-110" data-icon="notifications">notifications</span>
<h4 class="font-label-caps text-label-caps text-primary">Archivio Modulistica</h4>
</a>
<a class="bg-white p-8 rounded-3xl text-center hover:shadow-xl transition-all border border-border-subtle group" href="#">
<span class="material-symbols-outlined text-4xl text-primary mb-4 block transition-transform group-hover:scale-110" data-icon="assignment_ind">assignment_ind</span>
<h4 class="font-label-caps text-label-caps text-primary">Archivio Comunicazioni</h4>
</a>
<a class="bg-white p-8 rounded-3xl text-center hover:shadow-xl transition-all border border-border-subtle group" href="#">
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
<div class="space-y-8 mb-12">
<div class="flex items-start gap-4">
<span class="material-symbols-outlined text-secondary" data-icon="location_on">location_on</span>
<div>
<h4 class="font-semibold text-primary">Sede Centrale</h4>
<p class="text-on-surface-variant">Via delle Scuole, 15 - 00100 Roma (RM)</p>
</div>
</div>
<div class="flex items-start gap-4">
<span class="material-symbols-outlined text-secondary" data-icon="call">call</span>
<div>
<h4 class="font-semibold text-primary">Telefono</h4>
<p class="text-on-surface-variant">+39 06 123 4567</p>
</div>
</div>
<div class="flex items-start gap-4">
<span class="material-symbols-outlined text-secondary" data-icon="mail">mail</span>
<div>
<h4 class="font-semibold text-primary">Email</h4>
<p class="text-on-surface-variant">segreteria@scuolaaperta.it</p>
</div>
</div>
</div>
<div class="flex flex-wrap gap-4">
<button type="button" class="bg-primary text-on-primary px-8 py-3 rounded-full font-label-caps text-label-caps font-semibold hover:opacity-90 transition-all">Scrivici</button>
<button type="button" class="border-2 border-primary text-primary px-8 py-3 rounded-full font-label-caps text-label-caps font-semibold hover:bg-primary/5 transition-all">Chiama ora</button>
</div>
</div>
<div class="relative min-h-[400px]">
<img class="w-full h-full object-cover" alt="Mappa della zona della scuola" data-location="Rome, Italy" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAuunF_uo4bdmo8_tl1U4-RyiuxPcGMMeMfuMPW7IJjz8wwmkk4bh4Z70Ja0AC3sc_r4kFI2evYy7beIz2Uh-j4kgfX5UuSOxVdNg5Nz80UBLyUdSYMedgaQiwIH93F_sgLr2KDFjAyynv5niblO5QLoteCJCkNZAUrkWTrUQH2kzzBqV93lFtmWwecSaQ2AxWOOFv-FbsggaQTqS_pWvTnjsAcVi9-kCfindJS6vrGtk3oQR5MED-I8_36OjXGY1t4EnePAdBhODAm"/>
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
<span class="font-headline-sm text-headline-sm font-bold text-on-primary mb-6 block">Scuola Aperta</span>
<p class="font-body-sm text-body-sm opacity-80 max-w-md mb-8">
                    La nostra scuola paritaria dell'infanzia offre un percorso formativo d'eccellenza, radicato nei valori della comunità e dell'innovazione didattica, per accompagnare i bambini nelle loro prime tappe di scoperta del mondo.
                </p>
<div class="flex gap-4">
<a class="hover:opacity-70 transition-opacity" href="#" aria-label="Pagina social principale"><span class="material-symbols-outlined" data-icon="facebook">social_leaderboard</span></a>
<a class="hover:opacity-70 transition-opacity" href="#" aria-label="Profilo foto"><span class="material-symbols-outlined" data-icon="camera">camera</span></a>
<a class="hover:opacity-70 transition-opacity" href="#" aria-label="Galleria immagini"><span class="material-symbols-outlined" data-icon="linked_camera">linked_camera</span></a>
</div>
</div>
<div>
<h4 class="font-label-caps text-label-caps mb-6 font-bold uppercase tracking-widest">Link Utili</h4>
<ul class="space-y-4 font-body-sm text-body-sm">
<li><a class="text-on-primary-container dark:text-on-primary-fixed-variant opacity-80 hover:opacity-100 hover:underline transition-opacity" href="#">La nostra scuola</a></li>
<li><a class="text-on-primary-container dark:text-on-primary-fixed-variant opacity-80 hover:opacity-100 hover:underline transition-opacity" href="#">Attività</a></li>
<li><a class="text-on-primary-container dark:text-on-primary-fixed-variant opacity-80 hover:opacity-100 hover:underline transition-opacity" href="#">Area famiglie</a></li>
<li><a class="text-on-primary-container dark:text-on-primary-fixed-variant opacity-80 hover:opacity-100 hover:underline transition-opacity" href="#">Mappa del Sito</a></li>
</ul>
</div>
<div>
<h4 class="font-label-caps text-label-caps mb-6 font-bold uppercase tracking-widest">Legale</h4>
<ul class="space-y-4 font-body-sm text-body-sm">
<li><a class="text-on-primary-container dark:text-on-primary-fixed-variant opacity-80 hover:opacity-100 hover:underline transition-opacity" href="#">Feedback Accessibilità</a></li>
<li><a class="text-on-primary-container dark:text-on-primary-fixed-variant opacity-80 hover:opacity-100 hover:underline transition-opacity underline" href="#">Privacy Policy</a></li>
<li><a class="text-on-primary-container dark:text-on-primary-fixed-variant opacity-80 hover:opacity-100 hover:underline transition-opacity" href="#">Cookie Policy</a></li>
<li><a class="text-on-primary-container dark:text-on-primary-fixed-variant opacity-80 hover:opacity-100 hover:underline transition-opacity" href="#">Amministrazione Trasparente</a></li>
</ul>
</div>
</div>
<div class="border-t border-white/10 px-gutter max-w-container-max mx-auto py-8">
<p class="font-label-caps text-label-caps text-on-primary opacity-60 text-center">
                © 2024 Scuola Paritaria. Tutti i diritti riservati. Codice Meccanografico: SC12345. P.IVA 01234567890.
            </p>
</div>
</footer>
<?php wp_footer(); ?>
</body></html>