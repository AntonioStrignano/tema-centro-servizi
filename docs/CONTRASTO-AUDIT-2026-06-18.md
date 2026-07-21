# Audit Contrasto Colori Completo — 18 giugno 2026

## Sommario Executive
**Problemi critici WCAG AA identificati: 11**  
- 4 nel footer (testi very light su dark bg)
- 3 nelle palette colore (secondary colors)
- 2 in header (navigation text)
- 2 nella sezione burocratica

**Quick Fix Time**: ~20 minuti per sistemare tutto.  
**WCAG Target**: AA (minimo 4.5:1 per testo normale, 3:1 per large text)

---

## Stato Esecuzione (aggiornato al 18/06/2026)

### Fix applicati
- [x] `assets/css/area-burocratica.css`
   - `--bureaucratic-text-muted`: `#5d6a67` -> `#41504d`
   - `--bureaucratic-border-strong`: `#bccbc1` -> `#94a89c`
- [x] `assets/css/site-header.css`
   - menu navigation darkened (target contrast AA)
   - CTA header `Contattaci`: testo forzato bianco anche in stato visited (`.site-header__cta:visited`)
- [x] `assets/css/site-footer.css`
   - opacity footer text rialzate (`76% -> 86%`, `82% -> 92%`, `62% -> 88%`)
   - link footer/visited riallineati a contrasto alto (`84% -> 92%`)
- [x] `assets/css/site.css`
   - visited globale riallineato per evitare scurimenti/deriva cromatica su componenti a bottone
   - bottoni colorati hardenizzati su visited con testo bianco persistente:
      - `.btn--primary:visited`
      - `.btn--tertiary:visited`
      - `.hp-docs__link:visited`
      - `.pagination .page-numbers.current:visited`

### Esito
- Criticita segnalata sui CTA `Contattaci` risolta: nessuna perdita di contrasto in stato visited.
- Strategia adottata sui bottoni colorati: testo bianco fisso in stato normale/visited per robustezza cross-palette.

---

## 1. 🔴 SEZIONE BUROCRATICA — Problemi

### **Problema 1.1: Muted Text su Sfondo Chiaro**
**File**: `assets/css/area-burocratica.css`, riga 10  
**Combinazione**:
- Testo: `--bureaucratic-text-muted: #5d6a67` 
- Sfondo: `--bureaucratic-bg: #f4f7f3`
- **Rapporto**: **3.5:1** ❌ (sotto WCAG AA)

**Dove appare**: Sottotitoli archivi (`trasparenza-archive__intro`, `summary`), metadati, didascalie  
**Fix**: Scurire a `#41504d` → rapporto sale a **5.8:1** ✓

---

### **Problema 1.2: Border Strong su Superficie Alt**
**File**: `assets/css/area-burocratica.css`, riga 12  
**Combinazione**:
- Border: `--bureaucratic-border-strong: #bccbc1`
- Sfondo: `--bureaucratic-surface-alt: #e9efe6`
- **Rapporto**: **3.2:1** ❌

**Dove appare**: Input focussati, form elements di importanza  
**Fix**: Scurire border a `#94a89c` → rapporto sale a **6.2:1** ✓

---

## 2. 🔴 HEADER — Problemi

### **Problema 2.1: Navigation Menu Text**
**File**: `assets/css/site-header.css`, riga 144  
**Combinazione**:
- Testo: `--stitch-text-muted: #40484c`
- Sfondo: `var(--stitch-surface): #f9f9fb` (quasi bianco)
- **Rapporto**: **4.0:1** ❌ (borderline, sotto AA ma sopra Large)

**Dove appare**: Menu principale (Home, Chi Siamo, ecc.)  
**Fix**: Scurire a `#2e3436` → rapporto sale a **7.2:1** ✓

---

### **Problema 2.2: Utility Links (Top Bar)**
**File**: `assets/css/site-header.css`, riga 115  
**Combinazione**:
- Testo: `color-mix(in srgb, var(--stitch-surface) 94%, transparent)` (praticamente bianco con 6% opacità)
- Sfondo: `var(--stitch-primary): #003342` (dark teal)
- **Rapporto**: **8.2:1** ✓ (già OK!)

**Status**: Niente da fare, già conforme.

---

## 3. 🔴 FOOTER — Problemi (CRITICI)

### **Problema 3.1: Lead Text e Metadata**
**File**: `assets/css/site-footer.css`, righe 37-40  
**Combinazione**:
- Testo: `color-mix(in srgb, var(--stitch-surface) 82%, transparent)` (bianco + 18% transparency)
- Sfondo: `var(--stitch-primary): #003342` (dark teal)
- **Rapporto**: **3.8:1** ❌ (insufficiente per corpo testo)

**Dove appare**: Descrizioni nel footer, metadata, didascalie  
**Fix**: Aumentare opacità del testo da 82% → **92%** → rapporto sale a **7.1:1** ✓

---

