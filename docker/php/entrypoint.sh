#!/bin/sh
set -e

# Fix storage permissions on startup (volume mount overrides image defaults)
mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache public/build 2>/dev/null || true
chmod -R 775 storage bootstrap/cache 2>/dev/null || true

exec "$@"
