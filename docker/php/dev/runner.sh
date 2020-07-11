#!/usr/bin/env bash

# init composer
composer install

# clear old xdebug files
find "/tmp/xdebug" -type f -name 'cachegrind.out.web.*' -exec rm {} \;

# initialize artisan
[[ -z ${APP_KEY} ]] && php /app/artisan key:generate

# start php-fpm
docker-php-entrypoint php-fpm
