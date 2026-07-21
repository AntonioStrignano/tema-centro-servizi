# Handoff Chat Riutilizzabile

Usa questo testo all'inizio di ogni nuova chat per non perdere contesto.

## Versione rapida (consigliata)

Copia e compila solo le righe tra parentesi quadre.

```text
Handoff progetto: tema-centro-servizi
Data: [YYYY-MM-DD]

Contesto stabile:
- Tema WordPress classico per scuole paritarie.
- Priorita': robustezza/compliance prima, poi polish estetico.
- Stato generale: core strutturale avanzato; paginazione archivio Trasparenza chiusa e verificata; ricerca sito chiusa; pagine legali rese dinamiche con seed + shortcodes + migrazione v3; restano revisione finale testi e QA.
- Stato attività: blocco CPT completato, ma la parte homepage/vetrina attività non è ancora stata ripresa in questa fase.
- Stato attività aggiornato: le attività ora vivono come pagina dedicata con menu admin proprio, sezioni ripetibili e gallery controllata dal tema; modale immagini con tastiera/accessibilità attiva e breadcrumb in orizzontale.

Obiettivo di questa chat:
- [scrivi qui 1 obiettivo chiaro, es. "chiudere search.php + search-form.php"]

Vincoli da rispettare:
- Non rompere pagine legali e trasparenza.
- Mantenere accessibilita' (skip links, landmark, focus, heading).
- Modifiche piccole e verificabili, niente refactor massivo non richiesto.

Cosa e' gia' fatto (non rifare):
- Homepage dinamica + preset stile.
- Header/footer unificati.
- Archivi CPT principali operativi.
- Archivio Trasparenza senza ricerca testuale (solo filtri coerenti con la struttura).
- Colonne admin CPT gia' implementate (attivita, trasparenza, area-famiglie, area-personale).
- Registro componenti accessibilita attivo in docs/REGISTRO-COMPONENTI-ACCESSIBILITA.md.

Aperti prioritari:
1) Revisione pagine obbligatorie seedate: Privacy, Cookie, Accessibilita, Whistleblowing
2) QA accessibilita'/Lighthouse/focus tastiera
3) Rifiniture frontend residue realmente necessarie

Richiesta operativa per questa chat:
- Prima fai check rapido stato file interessati.
- Poi proponi mini piano in 3 step max.
- Poi implementa direttamente.
- Infine dammi recap: fatto / non fatto / prossimo passo.
```

## Versione ultra-corta (30 secondi)

```text
Riprendiamo tema-centro-servizi.
Priorita': robustezza e compliance > estetica.
Task ora: [TASK].
Non toccare parti non correlate.
Chiudi con recap fatto/non fatto/prossimo passo.
```

## Snapshot pronto all'uso (stato attuale)

```text
Handoff progetto: tema-centro-servizi
Data: 2026-06-18

Siamo in fase mista: non solo estetica.
Strutturale: buona base, con colonne admin gia' chiuse, paginazione Trasparenza completata e ricerca sito implementata; restano rifiniture non bloccanti.
Estetica: avanzata ma non finita (copy definitivo, vetrina attivita', micro-polish recapiti).
Compliance/QA finale ancora aperta (audit accessibilita', Lighthouse, test tastiera/screen reader, revisione legali seed).
Contrasto colori: audit WCAG AA completato e fix principali applicati (header/footer/burocratica).
Attivita': riprese e popolate nella pagina dedicata con verifica resa frontend completata.
Attivita' come contenuto: ora gestita come pagina WordPress dedicata, non come post type.

Delta decisioni recenti:
- Rimosso uso della ricerca nell'archivio Trasparenza.
- Rinomina tassonomia: "01 Documentazione Trasparenza".
- Fisarmonica categorie Trasparenza valutata e scartata (non prioritaria rispetto alla robustezza accessibilita dei filtri dinamici).
- Paginazione su `/trasparenza/` chiusa: 10 per pagina, range risultati `x-y su totale`, filtri preservati, test manuale ok.
- Ricerca sito chiusa: filtri per tipo contenuto, form custom condiviso, risultati raggruppati per tipo.
- Pagine legali rese dinamiche: seed con shortcodes, campi ACF dedicati, fallback contatto privacy DPO -> legale rappresentante -> Centro Servizi.
- Migrazione v3 attiva per aggiornare le pagine legali gia' seedate.
- Hardening `:visited` completato su CTA e bottoni colorati per evitare regressioni di contrasto cross-palette.

Task da attaccare adesso: chiudere QA accessibilita' finale (axe/Lighthouse/tastiera/VoiceOver) e finalizzare solo rifiniture contenutistiche non bloccanti (es. copy Chi siamo).
```

