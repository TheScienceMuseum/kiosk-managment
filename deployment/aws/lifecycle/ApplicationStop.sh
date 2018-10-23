#!/usr/bin/env bash

cd /var/www/kiosk_manager;

# Bring the application down while we update it
# (send 503 to all requests)
php /var/www/kiosk_manager/artisan down
