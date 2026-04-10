FROM composer:latest AS composer_builder

FROM dunglas/frankenphp:latest

# Install additional PHP extensions required by dependencies
RUN install-php-extensions intl zip

# Copy composer from builder
COPY --from=composer_builder /usr/bin/composer /usr/bin/composer

# Copy application
WORKDIR /app
COPY . .

# Install dependencies
RUN composer install --optimize-autoloader --no-scripts --no-interaction

# Build assets
RUN npm ci && npm run build

# Create necessary directories
RUN mkdir -p storage/framework/{sessions,views,cache,testing} storage/logs bootstrap/cache && \
    chmod -R a+rw storage

# Cache Laravel configs
RUN php artisan config:cache && \
    php artisan event:cache && \
    php artisan route:cache && \
    php artisan view:cache

EXPOSE 8000
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
