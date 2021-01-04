FROM php:7.4.10-fpm

RUN apt-get update && apt-get install -y \
        unzip \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libpng-dev \
        libicu-dev \
        libpq-dev \
        libxpm-dev \
        libvpx-dev \
        libxml2-dev \
        libzip-dev \
        zip \
        git \
        tesseract-ocr \
        libtesseract-dev \
        supervisor \
        libmagickwand-dev --no-install-recommends
RUN pecl install xdebug
RUN docker-php-ext-enable xdebug
RUN docker-php-ext-install -j$(nproc) zip
RUN docker-php-ext-install -j$(nproc) gd
RUN docker-php-ext-install -j$(nproc) intl
RUN docker-php-ext-install -j$(nproc) sockets
RUN docker-php-ext-install -j$(nproc) pgsql \
    && docker-php-ext-install -j$(nproc) pdo_pgsql \
    && docker-php-ext-install -j$(nproc) soap \
    && docker-php-ext-install -j$(nproc) bcmath

RUN rm -rf /var/lib/apt/lists/*
RUN printf "\n" | pecl install imagick
RUN docker-php-ext-enable imagick

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer self-update --1
RUN composer global require "fxp/composer-asset-plugin:^1.4.0"

RUN mkdir /var/www/.composer/
RUN chmod 775 -R /var/www/.composer/

COPY ./docker/supervisord/supervisord.conf /etc/supervisor
COPY ./docker/supervisord/conf.d/* /etc/supervisor/conf.d/

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/supervisord.conf"]
