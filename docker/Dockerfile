FROM php:7.4-fpm

RUN apt-get update --yes && apt-get upgrade --yes && apt-get install -y --no-install-recommends \
    apt-utils \
    unzip

# add composer
COPY --from=composer /usr/bin/composer /usr/bin/composer

# Install required php extensions
# Note: the commented out lines are already installed in the base image
ADD https://raw.githubusercontent.com/mlocati/docker-php-extension-installer/master/install-php-extensions /usr/local/bin/
RUN chmod uga+x /usr/local/bin/install-php-extensions && sync && \
    install-php-extensions \
    calendar \
#    ctype \
#    date \
    exif \
#    fileinfo \
#    filter \
#    ftp \
    gettext \
#    hash \
#    iconv \
#    json \
#    libxml \
    mysqli \
#    mysqlnd \
#    openssl \
    pcntl \
#    pcre \
    pdo_mysql \
#    posix \
#    readline \
#    session \
    shmop \
    sockets \
#    sodium \
    sysvmsg \
    sysvsem \
    sysvshm \
#    tokenizer \
    zip
#    zlib

