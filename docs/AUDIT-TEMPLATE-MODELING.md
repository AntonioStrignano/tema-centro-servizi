# Audit template, modeling e rendering

Data audit: 25 maggio 2026
Obiettivo: individuare dove sta il caos (mayo), come viene renderizzato ogni contesto e dove esistono riferimenti duplicati/conflittuali.

Riferimento di governance correlato:
- docs/CONTRATTO-HEADER-UNICO.md

## 1) Mappa routing reale (chi decide il template)

Sorgente: functions.php

Flusso:
1. Filtro template_include decide il template in templates/.
2. Priorita hardcoded: front-page, 404, archivi CPT, single attivita, pagine legali, pagine slug-specifiche, fallback page.php.

Evidenze:
- Filtro template_include: functions.php:33
- Slug pagine legali: functions.php:35-46
- Match pagine legali prima dello slug-specifico: functions.php:106-115
- Match template page-slug dopo legali: functions.php:117-131

Impatto:
- Se uno slug e nella lista legale, il relativo template page-slug non viene mai usato.

## 2) Mappa rendering (cosa viene stampato dove)

### Contratto standard (quasi tutti i template)

Catena:
1. template chiama partials/header.
2. partials/header stampa head/body e include:
   - wp_head
   - CSS tema via centro_servizi_get_theme_stylesheets
   - skip links
   - chrome header
   - breadcrumb
3. template stampa il main.
4. template chiama partials/footer.
5. partials/footer stampa footer + wp_footer + chiusura body/html.

Evidenze:
- partials/header: partials/header.php:12-21
- partials/footer: partials/footer.php:171-173

### Contratto speciale front-page

Catena:
1. front-page stampa direttamente html/head/body.
2. front-page chiama wp_head.
3. front-page stampa CSS tema via centro_servizi_get_theme_stylesheets.
4. front-page include solo partials/chrome-header (non partials/header).
5. front-page include partials/footer per chiusura documento.

Evidenze:
- Front manuale: templates/front-page.php:8-19
- CSS tema iniettati manualmente: templates/front-page.php:14-16
- Skip link custom con id diverso: templates/front-page.php:134
- Main id main-content: templates/front-page.php:137
- Chiusura tramite partials/footer: templates/front-page.php:327

## 3) Mappa modeling (da dove arrivano i dati)

### Modeling centralizzato (buono)

Sorgente: inc/homepage.php
- titolo/sottotitolo home da option
- mappa da option con fallback su contatto address
- normalizzazione contatti con icone/href
- ultimo documento trasparenza per slug tassonomia

Evidenze:
- inc/homepage.php:8-149

### Modeling duplicato nel layer template (rischio conflitto)

1. Front-page legge molte option gia lette in footer e ricostruisce mappe contatti/legale.
- templates/front-page.php:28-85
- partials/footer.php:8-80

2. Page contatti rifa parsing JSON contatti e fallback mappa, invece di usare helper centrali.
- templates/page-contatti.php:12-41
- Logica simile gia esistente in inc/homepage.php:22-108

3. Footer e front-page duplicano stessa logica legale/contatti.
- templates/front-page.php:34-80
- partials/footer.php:13-75

## 4) Mappa iniezioni (asset e hook)

### Iniezione CSS base tema

Sorgente funzione: inc/debug.php
- decide CSS base: site.css, css-debug (condizionale), area-burocratica (condizionale), site-header.css, site-footer.css

Evidenze:
- Definizione: inc/debug.php:541-593
- Invocata in header: partials/header.php:13-15
- Invocata in front-page: templates/front-page.php:14-16

Nota:
- In produzione e debug convivono nella stessa funzione di risoluzione CSS.

### Iniezione CSS/JS Stitch homepage

Sorgente: inc/enqueue.php
- enqueue Stitch solo in front-page
- dequeue esplicito fuori home o in contesto burocratico

Evidenze:
- Hook enqueue: inc/enqueue.php:8
- Regola contesto: inc/enqueue.php:19-27
- Enqueue home assets: inc/enqueue.php:30-57

### Iniezione debug UI runtime

Sorgente: inc/debug.php
- pulsante floating in wp_footer

Evidenze:
- Hook e render: inc/debug.php:628-646

## 5) Conflitti e punti di mayo (ordinati per severita)

## Bloccanti logici

1. Template potenzialmente irraggiungibile per priorita routing
- Problema: lo slug la-nostra-scuola e in lista pagine legali, quindi passa su page-legale prima di page-la-nostra-scuola.
- Evidenze:
  - functions.php:44
  - functions.php:106-115
  - functions.php:117-124
  - templates/page-la-nostra-scuola.php:1-81