## Snapshot operativo aggiornato (pulizia duplicati/cadaveri)

```text
Handoff progetto: tema-centro-servizi
Data: 2026-06-18

Obiettivo corrente:
- Pulizia tecnica con priorita su duplicati e cadaveri, senza regressioni visuali.

Dove siamo arrivati:
- Deduplica template completata tra:
	- templates/page-la-nostra-scuola.php
	- templates/page-attivita-speciali.php
- Estratto partial condiviso:
	- partials/page-sezioni-grid.php
- Cadaveri archiviati fuori runtime:
	- docs/_archivio-tecnico/settings-old.php
	- docs/_archivio-tecnico/site.css.backup
- CSS burocratico: completata Tranche A e Tranche B parziale su area-burocratica.css
	- consolidati form controls su body.centro-burocratico
	- rimossi duplicati legacy per link/hover/focus/background gia coperti da selettori canonici

Documenti guida aggiornati:
- docs/PIANO-PULIZIA-DUPLICATI-CADAVERI.md
- docs/REPORT-CSS-PASSATA-2026-06-18.md

Vincoli per la prossima chat:
- Modifiche piccole e verificabili
- Non toccare header/footer globali in questa passata
- Dopo ogni tranche CSS: check visuale minimo su
	- home
	- archivio trasparenza
	- area famiglie
	- area personale
	- una pagina legale

Prossimo passo consigliato (ordine):
1) Chiudere Tranche B residua solo se a basso rischio
2) Avviare Tranche C con riduzione liste body-page specifiche mantenendo eccezioni di layout (64rem legale, 88rem archivi)
3) Eseguire mini QA visuale e aggiornare report con fatto/non fatto/prossimo passo
```

## Consiglio pratico

Per evitare deriva nelle chat lunghe, chiudi ogni sessione con 3 righe fisse:

```text
Fatto: ...
Non fatto: ...
Prossimo passo unico: ...
```

## Nota cambio chat (2026-06-18)

```text
Cambio chat: SI

Fatto:
- Pulizia CSS burocratico avanzata (Tranche B consolidata su selettori canonici).
- Cadavere archiviato: docs/_archivio-tecnico/legal-pages.css.
- Documentazione allineata (README + piano pulizia + report CSS).
- Audit contrasto WCAG AA completato con fix applicati su header/footer/contesto burocratico.
- Hardening `:visited` su CTA e bottoni colorati completato e tracciato.

Non fatto:
- Mini QA visuale sulle 5 pagine guardrail.

Prossimo passo unico:
- Aprire nuova chat e fare mini QA visuale (home, trasparenza, area famiglie, area personale, una legale), poi aggiornare report fatto/non fatto.
```

## Delta homepage servizi (2026-06-18)

```text
Aggiornamento sezione "Servizi per le famiglie" in homepage:
- Aggiunta card "Moduli iscrizione" con link a area-famiglie prefiltrata su `cat=moduli-iscrizione`.
- Aggiunta card "Area famiglie" (archivio completo).
- Aggiunta card "Attivita" (archivio attivita).
- Card "Contattaci" mantenuta/ripristinata nella stessa griglia servizi.

Stato attuale blocco servizi homepage: 4 card
1) Moduli iscrizione
2) Area famiglie
3) Attivita
4) Contattaci
```
