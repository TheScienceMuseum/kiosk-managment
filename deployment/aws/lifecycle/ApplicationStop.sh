#!/usr/bin/env bash

# Ensure we start in the application folder
cd /var/www/kiosk_manager/

# Stop the horizon queue process
supervisorctl stop horizon

# Bring the application down while we update it
# (send 503 to all requests)
php artisan down
