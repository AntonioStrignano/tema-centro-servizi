# Prompt Stitch AI — Frontpage Scuola Paritaria

Usa questo prompt in Stitch AI per ottenere una proposta concreta, fresca e realizzabile della homepage.

## Nota di allineamento documentale (18/06/2026)

- Contrasto colore validato con audit WCAG AA dedicato.
- CTA e bottoni con sfondo colorato: testo bianco persistente anche in stato visited per robustezza cross-palette.
- Riferimenti operativi: `docs/CONTRASTO-AUDIT-2026-06-18.md`, `TODO.md`, `README.md`, `PROGETTO.md`.

---

## Prompt da incollare in Stitch

Voglio una proposta di frontpage per un sito WordPress di scuola paritaria dell'infanzia in Italia.

Obiettivo: ottenere una direzione di design concreta, fresca, professionale e credibile, non il classico template generico.

### Contesto progetto

- Pubblico principale: famiglie con bambini 3-6 anni (genitori e nonni), segreteria, personale scolastico.
- Tono: accogliente, affidabile, chiaro, umano, linguaggio semplice.
- Il sito deve comunicare: qualita educativa, trasparenza, sicurezza, contatto rapido.
- Vincoli forti: accessibilita WCAG 2.1 AA, chiarezza dei contenuti, conformita legale base visibile nel footer.
- CMS target: WordPress classico, quindi layout realistico da implementare in tema custom.

### Cosa voglio da te

1. Una homepage completa in stile moderno-editoriale, con personalita visiva ma elegante.
2. Gerarchia dei contenuti chiarissima.
3. Design mobile-first e desktop.
4. Componenti riutilizzabili.
5. Soluzione realistica (niente effetti ingestibili o dipendenze pesanti).

### Struttura obbligatoria homepage (ordine richiesto)

1. Header sticky
- Logo scuola
- Menu: La nostra scuola, Attivita, Area famiglie, Area personale, Amministrazione trasparente, Contatti
- CTA: Contattaci
- Ricerca visibile

2. Hero emozionale
- Titolo forte orientato alle famiglie
- Sottotitolo breve
- Due CTA: Prenota un colloquio (primaria) + Scopri la scuola (secondaria)
- Background caldo/autentico (non corporate freddo)

3. Sezione Chi siamo
- Testo introduttivo breve
- Tre valori con icona: Accoglienza, Crescita, Comunita

4. Sezione La nostra scuola
- Mini gallery con 4 immagini e didascalia
- Link: Scopri la scuola

5. Sezione Attivita in evidenza
- Griglia 3-4 card
- Card: immagine, titolo, estratto, link Approfondisci
- Look editoriale (non blog standard)

6. Sezione Orari e calendario
- Due blocchi: Orari di funzionamento + Calendario scolastico
- Link rapidi ai documenti ufficiali

7. Sezione Servizi principali
- Quick links/card: Mensa, Modulistica, Comunicazioni, Iscrizioni, Inclusione
- Massima leggibilita anche per utenti poco digitali

8. Sezione Contatti rapidi
- Indirizzo, telefono, email, mappa
- CTA: Scrivici / Chiama ora

9. Footer legale completo (obbligatorio)
- Link: Privacy Policy, Cookie Policy, Dichiarazione accessibilita, Obiettivi di accessibilita, Whistleblowing, Amministrazione Trasparente
- Dati ente (placeholder realistici): ragione sociale, P.IVA, CF, PEC, codice meccanografico
- Nota feedback accessibilita

### Direzione visiva richiesta

- Fresca, luminosa, istituzionale ma non rigida.
- Palette consigliata: base chiara calda + accenti naturali (verde salvia, blu petrolio, terracotta tenue).
- Evita look tech freddo e combinazioni banali.
- Tipografia con carattere, molto leggibile.
- Uso forte di spaziatura, ritmo verticale e blocchi distinti.
- Micro-animazioni sobrie (hover/reveal) con rispetto prefers-reduced-motion.

### Accessibilita obbligatoria nel concept

- Contrasto adeguato.
- Focus visibile su tutti i controlli.
- Heading ordinati (un solo H1).
- Link descrittivi.
- Nessuna informazione importante affidata solo al colore.
- Navigazione da tastiera considerata fin da layout.
- CTA e moduli chiari.

### Vincoli tecnici e di implementazione

- Deve essere implementabile in un tema WordPress custom senza page builder pesanti.
- Nessuna dipendenza da librerie front-end complesse.
- Struttura component-based riusabile (card, section head, CTA row, legal links, contact block).
- Prestazioni buone su mobile (evitare layout troppo carichi).

### Output richiesto da Stitch

1. Concept sintetico (2-3 paragrafi).
2. Sitemap della homepage a blocchi.
3. Wireframe testuale sezione per sezione.
4. UI direction: palette (hex), font pairing, bottoni, card, spacing.
5. Esempio copy realistico per Hero + 2 sezioni.
6. Mini design system (componenti riutilizzabili).
7. Note pratiche per implementazione WordPress.
8. Due varianti:
- Variante A: piu emozionale.
- Variante B: piu istituzionale.

