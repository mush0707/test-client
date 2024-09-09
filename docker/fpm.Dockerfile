FROM php:8.2-fpm-alpine AS base
ENV EXT_APCU_VERSION=master

RUN curl -vvv https://github.com/krakjoe/apcu.git

RUN apk add --update zlib-dev libpng-dev libzip-dev $PHPIZE_DEPS

RUN docker-php-ext-install exif
RUN docker-php-ext-install gd
RUN docker-php-ext-install zip
RUN docker-php-ext-install pdo_mysql
# RUN pecl install apcu
RUN docker-php-source extract \
    && apk -Uu add git \
    && git clone --branch $EXT_APCU_VERSION --depth 1 https://github.com/krakjoe/apcu.git /usr/src/php/ext/apcu \
    && cd /usr/src/php/ext/apcu && git submodule update --init \
    && docker-php-ext-install apcu
RUN docker-php-ext-enable apcu

FROM base AS dev
USER 1001:1001

FROM base AS build-fpm

WORKDIR /var/www/html

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY . /var/www/html
# COPY /composer.json composer.json

RUN composer install --prefer-dist --no-ansi --no-autoloader


# COPY . /var/www/html

RUN composer dump-autoload -o
RUN mkdir -p /var/www/html/storage/app/main_service
RUN chown 1001:1001 /var/www/html/storage/app/main_service
FROM build-fpm AS fpm

COPY --from=build-fpm /var/www/html /var/www/html
