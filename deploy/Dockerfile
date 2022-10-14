FROM php:8.1.0-apache AS base
ENV APP_VERSION=0.33.6
RUN a2enmod rewrite
RUN apt-get update \
    && apt-get install -y libmemcached-dev zlib1g-dev libzip-dev zip
RUN pecl channel-update pecl.php.net \
    && pecl install memcache-8.0
RUN docker-php-ext-install pdo_mysql opcache
RUN docker-php-ext-enable memcache
COPY src/ /var/www/html/
#RUN chmod 777 /var/www/html/logs/ \
#   && chown www-data:www-data -R /var/www/html/logs/

FROM base AS development
RUN pecl install xdebug
RUN docker-php-ext-enable xdebug
COPY php.ini-development $PHP_INI_DIR/conf.d/php.ini
COPY docker-php-ext-xdebug.ini $PHP_INI_DIR/conf.d/docker-php-ext-xdebug.ini

FROM base AS production
COPY php.ini-production $PHP_INI_DIR/conf.d/php.ini
COPY docker-php-ext-opcache.ini $PHP_INI_DIR/conf.d/docker-php-ext-opcache.ini