FROM composer AS composer

FROM node:18-alpine AS node
WORKDIR /home/node/app
COPY / /home/node/app
RUN npm install && npm run build

FROM dunglas/frankenphp:latest-php8.3-bookworm

ARG USER=app
ENV SERVER_NAME=:80

RUN \
    useradd ${USER}; \
    setcap -r /usr/local/bin/frankenphp; \
    chown -R ${USER}:${USER} /data/caddy; \
    chown -R ${USER}:${USER} /config/caddy; \
    chown -R ${USER}:${USER} /app;

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

RUN \
    mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"; \
    mkdir /app/vendor && chown app:app /app/vendor; \
    mkdir /app_database && chown app:app /app_database;

USER ${USER}

COPY --chown=app:app --link app /app/app
COPY --chown=app:app --link bootstrap /app/bootstrap
COPY --chown=app:app --link config /app/config
COPY --chown=app:app --link database /app/database
COPY --chown=app:app --link public /app/public
COPY --chown=app:app --link resources /app/resources
COPY --chown=app:app --link routes /app/routes
COPY --chown=app:app --link storage /app/storage
COPY --chown=app:app --chmod=+x --link artisan /app/artisan
COPY --chown=app:app --link composer.* /app
COPY --chown=app:app --link docker/prod/Caddyfile /app
COPY --chown=app:app --link --from=node /home/node/app/public /app/public
COPY --chown=app:app --link --from=composer /usr/bin/composer /usr/bin/composer

RUN composer install --prefer-dist --no-scripts --optimize-autoloader --no-dev