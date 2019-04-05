#!/bin/bash
echo "##########################################################################"
echo "# Refresh data model, reload all reference data, load fixtures,          #"
echo "# validate schema for the dev env.                                       #"
echo "##########################################################################"

php bin/console doctrine:schema:update --force --env=test
php bin/console doctrine:fixtures:load -n --env=test
echo -e " --> DONE\n"