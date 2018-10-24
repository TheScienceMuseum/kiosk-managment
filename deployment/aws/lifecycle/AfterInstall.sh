#!/usr/bin/env bash

cd /var/www/kiosk_manager;

# Install dependencies
composer install --no-dev

# Run new migrations
php artisan migrate --force
