# TODO — Master Checklist Progetto

> Lista completa di tutto quello che c'è da fare, divisa per categoria e priorità.
> Aggiornare man mano che si procede.

## Stato audit tecnico (20/04/2026)

- [x] Allineati i template archivio al target skip link `#contenuto-principale`
- [x] Aggiunto landmark principale coerente nei template archivio (`<main ... role="main">`)
- [x] Rimossa duplicazione breadcrumb nei template archivio (evita doppio annuncio ai lettori di schermo)
- [x] Aggiunto avviso accessibile "apre in nuova finestra" sui link allegati negli archivi
- [x] Routing pagine legali consolidato su template unico `templates/page-legale.php` (copre anche gli slug prioritari)
- [ ] `screenshot.png` rinviato (non bloccante in questa fase)
- [x] Blocco Attivita completato lato template (archive + single + card + filtri)

## Ordine operativo attuale

- [ ] Prima chiudere tutti i template e partial frontend rimasti
- [ ] Solo dopo: passaggio accessibilità, audit e rifiniture compliance finali

## Note operative rapide

- [x] Cartella `samples/` disponibile per HTML/CSS di riferimento (esclusa da git)

## Aggiornamento sessione (21/05/2026) — Homepage + sistema stile

### Completato

- [x] Homepage resa dinamica e rifinita in `templates/front-page.php` (hero, sezioni, contatti, footer)
- [x] Consolidata la logica dati homepage in `inc/homepage.php` (helper centralizzati)
- [x] Risolti regressioni layout: menu header senza bullet fallback, hero ripristinata, sezioni riallineate
- [x] Ridisegnata area contatti con lista robusta e CTA email/telefono derivate dai contatti configurati
- [x] Footer legale ristrutturato con colonne navigazione/legale e collegamenti obbligatori
- [x] Introdotti preset vincolati in `inc/settings.php` per palette colore e mood tipografici
- [x] Applicazione preset collegata al frontend con CSS dinamico (variabili colore/font)
- [x] Escluso il tema dinamico da pagine legali/trasparenza per mantenere contrasti e coerenza normativa
- [x] Migliorata UX admin: selezione preset tramite card cliccabili (radio-card) invece di sole select
- [x] Aggiunte anteprime font nei mood (titolo/corpo/label) con caricamento font per preview affidabile
- [x] Tokenizzati gli stili Stitch in `assets/css/stitch-layout.css`, `assets/css/stitch-header-footer.css`, `assets/css/stitch-homepage.css`

## Aggiornamento sessione (26/05/2026) — Bonifica CSS contesto burocratico

### Completato

- [x] Uniformato il comportamento tipografico nelle pagine burocratiche (Trasparenza, Area Famiglie, Area Personale) eliminando la divergenza font su H1
- [x] Separati i token dinamici (`:root` con variabili colore/font) dagli override globali: in area burocratica restano attivi i token, non gli override globali
- [x] Mantenuto il caricamento Google Fonts anche in contesto burocratico per evitare fallback inattesi
- [x] Confermata separazione strutturale dei CSS per contesto senza toccare header/footer globali

### Intenzioni prossime

- [ ] Validazione end-to-end in admin: click card preset -> salva -> verifica persistenza e resa frontend
- [ ] Pulizia tecnica: rimuovere JS/CSS legacy del vecchio picker tipografico in `inc/settings.php`
- [ ] Rifinitura UX admin: feedback visuale ancora piu evidente su card selezionata (micro-polish)
- [ ] QA finale esclusioni tema dinamico su pagine legali e trasparenza

### Audit accessibilita template (26/05/2026)

