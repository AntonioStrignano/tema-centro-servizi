# Whistleblowing su Hetzner Cloud - Setup operativo

Runbook pratico per pubblicare GlobaLeaks su Hetzner Cloud e collegarlo ai siti WordPress delle scuole.
Obiettivo: una singola istanza dedicata al whistleblowing, separata dai siti pubblici.

## Costi indicativi (aggiornato maggio 2026)

```json
{
  "vm_suggerita": "Hetzner Cloud piano entry disponibile (2 vCPU nel tuo account)",
  "range_costo_mensile": "fascia bassa (circa pochi euro al mese, IVA esclusa)",
  "ssl": "gratis con Let's Encrypt",
  "ipv4": "Primary IPv4 attiva (obbligatoria)",
  "traffico": "2 TB inclusi: overkill per questo scenario",
  "note": "Confronta sempre il prezzo finale in checkout, inclusi backup opzionali."
}
```

## Architettura consigliata

- 1 VM Hetzner dedicata solo a GlobaLeaks
- 1 sottodominio centralizzato: segnalazioni.centroservizi.it
- Istanza GlobaLeaks multi-contesto (una scuola = un contesto interno)
- HTTPS obbligatorio
- Nessun WordPress sulla stessa VM

## Prerequisiti

- Account Hetzner Cloud attivo
- Accesso DNS del dominio
- Chiave SSH sul Mac
- Elenco scuole + destinatari segnalazioni

Genera chiave SSH se non ce l hai:

```bash
ssh-keygen -t ed25519 -C "whistleblowing-hetzner"
cat ~/.ssh/id_ed25519.pub
```

## Sequenza operativa completa

### 1) Crea progetto e VM su Hetzner

Nel pannello Hetzner Cloud:

1. Crea progetto (esempio: whistleblowing)
2. Add Server
3. Seleziona:
   - Location: Germania (consigliata)
   - Image: Ubuntu 24.04 LTS
   - Server type: piano minimo disponibile nel tuo account (nel tuo caso 2 vCPU)
   - Networking: Primary IPv4 attiva
   - SSH keys: aggiungi la tua chiave pubblica
   - Name: globaleaks-prod-01
4. Crea server

Annota subito l IP pubblico.

### 2) Configura firewall Hetzner

Crea firewall e applicalo alla VM:

- TCP 22 da tuo IP pubblico
- TCP 80 da 0.0.0.0/0
- TCP 443 da 0.0.0.0/0

Non aprire altre porte.

### 3) Accesso SSH e hardening base

Connessione:

```bash
ssh root@IP_VM
```

Aggiornamenti base:

```bash
apt update && apt upgrade -y
timedatectl set-timezone Europe/Rome
```

Crea utente operativo e abilita sudo:

```bash
adduser deploy
usermod -aG sudo deploy
```

Copia chiave SSH su deploy (dal tuo Mac):

```bash
ssh-copy-id deploy@IP_VM
```

Poi entra con deploy e disabilita login root/password in sshd_config.

### 4) Firewall OS (UFW)

```bash
sudo ufw default deny incoming
sudo ufw default allow outgoing
sudo ufw allow 22/tcp
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw enable
sudo ufw status
```

### 5) DNS

Configura record A:

- Host: segnalazioni.centroservizi.it
- Tipo: A
- Valore: IP_VM

Test:

```bash
dig +short segnalazioni.centroservizi.it
```

Deve restituire l IP della VM.

### 6) Installa GlobaLeaks

Usa solo documentazione ufficiale aggiornata:

- https://docs.globaleaks.org/
- https://github.com/globaleaks/globaleaks-whistleblowing-software

Nota: i comandi di installazione possono cambiare nel tempo, quindi segui la guida ufficiale del momento.

### 7) HTTPS

Configura certificato TLS con il metodo previsto dalla guida ufficiale GlobaLeaks.

Controlla che:

- il certificato sia valido
- il dominio risponda in HTTPS
- non ci siano warning browser

### 8) Bootstrap applicativo

Al primo accesso:

1. imposta lingua italiana
2. crea admin
3. crea contesto per ogni scuola
4. assegna i destinatari delle segnalazioni
5. configura testi minimi informativi

### 9) Test end-to-end obbligatorio

Prima del go-live fai un test completo:

1. invio segnalazione anonima di prova
2. recupero con codice univoco
3. presa in carico lato ricevente
4. risposta al segnalante
5. nuova apertura con codice e verifica risposta

Se un punto fallisce, non andare live.

### 10) Pubblicazione link sui siti

Nei siti WordPress attuali aggiungi subito:

- link footer o sezione Amministrazione Trasparente
- pagina informativa whistleblowing con link esterno

URL esempio:

- https://segnalazioni.centroservizi.it

## Backup e continuita

- Attiva backup/snapshot su Hetzner
- Definisci frequenza minima (es. giornaliera o settimanale)
- Verifica periodicamente il ripristino

## Checklist rapida finale

```json
{
  "vm_running": true,
  "firewall_hetzner_ok": true,
  "ufw_ok": true,
  "dns_ok": true,
  "https_ok": true,
  "globaleaks_reachable": true,
  "test_e2e_ok": true,
  "link_pubblicato_sui_siti": true,
  "backup_attivo": true
}
```

## Scelte operative per minimizzare costo e complessita

```json
{
  "provider": "Hetzner Cloud",
  "region": "Germania",
  "cpu": "piano minimo disponibile nel tuo account (2 vCPU va benissimo)",
  "ipv4": "abilitata",
  "approccio": "1 VM centralizzata multi-scuola",
  "upgrade": "solo se servono risorse aggiuntive reali"
}
```
