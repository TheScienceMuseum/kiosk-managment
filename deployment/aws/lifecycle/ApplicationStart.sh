#!/usr/bin/env bash

# Ensure we start in the application folder
cd /var/www/kiosk_manager/

# Bring the application back up now we have finished
php artisan up

# Start the horizon queue process
supervisorctl start horizon
