FROM php:8.1-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libonig-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Install Node.js and npm
RUN curl -sL https://deb.nodesource.com/setup_16.x | bash -
RUN apt-get install -y nodejs
# Install npm
RUN apt-get install -y npm

# Set working directory
WORKDIR /var/www

# Copy existing application directory contents
COPY . /var/www

# Set user permissions
RUN chown -R www-data:www-data /var/www

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
