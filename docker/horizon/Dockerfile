FROM php:8.0-fpm

WORKDIR /var/www/html

RUN apt-get update \
    && apt-get -y install libzip-dev zip libpng-dev libxml2-dev zlib1g-dev supervisor \
    && pecl install redis\
    && docker-php-ext-enable redis \
    && docker-php-ext-install pdo_mysql zip gd pcntl

RUN usermod -u 1000 www-data

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]