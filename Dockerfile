FROM composer:latest AS composer_builder

FROM dunglas/frankenphp:latest

# Install Node.js and additional PHP extensions
RUN apt-get update && apt-get install -y \
    curl gnupg ca-certificates && \
    curl -fsSL https://deb.nodesource.com/setup_22.x | bash - && \
    apt-get install -y nodejs && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

RUN install-php-extensions intl zip pdo_mysql

# Copy composer from builder
COPY --from=composer_builder /usr/bin/composer /usr/bin/composer

# Copy application
WORKDIR /app
COPY . .

# Install PHP dependencies
RUN composer install --optimize-autoloader --no-scripts --no-interaction

# Install and build Node assets
RUN npm ci && npm run build

# Create necessary directories
RUN mkdir -p storage/framework/{sessions,views,cache,testing} storage/logs bootstrap/cache storage/app/public && \
    chmod -R a+rw storage bootstrap/cache

# Create storage link
RUN php artisan storage:link || true

# Clear all caches first
RUN php artisan cache:clear || true && \
    php artisan config:clear || true && \
    php artisan view:clear || true && \
    php artisan route:clear || true

# Set permissions
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache && \
    chmod -R 775 /app/storage /app/bootstrap/cache

EXPOSE 8000
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
