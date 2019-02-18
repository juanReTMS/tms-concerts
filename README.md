# Konzertverwaltung für die TMS Bad Oldesloe

Eine Konzertverwaltungs Webanwendung für die TMS Bad Oldesloe mit Symfony 4.2

Requirements:  
php >= 7.1


Setup:  
composer install


Server starten/stoppen:  
*php bin/console server:start*  
*php bin/console server:stop* 

Datenbankschema updaten:  
*php bin/console doctrine:schema:update*  
Eventuell muss die --force Option benutz werden:  
*php bin/console doctrine:schema:update --force*   

Fixtures laden:   
**Achtung, dies löscht alle Einträge in der Datenbank**  
*php bin/console doctrine:fixtures:load -n* 
