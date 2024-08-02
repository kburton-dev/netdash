FROM composer AS composer
FROM dunglas/frankenphp:latest-php8.3-bookworm

ARG USER=app
ENV SERVER_NAME=:80

RUN \
    useradd app; \
    setcap -r /usr/local/bin/frankenphp; \
    chown -R app:app /data/caddy; \
    chown -R app:app /config/caddy; \
    chown -R app:app /app;

RUN install-php-extensions \
    gd \
    xml \
    xsl \
    zip \
    curl \
    intl \
    soap \
    oauth \
    pcntl \
    redis \
    bcmath \
    mysqli \
    xdebug \
    xhprof \
    sqlite3 \
    opcache \
    mbstring \
    pdo_mysql

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

USER app

COPY --chown=app:app --link --from=composer /usr/bin/composer /usr/bin/composer
