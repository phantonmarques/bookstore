FROM php:8.4.27-fpm

RUN apt-get update \
    && apt-get install -y --no-install-recommends libpq-dev libicu-dev \
    && docker-php-ext-install pdo_pgsql intl \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html
