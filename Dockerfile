FROM php:8.3-fpm-alpine

# Install system dependencies and PHP extensions
RUN apk update && apk add --no-cache \
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
    libxml2-dev \
    libsodium-dev \
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
        mbstring \
        xml \
        exif \
        sodium \
        bcmath \
    && docker-php-ext-enable intl \
    && rm -rf /var/cache/apk/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy composer files and install PHP dependencies
COPY composer.json composer.lock ./
RUN composer install --prefer-dist --no-dev --optimize-autoloader --no-scripts

# Copy application code
COPY . ./

# Ensure permissions for storage and cache
RUN chown -R www-data:www-data storage bootstrap/cache

# Expose PHP-FPM port
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]