#!/usr/bin/with-contenv bash

touch /app/storage/logs/cron.log && chown abc:abc /app/storage/logs/cron.log
touch /app/storage/logs/laravel.log && chown abc:abc /app/storage/logs/laravel.log

echo "Clearing cache, migrating DB, etc..."

if [ ! -z "$PUID" ] && [ ! -z "$PGID" ]; then
    echo "Setting permissions..."
    chown -R $PUID:$PGID /app/
fi

cd /app

gosu abc php artisan cache:clear
gosu abc php artisan migrate --force

# cache laravel config?
# php artisan config:cache
# cache laravel routes?
# php artisan route:cache