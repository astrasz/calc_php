FROM php:8.2-fpm-alpine

RUN apk update && apk add \
    git \
    curl \
    vim

ENV COMPOSER_ALLOW_SUPERUSER=1

COPY --from=composer:2.4 /usr/bin/composer /usr/bin/composer

COPY ../../app/composer.* ./

RUN composer install --prefer-dist --no-dev --no-scripts --no-progress --no-interaction

COPY ../../app .

WORKDIR /var/www/html

# RUN composer dump-autoload --optimize
