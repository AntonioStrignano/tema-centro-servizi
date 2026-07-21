# L. 4/2004 (Legge Stanca) + D.Lgs 106/2018

## Cos'è

La **Legge 4/2004** (detta "Legge Stanca") stabilisce i requisiti di accessibilità per i siti web della Pubblica Amministrazione e dei soggetti che erogano servizi pubblici.

Il **D.Lgs 106/2018** recepisce la Direttiva UE 2016/2102 e **estende l'obbligo** a tutti i soggetti che offrono servizi al pubblico attraverso il web, comprese le scuole paritarie.

## Testo normativo

- L. 4/2004: [Normattiva](https://www.normattiva.it/uri-res/N2Ls?urn:nir:stato:legge:2004-01-09;4)
- D.Lgs 106/2018: [Normattiva](https://www.normattiva.it/uri-res/N2Ls?urn:nir:stato:decreto.legislativo:2018-09-10;106)

## Chi è obbligato

- Pubbliche Amministrazioni
- **Enti che erogano servizi pubblici** e svolgono attività di interesse pubblico tramite il web
- Le **scuole paritarie** rientrano in quanto erogano servizio pubblico di istruzione e ricevono contributi statali (MIM)

## Standard tecnico richiesto

**WCAG 2.1 livello AA** — Web Content Accessibility Guidelines del W3C.

I criteri principali (non esaustivo):
- Testo alternativo per immagini (`alt`)
- Navigazione da tastiera completa
- Contrasto colori minimo 4.5:1 (testo normale), 3:1 (testo grande)
- Struttura semantica (heading gerarchici, landmark ARIA)
- Form accessibili (label, errori chiari)
- Tabelle con header (`<th>`, `scope`)
- Link con testo significativo (mai "clicca qui")
- Contenuto comprensibile senza CSS/JS
- Skip links per navigazione rapida

## Cosa deve esserci sul sito

| Requisito | Dettaglio | Stato tema |
|-----------|-----------|------------|
| Conformità tecnica WCAG 2.1 AA | Tutto il sito | ✅ Previsto in §3 PROGETTO.md |
| Link "Dichiarazione di accessibilità" | Nel footer, rimanda a form.agid.gov.it | ✅ Previsto in §3.3 |
| Meccanismo di feedback | Per segnalare problemi di accessibilità | ✅ Google Form esterno |

## Sanzioni

- **AgID** è l'organismo di monitoraggio. Può effettuare verifiche e disporre interventi correttivi.
- Per soggetti obbligati dal D.Lgs 106/2018: sanzione pecuniaria fino al **5% del fatturato**.
- Nella pratica AgID procede con **diffide e piani di adeguamento** prima delle sanzioni.
- Rischio reputazionale e contenzioso da parte di famiglie/utenti che non riescono ad accedere ai servizi.

## Cosa devi sapere come webmaster

1. **Ogni pagina** deve funzionare senza mouse, solo tastiera
2. **Ogni immagine** deve avere un `alt` descrittivo (o vuoto `alt=""` se decorativa)
3. Mai usare colore come unico mezzo per trasmettere informazione
4. I PDF pubblicati devono essere accessibili (PDF/UA) — i PDF scansionati come immagine NON sono accessibili
5. Le tabelle devono avere `<th>` con `scope` e `<caption>`
6. La dichiarazione di accessibilità va compilata su [form.agid.gov.it](https://form.agid.gov.it/) e il link va nel footer
