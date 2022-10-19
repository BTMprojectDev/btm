#!/usr/bin/env bash

rm -Rf var/cache/*

mkdir ./config/jwt

COMPOSER_ALLOW_SUPERUSER=1 docker-compose exec -T btm-php composer self-update
COMPOSER_ALLOW_SUPERUSER=1 docker-compose exec -T btm-php composer update --no-interaction --classmap-authoritative --optimize-autoloader

docker-compose exec -T btm-php php bin/console doctrine:database:create --if-not-exists
#docker-compose exec -T btm-php php bin/console doctrine:migrations:migrate --no-interaction
