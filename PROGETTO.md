# Tema WordPress "Centro Servizi" — Documento di Progetto

> Ultimo allineamento documentale: 18/06/2026

## 1. Overview

Tema WordPress classico per siti web di **scuole paritarie d'infanzia**. Leggero, zero dipendenze esterne, pienamente accessibile secondo normativa italiana.

- **PHP**: 8.1+
- **WordPress**: 6.4+
- **Lingua**: Solo italiano (no i18n)
- **Build system**: Nessuno — file CSS/JS diretti, organizzati in cartelle
- **CSS framework**: Nessuno — CSS debug minimale (bordi, padding, basta)
- **JS**: Presente solo JS minimale in `assets/js/site-header.js` (menu mobile accessibile)
- **Sistema metabox**: ACF Free

---

## 2. Plugin Previsti (esterni al tema)

| Plugin             | Ruolo                          |
|--------------------|--------------------------------|
| ACF Free           | Custom fields per CPT e pagine |
| Classic Editor     | Editor classico                |
| Contact Form 7     | Form (whistleblowing futuro)   |
| CookieYes          | Cookie consent GDPR            |
| Site Kit by Google | Analytics/Search Console       |
| TablePress         | Tabelle dati                   |
| UpdraftPlus        | Backup                         |
| Wordfence Security | Sicurezza                      |
| Yoast SEO          | SEO                            |

Il tema **non dipende** da nessun plugin per funzionare. ACF è richiesto per la gestione dei campi custom, ma il tema deve degradare gracefully se ACF non è attivo (mostrando messaggio admin).

---

## 3. Accessibilità — Requisiti Vincolanti

### 3.1 Standard di riferimento

- **Legge Stanca** (L. 4/2004, aggiornata D.Lgs 106/2018)
- **WCAG 2.1 livello AA** (tutti i criteri)
- **Linee Guida AgID** per l'accessibilità dei siti web della PA e soggetti obbligati

Le scuole paritarie rientrano tra i soggetti obbligati dal D.Lgs 106/2018.

### 3.2 Requisiti tecnici da implementare nel tema

| #   | Requisito                   | Implementazione                                                                                            |
|-----|-----------------------------|------------------------------------------------------------------------------------------------------------|
| A1  | **Skip links**              | Link "Vai al contenuto principale", "Vai alla navigazione", "Vai al footer" — visibili al focus            |
| A2  | **Landmark ARIA**           | `<header role="banner">`, `<nav role="navigation">`, `<main role="main">`, `<footer role="contentinfo">`   |
| A3  | **Heading gerarchici**      | Un solo `<h1>` per pagina, gerarchia sequenziale senza salti                                               |
| A4  | **Focus visibile**          | Outline visibile su tutti gli elementi interattivi, mai `outline: none` senza alternativa                  |
| A5  | **Contrasto colore**        | Minimo 4.5:1 testo normale, 3:1 testo grande (verificato con audit dedicato del 18/06/2026)                |
| A6  | **Testo ridimensionabile**  | Layout in `rem`/`em`, nessun testo in px fissi, funzionale fino a 200% zoom                                |
| A7  | **Alt text immagini**       | Campo alt obbligatorio su tutte le immagini; decorative → `alt=""` + `role="presentation"`                 |
| A8  | **Navigazione da tastiera** | Tab order logico, tutti i controlli raggiungibili da tastiera, nessun keyboard trap                        |
| A9  | **Form accessibili**        | Ogni input ha `<label>` associato, errori annunciati con `aria-live`, `aria-describedby` per istruzioni    |
| A10 | **Tabelle accessibili**     | `<caption>`, `<th scope>`, `<thead>/<tbody>` su tutte le tabelle dati                                      |
| A11 | **Link significativi**      | No "clicca qui", testo del link descrive la destinazione. Link che aprono nuove finestre: avviso esplicito |
| A12 | **Lingua della pagina**     | `<html lang="it">` sempre presente                                                                         |
| A13 | **Documenti scaricabili**   | Formato e peso file indicati nel link: es. "Regolamento (PDF, 245 KB)"                                     |
| A14 | **Breadcrumb**              | Custom nel tema con `<nav aria-label="Breadcrumb">` e `<ol>` strutturato con `aria-current="page"`         |
| A15 | **Responsive**              | Mobile-first, usabile con screen reader su mobile                                                          |
| A16 | **Riduzione movimento**     | Rispettare `prefers-reduced-motion` per animazioni/carosello                                               |

Stato A5 aggiornato (18/06/2026): audit contrasto eseguito e fix principali applicati. Tracciamento operativo in `docs/CONTRASTO-AUDIT-2026-06-18.md` e `TODO.md`.

### 3.3 Dichiarazione di Accessibilità (obbligatoria)

La **Dichiarazione di Accessibilità** è un documento obbligatorio per tutti i soggetti sottoposti alla normativa (incluse scuole paritarie). Va compilata e pubblicata sul portale AgID, e il sito deve contenere un link nel footer che rimanda a questa dichiarazione.

