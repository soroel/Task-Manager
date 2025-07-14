# Use PHP 8.2 with required extensions
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

# Install Node.js + npm (LTS version)
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Install Composer globally
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy application files
COPY . .

# Create the SQLite database file
RUN mkdir -p database && touch database/database.sqlite

# Install backend and frontend dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader
RUN npm install && npm run build

# Set correct permissions
RUN chmod -R 775 storage bootstrap/cache database && \
    chown -R www-data:www-data .

# create the database directory ad empty SQLite file
RUN mkdir -p /app/database && touch /app/database/database.sqlite

# Fix permissions so Laravel can write to it
RUN chown -R www-data:www-data /app/database && \
    chmod -R 775 /app/database
# Laravel listens on port 10000 on Render
EXPOSE 10000

# Start Laravel server
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=10000"]
