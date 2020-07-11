#!/usr/bin/env bash

# init composer
composer install --no-dev

# initialize artisan
[[ -z ${APP_KEY} ]] && php /app/artisan key:generate

# start php-fpm
docker-php-entrypoint php-fpm
