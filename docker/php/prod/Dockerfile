FROM php:7.4-fpm

RUN apt-get update --yes && apt-get install -y --no-install-recommends unzip

# add composer
COPY --from=composer /usr/bin/composer /usr/bin/composer

# Install required php extensions
#ADD https://raw.githubusercontent.com/mlocati/docker-php-extension-installer/master/install-php-extensions /usr/local/bin/
#RUN chmod uga+x /usr/local/bin/install-php-extensions && sync && \
#    install-php-extensions \
#    calendar \
#    exif \
#    gd \
#    gettext \
#    intl \
#    mysqli \
#    pcntl \
#    pdo_mysql \
#    shmop \
#    soap \
#    sockets \
#    sysvmsg \
#    sysvsem \
#    sysvshm \
#    xsl \
#    zip

# update the path to add composer binaries
ENV PATH="/app/vendor/bin:${PATH}"
# add the composer home for caching etc.
ENV COMPOSER_HOME="/tmp/composer/"

# set the default directory
WORKDIR /app

# set the default user
ARG USER_ID=1000
USER $USER_ID

# install runner script and set it as default command
COPY runner.sh /usr/local/bin/runner.sh
CMD /usr/bin/env bash /usr/local/bin/runner.sh
