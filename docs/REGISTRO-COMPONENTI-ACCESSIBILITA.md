# Registro Componenti Accessibilita

Scopo: tenere traccia dei componenti accessibilita implementati nel tema, con evidenze tecniche e stato verifica.

Come usarlo:
- Aggiornare questo file ogni volta che viene introdotto o modificato un componente accessibilita.
- In fase di autocertificazione usare la colonna Evidenze come riferimento rapido.
- Non sostituisce i test manuali finali (tastiera, screen reader, contrasti, zoom, validazione).

## Stato sintetico

- Implementato e chiuso lato sviluppo: skip links, landmark principali, breadcrumb accessibile, avvisi link esterni, riduzione movimento CSS, componente filtri dinamici accessibili.
- Da verificare in QA finale: controlli contrasti su tutte le combinazioni preset, validazione completa pagine legali seed.

## Inventario componenti

| ID      | Componente                                                                     | Stato        | Evidenze tecniche                                                                                                                                               | Note QA                                                         |
|---------|--------------------------------------------------------------------------------|--------------|-----------------------------------------------------------------------------------------------------------------------------------------------------------------|-----------------------------------------------------------------|
| A11Y-01 | Skip links globali                                                             | Implementato | partials/skip-links.php, partials/header.php, templates/front-page.php, inc/accessibility.php, assets/css/site.css                                              | Verificare ordine tab da inizio pagina                          |
| A11Y-02 | Landmark principali (banner, navigation, main, contentinfo)                    | Implementato | partials/chrome-header.php, templates/page.php, templates/page-legale.php, templates/archive-area-burocratica-common.php, partials/footer.php                   | Verificare tutte le pagine custom residue                       |
| A11Y-03 | Focus visibile globale                                                         | Implementato | assets/css/site.css, assets/css/area-burocratica.css                                                                                                            | Verificare contrasto outline su preset chiari/scuri             |
| A11Y-04 | Utility sr-only e testi di supporto                                            | Implementato | assets/css/site.css, partials/card-trasparenza.php, partials/card-area-famiglie.php, partials/card-area-personale.php, partials/footer.php                      | Verificare output su tutti i link target blank                  |
| A11Y-05 | Breadcrumb accessibile con aria-current                                        | Implementato | partials/breadcrumb.php                                                                                                                                         | Verificare assenza duplicazioni nei template pagina             |
| A11Y-06 | Avviso accessibile su link esterni/nuova finestra                              | Implementato | partials/card-trasparenza.php, partials/card-area-famiglie.php, partials/card-area-personale.php, partials/page-contatti-recapiti.php, templates/front-page.php | Verificare coerenza microcopy                                   |
| A11Y-07 | Riduzione movimento con prefers-reduced-motion                                 | Implementato | assets/css/site.css, assets/css/area-burocratica.css                                                                                                            | Verificare assenza animazioni bloccanti                         |
| A11Y-08 | Filtri dinamici accessibili (live region, focus post refresh, fallback errore) | Implementato | inc/accessibility.php, templates/archive-area-burocratica-common.php                                                                                            | Verificare con tastiera + screen reader su archivio burocratico |

## Dettaglio componente filtri dinamici

Nome interno:
- Componente filtri dinamici accessibili

Copertura:
- Archivio area burocratica comune (trasparenza, area famiglie, area personale)

Funzioni helper:
- centro_servizi_get_dynamic_filters_a11y_messages
- centro_servizi_render_dynamic_filters_live_region

Comportamenti garantiti:
- Annuncio stato caricamento risultati
- Annuncio stato aggiornamento risultati con summary
- Annuncio errore con fallback reload URL filtrato
- Focus post aggiornamento su target summary risultati

## Traccia verifiche (compilare in QA)

| Data       | Scenario                                 | Esito | Note                                                        |
|------------|------------------------------------------|-------|-------------------------------------------------------------|
| 2026-06-15 | Tastiera: cambio filtri anno e categoria | OK    | Cambio automatico coerente, focus sul riepilogo risultati   |
| 2026-06-15 | Screen reader: annuncio live region      | OK    | Annunci presenti e comprensibili                            |
| 2026-06-15 | Fallback errore fetch                    | OK    | Comportamento coerente con redirect su URL filtrato         |
| 2026-06-15 | Verifica zoom 200 percento               | OK    | Usabilita ridotta per header sticky, ma categorie leggibili |

## Log aggiornamenti

- 2026-06-15: creato registro componenti accessibilita.
- 2026-06-15: registrato componente filtri dinamici accessibili con helper riusabili e focus management.
- 2026-06-15: componente filtri dinamici accessibili considerato chiuso lato sviluppo; resta solo la verifica QA manuale.
- 2026-06-15: verifica manuale completata; il solo limite osservato e la densita dell'header sticky al 200% zoom.
