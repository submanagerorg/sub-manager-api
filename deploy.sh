#!/bin/bash

echo "Running deployment script"

# Check if the number of arguments provided is correct
if [ $# -ne 1 ]; then
    echo "Usage: $0 <branch>"
    exit 1
fi

# Assign the first argument to branch
branch=$1

echo "Deploying to branch: ${branch}"

# Navigate to your Laravel project directory
cd /var/www/subsync

# Pull the latest changes from your GitHub repository for the specified branch
git pull origin $branch

# Install or update Composer dependencies
composer install --no-interaction --prefer-dist

# Run database migrations (if needed)
php artisan migrate --force

# Clear application cache
php artisan cache:clear

# Restart your web server (e.g., Nginx)
sudo systemctl restart nginx

echo "Done ✅"
echo "Deployed to ${branch} ✅"