**Cosa facciamo nel tema:**
- Template legale condiviso `templates/page-legale.php` applicato agli slug legali riservati
- Link automatico nel footer alla pagina (se esiste una pagina con slug `dichiarazione-accessibilita`)
- **Meccanismo di feedback accessibilità**: link nel footer a un Google Form esterno (soddisfa il requisito AgID senza introdurre form nel sito)
- È possibile dichiarare **"parzialmente conforme"** su form.agid.gov.it specificando le non conformità note e le azioni correttive previste con tempistiche

### 3.4 Contatti privacy e fallback operativi

- Il tema gestisce i contenuti privacy in modo dinamico con shortcode e campi amministrabili
- Se il DPO e' presente, viene mostrato il suo contatto
- Se il DPO non e' compilato, il tema mostra il legale rappresentante
- Se anche il legale rappresentante non e' disponibile, viene mostrato il contatto Centro Servizi configurato
- Questa e' una scelta operativa di template; la validazione giuridica del caso concreto resta esterna al codice

---

## 4. Architettura del Tema

### 4.1 Struttura cartelle

```
tema-centro-servizi/
├── style.css                          # Header tema WP + CSS debug unico
├── functions.php                      # Bootstrap: carica moduli da /inc
├── index.php                          # Fallback obbligatorio
├── screenshot.png                     # Screenshot tema
│
├── templates/                         # Template gerarchici WP
│   ├── front-page.php                 # Homepage
│   ├── single-attivita.php            # Singola attività
│   ├── archive-attivita.php           # Archivio attività
│   ├── archive-area-burocratica-common.php # Archivio comune: trasparenza/famiglie/personale
│   ├── archive-area-famiglie.php      # Archivio area famiglie
│   ├── archive-area-personale.php     # Archivio area personale
│   ├── single-area-burocratica.php    # Singolo area famiglie/personale
│   ├── page.php                       # Pagina generica (include recapiti se slug = contatti)
│   ├── page-legale.php                # Template legale condiviso per slug riservati
│   ├── page-la-nostra-scuola.php      # Pagina custom scuola
│   ├── page-attivita-speciali.php     # Pagina custom attivita speciali
│   ├── search.php                     # Risultati ricerca
│   └── 404.php                        # Pagina errore
│
├── partials/                          # Componenti riutilizzabili
│   ├── header.php                     # Header + nav
│   ├── footer.php                     # Footer
│   ├── breadcrumb.php                 # Breadcrumb accessibile
│   ├── skip-links.php                 # Skip links accessibilità
│   ├── search-form.php                # Form ricerca
│   ├── card-attivita.php              # Card attività per archivi
│   ├── card-trasparenza.php           # Card documento amm. trasparente
│   ├── card-area-famiglie.php         # Card contenuto area famiglie
│   ├── card-area-personale.php        # Card contenuto area personale
│   ├── page-contatti-recapiti.php     # Blocco recapiti/mappa per pagina contatti
│   └── pagination.php                 # Paginazione accessibile
│
├── inc/                               # Logica PHP modulare
│   ├── setup.php                      # Theme supports, menus, sidebars
│   ├── enqueue.php                    # Registrazione CSS
│   ├── cpt-attivita.php               # CPT Attività
│   ├── cpt-trasparenza.php            # CPT Amm. Trasparente
│   ├── cpt-area-famiglie.php          # CPT Area Famiglie
│   ├── cpt-area-personale.php         # CPT Area Personale
│   ├── taxonomies.php                 # Tassonomie custom
│   ├── acf-fields.php                 # Registrazione gruppi ACF via PHP
│   ├── search.php                     # Filtri ricerca custom
│   ├── accessibility.php              # Helpers accessibilità
│   └── admin.php                      # Personalizzazioni admin
│
└── assets/
   ├── css/                           # site.css + layer header/footer/legale/area-burocratica
   └── js/site-header.js              # Toggle menu mobile accessibile
```

### 4.2 Approccio template

Usiamo la **template hierarchy nativa** di WordPress. I file in `templates/` vengono caricati tramite il filtro `template_include` in `functions.php` (WP non cerca automaticamente in sottocartelle per i temi classici).

I `partials/` vengono inclusi con `get_template_part()`.

---

## 5. Custom Post Types

### 5.1 CPT: `attivita` — Attività

Le attività della scuola (laboratori, feste, uscite, ecc.)

| Campo                | Tipo      | Note                                         |
|----------------------|-----------|----------------------------------------------|
| Titolo               | WP nativo | —                                            |
| Contenuto            | WP editor | Editor classico (supporta gallery shortcode) |
| Immagine in evidenza | WP nativo | Thumbnail del post                           |

**Tassonomie** (tutte gerarchiche):

| Tassonomia           | Slug                 | Note                         |
|----------------------|----------------------|------------------------------|
| Anno scolastico att. | `anno-scol-attivita` | Es. "2025/2026", "2024/2025" |
| Sezione              | `sezioni`            | Es. "Coccinelle", "Farfalle" |

**Archivio**: lista con card. Filtrabile per anno scolastico e sezione.

