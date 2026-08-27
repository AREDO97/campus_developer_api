#!/usr/bin/env bash
set -e

echo "Installing composer dependencies..."
composer install --no-dev --optimize-autoloader

echo "Caching configurations..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Running database migrations..."
php artisan migrate --force
