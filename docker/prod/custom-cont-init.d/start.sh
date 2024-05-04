#!/usr/bin/with-contenv bash

echo "Clearing cache, migrating DB, etc..."

if [ ! -z "$PUID" ] && [ ! -z "$PGID" ]; then
    echo "Setting permissions..."
    chown -R $PUID:$PGID /app/
fi

cd /app

gosu abc php artisan cache:clear
gosu abc php artisan optimize
gosu abc php artisan migrate --force
# gosu abc php artisan storage:link
