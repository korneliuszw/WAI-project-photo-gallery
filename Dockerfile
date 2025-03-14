FROM php:7.0-apache

RUN echo "deb http://archive.debian.org/debian/ stretch main contrib" > /etc/apt/sources.list
RUN apt update -y && apt upgrade -y
RUN apt install git libssl-dev libfreetype6-dev libpng-dev libjpeg62-turbo-dev -y
RUN pecl install mongodb-1.9.0 && docker-php-ext-enable mongodb
RUN pecl install xdebug-2.7.0
RUN docker-php-ext-enable xdebug mongodb
RUN docker-php-ext-configure gd  --with-freetype-dir --with-jpeg-dir
RUN docker-php-ext-install gd
RUN a2enmod rewrite

COPY --from=composer:2.2 /usr/bin/composer /usr/bin/composer

# Install Playwright dependencies
RUN apt-get update && apt-get install -y wget gnupg
RUN wget -qO - https://deb.nodesource.com/gpgkey/nodesource.gpg.key | apt-key add -
RUN echo "deb https://deb.nodesource.com/node_14.x stretch main" > /etc/apt/sources.list.d/nodesource.list
RUN apt-get update && apt-get install -y nodejs
RUN npm install -g playwright

# Install pnpm
RUN npm install -g pnpm

# Set image path to environment variable TEST_IMAGE
ENV TEST_IMAGE=/path/to/test/image

EXPOSE 80 443
