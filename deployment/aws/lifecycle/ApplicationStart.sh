#!/usr/bin/env bash

cd /var/www/kiosk_manager;

# Bring the application back up now we have finished
php /var/www/kiosk_manager/artisan up