- [x] Verificata presenza landmark main + target skip link coerente su tutti i template principali
- [x] Rimossa duplicazione breadcrumb nei template pagina custom (contatti, la-nostra-scuola, attivita-speciali)
- [x] Header mobile: introdurre menu hamburger accessibile con bottone (aria-expanded, aria-controls, etichetta chiara)
- [x] Header mobile: garantire apertura/chiusura menu senza rompere ordine tab e focus
- [x] Archivio burocratico AJAX: aggiungere annuncio accessibile aggiornamento risultati (aria-live + messaggio stato)
- [x] Archivio burocratico AJAX: gestire focus dopo aggiornamento filtri per utenti tastiera/screen reader
- [x] Archivio burocratico AJAX: dopo refresh filtro, scroll smooth verso l'intestazione dell'archivio
- [x] Homepage: riallineati skip links al set completo globale (contenuto, navigazione, footer)
- [x] Homepage: sostituire CTA non operative (`button` senza azione) con link reali o azioni JS accessibili

### Normalizzazione header/footer (26/05/2026)

- [x] Header: isolati gli override burocratici al solo `.site-main` per evitare variazioni cromatiche/tipografiche tra homepage e archivi
- [x] Footer: stesso isolamento (`.site-main`) per impedire che il layer burocratico alteri link/focus del footer globale
- [x] Skip links: unificati su partial globale + regole in `assets/css/site.css` (rimossa variante locale Stitch)
- [x] CSS dinamico tipografico: profili `links/buttons` limitati a `.site-main` in `inc/settings.php` per evitare override su header/footer solo in homepage

### Audit duplicazioni template (26/05/2026)

- [x] Verificata pipeline condivisa header/footer su tutti i template principali (uniformata)
- [x] Verificata assenza breadcrumb duplicato nei template pagina custom
- [x] Verificati wrapper minimi per archivi `area-famiglie` e `area-personale` (riuso corretto di `archive-area-burocratica-common.php`)
- [x] Dismesso `templates/archive-trasparenza.php` (duplicazione legacy non instradata dal runtime)
- [ ] Ridurre duplicazione tra `templates/page-la-nostra-scuola.php` e `templates/page-attivita-speciali.php` con partial/template condiviso
- [x] Ripulite variabili legacy non usate in `templates/front-page.php` (dati footer/legale già gestiti da `partials/footer.php`)

### Aggiornamento sessione (15/06/2026) — Paginazione accessibile

### Completato

- [x] Creato `partials/pagination.php` con markup ARIA (`aria-label="Navigazione pagine"`)
- [x] Aggiornato `templates/archive-attivita.php`: `posts_per_page` -1 → 10, `no_found_rows` true → false
- [x] Aggiornato `templates/archive-area-burocratica-common.php`: stessi cambiamenti (vale per trasparenza, area-famiglie, area-personale)
- [x] Aggiunto `get_template_part('partials/pagination')` negli archivi post-grid/post-list
- [x] Corretto il conteggio risultati su archivi burocratici: usa il totale query (`found_posts`) invece dei soli elementi della pagina corrente
- [x] Aggiornato il riepilogo risultati in formato range: `Documenti x-y su totale` coerente con la pagina corrente
- [x] Corretta la paginazione per query custom: passaggio esplicito della query al partial + supporto `paged` su archivi e risultati filtrati/ricerca
- [x] Corretta la generazione href della paginazione: i link `pagina 2` / `Successiva` ora preservano i filtri correnti e puntano a URL distinti via query arg `paged`
- [x] Aggiunto styling base della navigazione paginata in `assets/css/site.css` e override coerente in `assets/css/area-burocratica.css`

### Aggiornamento sessione (26/05/2026) — Template pagine contenuto + contatti

### Completato

- [x] Consolidato template pagine standard in `templates/page.php` con layout base pulito (`page-basic`) e contenuto centrato
- [x] Rimossa pagina slug-specifica `templates/page-contatti.php` per usare il fallback unico del routing
- [x] Estratto blocco recapiti in partial dedicata `partials/page-contatti-recapiti.php`
- [x] Render condizionale recapiti solo su pagina `contatti` dentro `templates/page.php`

### Da rifinire

