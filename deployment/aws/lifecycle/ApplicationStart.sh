#!/usr/bin/env bash

# Bring the application back up now we have finished
php /var/www/kiosk_manager/artisan up

# Start the horizon queue process
supervisorctl start horizon
