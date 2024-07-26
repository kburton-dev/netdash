FROM composer AS composer

FROM node:18-alpine AS node
WORKDIR /home/node/app
COPY / /home/node/app
RUN npm install && npm run build

FROM php:8.3-bookworm

RUN \
    mkdir /app; \
    useradd app; \
    chown app:app /app; \
    chmod 700 /app; \
    mkdir /app_database; \
    chown app:app /app_database; \
    mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

RUN install-php-extensions \
    gd \
    xml \
    xsl \
    zip \
    curl \
    intl \
    soap \
    redis \
    oauth \
    bcmath \
    mysqli \
    sqlite3 \
    opcache \
    mbstring \
    pdo_mysql

USER app

COPY --chown=app:app app /app/app
COPY --chown=app:app bootstrap /app/bootstrap
COPY --chown=app:app config /app/config
COPY --chown=app:app database /app/database
COPY --chown=app:app public /app/public
COPY --chown=app:app resources /app/resources
COPY --chown=app:app routes /app/routes
COPY --chown=app:app storage /app/storage
COPY --chown=app:app --chmod=+x artisan /app/artisan
COPY --chown=app:app composer.* /app
COPY --chown=app:app docker/prod/Caddyfile /app
COPY --chown=app:app --from=node /home/node/app/public /app/public
COPY --chown=app:app --from=composer /usr/bin/composer /usr/bin/composer

RUN \
    composer install --prefer-dist --no-scripts --optimize-autoloader --no-dev; \
    php artisan octane:install --server=frankenphp --no-interaction;
