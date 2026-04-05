FROM php:8.4-fpm-alpine

RUN apk update && apk add --no-cache \
    git \
    curl \
    libzip-dev \
    postgresql-dev \
    oniguruma-dev \
    libxml2-dev \
    $PHPIZE_DEPS \
    && docker-php-ext-install pdo_pgsql mbstring bcmath zip \
    && apk del $PHPIZE_DEPS

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/laravel

USER www-data