### Criterio di qualita finale

La proposta deve sembrare pronta per passare a mockup e sviluppo, non un insieme di idee astratte.

---

## Nota operativa

Se vuoi un output ancora piu concreto, chiedi a Stitch anche:

- una griglia tipografica completa (display, h1-h6, body, small);
- una token list CSS (colori, spaziature, radius, ombre);
- un esempio di layout mobile e desktop per Hero, Attivita e Footer.

---

## Direzione Nuova: Sistema Preset Anti-Casino

Se il rischio e` avere ogni sito diverso ma incoerente, il punto non e` dare piu opzioni: e` darne meno, ma migliori.

### Principio

- Niente scelta libera colore-per-colore.
- Niente mix casuale di font.
- Si sceglie un kit identita pre-approvato e si applica in 2 minuti.

### Regole operative

1. Ogni scuola sceglie solo 1 kit colore tra quelli approvati.
2. Ogni scuola sceglie solo 1 kit tipografico tra quelli approvati.
3. Vietato combinare palette e font fuori lista.
4. Le CTA usano sempre il colore accent del kit, mai colori custom.
5. I titoli usano sempre il font heading del kit, body e UI usano il font testo del kit.

### Kit Colori Consigliati (gia pronti)

Ogni kit usa 4 token base coerenti con il tuo pannello: main, secondary, body, accent.

#### Kit 01 - Bosco Educativo
- main: #2F6D62
- secondary: #F3E9D7
- body: #24303A
- accent: #D36B4A

#### Kit 02 - Mare Sereno
- main: #1F5A7A
- secondary: #EAF4F7
- body: #1E2A32
- accent: #E59C44

#### Kit 03 - Quaderno Naturale
- main: #3E6B4A
- secondary: #F5F2E8
- body: #202820
- accent: #C26A3D

#### Kit 04 - Sole Gentile
- main: #A24E2A
- secondary: #FFF3DE
- body: #2A2522
- accent: #2F6F6A

#### Kit 05 - Istituzionale Pulito
- main: #2D4A63
- secondary: #F7F4EF
- body: #1F2226
- accent: #B65E3C

#### Kit 06 - Cielo e Prato
- main: #2D6FA3
- secondary: #F6F9F3
- body: #1D2A2D
- accent: #6F8F3D

### Kit Font Consigliati (gia disponibili nel catalogo tema)

#### Font Kit A - Editoriale caldo
- headings: merriweather
- body/UI: source-sans-3

#### Font Kit B - Classico affidabile
- headings: playfair-display
- body/UI: lato

#### Font Kit C - Umano contemporaneo
- headings: bree-serif
- body/UI: nunito

#### Font Kit D - Istituzionale moderno
- headings: montserrat
- body/UI: open-sans

#### Font Kit E - Solido universale
- headings: georgia
- body/UI: arial

### Combinazioni consigliate (pronte all'uso)

- Combo 1: Kit 01 + Font Kit A
- Combo 2: Kit 02 + Font Kit D
- Combo 3: Kit 03 + Font Kit C
- Combo 4: Kit 04 + Font Kit B
- Combo 5: Kit 05 + Font Kit E
- Combo 6: Kit 06 + Font Kit D

### Flusso setup ridotto (a prova di imbecille)

1. Seleziona combo (es. Combo 3).
2. Applica i 4 colori nel pannello Stile Tema.
3. Applica il kit font su body, h1-h6, links, buttons.
4. Carica logo e foto scuola.
5. Fine: no ulteriori tuning cromatici.

---

## Prompt Stitch per costruire i kit (versione strategica)

Voglio che tu progetti un sistema visuale multi-scuola basato su preset chiusi, non su personalizzazione libera.

Obiettivo:
- mantenere identita specifica di ogni scuola;
- evitare palette brutte e abbinamenti font incoerenti;
- ridurre setup e decision fatigue.

Task:
1. Crea 6 style kit completi (ognuno con main, secondary, body, accent, varianti light/dark e stato hover/focus).
2. Crea 5 font kit con coppie heading/body leggibili e coerenti.
3. Definisci una matrice di compatibilita: ogni palette puo essere abbinata solo ad alcuni font kit.
4. Definisci regole bloccanti: cosa e vietato combinare.
5. Definisci token CSS per ogni kit (nome token + valore hex).
6. Definisci una checklist QA visuale/accessibilita da 10 controlli rapidi.

Vincoli:
- WCAG 2.1 AA.
- Aspetto istituzionale caldo, mai corporate freddo.
- Implementabile in tema WordPress custom senza framework front-end.

Output richiesto:
1. Tabella kit colori.
2. Tabella kit font.
3. Matrice compatibilita palette/font.
4. Esempio di applicazione su homepage (hero, servizi, card attivita, footer).
5. Regole anti-errore pronte da trasformare in UI admin.
