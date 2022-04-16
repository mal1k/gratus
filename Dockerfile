FROM php:8.0.7-fpm
#FROM php:8.0.7-fpm-alpine3.12
WORKDIR /var/www
RUN apt-get update && apt-get install -y \
    build-essential \
    libzip-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libwebp-dev libjpeg62-turbo-dev libpng-dev libxpm-dev \
    libfreetype6 \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    unzip \
    curl \
    openssl \
    git \
    libonig-dev \
    libpq-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/*
USER root
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl
# Install composer
COPY composer.lock composer.json /var/www/
#COPY artisan /var/www/
#COPY ./bootstrap /var/www/
COPY . /var/www

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN chmod -R 777 /var/www
RUN composer upgrade
#RUN composer install  --prefer-source

#COPY . /var/www
RUN chmod -R 777 /var/www
RUN php artisan telescope:install

RUN rm  /var/www/public/storage
RUN  php artisan storage:link
EXPOSE 9000
CMD ["php-fpm"]
