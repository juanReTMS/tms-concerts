# Konzertverwaltung für die TMS Bad Oldesloe

Eine Konzertverwaltungs Webanwendung für die TMS Bad Oldesloe mit Symfony 4.2

### Abhängigkeiten:  
php >= 7.1


### Setup:  
```bash
composer install
```
### Server starten/stoppen: 
```bash
php bin/console server:start
php bin/console server:stop
```

### Datenbankschema updaten: 
```bash
php bin/console doctrine:schema:update
```
Eventuell muss die --force Option benutzt werden: 
```bash
php bin/console doctrine:schema:update --force
``` 

### Fixtures laden:   
Achtung, dies löscht alle Einträge in der Datenbank  
```bash
php bin/console doctrine:fixtures:load -n
```