**Card attività (`partials/card-attivita.php`):**
```
[ Titolo (h2 link)                                  ]
[ Anno scolastico: 2025/2026 | Sezione: Coccinelle  ]
[ Contenuto completo dall'editor classico            ]
[ (include gallery shortcode, testo, immagini, ecc.) ]
[ Pubblicato: 10/03/2026 | Modificato: 12/03/2026   ]
```
- Titolo = titolo WP nativo
- Anno scolastico e sezione = termini delle tassonomie (se assegnati)
- Contenuto = `the_content()` completo — contiene tutto (testo, gallery, immagini)
- **Data pubblicazione + data ultima modifica** sempre visibili (obbligatorio per normativa)

### 5.2 CPT: `trasparenza` — Amministrazione Trasparente

Documenti pubblicati nella sezione Amministrazione Trasparente.

| Campo     | Tipo      | Note                                                              |
|-----------|-----------|-------------------------------------------------------------------|
| Titolo WP | WP nativo | Titolo interno (usato in admin)                                   |
| Contenuto | WP editor | Editor classico — testo libero + shortcode TablePress per tabelle |

**Campi ACF:**

| Campo     | Key ACF     | Tipo ACF            | Note                                                                   |
|-----------|-------------|---------------------|------------------------------------------------------------------------|
| Titolo    | `titolo`    | Testo               | Sottotitolo/etichetta da mostrare nel frontend                         |
| Tag anno  | `tag_anno`  | Testo               | Es. "2024", "2025" — etichetta anno                                    |
| Documento | `documento` | File (return Array) | Upload PDF/file, restituisce array con url/filename/filesize/mime_type |

**Tassonomie** (tutte gerarchiche):

| Tassonomia                 | Slug                | Note                            |
|----------------------------|---------------------|---------------------------------|
| Contenuti Amm. Trasparente | `contenutiammtrasp` | Categorizzazione ANAC (vedi §6) |
| Anno scolastico            | `annoscolastico`    | Per filtraggio temporale        |

**Card trasparenza (`partials/card-trasparenza.php`):**
```
[ Categoria Trasparenza + Tag anno  (h2)             ]  ← es. "04 Personale — 2025"
[ Sottotitolo ACF                                    ]  ← campo `titolo`
[ [Scarica documento (PDF, 245 KB)]                  ]  ← button, apre in blank
[ Testo dall'editor classico                         ]  ← the_content()
[ [Tabella TablePress inline]                        ]  ← shortcode nel contenuto
[ Pubblicato: 10/03/2026 | Modificato: 12/03/2026   ]
```

**Composizione titolo principale**: Termine tassonomia `contenutiammtrasp` (figlio) + " — " + campo `tag_anno`.
Es: se il documento è in "Organico" (figlio di "04 Personale") con tag_anno "2025" → heading = "Organico — 2025"

**Logica allegato nel template:**
- Se `documento` è compilato → bottone con nome file, formato e peso: es. "Scarica: Regolamento (PDF, 245 KB)"
- Link apre in `target="_blank"` con avviso accessibilità `<span class="sr-only">(apre in nuova finestra)</span>`

**Tabelle (TablePress):**
- Le tabelle vengono inserite tramite shortcode `[table id=X /]` nell'editor classico del post
- Problema attuale: in card ridotte le tabelle non sono responsive
- **Fase futura**: modale che apre la tabella a schermo intero per leggibilità (richiede JS — rimandato)
- Per ora: la tabella viene renderizzata inline nella card così com'è

**Date obbligatorie**: Data pubblicazione + data ultima modifica sempre visibili in ogni card (normativa trasparenza).

**Nessuna pagina singola** — i documenti trasparenza non hanno template single. Tutto il contenuto è visibile direttamente nelle card dell'archivio.

Impostazione CPT: `'publicly_queryable' => false`.

### 5.3 CPT: `area-famiglie` — Area Famiglie

Contenuti scarni destinati alle famiglie (circolari, modulistica, comunicazioni).

| Campo     | Tipo      | Note                                         |
|-----------|-----------|----------------------------------------------|
| Titolo WP | WP nativo | Titolo interno (usato in admin)              |
| Contenuto | WP editor | Editor classico — testo libero se necessario |

**Campi ACF:**

| Campo    | Key ACF    | Tipo ACF            | Note                                                                   |
|----------|------------|---------------------|------------------------------------------------------------------------|
| Testo    | `testo`    | Testo               | Titolo/etichetta da mostrare nel frontend                              |
| Allegato | `allegato` | File (return Array) | Upload PDF/file, restituisce array con url/filename/filesize/mime_type |

**Tassonomie**: nessuna.

**Card area-famiglie (`partials/card-area-famiglie.php`):**
```
[ Titolo (dal campo `testo`)                         ]
[ [Scarica allegato (PDF, 120 KB)]                   ]  ← se presente
[ Testo dall'editor classico                         ]  ← se presente
[ Pubblicato: 10/03/2026 | Modificato: 12/03/2026   ]
```

**Date obbligatorie**: Data pubblicazione + data ultima modifica sempre visibili.

