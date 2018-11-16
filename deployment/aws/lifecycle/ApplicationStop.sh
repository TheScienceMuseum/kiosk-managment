#!/usr/bin/env bash

# Stop the horizon queue process
supervisorctl stop horizon

# Bring the application down while we update it
# (send 503 to all requests)
php /var/www/kiosk_manager/artisan down