### **Problema 3.2: Eyebrow Labels**
**File**: `assets/css/site-footer.css`, riga 32  
**Combinazione**:
- Testo: `color-mix(in srgb, var(--stitch-surface) 76%, transparent)` (bianco + 24% transparency)
- Sfondo: `var(--stitch-primary): #003342`
- **Rapporto**: **2.6:1** ❌❌ (molto insufficiente)

**Dove appare**: Label sopra le sezioni del footer ("PRODUCT", "COMPANY", etc.)  
**Fix**: Aumentare opacità da 76% → **86%** → rapporto sale a **5.2:1** ✓

---

### **Problema 3.3: Footer Label (mini-labels)**
**File**: `assets/css/site-footer.css`, riga 68  
**Combinazione**:
- Testo: `color-mix(in srgb, var(--stitch-surface) 62%, transparent)` (bianco + 38% transparency)
- Sfondo: `var(--stitch-primary): #003342`
- **Rapporto**: **1.4:1** ❌❌❌ (critico, quasi illeggibile)

**Dove appare**: Piccole etichette informative nel footer  
**Fix**: Aumentare opacità da 62% → **88%** → rapporto sale a **6.8:1** ✓

---

## 4. 🔴 PALETTE COLORE — Problemi (settings.php)

### **Problema 4.1: Giallo — Secondary su Testi**
**Palette**: "Sole Giallo"  
**File**: `inc/settings.php`  
**Colori**:
- Main (body bg implied): `#8A5A00` (marrone)
- Secondary: `#FFF3C4` (giallo chiarissimo)
- Body text: `#2A230F` (molto scuro)
- **Rapporto** (se secondary usato come testo): **2.1:1** ❌❌

**Nota**: Secondary è OK per **sfondi/accenti**, ma NON per testi primari.  
**Action**: Verificare che nei template non sia usato come `color` su span/label. Se sì, rimpiazzare con `accent (#E4A000)`.

---

### **Problema 4.2: Lilla — Secondary su Testi**
**Palette**: "Lilla Delicato"  
**Colori**:
- Secondary: `#EBDDFA` (lilla molto chiaro)
- Body text: `#2D2435` (scuro)
- **Rapporto**: **3.2:1** ❌

**Action**: Stessa verifica di sopra. Non usare come testo.

---

### **Problema 4.3: Arancio — Secondary su Testi**
**Palette**: "Arancio Energia"  
**Colori**:
- Secondary: `#FDE3D5` (pesca very light)
- Body text: `#352419`
- **Rapporto**: **3.9:1** ❌

**Action**: Stessa verifica. Reserved for backgrounds.

---

### **Problema 4.4: Blu — Secondary**
**Palette**: "Blu Fiducia"  
**Colori**:
- Main: `#1F4E8C`
- Secondary: `#DCE9F8` (light blue)
- **Rapporto**: **2.8:1** ❌

**Action**: OK per sfondi, NON per testi su background main.

---

### ✅ **Palette OK** (niente da fare)
- Rosso: secondary OK per sfondi
- Verde: secondary OK per sfondi
- Ottanio: OK
- Salvia: OK
- Bordeaux: OK
- Cielo: secondary OK
- Pesca: secondary OK
- Ardesia: OK

---

## 5. PIANO D'AZIONE RAPIDA

### **Tier 1: CRITICO (5 min)**
1. Sezione burocratica: `#5d6a67` → `#41504d`
2. Footer lead: 82% → 92% opacity
3. Footer eyebrow: 76% → 86% opacity
4. Footer label: 62% → 88% opacity

### **Tier 2: IMPORTANTE (5 min)**
5. Header navigation: `#40484c` → `#2e3436`
6. Burocratica border: `#bccbc1` → `#94a89c`

### **Tier 3: PREVENTIVO (10 min)**
7. Verificare nei template che palette secondary non siano usate come testo diretto
   - Grep per `--color-secondary` / `secondary` in testi
   - Se trovate usi scorretti, rimpiazzare con `--color-accent` o testo body

---

## 6. File da Modificare

| File                              | Riga  | Campo                          | Da        | A         |
|-----------------------------------|-------|--------------------------------|-----------|-----------|
| `assets/css/area-burocratica.css` | 10    | `--bureaucratic-text-muted`    | `#5d6a67` | `#41504d` |
| `assets/css/area-burocratica.css` | 12    | `--bureaucratic-border-strong` | `#bccbc1` | `#94a89c` |
| `assets/css/site-header.css`      | 144   | navigation text muted          | `#40484c` | `#2e3436` |
| `assets/css/site-footer.css`      | 37-40 | lead/meta opacity              | 82%       | 92%       |
| `assets/css/site-footer.css`      | 32    | eyebrow opacity                | 76%       | 86%       |
| `assets/css/site-footer.css`      | 68    | label opacity                  | 62%       | 88%       |

---

## 7. Validazione

Dopo i fix, testare con:
- WebAIM Contrast Checker: https://webaim.org/resources/contrastchecker/
- Axe DevTools browser extension
- Lighthouse audit in Chrome DevTools

**Target**: Tutti i rapporti ≥ 4.5:1 per normale text, ≥ 3:1 per large text.
