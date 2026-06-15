FROM php:8.3-cli

WORKDIR /var/www

# System dependencies
RUN apt-get update && apt-get install -y \
    git unzip zip curl \
    libpq-dev \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    libicu-dev \
    pkg-config

# PHP extensions
RUN docker-php-ext-install \
    pdo \
    pdo_pgsql \
    mbstring \
    bcmath \
    intl \
    zip

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy full project FIRST (fixes artisan issue)
COPY . .

# Install dependencies WITHOUT triggering artisan scripts
RUN composer install --no-interaction --prefer-dist --no-scripts

# Fix Laravel permissions
RUN chmod -R 775 storage bootstrap/cache

# Expose port
EXPOSE 8000

# Start app
CMD sh -c "php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=8000"