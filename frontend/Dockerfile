FROM php:8.1-apache

WORKDIR /var/www/html

# প্রয়োজনীয় PHP extension ও wkhtmltopdf ইনস্টল করুন
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    libsocket++-dev \
    wkhtmltopdf \
    xvfb \
    libfontconfig1 \
    libxrender1 \
    libjpeg62-turbo && \
    docker-php-ext-install mysqli zip sockets

# Composer ইনস্টল করুন
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# অ্যাপ ফাইলগুলো কপি করুন
COPY . /var/www/html

# যদি vendor ফোল্ডার না থাকে, তখনই composer install চালান
RUN if [ ! -d "vendor" ]; then composer install; fi
