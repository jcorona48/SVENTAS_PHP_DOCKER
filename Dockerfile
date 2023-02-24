FROM php:8.1.16-apache

RUN apt-get update && apt-get upgrade -y

RUN apt-get install -y libpq-dev libzip-dev libicu-dev \
    && docker-php-ext-install pdo_mysql mysqli zip intl

COPY ./src /var/www/html/

EXPOSE 80