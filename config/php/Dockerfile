FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
        curl \
        wget \
        git \
        zip \
        unzip \
        vim \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libpng-dev \
		    libzip-dev \
    && docker-php-ext-install -j$(nproc) iconv mysqli pdo_mysql zip \
    && docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

USER www-data:www-data

WORKDIR /var/www

CMD ["php-fpm"]