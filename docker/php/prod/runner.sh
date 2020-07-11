#!/usr/bin/env bash

# init composer
composer install

# initialize artisan
[[ -z ${APP_KEY} ]] && php /app/artisan key:generate

# start php-fpm
docker-php-entrypoint php-fpm