**Archivio**: lista semplice di card.

### 5.4 CPT: `area-personale` — Area Personale

Contenuti destinati al personale. Stessa struttura identica di Area Famiglie.

| Campo     | Tipo      | Note                                         |
|-----------|-----------|----------------------------------------------|
| Titolo WP | WP nativo | Titolo interno (usato in admin)              |
| Contenuto | WP editor | Editor classico — testo libero se necessario |

**Campi ACF:**

| Campo    | Key ACF    | Tipo ACF            | Note                                                                   |
|----------|------------|---------------------|------------------------------------------------------------------------|
| Testo    | `testo`    | Testo               | Titolo/etichetta da mostrare nel frontend                              |
| Allegato | `allegato` | File (return Array) | Upload PDF/file, restituisce array con url/filename/filesize/mime_type |

**Tassonomie**: nessuna.

**Card**: identica a `card-area-famiglie.php` (stessa struttura).

**Date obbligatorie**: Data pubblicazione + data ultima modifica sempre visibili.

**Archivio**: identico ad area-famiglie.

> **Nota**: `area-famiglie` e `area-personale` sono identici come struttura. Possibile futura unificazione in un CPT unico con tassonomia flag ("Famiglie" / "Personale"). Per ora li teniamo separati per semplicità di gestione admin.

---

## 6. Amministrazione Trasparente — Struttura Tassonomia

Struttura basata sull'esperienza operativa delle scuole paritarie. Le sezioni e sotto-sezioni sono definite come **termini della tassonomia gerarchica `contenutiammtrasp`** (con termini genitori = sezioni principali e termini figli = sotto-sezioni).

L'admin seleziona la categoria direttamente dalla sidebar di WP (come le categorie standard) quando crea un documento `trasparenza`.

> **Nota**: Non è la struttura ANAC completa della PA, ma una struttura adeguata alle scuole paritarie d'infanzia. I contenuti richiesti vengono pubblicati in tassonomie comprensibili e navigabili.

### Sezioni (tassonomia `contenutiammtrasp`)

Slug parent → Slug figli:

1. **01 Documentazione Trasparente** (`01-documentaz-trasp`)
   - Circolari MIM (`circolari-mim`)
   - Normativa (`normativa`)

2. **02 Organizzazione** (`02-organizzazione`)
   - Organi Collegiali (`organi-collegiali`)
   - Organigramma (`organigramma`)
   - Organizzazione (`organizzazione`)

3. **03 Autorizzazioni** (`03-autorizzazioni`)
   - Autorizzazioni (`autorizzazioni`)
   - Patto Corresponsabilità (`patto-corresp`)

4. **04 Personale** (`04-personale`)
   - CCNL (`ccnl`)
   - Costi Personale (`costi-pers`)
   - Organico (`organico`)
   - Regolamento Interno Lavoratori (`r-i-l`)
   - Tassi Assenza (`tassi-ass`)

5. **05 Consulenti e Collaboratori** (`05-consulenti-e-collaboratori`)
   - Consulenti e Collaboratori Esterni (`consul-e-collab`)

6. **06 Bilanci** (`06-bilanci`)
   - Bilancio Consuntivo (`consuntivo`)
   - Bilancio Preventivo (`preventivo`)
   - Bilancio Sociale (`sociale`)

7. **07 Immobili** (`07-immobili`)
   - Immobili (`immobili`)

8. **08 Aiuti Economici** (`08-aiuti-economici`)
   - Contributi Pubblici (`contributi-pubblici`)
   - Incentivi per Occupazione (`incentivi-per-occupaz`)

9. **09 Orari e Calendario** (`09-orari-e-calendario`)
   - Calendario Scolastico (`calendario`)
   - Giornata Tipo (`giornata-tipo`)
   - Orari Funzionamento (`orari-funz`)

10. **10 Iscrizioni** (`10-iscrizioni`)
    - Moduli Iscrizione (`iscrizioni`)

11. **11 Servizi Erogati** (`11-servizi-erogati`)
    - Carta Servizi (`carta-servizi`)
    - PTOF (`ptof`)
    - Regolamento Interno Scuola (`regolamento-interno-scuola`)

12. **12 Controlli e Rilievi** (`12-controlli-e-rilievi`)
    - Verifiche Periodiche (`verifiche-periodiche`)

La sezione Amministrazione Trasparente usa l'archivio del CPT `trasparenza` con template condiviso `archive-area-burocratica-common.php`; la pagina con slug `amministrazione-trasparente` viene renderizzata dal template legale condiviso `page-legale.php`.

> I filtri archivio sono attivi; restano possibili solo rifiniture UX e tuning dei micro-copy.

---

## 7. Area Famiglie / Area Personale

Entrambe le sezioni sono archivi semplici dei rispettivi CPT.

### 7.1 Archivio

Lista di card con: titolo (dal campo ACF `testo`), link allegato con formato e peso file, eventuale testo dall'editor classico, data pubblicazione + data ultima modifica.

Nessuna landing page speciale — l'archivio WP nativo (`archive-area-famiglie.php` e `archive-area-personale.php`) è sufficiente.

