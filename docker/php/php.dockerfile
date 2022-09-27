FROM composer:1.10.20

# FROM php:8.2-fpm-alpine3.13
FROM php:8.1-fpm-alpine3.16

# Install Postgre PDO
RUN set -ex \
    && apk --no-cache add \
    postgresql-dev

RUN docker-php-ext-install pdo pdo_pgsql


COPY --from=composer /usr/bin/composer /usr/bin/composer

# XDEBUG
RUN apk add --no-cache $PHPIZE_DEPS \
    && pecl install xdebug-3.1.5 \
    && docker-php-ext-enable xdebug

# set working directory
WORKDIR /var/www/html

# copy code
COPY ./src /var/www/html

# install composer and its dependecies from composer.json
RUN composer install --ignore-platform-reqs

# add group and user
RUN addgroup -g 1000 laravel-group
RUN adduser -u 1000 -G laravel-group -h /home/laravel-user -D laravel-user
RUN chown -R laravel-user:laravel-group /var/www/html
RUN chown -R laravel-user:laravel-group /var/log
USER laravel-user