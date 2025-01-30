FROM php:8.3.0-fpm

COPY php.ini /usr/local/etc/php/

RUN apt update \
    && apt install -y --no-install-recommends default-mysql-client zlib1g-dev libzip-dev unzip \
    && docker-php-ext-install pdo_mysql zip \
    && apt clean \
    && rm -rf /var/lib/apt/lists/*

RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer

WORKDIR /var/www