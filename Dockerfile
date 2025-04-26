FROM php:8.3-fpm-alpine

# Install system dependencies and PHP extensions
RUN apk add --no-cache \
    curl \
    git \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    libxpm-dev \
    freetype-dev \
    oniguruma-dev \
    zip \
    unzip \
    libzip-dev \
    icu-dev \
  && docker-php-ext-configure gd \
        --with-freetype \
        --with-jpeg \
        --with-webp \
        --with-xpm \
  && docker-php-ext-install \
        zip \
        pdo_mysql \
        gd \
        intl \
  && rm -rf /var/cache/apk/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy composer files + env
COPY composer.json composer.lock ./
COPY .env .env

# Install PHP dependencies
RUN composer install --prefer-dist --no-dev --optimize-autoloader --no-scripts

# Copy application code
COPY . ./

# Ensure permissions
RUN chown -R www-data:www-data storage bootstrap/cache

# Expose PHP-FPM port
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]
