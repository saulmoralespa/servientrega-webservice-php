FROM php:8.3-apache
RUN apt-get update \
    && apt-get install -y libxml2-dev \
    && docker-php-ext-install soap