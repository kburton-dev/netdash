FROM composer AS composer

FROM node:18-alpine AS node
WORKDIR /home/node/app

COPY /resources /home/node/app/resources
COPY /package.json /home/node/app/package.json
COPY /package-lock.json /home/node/app/package-lock.json
COPY /postcss.config.js /home/node/app/postcss.config.js
COPY /tailwind.config.js /home/node/app/tailwind.config.js
COPY /vite.config.js /home/node/app/vite.config.js

RUN npm install && npm run build

FROM lsiobase/ubuntu:jammy

ENV TZ=UTC
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN apt-get update && \
    apt-get install -y \
        gosu \
        ca-certificates \
        apt-transport-https \
        software-properties-common && \
    add-apt-repository ppa:ondrej/php

RUN apt-get update && \
    apt-get install -y \
        cron \
        mysql-client \
        gettext \
        php8.3 \
        php8.3-gd \
        php8.3-xml \
        php8.3-zip \
        php8.3-curl \
        php8.3-intl \
        php8.3-soap \
        php8.3-mysql \
        php8.3-redis \
        php8.3-oauth \
        php8.3-mysqli \
        php8.3-sqlite \
        php8.3-opcache \
        php8.3-mbstring \
        php8.3-bcmath

WORKDIR /app

COPY . .
COPY --from=node /home/node/app/public/build /app/public/build
COPY --from=composer /usr/bin/composer /usr/bin/composer
RUN composer install --prefer-dist --no-scripts --optimize-autoloader --no-dev
RUN chown -R abc:abc .
RUN chmod +x artisan
RUN gosu abc php artisan octane:install --server=frankenphp --no-interaction

COPY /docker/prod/services.d /custom-services.d
COPY /docker/prod/custom-cont-init.d /custom-cont-init.d
COPY /docker/prod/cron /config/crontabs/abc

# 0: Continue silently even if any script (fix-attrs or cont-init) has failed.
# 1: Continue but warn with an annoying error message.
# 2: Stop by sending a termination signal to the supervision tree.
# Check s6-overlay documentation for more info.
ENV S6_BEHAVIOUR_IF_STAGE2_FAILS 2

ENV DOCKER_MODS linuxserver/mods:universal-cron