> **Nota**: Evento/calendario completamente rimandato a fase futura lontana. Eventuale implementazione futura: flag su CPT area-famiglie "è evento?" → inserimento in calendario al giorno specificato. Navigazione calendario mese per mese. Ma per ora: nulla.

---

## 8. Homepage (`front-page.php`)

Struttura dall'alto verso il basso:

1. **Hero** — `<h1>` con nome sito (`bloginfo('name')`) + sottotitolo (`bloginfo('description')`)
2. **Chi siamo** — Breve testo (da pagina con slug `chi-siamo`, field excerpt o contenuto troncato) + link "Scopri di più"
3. **Attività recenti** — Ultimi 4-6 post `attivita` (lista semplice, niente scroll/carosello per ora)
4. **Contatti** — Rimando alla pagina Contatti dedicata (link + anteprima dati principali)
5. **I nostri servizi** — Blocco **hardcoded** nel template con testo per i servizi standard (non cambieranno mai)

---

## 8bis. Pagina Contatti (slug `contatti`, render da `page.php`)

La pagina Contatti non usa piu un template dedicato: passa dal template base `templates/page.php`.

Quando la pagina corrente ha slug `contatti`, `page.php` include la partial `partials/page-contatti-recapiti.php`.

I recapiti (lista + mappa) arrivano dagli helper condivisi:
- `centro_servizi_get_homepage_contacts()`
- `centro_servizi_get_homepage_map_embed_url()`

Vantaggi:
1. Coerenza visiva con tutte le pagine contenuto
2. Un solo punto di manutenzione per il layout pagina
3. Blocco recapiti riusabile e isolato

---

## 9. Navigazione

### 9.1 Menu locations

| Location  | Nome            | Note                                    |
|-----------|-----------------|-----------------------------------------|
| `primary` | Menu principale | Header, con supporto 2 livelli dropdown |
| `footer`  | Menu footer     | Link rapidi nel footer                  |

### 9.2 Header

```
[Skip links]
[Logo/Nome scuola] ............. [Menu principale] [Ricerca]
[Breadcrumb]
```

Menu responsive: hamburger accessibile sotto breakpoint tablet (JS minimale in `assets/js/site-header.js`).

### 9.3 Footer

```
[Menu footer]
[Ragione sociale | P.IVA | Codice Meccanografico]
[Contatti: indirizzo, tel, email, PEC]
[Link: Privacy | Cookie | Amm. Trasparente | Dichiarazione Accessibilità]
[Feedback accessibilità: "Segnala un problema di accessibilità" → link Google Form esterno]
[Powered by Centro Servizi]
[© Nome scuola — Anno]
```

Dati footer **hardcoded** nel template (non da ACF/opzioni). Aggiornamento manuale nel file.

---

## 10. Ricerca

Ricerca WP standard potenziata con filtri per tipo contenuto:

- Form in `partials/search-form.php` con checkboxes per filtrare: Pagine, Attività, Amm. Trasparente, Area Famiglie, Area Personale
- Filtro implementato in `inc/search.php` tramite hook `pre_get_posts`
- Risultati raggruppati per tipo contenuto con heading
- Ogni risultato mostra: titolo (link), excerpt, tipo, data

JS minimale in futuro per toggle pannello filtri (per ora filtri sempre visibili, niente JS).

---

## 11. Breadcrumb Custom

Implementato in PHP puro in `partials/breadcrumb.php`:

```html
<nav aria-label="Breadcrumb">
  <ol>
    <li><a href="/">Home</a></li>
    <li><a href="/amministrazione-trasparente/">Amministrazione Trasparente</a></li>
    <li><a href="/amministrazione-trasparente/?sezione=bilanci">Bilanci</a></li>
    <li><span aria-current="page">Bilancio consuntivo 2024</span></li>
  </ol>
</nav>
```

Logica: rileva il tipo di contenuto e costruisce il percorso appropriato. Per le pagine segue la gerarchia parent/child.

---

## 12. Strategia CSS — Regole Operative (18 maggio 2026)

Obiettivo: tema pulito, uniforme, leggibile, con meno varianti possibili.

**Regole base CSS:**
- CSS il più pulito possibile, riducendo al minimo eccezioni e componenti diversi tra loro.
- Spaziatura uniforme tra blocchi e loop: usare un solo valore base di gap/padding verticale per quasi tutto il frontend.
- Evitare micro-variazioni non necessarie (niente margini "ad hoc" per ogni sezione).
- Mantenere markup essenziale: meno wrapper possibili, niente div inutili.

**Larghezza contenuti (desktop):**
- Container contenuti principale fino a `max-width: 1500px`.
- Alcune sezioni possono restare full-width (bordo-bordo) solo quando serve davvero per il contenuto.

**Policy mobile (fase attuale):**
- Priorita: funzionamento, leggibilita, accesso ai dati da parte di utenti e autorita.
- Ottimizzazioni mobile avanzate (rifiniture estetiche e micro-layout) non sono prioritarie adesso.
- Restano comunque obbligatorie: assenza di overflow orizzontale, testi leggibili, navigazione e contenuti consultabili.

