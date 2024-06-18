FROM php:8.2-fpm

ARG user
ARG uid

RUN apt-get update && apt-get install -y \
    libpq-dev \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    supervisor \
    nginx \
    build-essential \
    openssl

# Установка расширений PHP
RUN docker-php-ext-install pdo_pgsql mbstring exif pcntl bcmath gd

# Установка рабочей директории
WORKDIR /var/www/laravel

# Копирование исходного кода
COPY ../../ .

#ENV COMPOSER_ALLOW_SUPERUSER=1
#
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Установка прав
RUN chmod -R 777 /var/www/laravel/*

# Установка зависимостей
#RUN composer install

EXPOSE 9000
CMD ["php-fpm"]
