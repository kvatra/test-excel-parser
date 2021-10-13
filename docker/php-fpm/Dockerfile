FROM php:8.0-fpm

RUN apt-get update \
    && apt-get -y install libzip-dev zip libpng-dev libxml2-dev zlib1g-dev\
    && pecl install redis\
    && docker-php-ext-enable redis \
    && docker-php-ext-install zip pdo_mysql gd

RUN usermod -u 1000 www-data
