# Piano operativo di risanamento tema

Data: 25 maggio 2026
Obiettivo: rimettere in ordine il tema senza riscriverlo da zero, riducendo rischio regressioni e tempi.

Riferimento baseline analisi:
- docs/AUDIT-TEMPLATE-MODELING.md
- docs/CONTRATTO-HEADER-UNICO.md

## Patto operativo (decisioni concordate)

Decisioni bloccate:
1. Priorita: stabilita tecnica, poi pulizia codice.
2. Regressioni visive minori: accettate solo se non impattano flussi critici.
3. Ambito intoccabile: tutto (home, compliance, admin) con priorita massima su compliance.
4. Accessibilita: ogni modifica deve mantenere o migliorare WCAG base.
5. Gate tranche: nessun gate fisso per tranche; avanzamento pragmatico.
6. Pagine legali/trasparenza: massima sobrieta, zero sperimentazione UI.
7. Legacy settings: congelare subito `inc/settings-old.php`, rimozione a fine ciclo.
8. CSS legacy: decommission graduale con checklist per contesto.
9. Front-page: resta speciale per ora, riallineamento rinviato.
10. Enqueue policy: regole rigide per contesto pagina, documentate.
11. Modalita lavoro: micro-tranche.
12. Checkpoint: ad ogni tranche.
13. In conflitto bello vs robusto: vince robusto.
14. Placeholder: mantenuti, ma marcati chiaramente.
15. Criterio finale: stop solo quando e tutto a posto (no compromessi), con definizione operativa esplicita.

## Definizione operativa di "tutto a posto"

Perfezione in questo ciclo significa:
1. Tutti i blocchi core funzionano.
2. Loop archivi funzionanti e stabili (attivita, trasparenza, area famiglie, area personale).
3. Modeling stabile: la logica che legge da Impostazioni Sito (opzioni PHP) alimenta correttamente frontend e template.
4. Homepage non si rompe su cambi opzioni e resta stabile.
5. Responsive minimo indispensabile:
  - menu header collassabile ad hamburger
  - filtri tassonomia degli archivi collassabili ad hamburger

## Decisioni di compromesso accettate

1. Front-page resta speciale in questa fase, ma senza introdurre nuove eccezioni strutturali non necessarie.
2. Accessibilita resta vincolo sempre attivo, ma senza gate formale ad ogni tranche.
3. Verifica completa demandata al checkpoint finale, con priorita su funzionalita e stabilita.

## Decisione strategica

Approccio scelto: refactor guidato a tranche.

Motivo:
- La base funzionale WordPress e compliance esiste gia.
- Il caos e concentrato in alcuni hotspot (settings, homepage, CSS multipli), non in tutto il progetto.
- Una riscrittura totale aumenterebbe rischio e tempi senza vantaggio proporzionale.

## Obiettivi misurabili (Definition of Done)

1. Una sola pipeline asset chiara per ogni contesto pagina.
2. Nessun file legacy attivo non usato (es. old settings, backup CSS).
3. Front-page stabilizzata senza nuove eccezioni; riallineamento completo spostato in fase dedicata successiva.
4. Nessuna regressione su pagine legali/trasparenza/archivi CPT.
5. Responsive minimo attivo: hamburger header + hamburger filtri tassonomia archivi.

## Hotspot da trattare per primi

1. inc/settings.php (monolite molto grande)
2. inc/settings-old.php (legacy da dismettere)
3. templates/front-page.php (strada separata rispetto al resto)
4. inc/enqueue.php (guardrail da rendere espliciti e robusti)
5. assets/css/ (sovrapposizione tra site, stitch, area-burocratica, backup)

## Piano per fasi (ordine consigliato)

### Fase 0 - Baseline e sicurezza (0.5 giornata)

Output atteso:
- Baseline stabile e confrontabile prima di toccare architettura.

Attivita:
- Creare branch dedicato: chore/theme-risanamento.
- Fotografare stato attuale con checklist pagine chiave:
  - home
  - archivio attivita
  - archivio trasparenza
  - area famiglie
  - area personale
  - 1 pagina legale
  - 404
- Annotare bug gia noti in un file di tracking.

Criterio di chiusura fase:
- Esiste una baseline scritta e condivisa per confronto regressioni.

### Fase 1 - Guardrail enqueue e contesti pagina (0.5-1 giornata)

Output atteso:
- Regole di caricamento asset esplicite, prevedibili e verificabili.

Attivita:
- Documentare in testa a inc/enqueue.php la matrice:
  - Home
  - Contesto burocratico/legale
  - Resto del sito
- Centralizzare le condizioni in helper piccoli e leggibili.
- Verificare che gli asset Stitch siano caricati solo dove previsto.

Criterio di chiusura fase:
- In ogni contesto il numero/ordine asset e coerente con la matrice.

