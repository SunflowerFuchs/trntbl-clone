FROM debian:latest

# install php 7.4 (newest version currently compatible with trntbl)
RUN apt update && \
	apt -y install lsb-release ca-certificates curl && \
	curl -sSLo /tmp/debsuryorg-archive-keyring.deb https://packages.sury.org/debsuryorg-archive-keyring.deb && \
	dpkg -i /tmp/debsuryorg-archive-keyring.deb && \
	sh -c 'echo "deb [signed-by=/usr/share/keyrings/debsuryorg-archive-keyring.gpg] https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt/sources.list.d/php.list' && \
	apt update && \
	apt install -y --no-install-recommends \
		php7.4-fpm \
		php7.4-dom \
		php7.4-mbstring \
		php7.4-zip \
		unzip \
		curl \
		nginx && \
	rm -rf /var/lib/apt/lists/*

# enable fpm to listen on a port instead of a socket
RUN sed -ri 's/^listen = .+/listen = 0.0.0.0:9000/' /etc/php/7.4/fpm/pool.d/www.conf
EXPOSE 9000

# copy all application code
RUN mkdir /app
COPY . /app
RUN mkdir -p /app/storage/framework/sessions \
		     /app/storage/framework/views \
			 /app/storage/framework/cache && \
	chown -R www-data:www-data /app
WORKDIR /app

# composer
COPY --from=composer /usr/bin/composer /usr/bin/composer
RUN composer install && chown -R www-data:www-data /app/vendor

# run php-fpm directly
CMD [ "php-fpm7.4", "--nodaemonize" ]
