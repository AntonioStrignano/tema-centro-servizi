# Contratto Header Unico

Data: 25 maggio 2026
Stato: approvazione operativa
Scopo: definire una volta per tutte ruolo, contenuto e confini dell'header per tutto il tema.

## 1) Principio guida

Header unico per tutto il sito.

Significa:
1. Una sola pipeline di rendering dell'header.
2. Una sola sorgente dati per header.
3. Nessun template puo reinventare header, skip link o breadcrumb.

## 2) Cosa deve contenere l'header

Elementi obbligatori:
1. Struttura documento tecnica iniziale:
   - apertura html
   - head base
   - wp_head
   - apertura body
   - wp_body_open
2. Skip links accessibili.
3. Header chrome (brand, utility, navigazione, CTA contatti).
4. Breadcrumb, ma solo se la pagina non e in contesto home.

Elementi opzionali condizionati:
1. Utility phone/email: solo se presenti nei dati contatto.
2. CTA contatti: priorita pagina contatti, fallback mail, fallback telefono.

## 3) Cosa NON deve contenere l'header

1. Query business di contenuti editoriali (attivita, trasparenza, ecc.).
2. Logica legale/footer.
3. Logica specifica della homepage (hero, sezioni speciali, partner, ecc.).
4. Logica pagina-specifica di filtri archivi.
5. Duplicazioni di blocchi gia stampati dall'header (esempio breadcrumb ristampato nel template).

## 4) Responsabilita per file

Ruoli target:
1. partials/header.php:
   - unico entrypoint header globale
   - include skip-links, chrome-header, breadcrumb condizionale
2. partials/chrome-header.php:
   - solo markup header visuale + dati normalizzati gia pronti
3. partials/skip-links.php:
   - solo markup skip links
