#!/bin/bash

# Clear config and cache (optional but good practice)
php artisan config:clear
php artisan cache:clear

# Run database migrations
php artisan migrate --force

# Start Laravel server
exec php artisan serve --host=0.0.0.0 --port=10000
