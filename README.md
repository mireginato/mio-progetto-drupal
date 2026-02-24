# Appunti Sviluppo Drupal

### Avvio e Arresto
- `ddev start` / `ddev stop`
- `ddev xdebug on` (per attivare il debug)

### Comandi Drush Utili
- `ddev drush cr` (pulisce la cache)
- `ddev drush en il_mio_modulo` (attiva il modulo)
- `ddev drush pmu il_mio_modulo` (disinstalla il modulo)

### Struttura Modulo Custom
- Percorso: `web/modules/custom/il_mio_modulo`
- Rotta: `il_mio_modulo.routing.yml`
- Controller: `src/Controller/TestController.php`
- Blocco: `src/Plugin/Block/SalutoBlock.php`

### Database
- Tipo: PostgreSQL 17
- Backup: `ddev snapshot --name nome_backup`



# mio-progetto-drupal