### Fase 2 - Consolidamento CSS (1-2 giornate)

Output atteso:
- Strato CSS ordinato per responsabilita, senza doppioni attivi.

Attivita:
- Definire ruoli file CSS:
  - base globale
  - header/footer globali
  - homepage
  - area burocratica/legale
  - debug
- Disattivare gradualmente i file duplicati/legacy con verifica visiva a ogni step.
- Spostare override contestuali fuori dai file globali se fuori scope.

Criterio di chiusura fase:
- Nessun conflitto evidente tra file CSS per la stessa responsabilita.

### Fase 3 - Front-page allineata al pattern tema (1 giornata)

Output atteso:
- Homepage coerente con partial/shared behavior del resto del tema.

Attivita:
- Ridurre logica presentazionale inline nella front-page.
- Estrarre blocchi riusabili se ripetuti.
- Verificare coerenza skip link, main landmark, header/footer.

Criterio di chiusura fase:
- Front-page segue lo stesso contratto strutturale dei template principali.

### Fase 4 - Smontaggio legacy settings (1-2 giornate)

Output atteso:
- Codice impostazioni modulare, old disattivato e poi rimosso.

Attivita:
- Separare funzioni per area:
  - stile
  - contatti
  - normative
  - legale
- Garantire backward compatibility delle option key gia salvate.
- Disaccoppiare e rimuovere gradualmente settings-old.php.

Criterio di chiusura fase:
- settings-old.php non e piu necessario e non viene caricato.

### Fase 5 - QA funzionale finale (1 giornata)

Output atteso:
- Validazione finale senza regressioni bloccanti.

Attivita:
- Smoke test completo delle pagine baseline.
- Verifica loop archivi e modellazione da Impostazioni Sito.
- Verifica stabilita homepage con cambio opzioni principali.
- Verifica responsive minimo: menu header hamburger e filtri archivi ad hamburger.

Criterio di chiusura fase:
- Zero regressioni bloccanti su loop, modeling opzioni, homepage e compliance.

## Matrice priorita (impatto x rischio)

Alta priorita:
1. enqueue + contesti
2. consolidamento CSS
3. front-page alignment

Media priorita:
1. modularizzazione settings
2. rimozione legacy

Bassa priorita:
1. ottimizzazioni estetiche non bloccanti
2. polish micro-interazioni

## Regole operative durante il risanamento

1. Niente modifiche massive multi-file senza checkpoint intermedio.
2. Ogni tranche deve essere testabile in autonomia.
3. Prima si riduce complessita, poi si fa rifinitura visiva.
4. Se emerge dubbio su compliance, si privilegia stabilita e leggibilita.

## Stima realistica

Scenario prudente: 4-6 giornate nette.
Scenario aggressivo: 3-4 giornate se non emergono regressioni nascoste.

## Piano esecutivo immediato (prossimo step)

Step 1:
- Aprire Fase 1 su inc/enqueue.php con matrice contesti scritta e helper dedicati.

Step 2:
- Fare subito smoke test su home + archivio trasparenza + pagina legale.

Step 3:
- Solo se verde, partire con Fase 2 (consolidamento CSS).

Step 4:
- Applicare checklist "tutto a posto" come criterio di uscita unico.

## Stato avanzamento (26 maggio 2026)

### Fase 2 - Consolidamento CSS

Completamenti registrati:

1. Uniformata la tipografia tra pagine burocratiche (Trasparenza, Area Famiglie, Area Personale), eliminando la divergenza font sull'H1.
2. Separati i token dinamici globali (:root con variabili colore/font) dagli override globali tipografici; nel contesto burocratico restano attivi i token ma non gli override generici su body/headings/link/button.
3. Mantenuto il caricamento Google Fonts anche nel contesto burocratico per evitare fallback incoerenti.
4. Confermata separazione dei layer CSS per contesto senza modificare i fogli globali di header/footer.

Note operative:

- File toccato per la bonifica runtime: `inc/settings.php`.
- Nessuna modifica strutturale a `assets/css/site-header.css` e `assets/css/site-footer.css`.

### Checkpoint accessibilita template (26 maggio 2026)

Conferme:

1. Landmark principali e target skip link main risultano coerenti nel parco template attuale.
2. Duplicazioni breadcrumb rimosse dai template pagina custom allineati al contratto header unico.
3. Skip links allineati al partial globale con styling centralizzato nel CSS principale.
4. Header/footer normalizzati tra home e contesto burocratico: override link/focus limitati al solo `.site-main`.
5. Archivio burocratico: dopo refresh filtri la viewport risale in alto con scroll smooth verso l'intestazione.

Azioni aperte prioritarie:

1. Nessuna azione prioritaria aperta su questo blocco; mantenere solo verifiche QA di regressione.
