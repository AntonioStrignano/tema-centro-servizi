# Catalogo Font Tema

Documento di lavoro per tracciare i font disponibili e quelli da aggiungere al tema.

## Font System (Senza Google Fonts)

Questi font si basano su stack di sistema e non richiedono caricamento esterno:

- `arial` — Arial, Helvetica, sans-serif
- `georgia` — Georgia, "Times New Roman", serif
- `verdana` — Verdana, Geneva, sans-serif
- `times-new-roman` — "Times New Roman", Times, serif
- `trebuchet` — "Trebuchet MS", Tahoma, sans-serif
- `courier` — "Courier New", Courier, monospace
- `garamond` — Garamond, "Times New Roman", serif
- `tahoma` — Tahoma, Geneva, sans-serif
- `helvetica` — Helvetica, Arial, sans-serif
- `system-ui` — system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif

## Font Google Fonts (Caricamento da Google)

Questi font sono disponibili in `inc/settings.php` nella funzione `centro_servizi_get_font_catalog()`:

### Sans Serif

- `roboto` — Roboto (weights: 100, 300, 400, 500, 700, 900)
- `open-sans` — Open Sans (weights: 300, 400, 500, 600, 700, 800)
- `lato` — Lato (weights: 100, 300, 400, 700, 900)
- `montserrat` — Montserrat (weights: 100-900)
- `poppins` — Poppins (weights: 100-900)
- `nunito` — Nunito (weights: 200-900)
- `source-sans-3` — Source Sans 3 (weights: 200, 300, 400, 500, 600, 700, 900)
- `raleway` — Raleway (weights: 100-900)
- `oswald` — Oswald (weights: 200-700)
- `inter` — Inter (weights: 100-900)
- `work-sans` — Work Sans (weights: 100-900)
- `jost` — Jost (weights: 100-900)

### Serif

- `bree-serif` — Bree Serif (weights: 400, 700)
- `croissant-one` — Croissant One (weights: 400)
- `merriweather` — Merriweather (weights: 300, 400, 700, 900)
- `playfair-display` — Playfair Display (weights: 400-900)

---

## Font da Aggiungere

Spazio libero per aggiungere nuovi font man mano che li trovi e vuoi integrare nel tema.

### Candidati (da testare/aggiungere)

(Nessuno al momento)

---

## Note di Integrazione

Quando aggiungi un nuovo font:

1. **Reperibile su Google Fonts?** → Sì → aggiungi a `inc/settings.php` in `centro_servizi_get_font_catalog()`
2. **Slug**: converti il nome da "Font Name" a "font-name" (kebab-case, tutti lowercase)
3. **Label**: "Font Name (Google)" per differenziare dai system font
4. **Google Family**: esatto nome famiglia da `https://fonts.google.com/` (case-sensitive)
5. **Weights**: consulta la pagina Google Fonts e aggiungi gli weights disponibili (array numerico)

**Esempio entry (PHP):**

```php
'font-name-slug' => [
    'label' => 'Font Name (Google)',
    'stack' => '"Font Name", Arial, sans-serif',
    'google_family' => 'Font Name',
    'weights' => [300, 400, 600, 700]
],
```

Ricordati di testare il font nel form admin e nel frontend prima di committare.

---

**Ultimo aggiornamento:** 19 maggio 2026
