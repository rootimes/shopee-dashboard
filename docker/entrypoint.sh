#!/bin/sh
set -e

APP_DIR="${APP_DIR:-/var/www}"
APP_USER="${APP_USER:-www-data}"
APP_GROUP="${APP_GROUP:-www-data}"

cd "$APP_DIR"

umask 0002

if [ "$(id -u)" = "0" ]; then
    chown -R "${APP_USER}:${APP_GROUP}" storage bootstrap/cache

    find storage bootstrap/cache -type d -exec chmod 2775 {} \;
    find storage bootstrap/cache -type f -exec chmod 0664 {} \;
fi

exec "$@"
