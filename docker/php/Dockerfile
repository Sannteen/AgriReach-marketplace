
FROM php:8.4-fpm-alpine

# Install the MySQL PDO extension.
RUN docker-php-ext-install pdo_mysql

# Copy custom PHP configuration.
COPY php.ini /usr/local/etc/php/conf.d/custom.ini

WORKDIR /var/www/html





