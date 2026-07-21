# Obblighi Legali — Checklist Operativa

> Cosa deve avere il sito web di una scuola paritaria d'infanzia per essere **a norma**.
> Caso peggiore: congregazione, coop sociale, 50-249 dipendenti, contributi MIM, 5x1000.
> Per i dettagli di ogni norma → cartella `normativa/`.

---

## Profilo di riferimento (caso peggiore)

|                     |                                                   |
|---------------------|---------------------------------------------------|
| Forma giuridica     | Coop sociale / Ente ecclesiastico / Congregazione |
| Dipendenti          | 50–249 (totale congregazione)                     |
| Contributi pubblici | Sì (MIM)                                          |
| 5x1000              | Sì                                                |
| RUNTS               | Da verificare caso per caso                       |

---

## 🔴 DA FARE — Obbligatorio, manca

Queste cose **devono** essere sul sito. Chi non le ha rischia sanzioni.

### 1. Contatto DPO
- **Norma**: GDPR art. 37 → [`04-gdpr.md`](normativa/04-gdpr.md)
- **Sanzione**: fino a 10M€ o 2% fatturato
- **Cosa serve sul sito**: email DPO nella privacy policy quando il DPO e' effettivamente nominato (es. `dpo@nomescuola.it`)
- **Chi lo fa**: Centro Servizi definisce formalmente il ruolo di DPO esterno con ogni cliente, se previsto dal caso concreto
- **Cosa fai tu (webmaster)**: quando il cliente ti dà l'email, la inserisci nella pagina Privacy Policy di WP; se il campo DPO resta vuoto, il tema mostra il contatto privacy configurato come fallback operativo
- [ ] Formalizzare nomina DPO con ogni cliente quando richiesto
- [ ] Inserire email DPO nella privacy policy quando disponibile

### 2. Whistleblowing — canale segnalazioni ‼️ URGENTE
- **Norma**: D.Lgs 24/2023 → [`05-dlgs-24-2023.md`](normativa/05-dlgs-24-2023.md)
- **Sanzione**: ANAC, da 10.000€ a 50.000€ **per ente**
- **Obbligatorio dal**: 17 dicembre 2023 — **SIAMO IN RITARDO DI 2+ ANNI**
- **Vale anche sotto i 50 dip.**: le paritarie rientrano comunque per i contributi MIM (criterio "settore pubblico")
- **Soluzione scelta**: GlobaLeaks self-hosted su VM Linux dedicata multi-tenant (Oracle Cloud o VPS equivalente)
- ⚠️ Un form CF7 o Google Form **NON** è compliant (no crittografia, no anonimato)
- ⚠️ **Non dipende dal tema nuovo** — va fatto subito anche sui siti attuali
- Guida operativa OCI: [WHISTLEBLOWING-ORACLE-CLOUD.md](WHISTLEBLOWING-ORACLE-CLOUD.md)

**Todo (indipendente dal tema — fare PRIMA):**
- [ ] Attivare VM Linux dedicata (Oracle Cloud o provider equivalente, Ubuntu 22.04+)
- [ ] Installare GlobaLeaks sulla VM dedicata
- [ ] Configurare dominio per ogni scuola (`segnalazioni.nomescuola.it` → VM dedicata)
- [ ] HTTPS automatico via Let's Encrypt integrato in GlobaLeaks
- [ ] Creare contesto + utente ricevente per ogni scuola
- [ ] Aggiungere link nei siti **già online** (footer o Amm. Trasparente, basta una riga HTML)

**Todo (nel tema nuovo):**
- [x] Creare pagina WP `whistleblowing` con spiegazione + link alla piattaforma (template/seed presenti)
- [x] Aggiungere link in Amm. Trasparente e/o footer (link presente nel footer con fallback pagina interna)

> Nota operativa attuale: nel tema il contenuto privacy non si ferma al solo DPO. Se il DPO manca, la catena di fallback mostra prima il legale rappresentante e, se anche quello non e' disponibile, il contatto Centro Servizi configurato.

### 3. Obiettivi di accessibilità
- **Norma**: Linee Guida AgID → [`02-linee-guida-agid.md`](normativa/02-linee-guida-agid.md)
- **Sanzione**: fino al 5% del fatturato (in teoria; nella pratica, diffide e piani)
- **Scadenza**: ogni anno **entro il 31 marzo**
- **Cosa serve sul sito**: pagina con stato conformità + piano miglioramento annuale
- [ ] Creare pagina WP (o sezione in Amm. Trasparente) "Obiettivi di accessibilità"
- [ ] Ricordare al cliente: aggiornamento annuale entro il 31/3

### 4. Dati societari nel footer (se cooperativa)
- **Norma**: Art. 2250 CC → [`09-codice-civile-2250.md`](normativa/09-codice-civile-2250.md)
- **Sanzione**: 258€–2.065€ (P.IVA); responsabilità amministratori
- **Cosa serve**: n. REA, CCIAA, Albo Cooperativo, capitale sociale (versato/non versato)
- **Chi lo fa**: il cliente fornisce i dati, tu li aggiungi nel footer hardcoded
- [ ] Chiedere al cliente: siete cooperativa? Se sì, fornire dati REA/Albo/capitale
- [ ] Aggiungere i dati nel footer del tema

