FROM php:8.2-cli

# Install system dependencies
RUN apt-get update && \
    apt-get install -y unzip curl libpq-dev && \
    docker-php-ext-install pdo_pgsql

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer

# Copy project files
COPY . /var/www
WORKDIR /var/www

# Install PHP dependencies
RUN composer install

# Expose port and start the PHP built-in server
EXPOSE 10000
CMD ["php", "-S", "0.0.0.0:10000", "router.php"]
