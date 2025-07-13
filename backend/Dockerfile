FROM php:8.1-apache

WORKDIR /var/www/html

# intl এবং sockets সহ প্রয়োজনীয় extension ইনস্টল করুন
RUN apt-get update && apt-get install -y \
    zip \
    unzip \
    git \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    libicu-dev \
    && docker-php-ext-install pdo pdo_mysql zip intl sockets

# অ্যাপ ফাইলগুলো কপি করুন
COPY . /var/www/html

# Composer ইনস্টল করুন এবং dependency গুলো সেটআপ করুন
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer install
