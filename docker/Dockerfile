FROM php:8.3-apache

RUN docker-php-ext-install pdo_mysql

RUN a2enmod rewrite

WORKDIR /var/www/html

FROM php:8.3-apache

# Install the PHP driver needed for MySQL connections.
RUN docker-php-ext-install pdo_mysql

# Enable clean URLs and set public as the only browser-accessible folder.
RUN a2enmod rewrite && \
    sed -ri 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# Set the project root as the working directory.
WORKDIR /var/www/html


FROM php:8.3-apache

# Install the PHP driver needed for MySQL connections.
RUN docker-php-ext-install pdo_mysql

# Enable clean URLs and set public as the only browser-accessible folder.
RUN a2enmod rewrite && \
    sed -ri 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# Set the project root as the working directory.
WORKDIR /var/www/html