- Effetto: template custom presente ma non usato in scenario normale.

2. Fallback 404 non sicuro in due template custom
- Problema: i template chiamano get_header all'inizio, ma nel ramo else includono templates/404 che a sua volta include header/footer completi; poi chiamano anche get_footer.
- Evidenze:
  - templates/page-attivita-speciali.php:8, 77-81
  - templates/page-la-nostra-scuola.php:8, 77-81
  - templates/404.php:8, 20
- Effetto: in caso di no posts, rischio output duplicato (head/body/footer duplicati).

## Conflitti alti

3. Breadcrumb duplicato su alcune pagine
- Problema: header lo stampa sempre e alcuni template lo ristampano nel main.
- Evidenze:
  - partials/header.php:21
  - templates/page-contatti.php:50
  - templates/page-attivita-speciali.php:17
  - templates/page-la-nostra-scuola.php:17
- Effetto: doppio blocco breadcrumb e possibile rumore per screen reader.

4. Modeling duplicato tra front-page e footer
- Problema: entrambe le viste rileggono stesse option legali/contatti e ricreano trasformazioni.
- Evidenze:
  - templates/front-page.php:30-80
  - partials/footer.php:10-75
- Effetto: rischio divergenza output e bug incoerenti dopo modifiche future.

5. Modeling contatti duplicato in page-contatti
- Problema: parsing JSON contatti e fallback mappa ricostruiti localmente, mentre esistono helper.
- Evidenze:
  - templates/page-contatti.php:12-30
  - inc/homepage.php:22-38, 40-108
- Effetto: doppia manutenzione e output diversi a seconda della pagina.

6. Contratto rendering non uniforme tra front-page e resto tema
- Problema: front-page gestisce html/head/body in proprio, resto tema passa da partials/header.
- Evidenze:
  - templates/front-page.php:8-19
  - partials/header.php:7-21
- Effetto: due pipeline da mantenere (piu rischio regressioni).

## Conflitti medi

7. Skip link id non allineato tra front-page e convenzione globale
- Problema: front usa #main-content, resto tema e skip links globali lavorano con #contenuto-principale.
- Evidenze:
  - templates/front-page.php:134, 137
  - inc/accessibility.php:10
- Effetto: standard non uniforme.

8. Logica dominio pesante dentro template archivio trasparenza
- Problema: blueprint categorie, alias, ricerca estesa e query composita sono nello stesso file template.
- Evidenze:
  - templates/archive-trasparenza.php:1-240
  - templates/archive-trasparenza.php:390-532
- Effetto: difficile isolare bug di modeling da bug di rendering.

## 6) Dove viene renderizzato cosa (vista sintetica)

1. Archivi attivita/famiglie/personale/trasparenza
- Wrapper: partials/header + partials/footer
- Query: WP_Query locale nel template
- Card: partial dedicata
- Rischio: basso-medio, tranne trasparenza (alta complessita)

2. Pagine standard e legali
- Wrapper: partials/header + partials/footer
- Contenuto: loop page standard + the_content
- Rischio: basso

3. Pagine custom slug (attivita speciali, la nostra scuola, contatti)
- Wrapper misto
- Dati ACF o option dirette
- Rischio: medio-alto per duplicazioni

4. Front-page
- Wrapper speciale manuale
- Dati da helper + option dirette duplicate
- Rischio: alto (doppia pipeline)

## 7) Registro tecnico pre-refactor (da usare come backlog)

Priorita P0:
1. Correggere conflitto routing la-nostra-scuola (template raggiungibile o rimozione file morto).
2. Correggere fallback 404 nei template custom senza doppio header/footer.
3. Eliminare breadcrumb duplicati nei 3 template coinvolti.

Priorita P1:
1. Unificare modeling legale/contatti (fonte unica, riuso helper).
2. Ridurre duplicazione page-contatti usando helper condivisi.

Priorita P2:
1. Stabilizzare contratto front-page senza introdurre nuove eccezioni.
2. Estrarre logica dominio di archive-trasparenza in helper dedicati.

## 8) Risposta secca alla domanda iniziale

Si, c'e mayo concentrata ma non ovunque.
Il caos vero e in:
1. routing pagine speciali/legali
2. duplicazioni modeling option
3. front-page pipeline speciale
4. template trasparenza troppo carico

Il resto e recuperabile con refactor progressivo senza riscrittura totale.
