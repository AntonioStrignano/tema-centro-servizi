# Storico struttura homepage

Data: 2026-05-20

## Obiettivo
Definire una base solida della homepage prima del lavoro estetico.

## Priorita
1. Chiarezza immediata: chi siamo, per chi siamo, cosa offriamo.
2. Navigazione per intenti: Famiglie, Personale, Trasparenza, Contatti.
3. Azioni principali visibili: Contatti, Iscrizioni, Orari, Modulistica.
4. Fiducia e credibilita: recapiti reali, dati legali, pagine obbligatorie.
5. Manutenibilita: sezioni modulari aggiornabili dal backend.

## Struttura proposta
1. Hero funzionale
- Value proposition breve
- Sottotitolo chiaro
- 2 CTA principali

2. Accesso rapido utenti
- 3 card principali: Famiglie, Personale, Trasparenza
- Link task-based per ogni card

3. Info operative
- Orari
- Contatti rapidi
- Avvisi urgenti

4. Sezione istituzionale
- Chi siamo (sintesi)
- Link di approfondimento

5. News/attivita
- Ultimi 3 contenuti con data
- Link a archivio completo

6. Trasparenza e legale
- Accesso rapido a pagine obbligatorie
- Data ultimo aggiornamento documenti

7. Footer istituzionale
- Dati ente
- Link policy
- Contatti

## Regole di contenuto
1. Una domanda utente per sezione.
2. Testi brevi e orientati all'azione.
3. CTA primaria unica per viewport.
4. Date sempre visibili nei contenuti dinamici.
5. Linguaggio semplice in home, dettaglio normativo nelle pagine dedicate.

## Dati da definire subito
```json
{
  "audience_prioritarie": ["famiglie", "personale", "enti/trasparenza"],
  "azioni_top_5": ["contattare", "consultare_orari", "scaricare_moduli", "leggere_avvisi", "accedere_trasparenza"],
  "contenuti_obbligatori_home": ["contatti", "orari", "link_legali", "ultimi_aggiornamenti"],
  "kpi_90_giorni": ["click_contatti", "click_iscrizioni", "tempo_per_trovare_orari", "rimbalzo_home"]
}
```
