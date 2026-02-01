#!/bin/sh
set -e

APP_DIR="${APP_DIR:-/var/www}"
APP_USER="${APP_USER:-www-data}"
APP_GROUP="${APP_GROUP:-www-data}"

cd "$APP_DIR"

chown -R "${APP_USER}:${APP_GROUP}" storage bootstrap/cache || true

find storage bootstrap/cache -type d -exec chmod 2775 {} \; || true
find storage bootstrap/cache -type f -exec chmod 664  {} \; || true

umask 0002

exec "$@"
