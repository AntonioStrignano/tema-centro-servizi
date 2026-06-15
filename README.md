# Tema Centro Servizi

Tema WordPress classico per scuole paritarie, con focus su compliance italiana (accessibilita, trasparenza, pagine legali) e gestione contenuti tramite CPT.

## Stato progetto

- Ultimo allineamento documentale: 15/06/2026
- Tema attivo con struttura templates/partials/inc consolidata
- Pagine legali gestite da template unico `templates/page-legale.php` su slug riservati
- Archivi burocratici unificati in `templates/archive-area-burocratica-common.php`
- Paginazione accessibile attiva negli archivi principali (`partials/pagination.php`)

## Struttura principale

- `functions.php`: bootstrap moduli e mapping template da `templates/`
- `inc/`: setup tema, CPT/tassonomie, settings admin, accessibility/search
- `templates/`: page/front/search/archive/single
- `partials/`: header/footer, card contenuti, breadcrumb, skip links, pagination
- `assets/css/`: `site.css`, `site-header.css`, `site-footer.css`, `area-burocratica.css`, `legal-pages.css`
- `assets/js/site-header.js`: hamburger menu accessibile + gestione stato pannello nav

## Routing attuale (sintesi)

- Front page: `templates/front-page.php`
- Pagine legali (privacy, cookie, dichiarazione accessibilita, whistleblowing, obiettivi accessibilita, amministrazione-trasparente): `templates/page-legale.php`
- Pagine standard: `templates/page.php` (con partial recapiti per slug `contatti`)
- Archivi `trasparenza`, `area-famiglie`, `area-personale`: `templates/archive-area-burocratica-common.php`
- Archivio `attivita`: `templates/archive-attivita.php`

## Documentazione di riferimento

- `TODO.md`: backlog operativo e priorita
- `PROGETTO.md`: specifica funzionale/tecnica estesa
- `DESIGN.md`: linee visual e design system
- `docs/`: audit, normativa, handoff, registri compliance

## Prossime priorita

- Revisione testi pagine seed obbligatorie (Privacy, Cookie, Dichiarazione Accessibilita, Whistleblowing)
- Audit finale accessibilita (axe, Lighthouse, tastiera, VoiceOver)
- Rifiniture homepage (copy definitivo e vetrina attivita in evidenza)