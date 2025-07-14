# Use PHP 8.2 with FPM
FROM php:8.2-fpm

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    zip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    sqlite3 \
    libsqlite3-dev \
    gnupg \
    && docker-php-ext-install pdo pdo_sqlite mbstring exif pcntl bcmath gd zip

# Install Node.js + npm
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set the working directory to where Render deploys apps
WORKDIR /app

# Copy app files
COPY . .

# Create the SQLite DB file before running Laravel
RUN mkdir -p database && touch database/database.sqlite

# Set correct permissions
RUN chmod -R 775 storage bootstrap/cache database && \
    chown -R www-data:www-data .

# Install dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader
RUN npm install && npm run build

# Run database migrations
RUN php artisan config:clear && php artisan migrate --force

# Expose the port Laravel will listen on
EXPOSE 10000

# Start Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=10000"]