**Struttura minima loop Amm. Trasparente:**
- Wrapper elenco.
- Wrapper singolo elemento loop con: titolo, sottotitolo, contenuti vari, metadati.
- Niente annidamenti superflui oltre questi livelli.

Tutto resta centralizzato in `style.css` del tema (dopo l'header WP obbligatorio), evitando frammentazione in molti file salvo necessita reale.

---

## 13. Strategia JavaScript

**Fase attuale: nessun JS.** Tutto deve funzionare senza JavaScript.

JS verrà aggiunto nelle fasi successive per:
- Menu hamburger mobile
- Lightbox galleria
- Calendario eventi (modale)
- Filtri ricerca

Per ora ogni interazione avviene tramite link, form submission, e CSS puro.

---

## 14. Performance

| Obiettivo                       | Target  |
|---------------------------------|---------|
| HTML totale pagina              | < 50 KB |
| CSS totale                      | < 2 KB  |
| JS totale                       | 0 KB    |
| Richieste HTTP (senza immagini) | < 3     |
| No jQuery                       | ✓       |
| No web fonts esterne            | ✓       |
| No CDN                          | ✓       |

---

## 15. Ordine di Implementazione

### Sezioni obbligatorie per legge (da non saltare)

Queste pagine/sezioni **devono esistere** sul sito per evitare sanzioni:

| Sezione                     | Norma di riferimento                | Note                                                         |
|-----------------------------|-------------------------------------|--------------------------------------------------------------|
| Amministrazione Trasparente | D.Lgs 33/2013 + D.Lgs 106/2018      | Indice + archivio documenti per categoria                    |
| Dichiarazione Accessibilità | L. 4/2004 + D.Lgs 106/2018 + AgID   | Compilata su form.agid.gov.it, link nel sito                 |
| Obiettivi Accessibilità     | Linee Guida AgID                    | Pubblicazione entro 31 marzo ogni anno                       |
| Feedback accessibilità      | Linee Guida AgID                    | Meccanismo segnalazione (Google Form esterno)                |
| Privacy Policy              | GDPR (Reg. UE 2016/679)             | Pagina WP nativa + CookieYes                                 |
| Cookie Policy               | GDPR + Direttiva ePrivacy + Garante | Gestita da CookieYes                                         |
| Contatto DPO                | GDPR art. 37                        | Email DPO nella privacy policy e/o footer — 🔴 DA DEFINIRE   |
| Contatti / Dati legali      | Codice Civile + D.Lgs 196/2003      | Ragione sociale, P.IVA, sede, PEC, REA (se coop)             |
| Contributi L. 124/2017      | L. 124/2017 art.1 c.125             | Già pubblicati con TablePress — verificare campi obbligatori |
| Responsabile Trasparenza    | L. 190/2012 + ANAC                  | ✅ Già definito in organigramma                               |
| Whistleblowing              | D.Lgs 24/2023 (Dir. UE 2019/1937)   | 🔴 OBBLIGATORIO — link a piattaforma esterna (GlobaLeaks)    |

> **DPO**: Obbligatorio per tutte le scuole paritarie (trattamento dati minori su larga scala, art. 37 GDPR). Presumibilmente sarà Centro Servizi in qualità di DPO esterno. Da formalizzare con ogni cliente. Sul sito serve solo l'email di contatto.

> **Whistleblowing**: Obbligatorio per enti con 50+ dipendenti e/o che ricevono contributi pubblici. Un form semplice (CF7, Google Form) **NON è compliant** — serve crittografia, anonimato, codice tracciamento. Soluzione raccomandata: **GlobaLeaks** (open source, self-hosted su VM Linux dedicata, anche su Oracle Cloud, usato da ANAC stessa). Sul sito serve link + pagina esplicativa. Vedi `docs/OBBLIGHI-LEGALI.md` e `docs/WHISTLEBLOWING-ORACLE-CLOUD.md`.

### Fase 1 — Struttura base e CPT (priorità: fondamenta)
1. `style.css` (header WP + CSS debug) + `functions.php` + `index.php`
2. `inc/setup.php` — theme supports, menu locations, commenti disabilitati
3. `inc/enqueue.php` — registrazione style.css
4. `inc/cpt-attivita.php` + `inc/cpt-trasparenza.php` + `inc/cpt-area-famiglie.php` + `inc/cpt-area-personale.php`
5. `inc/taxonomies.php` — `anno-scol-attivita`, `sezioni`, `contenutiammtrasp`, `annoscolastico`
6. `inc/acf-fields.php` — tutti i gruppi campi ACF
7. `partials/skip-links.php` + `partials/header.php` + `partials/footer.php`
8. `partials/breadcrumb.php`
9. `templates/page.php` + `templates/index.php` + `templates/404.php`

### Fase 2 — Pagine legali e funzionali (priorità: compliance)
10. `templates/page-legale.php` — template condiviso per tutte le pagine legali a slug riservato
11. `partials/card-trasparenza.php` + `templates/archive-area-burocratica-common.php`
12. `templates/page.php` + `partials/page-contatti-recapiti.php` (pagina `contatti`)
13. `templates/front-page.php`

### Fase 3 — Template contenuti (priorità: funzionalità)
15. `templates/single-attivita.php` + `partials/card-attivita.php`
16. `templates/archive-attivita.php`
17. `templates/archive-area-famiglie.php` + `partials/card-area-famiglie.php`
18. `templates/archive-area-personale.php` + `partials/card-area-personale.php`
19. `partials/pagination.php`
20. `inc/admin.php` — colonne admin personalizzate
21. `inc/search.php` + `partials/search-form.php` + `templates/search.php`

### Fase 4 — Verifica
22. Audit accessibilità con axe-core / Lighthouse
23. Verifica navigazione da tastiera
24. Verifica HTML valido

### Fase futura — Estetica, JS, Whistleblowing
- CSS vero (variabili, componenti, responsive)
- JS (menu mobile, lightbox, filtri ricerca)
- Calendario eventi (CPT `evento` + vista calendario)
- Whistleblowing (valutazione piattaforma + integrazione link/form)

---

## 16. Note Tecniche

### 16.1 ACF Free — Campi in uso

Tutti i campi sono di tipo base (Testo o File) — nessun campo avanzato richiesto.
I campi File usano `return_format => 'array'` per avere accesso a url, filename, filesize e mime_type.

| CPT              | Campo ACF   | Tipo           | Return format |
|------------------|-------------|----------------|---------------|
| `trasparenza`    | `titolo`    | Text           | —             |
| `trasparenza`    | `tag_anno`  | Text           | —             |
| `trasparenza`    | `documento` | File           | Array         |
| `area-famiglie`  | `testo`     | Text           | —             |
| `area-famiglie`  | `allegato`  | File           | Array         |
| `area-personale` | `testo`     | Text           | —             |
| `area-personale` | `allegato`  | File           | Array         |
| Pagina contatti  | (vari)      | Text/Email/URL | —             |

`attivita` non ha campi ACF — usa solo WP nativo (titolo, editor, featured image) + tassonomie.

**Uso nel template** (esempio per allegato):
```php
$file = get_field('documento');
if ($file) {
    $ext  = strtoupper(pathinfo($file['filename'], PATHINFO_EXTENSION));
    $size = size_format($file['filesize']);
    printf(
        '<a href="%s" target="_blank" rel="noopener">%s (%s, %s) <span class="sr-only">(apre in nuova finestra)</span></a>',
        esc_url($file['url']),
        esc_html($file['title']),
        esc_html($ext),
        esc_html($size)
    );
}
```

### 16.2 Template loading

WordPress classico non cerca template in sottocartelle. In `functions.php` registriamo un filtro su `template_include` che mappa i template dalla cartella `templates/`:

```php
add_filter('template_include', function($template) {
    $file = basename($template);
    $custom = get_template_directory() . '/templates/' . $file;
    return file_exists($custom) ? $custom : $template;
});
```

### 16.3 Deregistrazione jQuery

```php
add_action('wp_enqueue_scripts', function() {
    if (!is_admin()) {
        wp_deregister_script('jquery');
    }
});
```

### 16.4 Sicurezza base nel tema

- Escape di tutti gli output: `esc_html()`, `esc_attr()`, `esc_url()`, `wp_kses_post()`
- Nonce su qualsiasi form custom
- `current_user_can()` su qualsiasi azione admin
- No `eval()`, no `extract()`, no `$$var`

---

## 17. Decisioni Chiuse

| #   | Domanda                              | Decisione                                                                         |
|-----|--------------------------------------|-----------------------------------------------------------------------------------|
| D1  | CPT struttura                        | ✅ 4 CPT: `attivita`, `trasparenza`, `area-famiglie`, `area-personale`             |
| D2  | Galleria Attività                    | ✅ Gallery nativa editor classico (shortcode `[gallery]`)                          |
| D3  | Allegati Amm. Trasparente            | ✅ 1 campo ACF File (return Array) — accesso a url/filename/filesize/mime_type     |
| D4  | I "servizi" in homepage              | ✅ Hardcoded nel template                                                          |
| D5  | Pagina contatti                      | ✅ Pagina base `page.php` + partial `page-contatti-recapiti.php` (slug `contatti`) |
| D6  | ANAC: struttura tassonomia           | ✅ 12 sezioni dalla struttura operativa utente (non ANAC PA completa)              |
| D7  | CSS fase attuale                     | ✅ Debug puro: bordi 1px dashed, padding 5px, zero estetica                        |
| D8  | JS fase attuale                      | ✅ Nessun JS                                                                       |
| D9  | Calendario/eventi                    | ⏳ Rimandato a fase futura lontana                                                 |
| D10 | Area Famiglie/Personale unificazione | ⏳ Per ora separati (compatibilità siti esistenti)                                 |
| D11 | Form contatto                        | ✅ Nessun form contatto attivo. CF7 installato per uso futuro (whistleblowing)     |
| D12 | Feedback accessibilità               | ✅ Link a Google Form esterno (no form nel sito)                                   |
| D13 | Trasparenza pagina singola           | ✅ Nessuna — tutto in card archivio, `publicly_queryable => false`                 |
| D14 | Dati footer                          | ✅ Hardcoded nel template                                                          |
| D15 | Struttura ANAC obbligatoria?         | ✅ No per paritarie — basta pubblicare contenuti in modo comprensibile             |
| D16 | ACF File return format               | ✅ Array (non URL) — per avere peso e formato file negli allegati                  |
| D17 | Whistleblowing                       | 🔴 OBBLIGATORIO — GlobaLeaks self-hosted raccomandato. NO form semplice           |
| D18 | Image optimization                   | ✅ WP gestisce resize + srcset nativo. Upload in webp dal client                   |
| D19 | Modale tabelle TablePress            | ⏳ Fase futura (richiede JS) — per ora tabelle inline nella card                   |
| D20 | Contenuto editor nei CPT             | ✅ Tutti i CPT hanno WP editor per testo/shortcode. Attività: tutto lì dentro      |
| D21 | Date in ogni card                    | ✅ Data pubblicazione + data ultima modifica obbligatorie su tutte le card         |
| D22 | DPO                                  | 🔴 DA DEFINIRE — presumibilmente Centro Servizi come DPO esterno                  |
| D23 | Contributi L. 124/2017               | ✅ Già pubblicati con TablePress — verificare tutti i campi obbligatori            |
| D24 | Obiettivi accessibilità              | ❌ Da aggiungere — pagina/sezione dedicata, pubblicazione annuale entro 31/3       |
| D25 | Responsabile Trasparenza             | ✅ Già definito in organigramma (sezione Amm. Trasparente)                         |
| D26 | 5x1000 rendiconto                    | ✅ Pubblicato con TablePress in tassonomia 08 Aiuti Economici                      |

---

## 18. Note su Template per Slug

Per le pagine speciali usiamo mapping da `functions.php` verso `templates/`: prima prova `templates/page-{slug}.php`, poi fallback unico `templates/page.php`.

Per `contatti` ora non esiste piu `page-contatti.php`: entra nel fallback `templates/page.php`, che include la partial recapiti solo quando lo slug e `contatti`.

Nessun custom field flag necessario — basta creare la pagina WP con lo slug corretto. Nella documentazione admin del tema indicheremo gli slug riservati:

| Slug pagina                   | Template applicato            |
|-------------------------------|-------------------------------|
| `amministrazione-trasparente` | `page-legale.php`             |
| `contatti`                    | `page.php` + partial recapiti |
| `dichiarazione-accessibilita` | `page-legale.php`             |

---

## 19. Scelte Globali del Tema

### 19.1 Post nativi WordPress
I post nativi (`post`) restano abilitati ma il tema non li tratta in modo speciale. In assenza di template dedicati usano il fallback della gerarchia tema (template base `templates/index.php`). Non sono nel menu di default.

### 19.2 Commenti
Disabilitati globalmente tramite `remove_post_type_support('post', 'comments')` e `remove_post_type_support('page', 'comments')` in `inc/setup.php`. Nessun CPT custom li supporta.

### 19.3 Sidebar
Nessuna sidebar. Layout mono-colonna su tutto il sito.

### 19.4 Image sizes custom
Definite in `inc/setup.php`:

| Nome             | Dimensioni | Uso                               |
|------------------|------------|-----------------------------------|
| `card-thumbnail` | 400×300    | Card attività in archivio         |
| `gallery-medium` | 800×600    | Immagini galleria inline (futuro) |
| `hero-banner`    | 1200×400   | Eventuale immagine hero (futuro)  |

**Come funziona in WP:**
- Al momento dell'upload, WP genera automaticamente tutte le dimensioni registrate (crop incluso)
- Dal WP 4.4+, le immagini nel frontend hanno attributo `srcset` automatico — il browser carica la dimensione appropriata
- Dal WP 5.3+, le immagini grandi vengono ridimensionate a max 2560px ("big image threshold")
- Le immagini caricate in **webp** vengono gestite nativamente da WP. Caricare già in webp dal client è la strategia ottimale
- Nessun plugin di ottimizzazione immagini necessario se si carica già in webp

### 19.5 Favicon / Site Icon
Gestita tramite Customizer nativo WP (`site_icon`). Nessuna logica custom.

### 19.6 RSS Feed
Lasciato attivo di default (WordPress lo gestisce).

### 19.7 Privacy / Cookie Policy
Gestiti da CookieYes (cookie banner) e dalla funzionalità nativa WP "Pagina Privacy" (`Settings → Privacy`). Nessun template custom necessario.

**Approccio minimalista**: Zero cookie propri del tema. Nessun tracciamento. CookieYes gestisce solo i cookie tecnici strettamente necessari + eventuali analytics (Site Kit). Commenti disabilitati ovunque per evitare qualsiasi problema di account utente, email, cookie di sessione e consensi aggiuntivi.
