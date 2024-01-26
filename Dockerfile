FROM php:8.1-fpm
RUN apt-get update && apt-get install -y \
    libonig-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo_mysql mysqli mbstring exif pcntl bcmath gd zip
WORKDIR /var/www
COPY . /var/www
RUN chown -R www-data:www-data /var/www
