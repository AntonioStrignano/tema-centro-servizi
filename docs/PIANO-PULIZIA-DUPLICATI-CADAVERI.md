# Piano Pulizia Duplicati e Cadaveri

Data: 2026-06-18
Obiettivo: ridurre complessita manutentiva eliminando codice/file morti e consolidando i duplicati ad alto rischio regressione.

## Criteri decisionali
- Elimina: file non referenziato dal runtime e non utile come storico tecnico.
- Archivia: file non referenziato ma con valore storico temporaneo.
- Tieni: file attivo nel runtime o necessario a roadmap corrente.

## Inventario attuale (fase 1 + fase 2 completata, aggiornato)

| Elemento                                                                   | Stato runtime             | Evidenza                                                                                                | Decisione proposta                    | Priorita |
|----------------------------------------------------------------------------|---------------------------|---------------------------------------------------------------------------------------------------------|---------------------------------------|----------|
| inc/settings-old.php                                                       | Non caricato              | functions.php include solo inc/settings.php; riferimenti trovati solo in docs/PIANO-RISANAMENTO-TEMA.md | Archivia ora, rimuovi a fine ciclo    | Alta     |
| assets/css/site.css.backup                                                 | Non caricato              | theme-assets.php punta assets/css/site.css; nessun enqueue del backup                                   | Archivia ora, poi elimina             | Alta     |
| assets/css/legal-pages.css                                                 | Non caricato              | caricamento CSS centralizzato in inc/theme-assets.php (site, area-burocratica, header, footer)          | Archiviato in docs/_archivio-tecnico/ | Media    |
| templates/page-la-nostra-scuola.php + templates/page-attivita-speciali.php | Attivi ma quasi duplicati | diff: differenze quasi solo nei nomi campo ACF e classi CSS                                             | Deduplica con partial condiviso       | Alta     |

## Ordine operativo raccomandato

1. Bloccare regressioni prima della pulizia
- Verifica visuale pagine:
  - la-nostra-scuola
  - attivita-speciali
  - attivita

2. Deduplica template ad alto impatto
- Estrarre un partial unico da usare in:
  - templates/page-la-nostra-scuola.php
  - templates/page-attivita-speciali.php
- Lasciare nei template solo configurazione minima:
  - chiave repeater ACF
  - prefisso classi BEM

3. Cadaveri file singoli
- Spostare subito in archivio interno:
  - inc/settings-old.php
  - assets/css/site.css.backup
  - assets/css/legal-pages.css
- Cartella consigliata: docs/_archivio-tecnico/

4. Verifica post-pulizia
- Ricerca riferimenti residui ai file archiviati
- Check frontend pagine interessate
- Check admin settings base

5. Chiusura tracciabilita
- Aggiornare TODO.md con esito reale
- Aggiornare docs/PIANO-RISANAMENTO-TEMA.md se cambia lo stato dei legacy

## Definizione di Done
- Nessun file old/backup nel percorso attivo del tema.
- I due template deduplicati condividono lo stesso partial.
- Nessun riferimento runtime ai file archiviati.
- TODO e documentazione allineati allo stato reale.

## Rischi e mitigazioni
- Rischio: rottura classi CSS dopo deduplica.
  - Mitigazione: mantenere prefissi classi per template, toccare solo struttura comune.
- Rischio: perdita storico utile.
  - Mitigazione: archiviazione iniziale, eliminazione definitiva solo dopo un ciclo di verifica.
