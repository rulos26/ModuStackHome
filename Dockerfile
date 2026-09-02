FROM php:8.2-fpm-alpine

RUN apk add --no-cache \
    git curl zip unzip bash \
    libpng-dev libjpeg-turbo-dev freetype-dev \
    libzip-dev icu-dev oniguruma-dev

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install -j$(nproc) \
    pdo_mysql mysqli mbstring exif pcntl bcmath gd zip intl opcache

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

RUN if [ -f composer.json ]; then composer install --no-interaction --prefer-dist --no-scripts || true; fi \
 && mkdir -p storage bootstrap/cache \
 && chown -R www-data:www-data storage bootstrap/cache \
 && chmod -R 775 storage bootstrap/cache || true

EXPOSE 9000
CMD ["php-fpm"]