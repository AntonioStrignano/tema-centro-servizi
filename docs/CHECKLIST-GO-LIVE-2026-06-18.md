# Checklist Go-Live — 2026-06-18

Obiettivo: chiudere il rilascio senza zone grigie, in ordine pratico.

## 1) Blocco tecnico (prima di tutto)

- [ ] Eseguire audit accessibilita con axe-core sulle pagine chiave:
  - home
  - archivio trasparenza
  - area famiglie
  - area personale
  - 1 pagina legale
  - pagina attivita
- [ ] Eseguire audit Lighthouse (accessibility + performance) sulle stesse pagine.
- [ ] Eseguire passata tastiera end-to-end:
  - skip links
  - menu mobile
  - filtri archivi
  - modale gallery attivita
  - footer link legali
- [ ] Eseguire test VoiceOver su macOS (annunci live region, ordine lettura, focus).
- [ ] Eseguire verifica HTML W3C sulle pagine chiave.
- [ ] Verificare su frontend:
  - date pubblicazione/modifica su tutte le card
  - alt immagini effettivi
  - heading senza salti
  - link obbligatori in footer funzionanti

Output minimo richiesto:
- report sintetico con esito PASS/FAIL per ogni pagina + fix applicati.

## 2) Blocco contenuti (subito dopo il tecnico)

- [ ] Revisione finale pagine seed legali:
  - Privacy Policy
  - Cookie Policy
  - Dichiarazione di accessibilita
  - Whistleblowing
  - Obiettivi di accessibilita
- [ ] Verificare testo fallback privacy coerente con catena:
  - DPO
  - Legale rappresentante
  - Centro Servizi
- [ ] Chiudere copy pagina Chi siamo (versione easy cliente).
- [ ] Rifiniture non bloccanti residue:
  - micro-polish contatti
  - vetrina homepage se necessaria

Output minimo richiesto:
- conferma "testi validati" da parte di chi approva i contenuti.

## 3) Blocco cliente/compliance operativa

- [ ] Whistleblowing operativo cliente:
  - URL piattaforma definitivo
  - responsabile canale interno
  - verifica apertura link dal footer
- [ ] Dichiarazione accessibilita su AGID:
  - compilata
  - URL finale inserito in impostazioni
- [ ] Obiettivi accessibilita annuali 2026:
  - contenuti pubblicati
  - presenza link footer verificata
- [ ] Verifica contributi L.124/2017:
  - 5 campi obbligatori completi
- [ ] Verifiche anagrafiche cliente (se applicabili):
  - REA/Albo/CCIAA/capitale
  - RUNTS/ETS
  - Modello 231

Output minimo richiesto:
- conferma checklist cliente firmata o validata via email.

## 4) Gate finale di rilascio

Rilascio consentito solo se:
- [ ] Blocco tecnico chiuso
- [ ] Blocco contenuti chiuso
- [ ] Blocco cliente/compliance chiuso
- [ ] Nessun punto "critico" aperto

Se anche uno solo dei 4 gate e aperto: rilascio rinviato.

## 5) Post go-live (24h)

- [ ] Smoke test rapido produzione (home, trasparenza, attivita, legali).
- [ ] Ricontrollo link esterni (AGID, whistleblowing, cookie).
- [ ] Verifica assenza regressioni evidenti su mobile.
