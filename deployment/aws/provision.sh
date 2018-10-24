#!/usr/bin/env bash

DEPLOYMENT_DOMAIN=$1

if [[ -z $DEPLOYMENT_DOMAIN ]]; then
    echo "You need to specify the domain name as the first argument to this script";
    exit 1;
fi

sudo apt-get update
sudo apt-get install -yq software-properties-common

# Setup PPA Repos
sudo LC_ALL=C.UTF-8 add-apt-repository -yq ppa:certbot/certbot
sudo LC_ALL=C.UTF-8 add-apt-repository -y ppa:ondrej/php

# Install required servers
sudo apt update
sudo apt-get install -yq \
    php7.2-fpm php7.2-mbstring php7.2-xml php7.2-zip php7.2-mysql php7.2-curl \
    composer nginx curl python-certbot-nginx

# Setup lets encrypt for domain
sudo certbot -d $DEPLOYMENT_DOMAIN --nginx -n --agree-tos --email dev@joipolloi.com

# Create nginx configuration
read -r -d '' NGINX_CONFIGURATION <<-EOF
server {
    listen 80 default_server;
    listen [::]:80 default_server;
    server_name _;
    return 301 https://\$host\$request_uri;
}

server {
    listen 443 ssl;
    listen [::]:443 ssl;

    ssl_certificate /etc/letsencrypt/live/$DEPLOYMENT_DOMAIN/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/$DEPLOYMENT_DOMAIN/privkey.pem;

    root /var/www/kiosk_manager/public;

    # only use index.php as an index file
    index index.php;

    server_name $DEPLOYMENT_DOMAIN;

    location / {
        # First attempt to serve request as file, then
        # as directory, then fall back to calling laravel
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    # pass the PHP scripts to FastCGI server
    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php7.2-fpm.sock;
    }

    # deny access to .htaccess files, if Apache's document root
    # concurs with nginx's one
    location ~ /\.ht {
        deny all;
    }
}
EOF

echo "$NGINX_CONFIGURATION" | sudo tee /etc/nginx/sites-enabled/default > /dev/null

# Create folder for application
sudo mkdir -p /var/www/kiosk_manager
sudo chown -R www-data:www-data /var/www/kiosk_manager

# Restart nginx for good times
sudo service nginx restart

# Ensure sentry cli is installed
curl -sL https://sentry.io/get-cli/ | bash
