FROM php:8.1.29-fpm

RUN apt-get update \
    && apt-get install -y \
    zip nano libc-dev zlib1g-dev libzip-dev zip \
    && docker-php-ext-install zip pdo_mysql \
    && pecl install xdebug \
        && echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini \
    && rm -rf /var/lib/apt/lists/*

# Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN mkdir -p /code/logs/profiler

WORKDIR "/code"