4. templates/*.php:
   - non stampano breadcrumb se gia gestito in header
   - non stampano html/head/body

## 5) Contratto dati (modeling)

Sorgenti dati consentite per header:
1. Nome sito e descrizione da blog info.
2. Contatti da helper unificato contatti.
3. URL pagina contatti da pagina dedicata.

Regole modeling:
1. Il template non legge option raw per rifare la logica header.
2. La normalizzazione contatti (label/href/fallback) e centralizzata.
3. Nessuna trasformazione duplicata tra header e altri layer.

## 6) Regole di rendering per contesto

Matrice:
1. Homepage:
   - usa header unico
   - no breadcrumb
2. Pagine interne standard:
   - usa header unico
   - breadcrumb on
3. Pagine legali/burocratiche:
   - usa header unico
   - breadcrumb on
4. Archivi CPT:
   - usa header unico
   - breadcrumb on

## 7) Accessibilita minima obbligatoria

1. Presenza landmark banner sul blocco header.
2. Skip links sempre presenti e funzionanti.
3. Target skip link main coerente con id contenuto-principale.
4. Navigazione principale con id navigazione-principale e aria-label esplicita.
5. Focus visibile su utility link, nav link e CTA.
6. Link esterni marcati con testo per screen reader quando aprono nuova finestra.

## 8) Responsive minimo obbligatorio (vincolo progetto)

1. Menu header collassabile ad hamburger.
2. Bottone hamburger accessibile:
   - aria-expanded
   - aria-controls
   - etichetta chiara
3. Apertura/chiusura menu senza rompere tab order.
4. Nessuna dipendenza pesante extra.

## 9) Regole anti-conflitto (hard rules)

1. Vietato richiamare partials/breadcrumb dentro template che usano partials/header.
2. Vietato stampare html/head/body dentro template content quando esiste header globale.
3. Vietato duplicare letture option contatti per costruire utility header.
4. Ogni eccezione deve essere documentata in docs prima di essere introdotta.

## 10) Definition of Done per l'header unico

Header considerato stabilizzato quando:
1. Tutti i template passano da partials/header.
2. Zero breadcrumb duplicati nel DOM.
3. Zero template che aprono html/head/body fuori dal contratto.
4. Home inclusa nel contratto unico (con eccezione esplicita solo se documentata).
5. Hamburger menu operativo su mobile.

## 11) Piano di adozione in 3 micro-step

Step A:
1. Rimuovere duplicazioni breadcrumb nei template interni.
2. Eliminare fallback che puo doppiare header/footer.

Step B:
1. Spostare front-page sotto lo stesso contratto header (senza toccare contenuti hero).
2. Unificare id skip link e main.

Step C:
1. Consolidare modeling header in helper unico.
2. Rifinire hamburger e test manuale veloce su mobile.

## 12) Decisioni aperte da chiudere prima implementazione

1. Homepage nel primo giro entra subito nel contratto unico header oppure nel secondo giro?
2. Breadcrumb in home: confermato sempre off?
3. Utility topbar: resta visibile su mobile o si riduce a icone/solo CTA?

## 13) Proposta operativa (25 maggio 2026)

Per sbloccare la bonifica senza introdurre regressioni inutili, proposta default:

1. Homepage entra nel contratto unico in due tempi:
   - giro 1: mantiene shell dedicata ma allinea skip link, id main e punti di aggancio header/footer
   - giro 2: converge sulla stessa pipeline tecnica degli altri template
2. Breadcrumb in home: confermato sempre off.
3. Utility topbar su mobile: ridotta a CTA principale (Contatti) + menu hamburger; contatti completi restano nel pannello menu o nel footer.

Motivazione:
1. Riduce subito il rischio di doppia logica header.
2. Mantiene leggibilita e accessibilita su schermi piccoli.
3. Evita blocchi sul refactor completo della front-page.

## 14) Primo sprint bonifica header (micro-tranche)

Tranche 1:
1. Uniformare target skip link su `#contenuto-principale` anche in front-page.
2. Allineare landmark main della front-page al naming globale.
3. Confermare assenza breadcrumb in home.

Tranche 2:
1. Estrarre dalla front-page la logica dati header/contatti verso helper condivisi.
2. Eliminare duplicazioni di lookup opzioni tra front-page, chrome-header e footer.

Tranche 3:
1. Verifica finale mobile (hamburger, focus, ordine tab).
2. Smoke test rapido su home + una pagina interna + una pagina legale + un archivio CPT.

## 15) Checkpoint accessibilita (26 maggio 2026)

Esito sintetico audit template:
1. Landmark main e target skip link `#contenuto-principale`: allineati.
2. Breadcrumb: duplicazioni rimosse dai template pagina custom.
3. Link esterni con apertura nuova finestra: avvisi screen reader presenti nei blocchi principali.
4. Skip links: convergenza su partial globale + styling centralizzato nel CSS principale.
5. Header e footer: normalizzati tra homepage e contesto burocratico isolando gli override al solo `.site-main`.

Gap aperti da chiudere per rispettare il contratto:
1. Nessun gap aperto nel perimetro header + refresh archivio + CTA homepage.

## 16) Stato convergenza CSS (26 maggio 2026)

Delta applicato per uniformare estetica home e pagine interne:
1. Baseline visuale e token Stitch (reset base, tipografia base, utility accessibilita) migrati in `assets/css/site.css`.
2. `assets/css/stitch-layout.css` alleggerito: rimossi token/reset globali; restano solo utility transitorie.
3. Componenti homepage `hp-*` migrati in `assets/css/site.css`.
4. Caricamento runtime: disattivato enqueue di `stitch-layout.css` e `stitch-homepage.css` in `inc/enqueue.php`.

Conseguenza operativa:
1. Header e footer non dipendono piu dal baseline Stitch.
2. La homepage usa la stessa pipeline CSS principale delle altre pagine.
3. `assets/css/stitch-homepage.css` e mantenuto solo come placeholder documentale (non caricato).
4. Negli archivi burocratici il flusso e tornato naturale: niente scroll interno desktop, solo scroll smooth verso l'intestazione dopo refresh filtri.

## 17) Definizione smoke test (operativo)

Definizione:
1. Lo smoke test e un controllo rapido post-rilascio tecnico per verificare che le funzioni core non siano rotte.
2. Non sostituisce il collaudo completo: serve a dire in pochi minuti "si puo proseguire" oppure "stop e rollback".

Obiettivo nel progetto tema:
1. Garantire coerenza visiva e accessibilita minima su home, pagine interne e contesto burocratico dopo ogni modifica a template/CSS.

Campione minimo pagine (sempre):
1. Homepage (`front-page.php`).
2. Una pagina interna standard (`page.php` o equivalente reale).
3. Una pagina legale (`page-legale.php`).
4. Un archivio CPT/burocratico (`archive-area-burocratica-common.php` o route equivalente).

Checklist smoke test (8 controlli):
1. Header unico visibile e coerente (brand, nav, CTA) su tutte le pagine campione.
2. Footer unico visibile e coerente su tutte le pagine campione.
3. Skip links presenti e funzionanti, con focus visibile.
4. `#contenuto-principale` raggiungibile e landmark `main` presente.
5. Nessun breadcrumb duplicato nel DOM.
6. Focus keyboard visibile su link/pulsanti principali (header, contenuto, footer).
7. Nessuna regressione evidente di layout (sovrapposizioni, elementi fuori griglia, testo illeggibile).
8. Nessun errore bloccante lato console/browser percepibile durante navigazione base.

Esito:
1. PASS: 8/8 controlli superati.
2. FAIL: almeno 1 controllo fallito -> bloccare avanzamento, correggere, rieseguire smoke test completo.

Soglia temporale:
1. Durata target: 10-15 minuti.
2. Se supera i 15 minuti, il problema va trattato come regressione non banale e passa a test approfondito.
