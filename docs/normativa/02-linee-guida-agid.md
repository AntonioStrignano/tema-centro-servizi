# Linee Guida AgID sull'Accessibilità

## Cos'è

Le **Linee Guida AgID** (Agenzia per l'Italia Digitale) sono il documento operativo che traduce la Legge Stanca e il D.Lgs 106/2018 in obblighi pratici: dichiarazione di accessibilità, meccanismo di feedback, obiettivi annuali.

## Riferimento

- [Linee Guida AgID sull'accessibilità degli strumenti informatici](https://www.agid.gov.it/it/design-servizi/accessibilita/linee-guida-accessibilita-strumenti-informatici)
- [Form dichiarazione di accessibilità](https://form.agid.gov.it/)

## Obblighi concreti per il sito

### 1. Dichiarazione di accessibilità

- Va compilata sul portale **form.agid.gov.it**
- Deve indicare lo stato di conformità (totale, parziale, non conforme)
- Va **aggiornata ogni anno** (entro il 23 settembre)
- Il link deve essere nel **footer di ogni pagina**

| Stato tema | ✅ Previsto — link nel footer (§3.3 PROGETTO.md) |
|------------|--------------------------------------------------|

### 2. Meccanismo di feedback

- L'utente deve poter **segnalare problemi di accessibilità**
- Serve un contatto dedicato (email, form, link)
- Il soggetto deve rispondere entro 30 giorni
- Se non risponde, l'utente può rivolgersi al **Difensore civico digitale** di AgID

| Stato tema | ✅ Previsto — link a Google Form esterno (§3.3) |
|------------|------------------------------------------------|

### 3. Obiettivi di accessibilità

- Ogni anno, **entro il 31 marzo**, va pubblicato un documento con:
  - Stato di conformità attuale
  - Interventi di miglioramento previsti per l'anno
  - Tempistiche di attuazione
- Deve essere una **pagina/sezione del sito** o un documento PDF accessibile pubblicato

| Stato tema | ❌ **MANCA** — da aggiungere come pagina o sezione nella Amm. Trasparente |
|------------|--------------------------------------------------------------------------|

## Sanzioni

Le stesse della Legge Stanca: AgID può intervenire con diffide, piani di adeguamento, e in ultima istanza sanzioni fino al 5% del fatturato.

## Cosa devi sapere come webmaster

1. **form.agid.gov.it** è gratuito — devi compilare il modulo online con i dati della scuola, indicare i contenuti non accessibili e le motivazioni
2. Il link generato da AgID va nel footer: testo tipo "Dichiarazione di accessibilità"
3. **Gli obiettivi di accessibilità** sono un documento separato dalla dichiarazione. Puoi fare una pagina WP semplice con:
   - Stato attuale ("Il sito è parzialmente conforme a WCAG 2.1 AA")
   - Cosa si intende migliorare quest'anno
   - Entro quando
4. Scadenze da ricordare:
   - **31 marzo**: pubblicazione obiettivi accessibilità
   - **23 settembre**: aggiornamento dichiarazione su form.agid.gov.it
