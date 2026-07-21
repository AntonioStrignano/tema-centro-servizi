# Report CSS — Passata Duplicati e Cadaveri

Data: 2026-06-18
Ambito: assets/css/site.css, assets/css/site-header.css, assets/css/site-footer.css, assets/css/area-burocratica.css

## Esito sintetico
- Non emergono selettori identici duplicati tra file diversi (cross-file) in modo sistematico.
- Il punto critico reale e la duplicazione interna legacy in area-burocratica.css (liste selettori molto lunghe con stesso comportamento del selettore canonico `body.centro-burocratico`).
- Fuori runtime e' stato archiviato anche `assets/css/legal-pages.css` in `docs/_archivio-tecnico/legal-pages.css`.

## Intervento applicato in questa tranche
- Rimosso blocco legacy duplicato `font-family: inherit` per input/select/textarea/button in area-burocratica.css.
- Copertura garantita dal blocco canonico gia presente: `body.centro-burocratico input, select, textarea, button`.
- Consolidati i selettori legacy lunghi per form controls (`input/select/textarea` + `:focus`) nel selettore canonico `body.centro-burocratico`.
- Rimossi blocchi legacy duplicati per link/hover/focus/background gia coperti dai selettori canonici `body.centro-burocratico .site-main` e `body.centro-burocratico .site-section`.
- Rimossi i macro-blocchi legacy duplicati basati su `body.post-type-*`, `body.tax-*`, `body.page-*` per variabili tema, larghezze wrapper e card surface, mantenendo solo eccezioni esplicite necessarie.
- Uniformata la regola padding archivio da lista body-specifica a selettore unico `body.centro-burocratico-archive .trasparenza-archive`.
- Preservata l'eccezione di larghezza su pagina scuola con selettore canonico `body.centro-burocratico.page-la-nostra-scuola .site-section__inner`.
- Verifica editor: nessun errore.

## Duplicazioni residue ad alto valore

1. Duplicazioni strutturali in `area-burocratica.css`
- Stato: ridotte in modo sostanziale nella passata corrente; i macro-blocchi legacy body-specifici sono stati rimossi.

2. Possibili overlap semantici tra componenti archivio
- Stato: da verificare in QA visuale (stili molto vicini tra card/listing), non emergono piu duplicazioni massive di selettori.

## Merge sicuri consigliati (ordine)

1. Tranche A (basso rischio)
- Completata: selettori form controls consolidati su `body.centro-burocratico` mantenendo proprieta invariate.
- Lasciare invariati eventuali casi speciali di pagina.

2. Tranche B (medio-basso rischio)
- Completata: consolidamento su selettori canonici per variabili, superfici card e padding archivio, con mantenimento delle sole eccezioni layout necessarie.
- Casi separati rimasti: eccezioni esplicite di larghezza (legale 64rem, archivi 88rem, pagina scuola 64rem).

3. Tranche C (medio rischio)
- Ridurre liste body-page specifiche dove il body class `centro-burocratico` e garantito da `inc/theme-assets.php`.
- Conservare eccezioni esplicite per larghezza contenuto legale (`64rem`) e archivio (`88rem`) dove necessario.

## Guardrail per le prossime modifiche
- Non toccare regole di header/footer in questa passata.
- Dopo ogni tranche: verifica visuale minima su
  - home
  - archivio trasparenza
  - area famiglie
  - area personale
  - una pagina legale
- Aggiornare TODO/PIANO solo dopo verifica visuale.

## Mini QA visuale guardrail (eseguito)

Data: 2026-06-18
Ambiente: https://demo.pro06.it

Pagine verificate:
- Home: ok caricamento, skip links presenti, header/footer coerenti.
- Trasparenza: ok caricamento, filtri presenti, breadcrumb e struttura archivio coerenti.
- Area Famiglie: ok caricamento, filtri e lista documenti coerenti.
- Area Personale: ok caricamento, filtri e lista documenti coerenti.
- Privacy Policy (pagina legale): ok caricamento, heading e sezioni legali presenti.

Esito sintetico:
- Nessuna regressione CSS bloccante evidente sui 5 guardrail.
- Layout e gerarchia visiva risultano stabili rispetto all'obiettivo della passata duplicati/cadaveri.

## Delta tecnico applicato dopo mini QA

Data: 2026-06-18

- Tranche C (basso rischio) avviata in `assets/css/area-burocratica.css`:
  - consolidati selettori duplicati tra pagina legale e singola burocratica per wrapper interno (`.site-section__inner`) e titoli principali.
  - preservate eccezioni di larghezza richieste (64rem legale/singola, 88rem archivi).
- Footer Whistleblowing hardening in `partials/footer.php`:
  - normalizzazione URL senza schema (`https://` auto aggiunto se necessario).
  - fallback automatico alla pagina interna `/whistleblowing/` quando l'URL configurato e vuoto o coincide con la home.
  - apertura in nuova finestra solo per URL esterni reali.

Nota:
- Verifica visuale runtime post-fix da ripetere dopo deploy/sync dell'ambiente, per confermare il comportamento frontend effettivo.

Anomalie non bloccanti emerse (contenuto/config):
- Footer, voce "Whistleblowing": il link risulta puntare alla root del dominio (https://demo.pro06.it) invece di puntare all'URL whistleblowing atteso.
- Nei contenuti demo di Area Famiglie/Area Personale sono presenti testi di test espliciti (es. "ATTENZIONE TEST: ...") da sostituire prima della pubblicazione finale.

Fatto:
- Mini QA visuale completato su 5 pagine guardrail richieste.

Non fatto:
- Verifica mobile approfondita e hard refresh/cache-busting strumentato non inclusi in questa passata.

Prossimo passo unico:
- Ripetere mini QA guardrail dopo deploy locale/staging delle modifiche Tranche C e del fix Whistleblowing nel footer.

## Delta successivo (allineamento contrasto/visited)

Data: 2026-06-18

- Applicati fix di contrasto WCAG AA su:
  - `assets/css/area-burocratica.css`
  - `assets/css/site-header.css`
  - `assets/css/site-footer.css`
- Hardening stato `:visited` per CTA e bottoni colorati in:
  - `assets/css/site-header.css` (`.site-header__cta:visited`)
  - `assets/css/site.css` (`.btn--primary:visited`, `.btn--tertiary:visited`, `.hp-docs__link:visited`, `.pagination .page-numbers.current:visited`)
- Corretto comportamento `a:visited` per ridurre regressioni sui link con stile bottone.

Riferimento audit dedicato:
- `docs/CONTRASTO-AUDIT-2026-06-18.md`