---

## ⚠️ CONDIZIONATI — Dipendono dal caso specifico

### 5. Indicazione "ETS" nella ragione sociale
- **Quando**: se l'ente è iscritto al RUNTS → [`11-codice-terzo-settore.md`](normativa/11-codice-terzo-settore.md)
- **Cosa fare**: aggiungere "ETS" alla ragione sociale nel footer
- [ ] Chiedere al cliente: siete iscritti al RUNTS?

### 6. Modello Organizzativo 231
- **Quando**: se l'ente lo ha adottato → [`08-dlgs-231-2001.md`](normativa/08-dlgs-231-2001.md)
- **Non è obbligatorio** adottarlo, ma se esiste va pubblicato in Amm. Trasparente
- [ ] Chiedere al cliente: avete un Modello 231?

### 7. Bilancio sociale
- **Quando**: se cooperativa sociale, o ETS con ricavi > 1M€ → [`12-bilancio-sociale.md`](normativa/12-bilancio-sociale.md)
- **Dove**: Amm. Trasparente → "06 Bilanci > Bilancio Sociale"
- Struttura nel tema: ✅ già prevista. Il cliente deve caricare il documento.
- [x] Verificare che il bilancio sia effettivamente pubblicato e visibile

---

## ✅ GIÀ COPERTO — Nel tema o nei plugin

| #  | Cosa                         | Norma           | Dove nel tema                   | Note                                                                                         |
|----|------------------------------|-----------------|---------------------------------|----------------------------------------------------------------------------------------------|
| 1  | Conformità WCAG 2.1 AA       | L. 4/2004       | §3                              | [`01-legge-stanca.md`](normativa/01-legge-stanca.md)                                         |
| 2  | Dichiarazione accessibilità  | AgID            | Footer → form.agid.gov.it       | Aggiornare ogni anno entro il 23/9                                                           |
| 3  | Feedback accessibilità       | AgID            | Google Form esterno             |                                                                                              |
| 4  | Privacy Policy               | GDPR            | Pagina WP nativa                | Manca solo email DPO (vedi §🔴 1)                                                            |
| 5  | Cookie banner + policy       | GDPR + Garante  | CookieYes                       |                                                                                              |
| 6  | Amm. Trasparente (struttura) | D.Lgs 33/2013   | §6 — 12 sezioni                 | [`03-dlgs-33-2013.md`](normativa/03-dlgs-33-2013.md)                                         |
| 7  | Contributi L. 124/2017       | L. 124/2017     | Tassonomia "08 Aiuti Economici" | [`06-legge-124-2017.md`](normativa/06-legge-124-2017.md) — ⚠️ verificare 5 campi obbligatori |
| 8  | Dati legali base             | CC + D.P.R. 633 | Footer hardcoded                | Ragione soc., P.IVA, sede, PEC, CF, cod. mecc.                                               |
| 9  | Date pubblicazione/modifica  | D.Lgs 33/2013   | Tutte le card CPT               |                                                                                              |
| 10 | Bilanci in trasparenza       | D.Lgs 33/2013   | Tassonomia "06 Bilanci"         |                                                                                              |
| 11 | 5x1000 rendiconto            | DPCM 23/07/2020 | Tassonomia "08 Aiuti Economici" | [`10-5x1000.md`](normativa/10-5x1000.md)                                                     |
| 12 | Responsabile Trasparenza     | L. 190/2012     | Organigramma                    | [`07-legge-190-2012.md`](normativa/07-legge-190-2012.md)                                     |

---

## 📅 Scadenze ricorrenti

| Scadenza                          | Cosa                                                       | Chi se ne occupa     |
|-----------------------------------|------------------------------------------------------------|----------------------|
| **31 marzo** ogni anno            | Obiettivi di accessibilità                                 | Webmaster + cliente  |
| **23 settembre** ogni anno        | Aggiornare dichiarazione accessibilità su form.agid.gov.it | Webmaster            |
| **30 giugno** ogni anno           | Pubblicare contributi L. 124/2017 dell'anno precedente     | Cliente (segreteria) |
| Entro **1 anno** dalla percezione | Rendiconto 5x1000                                          | Cliente              |

---

## 🧑‍💻 Domande da fare a ogni nuovo cliente

Prima di mettere online il sito, chiedi:

1. **Siete una cooperativa sociale?** → Se sì: servono n. REA, Albo Coop, CCIAA, capitale sociale nel footer
2. **Siete iscritti al RUNTS?** → Se sì: aggiungere "ETS" alla ragione sociale
3. **Avete un DPO nominato?** → Se no: formalizzare (Centro Servizi come DPO esterno)
4. **Avete un Modello 231?** → Se sì: pubblicarlo in Amm. Trasparente
5. **Pubblicate il bilancio sociale?** → Se obbligatorio: verificare che sia online
6. **Le tabelle contributi MIM hanno tutti i campi?** → Denominazione, CF erogante, importo, data, causale

---

> Solo obblighi che impattano il **sito web**. Obblighi interni (registro trattamenti, DVR, DUVRI, ecc.) non rientrano in questo documento.
> Per i dettagli normativi di ogni voce → cartella [`normativa/`](normativa/).
