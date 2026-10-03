#!/bin/bash

set -e

mkdir -p /var/www/app/storage/logs
mkdir -p /var/www/app/storage/framework/cache
mkdir -p /var/www/app/storage/framework/sessions
mkdir -p /var/www/app/storage/framework/views
mkdir -p /var/www/app/bootstrap/cache

chown -R www-data:www-data /var/www/app/storage
chown -R www-data:www-data /var/www/app/bootstrap/cache

chmod -R 775 /var/www/app/storage
chmod -R 775 /var/www/app/bootstrap/cache

if [ -d "/var/app/www/public/sxd" ]; then
    chmod -R 777 /var/app/www/public/sxd/backup
    chmod 666 /var/app/www/public/sxd/cfg.php
    chmod 666 /var/app/www/public/sxd/ses.php
fi

# Создаём .env, если его ещё нет
if [ ! -f /var/www/app/.env ]; then
    cp /var/www/app/.env.example /var/www/app/.env
fi

if [ ! -f "/var/www/app/vendor/autoload.php" ]; then
    echo "📦 vendor не найден, запускаем composer install..."
    cd /var/www/app
    composer install
fi
# Генерируем APP_KEY, только если его ещё нет
if ! grep -q '^APP_KEY=.\+' /var/www/app/.env; then
    php /var/www/app/artisan key:generate --force
fi

# npm dependencies
#if [ -f "/var/www/app/package.json" ]; then
#    if [ ! -d "/var/www/app/node_modules" ]; then
#        npm install
#    fi
#fi



echo ""
echo "========================================"
echo "🌐 http://localhost:8085"
echo "========================================"
echo ""


exec "$@"
