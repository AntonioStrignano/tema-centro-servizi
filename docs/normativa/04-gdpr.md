# GDPR — Reg. UE 2016/679 + D.Lgs 196/2003

## Cos'è

Il **GDPR** (General Data Protection Regulation, Regolamento UE 2016/679) è il regolamento europeo sulla protezione dei dati personali. Il **D.Lgs 196/2003** (Codice Privacy) è la normativa italiana coordinata con il GDPR dopo le modifiche del D.Lgs 101/2018.

## Testo normativo

- GDPR: [EUR-Lex](https://eur-lex.europa.eu/legal-content/IT/TXT/?uri=CELEX%3A32016R0679)
- D.Lgs 196/2003 (testo coordinato): [Normattiva](https://www.normattiva.it/uri-res/N2Ls?urn:nir:stato:decreto.legislativo:2003-06-30;196)
- Provvedimento Garante 10/06/2021 (cookie): [Garante Privacy](https://www.garanteprivacy.it/home/docweb/-/docweb-display/docweb/9677876)

## Chi è obbligato

**Tutti** i titolari del trattamento di dati personali. Le scuole paritarie trattano:
- Dati di alunni (minori)
- Dati di famiglie/genitori
- Dati del personale dipendente
- Eventuali dati sanitari (allergie, certificati medici)

## Cosa deve esserci sul sito

### Privacy Policy (art. 13 GDPR)

Informativa completa che indichi:
- Identità e contatti del titolare del trattamento
- **Contatti del DPO** (se nominato — ed è obbligatorio, vedi sotto)
- Finalità e base giuridica del trattamento
- Destinatari dei dati
- Trasferimenti extra-UE (es. Google Analytics)
- Diritti dell'interessato
- Periodo di conservazione

| Stato tema | ✅ Pagina WP nativa Privacy + CookieYes (§19.7) |
|------------|--------------------------------------------------|

### Cookie Policy + Banner (Provv. Garante 10/06/2021)

- **Cookie banner** con consenso preventivo, possibilità di rifiuto, granularità per categoria
- Informativa cookie specifica (può essere sezione della privacy policy)
- Il banner deve apparire alla prima visita e non riapparire se l'utente ha scelto

| Stato tema | ✅ Gestito da CookieYes (§19.7) |
|------------|----------------------------------|

### DPO — Data Protection Officer (art. 37)

**Il DPO è OBBLIGATORIO** per le scuole paritarie perché:

1. **Art. 37(1)(c) GDPR**: obbligatorio quando il trattamento consiste in "trattamento su larga scala di categorie particolari di dati". I dati dei minori e le informazioni sanitarie nelle scuole rientrano.
2. Il **Garante Privacy italiano** ha confermato più volte che tutte le scuole (pubbliche e paritarie) devono nominare un DPO.
3. Congregazioni con 50+ dipendenti → trattamento su larga scala anche sotto il profilo quantitativo.

**Stato attuale**: 🔴 **DA DEFINIRE**

Il DPO sarà presumibilmente **Centro Servizi** in qualità di DPO esterno. Da formalizzare con ogni singolo cliente (contratto di nomina DPO).

**Cosa serve sul sito:**
- Email di contatto del DPO nella **Privacy Policy** (es. `dpo@nomescuola.it` o `privacy@centroservizi.it`)
- Non è necessario il nominativo completo per il DPO esterno — basta qualifica + contatto

## Sanzioni

Le sanzioni GDPR sono tra le più alte in assoluto:
- **Fino a 10 milioni di euro** o 2% del fatturato annuo per violazioni organizzative (inclusa mancata nomina DPO)
- **Fino a 20 milioni di euro** o 4% del fatturato per violazioni dei diritti degli interessati
- Nella pratica il Garante italiano procede gradualmente: ammonimento → ingiunzione → sanzione
- Per le scuole le sanzioni effettive sono più contenute (decine di migliaia di euro), ma il rischio è reale

## Cosa devi sapere come webmaster

1. **La privacy policy non la scrivi tu** — deve essere redatta o validata da un consulente privacy/DPO. Tu la pubblichi come pagina WP.
2. **CookieYes** gestisce banner e cookie policy automaticamente — assicurati che sia configurato con le categorie corrette (necessari, analytics, marketing)
3. Il sito **non deve avere cookie propri** (il tema non ne imposta, i commenti sono disabilitati)
4. Se usi Google Analytics (Site Kit): va configurato in CookieYes come cookie "analytics" con consenso preventivo
5. L'**email del DPO** va aggiunta nella privacy policy appena definita. Non hardcodarla nel tema — la gestisce il cliente dalla pagina WP.
6. **Mai** pubblicare dati personali non necessari (foto minori senza consenso, elenchi nominativi, ecc.)
