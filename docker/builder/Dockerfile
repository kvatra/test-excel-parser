FROM php:8.0-fpm as composer

WORKDIR /app
COPY --from=composer:2.1 /usr/bin/composer /usr/bin/composer

RUN apt-get update \
    && apt-get -y install libzip-dev zip libpng-dev libxml2-dev zlib1g-dev\
    && pecl install redis\
    && docker-php-ext-enable redis \
    && docker-php-ext-install zip pdo_mysql gd pcntl

COPY ./composer.* ./
RUN composer install --no-scripts --no-interaction

FROM node:14.17.0 as npm

WORKDIR /app
COPY ./package.json ./
COPY ./package-lock.json ./
COPY ./webpack.mix.js ./
COPY ./resources ./resources
COPY ./.env ./.env

RUN npm install
RUN npm run prod

FROM alpine:3.14 as result

COPY --from=composer /app/vendor /tmp/vendor
COPY --from=npm /app/node_modules /tmp/node_modules
COPY --from=npm /app/public/js /tmp/public/js
COPY --from=npm /app/mix-manifest.json /tmp/public/

USER 1000

CMD ["sh", "-c", "cp -r /tmp/* /builder"]
