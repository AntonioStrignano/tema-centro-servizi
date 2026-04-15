# TODO — Master Checklist Progetto

> Lista completa di tutto quello che c'è da fare, divisa per categoria e priorità.
> Aggiornare man mano che si procede.

---

## 🚨 URGENTE — Da fare PRIMA del tema (siti già online)

### Whistleblowing (obbligatorio dal 17/12/2023 — siamo in ritardo)
- [ ] Ordinare VPS (Hetzner/Contabo, ~4€/mese, Ubuntu 22.04+)
- [ ] Installare GlobaLeaks sul VPS
- [ ] Configurare HTTPS (Let's Encrypt integrato)
- [ ] Per ogni scuola:
  - [ ] Puntare DNS `segnalazioni.nomescuola.it` → VPS
  - [ ] Creare contesto + utente ricevente in GlobaLeaks
  - [ ] Aggiungere link nel sito attuale (footer o Amm. Trasparente)

### DPO (obbligatorio GDPR)
- [ ] Formalizzare Centro Servizi come DPO esterno (contratto con ogni cliente)
- [ ] Per ogni scuola: aggiungere email DPO nella privacy policy del sito attuale

---

## 🔧 TEMA — Fase 1: Struttura base e CPT

> Fondamenta del tema. Niente frontend ancora, solo registrazioni e scheletro.

- [x] `style.css` — header WP obbligatorio + CSS debug
- [x] `functions.php` — bootstrap, carica moduli da `/inc`
- [x] `index.php` — fallback obbligatorio
- [ ] `screenshot.png`
- [x] `inc/setup.php` — theme supports, menu locations, image sizes, commenti disabilitati
- [x] `inc/enqueue.php` — registrazione style.css, deregistrazione jQuery
- [x] `inc/cpt-attivita.php`
- [x] `inc/cpt-trasparenza.php`
- [x] `inc/cpt-area-famiglie.php`
- [x] `inc/cpt-area-personale.php`
- [x] `inc/taxonomies.php` — `anno-scol-attivita`, `sezioni`, `contenutiammtrasp`, `annoscolastico`
- [x] `inc/acf-fields.php` — tutti i gruppi campi ACF via PHP
- [x] `partials/skip-links.php`
- [x] `partials/header.php` — logo/nome + nav + ricerca
- [x] `partials/footer.php` — dati legali hardcoded + link obbligatori
- [x] `partials/breadcrumb.php`
- [x] `templates/index.php`
- [x] `templates/page.php`
- [x] `templates/404.php`
- [x] Filtro `template_include` in functions.php (per sottocartella `templates/`)

---

## ⚖️ TEMA — Fase 2: Pagine legali e compliance

> Priorità compliance. Queste pagine devono esistere per legge.

- [ ] `templates/page-amministrazione-trasparente.php` — indice navigabile 12 sezioni
- [ ] `partials/card-trasparenza.php` — card con heading composto, allegato, contenuto, date
- [ ] `templates/archive-trasparenza.php` — archivio filtrato per tassonomia
- [ ] `templates/page-dichiarazione-accessibilita.php`
- [ ] `templates/page-contatti.php` — dati ACF in `<dl>` + mappa
- [ ] `templates/front-page.php` — hero, chi siamo, attività recenti, contatti, servizi
- [ ] Link whistleblowing nel footer/Amm. Trasparente (a GlobaLeaks esterno)
- [ ] Pagina WP `whistleblowing` con spiegazione + link piattaforma
- [ ] Verificare contributi L. 124/2017: tabelle TablePress con tutti i 5 campi obbligatori

---

## 📄 TEMA — Fase 3: Template contenuti

- [ ] `templates/single-attivita.php`
- [ ] `partials/card-attivita.php` — titolo, tassonomie, contenuto editor, date
- [ ] `templates/archive-attivita.php`
- [ ] `templates/archive-area-famiglie.php`
- [ ] `partials/card-area-famiglie.php`
- [ ] `templates/archive-area-personale.php`
- [ ] `partials/card-area-personale.php`
- [ ] `partials/pagination.php` — paginazione accessibile
- [ ] `inc/admin.php` — colonne admin personalizzate per CPT
- [ ] `inc/search.php` — filtri ricerca per tipo contenuto
- [ ] `partials/search-form.php` — form con checkboxes tipo contenuto
- [ ] `templates/search.php` — risultati raggruppati per tipo

---

## ✅ TEMA — Fase 4: Verifica

- [ ] Audit accessibilità con axe-core
- [ ] Audit Lighthouse (performance + accessibility)
- [ ] Navigazione completa da tastiera (tab, enter, escape)
- [ ] Verifica HTML valido (W3C validator)
- [ ] Test screen reader (VoiceOver su macOS)
- [ ] Verifica tutti i link obbligatori nel footer
- [ ] Verifica date pub/modifica su tutte le card
- [ ] Verifica `alt` su tutte le immagini
- [ ] Verifica heading gerarchici (nessun salto)

---

## 📋 PER OGNI NUOVO CLIENTE — Domande e setup

- [ ] Siete una cooperativa? → Se sì: dati REA, Albo Coop, CCIAA, capitale sociale per footer
- [ ] Siete iscritti al RUNTS? → Se sì: "ETS" nella ragione sociale
- [ ] Avete un DPO nominato? → Se no: formalizzare Centro Servizi come DPO esterno
- [ ] Avete un Modello 231? → Se sì: pubblicare in Amm. Trasparente
- [ ] Pubblicate bilancio sociale? → Verificare visibilità in tassonomia "06 Bilanci"
- [ ] Tabelle contributi MIM complete? → 5 campi: denominazione, CF erogante, importo, data, causale
- [ ] Creare contesto GlobaLeaks per la scuola
- [ ] Configurare DNS `segnalazioni.nomescuola.it`
- [ ] Compilare dichiarazione accessibilità su form.agid.gov.it
- [ ] Hardcodare dati footer nel tema (ragione sociale, P.IVA, CF, PEC, cod. mecc.)

---

## 📅 RICORRENZE ANNUALI

| Scadenza                          | Cosa                                                       | Chi                  |
|-----------------------------------|------------------------------------------------------------|----------------------|
| **31 marzo**                      | Pubblicare obiettivi di accessibilità                      | Webmaster + cliente  |
| **23 settembre**                  | Aggiornare dichiarazione accessibilità su form.agid.gov.it | Webmaster            |
| **30 giugno**                     | Pubblicare contributi L. 124/2017 anno precedente          | Cliente (segreteria) |
| Entro **1 anno** dalla percezione | Rendiconto 5x1000                                          | Cliente              |

---

## 🔮 FASE FUTURA — Dopo il lancio

- [ ] CSS vero (variabili, componenti, responsive, colori, tipografia)
- [ ] JS — menu hamburger mobile (`navigation.js`)
- [ ] JS — lightbox galleria
- [ ] JS — modale tabelle TablePress (schermo intero per leggibilità)
- [ ] JS — filtri ricerca interattivi
- [ ] Calendario eventi (CPT `evento` + vista calendario)
- [ ] Possibile unificazione Area Famiglie + Area Personale in CPT unico con tassonomia flag
