FROM composer AS composer
FROM dunglas/frankenphp:latest-php8.3-alpine

RUN install-php-extensions \
    bcmath \
    xsl \
    intl

COPY app /app/app
COPY bootstrap /app/bootstrap
COPY config /app/config
COPY database /app/database
COPY public /app/public
COPY resources /app/resources
COPY routes /app/routes
COPY storage /app/storage
COPY artisan /app/artisan
COPY composer.json /app/composer.json
COPY composer.lock /app/composer.lock
#RUN rm -rf /app/bootstrap/cache/* /app/storage/logs/* /app/storage/framework/cache/* /app/storage/framework/sessions/* /app/storage/framework/views/* # For testing build locally.

COPY --from=composer /usr/bin/composer /usr/bin/composer
RUN composer install --prefer-dist --no-scripts --optimize-autoloader --no-dev

RUN chown -R 1000:1000 /app/*
