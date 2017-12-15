#!/bin/bash
composer install
npm install
php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force
php bin/console doctrine:migrations:version --add --all
php bin/console doctrine:fixtures:load
php bin/console fos:user:change-password admin_marcom
sh cl.sh



## PERMISSIONS

## LINUX
# HTTPDUSER=`ps axo user,comm | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1`
# sudo setfacl -R -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX var
# sudo setfacl -dR -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX var

## MAC
# rm -rf var/cache/* var/logs/* var/sessions/*
# HTTPDUSER=`ps axo user,comm | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1`
# sudo chmod -R +a "$HTTPDUSER allow delete,write,append,file_inherit,directory_inherit" var
# sudo chmod -R +a "`whoami` allow delete,write,append,file_inherit,directory_inherit" var