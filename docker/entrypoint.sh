#!/bin/sh
set -e

# Run migrations
php artisan migrate --force

# Run seeders if requested
if [ "$DB_SEED" = "true" ]; then
    echo "Running seeders..."
    php artisan db:seed --force
fi

# Start PHP-FPM in background
php-fpm -D

# Start Nginx
nginx -g "daemon off;"
