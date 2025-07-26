FROM php:8.3-cli

RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libicu-dev \
    libxml2-dev \
    libonig-dev \
    libpq-dev \
    build-essential \
    pkg-config \
    tzdata \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo \
        pdo_pgsql \
        zip \
        opcache \
        intl \
        bcmath \
        mbstring \
        sockets \
        xml \
        soap \
        pcntl \
        exif \
        ctype \
        fileinfo

RUN pecl install swoole && docker-php-ext-enable swoole

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --optimize-autoloader --no-dev

RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 770 /var/www/html/storage /var/www/html/bootstrap/cache

USER www-data

EXPOSE 8000

CMD ["php", "artisan", "octane:start", "--server=swoole", "--host=0.0.0.0", "--port=8000"]
