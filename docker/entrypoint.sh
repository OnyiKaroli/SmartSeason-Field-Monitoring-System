#!/bin/sh
set -e

# Run migrations
php artisan migrate --force

# Start PHP-FPM in background
php-fpm -D

# Start Nginx
nginx -g "daemon off;"
