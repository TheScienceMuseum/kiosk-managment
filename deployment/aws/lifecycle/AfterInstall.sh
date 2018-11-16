#!/usr/bin/env bash

cd /var/www/kiosk_manager;

# Install dependencies
composer install --no-dev

# Run new migrations
php artisan migrate --force

# Set up any git submodules (interface)
git submodule init
git submodule update
