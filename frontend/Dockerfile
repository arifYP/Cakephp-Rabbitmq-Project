FROM php:8.1-apache

WORKDIR /var/www/html

COPY . /var/www/html

RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    libsocket++-dev && \
    docker-php-ext-install mysqli zip sockets

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    composer install
