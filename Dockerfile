FROM php:8-fpm-buster

RUN apt-get update && apt-get install -y --no-install-recommends \
        curl \
        wget \
        git \
        unzip \
        libzip-dev \
        gnupg2 \
        libwebp-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libxpm-dev \
        libfreetype6-dev
RUN docker-php-ext-configure gd --with-webp --with-jpeg --with-freetype \
    && docker-php-ext-install pdo pdo_mysql gd
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && rm -rf /var/lib/apt/lists/* \
ADD ./php.ini /usr/local/etc/php/conf.d/40-custom.ini

WORKDIR /var/www

CMD ["php-fpm"]