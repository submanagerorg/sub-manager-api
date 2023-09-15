#!/bin/bash

echo "Running deployment script"

# Navigate to your Laravel project directory
cd /var/www/subsync

# Pull the latest changes from your GitHub repository
git pull origin main

# Install or update Composer dependencies
composer install --no-interaction --prefer-dist

# Run database migrations (if needed)
php artisan migrate --force

# Clear application cache
php artisan cache:clear

# Restart your web server (e.g., Nginx)
sudo systemctl restart nginx

echo "Done ✅"