- [ ] Ritocco estetico lista recapiti (spaziature, gerarchia visiva label/valore, micro-polish responsive)

### Bonifica CSS (decommission Stitch) — da pianificare

- [x] Definire mappa di uscita per `assets/css/stitch-layout.css`, `assets/css/stitch-homepage.css`, `assets/css/stitch-header-footer.css`
- [x] Migrato baseline globale da `stitch-layout.css` a `site.css` (token, reset, utility accessibilita base)
- [x] Disattivato enqueue di `stitch-layout.css`
- [x] Migrati componenti homepage `hp-*` da `stitch-homepage.css` a `site.css`
- [x] Disattivato enqueue di `stitch-homepage.css` e rimossi i file Stitch residui
- [x] Rimosso scroll interno desktop dagli archivi burocratici: flusso pagina tornato naturale
- [ ] Migrare progressivamente regole utili in pochi file target (`assets/css/site.css`, `assets/css/site-header.css`, `assets/css/site-footer.css`)
- [ ] Ridurre a zero i selettori duplicati tra layer Stitch e layer main
- [x] Spegnere enqueue degli asset Stitch in `inc/enqueue.php` quando la migrazione e completata

---

## 🚨 URGENTE — Da fare PRIMA del tema (siti già online)

### Whistleblowing (obbligatorio dal 17/12/2023 — siamo in ritardo)
- [x] Definito piano infrastruttura minimo: Hetzner CX11 (~29,90 €/anno + IVA), HTTPS Let's Encrypt
- [ ] Attivare VM dedicata per GlobaLeaks (Hetzner Cloud / VPS Linux, Ubuntu 22.04+)
- [ ] Installare GlobaLeaks sulla VM dedicata
- [ ] Configurare HTTPS (Let's Encrypt integrato)
- [x] Stato operativo: in pausa forzata finché la titolare completa validazione documenti/account provider
- [ ] Per ogni scuola:
  - [ ] Se approccio centralizzato: puntare DNS `segnalazioni.centroservizi.it` → VM dedicata GlobaLeaks
  - [ ] Se richiesto dal cliente: DNS dedicato `segnalazioni.nomescuola.it` → stessa VM
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
- [ ] `screenshot.png` (opzionale per ora)
- [x] `inc/setup.php` — theme supports, menu locations, image sizes, commenti disabilitati
- [x] `inc/enqueue.php` — deregistrazione jQuery (CSS gestito da header/debug)
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

> Priorità compliance. Per Amministrazione Trasparente si usa l'archivio `trasparenza` come soluzione principale.
- [x] `partials/card-trasparenza.php` — card con heading composto, allegato, contenuto, date
- [x] `templates/archive-trasparenza.php` — archivio filtrato per tassonomia
- [x] Template pagine legali condiviso (`templates/page-legale.php`) per: `amministrazione-trasparente`, `dichiarazione-accessibilita`, `privacy-policy`, `cookie-policy`, `whistleblowing`, `obiettivi-accessibilita`
- [x] Pagina `contatti` riallineata a template base: recapiti/mappa in partial `partials/page-contatti-recapiti.php` renderizzata da `templates/page.php`
- [x] `templates/front-page.php` — hero, chi siamo, attività recenti, contatti, servizi (versione dinamica attiva)
- [ ] Homepage: sostituire placeholder `Chi siamo` (Lorem ipsum) con copy definitivo cliente
- [x] Homepage: palette/font da `Impostazioni Sito > Stile Tema` applicati alle sezioni tramite preset e CSS dinamico
- [ ] Homepage: consolidare sezione `Attivita in evidenza` (vetrina esperienze distintive, non archivio completo)
- [ ] Homepage: definire fonte editoriale unica per vetrina (`pagina attivita-speciali` + repeater ACF)
- [x] Link whistleblowing nel footer/Amm. Trasparente (gestito da opzione `url_whistleblowing` con fallback pagina seed)
- [x] Pagina WP `whistleblowing` con spiegazione + link piattaforma (generata via seed pagine obbligatorie)
- [ ] Verificare contributi L. 124/2017: tabelle TablePress con tutti i 5 campi obbligatori

---

## 📄 TEMA — Fase 3: Template contenuti

- [x] `templates/single-attivita.php`
- [x] `partials/card-attivita.php` — titolo, tassonomie, data pubblicazione
- [x] `templates/archive-attivita.php`
- [x] `templates/archive-area-famiglie.php`
- [x] `partials/card-area-famiglie.php`
- [x] `templates/archive-area-personale.php`
- [x] `partials/card-area-personale.php`
- [x] `partials/pagination.php` — paginazione accessibile
- [x] `inc/admin.php` — colonne admin personalizzate per CPT
- [x] Definire e implementare componente accessibilita riusabile per filtri/risultati dinamici:
  - [x] helper PHP per live region (`role="status"`, `aria-live="polite"`, `aria-atomic="true"`)
  - [x] target focus post-refresh (`tabindex="-1"` + focus sicuro)
  - [x] micro-copy standardizzato (caricamento, aggiornato, errore fallback)
- [x] `inc/search.php` — filtri ricerca per tipo contenuto
- [x] `partials/search-form.php` — form con checkboxes tipo contenuto
- [x] `templates/search.php` — risultati raggruppati per tipo

### QA rapido ricerca (15/06/2026)

- [x] Query combinata verificata (`calendario 2025`) con match su contenuto + tassonomie
- [x] Risultati `trasparenza`: titolo coerente con archivio + link ad archivio prefiltrato
- [x] Risultati `area-famiglie` e `area-personale`: apertura su pagina singola dedicata
- [x] Stato empty results verificato

Nota: check manuale mobile header/search rimandato come rifinitura non bloccante.

### Colonne admin CPT da aggiungere (scope minimo)

- [x] `attivita`: Anno scolastico, Data, Sezione
- [x] `trasparenza`: Categoria, Anno scolastico, Nome allegato, Data, Ultima modifica
- [x] `area-famiglie`: Categoria, Nome allegato, Data, Ultima modifica
- [x] `area-personale`: Categoria, Nome allegato, Data, Ultima modifica

### Decisioni strutturali confermate

- [x] Archivio `trasparenza`: disattivata ricerca testuale (`q`) per ridurre complessita e incompatibilita con la struttura attuale dei filtri
- [x] Tassonomia `contenutiammtrasp`: rinomina sezione in "01 Documentazione Trasparenza"

### Stato attività

- [x] Dismesso il post type `attivita` e relativi template/archivi: la gestione passa a una pagina dedicata
- [x] Creato menu admin dedicato `Attività` che porta all'editor della pagina omonima
- [x] Introdotto repeater ACF per sezioni attività con titolo, didascalia e gallery immagini
- [x] Aggiunto fallback alt immagini: `bambini che fanno attività scolastica: [titolo sezione]`
- [ ] Popolare la nuova pagina `Attività` con i primi contenuti reali e verificare resa frontend

---

## ✅ TEMA — Fase 4: Verifica

- [x] Creare e mantenere registro componenti accessibilita per supporto autocertificazione (`docs/REGISTRO-COMPONENTI-ACCESSIBILITA.md`)
- [x] **Checkpoint coerenza documentazione progetto**
  - [x] Riallineare `README.md` allo stato reale (evitare note placeholder)
  - [x] Verificare coerenza tra `TODO.md`, `PROGETTO.md` e implementazione corrente (template legali, paginazione, seed pagine)
  - [x] Aggiornare data ultimo allineamento documentale e log breve delle discrepanze risolte
  - [x] Data allineamento: 15/06/2026
  - [x] Log discrepanze risolte: template legali unificati su `templates/page-legale.php`; archivio trasparenza riallineato a `archive-area-burocratica-common.php`; riferimento JS header corretto a `assets/js/site-header.js`; rimosse note placeholder da `README.md`
- [ ] **Revisione pagine obbligatorie generate dal seed** — rileggere i testi di Privacy Policy, Cookie Policy, Dichiarazione Accessibilità, Whistleblowing e aggiornare se necessario (normativa, fallback contatto privacy, DPA Google, shortcode CookieYes, link AGID)
- [ ] Verifica manuale migrazione v3 pagine legali gia' seedate nel pannello admin
- [ ] Audit accessibilità con axe-core
- [ ] Audit Lighthouse (performance + accessibility)
- [ ] Navigazione completa da tastiera (tab, enter, escape)
- [ ] Verifica HTML valido (W3C validator)
- [ ] Test screen reader (VoiceOver su macOS)
- [ ] Preparare e pubblicare dichiarazione di accessibilità finale quando struttura, contenuti e feedback channel sono stabili
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
- [ ] Configurare DNS whistleblowing (default `segnalazioni.centroservizi.it`, oppure dedicato scuola su richiesta)
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

---

## Aggiornamento sessione (16/06/2026) — Audit QA e pulizia pagine legali

### Completato

- [x] Verificata navigazione da tastiera (skip links, tab order, focus): **OK**
- [x] Verificata struttura HTML base (skip links, main landmark, headings, viewport, lang): **OK**
- [x] Pagine legali seedate verificate in admin:
  - [x] Privacy Policy: fallback DPO attivo (Centro Servizi) ✅
  - [x] Cookie Policy: shortcodes presenti (`[cookie_declaration]`, `[cky-preference-link]`) ✅
  - [x] Dichiarazione Accessibilità: **rimosso duplicato "Referente privacy"** (linea seed in `inc/settings.php`)
  - [x] Whistleblowing: struttura ok (completamento link/responsabile dipende da setup GlobaLeaks cliente)
- [x] Corretto fallback link footer "Amministrazione Trasparente": `/amministrazione-trasparente/` → `/trasparenza/`
- [x] Verificate immagini senza alt:
  - Hero: bg aria-hidden (ok), logo SVG con aria-label (ok)
  - Sezione highlights: alt presente ("Insegnante con bambini...")
  - Header logo: custom logo di WP (non toccare per ora — placeholder)

### Da fare prima di go-live

- [ ] **Audit contrasti WCAG AA** su pagine legali e burocratiche (area-famiglie, area-personale, trasparenza)
- [ ] **Completamento pagine obbligatorie lato cliente**:
  - [ ] Whistleblowing: link piattaforma GlobaLeaks + responsabile canale interno
  - [ ] Dichiarazione Accessibilità: compilare su form.agid.gov.it (link generico)
  - [ ] Obiettivi Accessibilità: compilare obiettivi annuali 2026
- [ ] **Alt immagini**: sistemare logo header (per ora placeholder)
- [ ] **Rifiniture non bloccanti**: copy "Chi siamo", micro-polish contatti, vetrina attività

### Note

- Fallback DPO nella privacy policy funziona correttamente (Centro Servizi Scuole In Rete)
- Cookie Policy usa shortcodes — verificare attivazione banner CookieYes su istanze reali
- Whistleblowing URL fallback attualmente vuoto: compilare nelle impostazioni sito quando GlobaLeaks è pronto

---

## 🔮 FASE FUTURA — Dopo il lancio

- [ ] CSS vero (variabili, componenti, responsive, colori, tipografia)
- [ ] JS — menu hamburger mobile (`navigation.js`)
- [ ] JS — lightbox galleria
- [ ] JS — modale tabelle TablePress (schermo intero per leggibilità)
- [ ] JS — filtri ricerca interattivi
- [ ] Calendario eventi (CPT `evento` + vista calendario)
- [ ] Possibile unificazione Area Famiglie + Area Personale in CPT unico con tassonomia flag
