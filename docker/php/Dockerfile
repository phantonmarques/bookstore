FROM php:8.4.27-fpm

RUN apt-get update \
    && apt-get install -y --no-install-recommends libpq-dev libicu-dev \
    && docker-php-ext-install pdo_pgsql intl \
    && rm -rf /var/lib/apt/lists/*

RUN echo "date.timezone=America/Sao_Paulo" > /usr/local/etc/php/conf.d/timezone.ini
RUN echo "session.upload_progress.enabled=0" > /usr/local/etc/php/conf.d/disable-functions.ini

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

WORKDIR /var/www/html
