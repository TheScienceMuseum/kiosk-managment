#!/usr/bin/env bash

# Start from the project repository
cd /var/www/kiosk_manager;

# Notify sentry of new deployed version
export SENTRY_AUTH_TOKEN=4a38811e725648e9905aac77c3168acc1d1a9c5067f6442db7095fe5b393e5a9
export SENTRY_ORG=joi-polloi
VERSION=$(sentry-cli releases propose-version)

# Create a release
sentry-cli releases new -p science-museum-kiosk-management $VERSION

# Associate commits with the release
sentry-cli releases set-commits --auto $VERSION

# Mark a deployment
sentry-cli releases deploys $VERSION new -e $(grep 'APP_ENV=' .env | cut -d "=" -f 2)

# Notify monitoring room about commit
php artisan ops:deployment:complete
