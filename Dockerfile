#Choose our environment version

FROM node:18.15 AS node
FROM php:8.1-apache

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- \
    &&  mv composer.phar /usr/local/bin/composer

RUN apt-get update \
    &&  apt-get install -y --no-install-recommends \
        npm \
        wget \
        libicu-dev \
        libzip-dev \
        libpng-dev \
        libxslt-dev
    
# Change apache source for index.php for symfony
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Adds php plugins
RUN docker-php-ext-install mysqli
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install opcache
RUN docker-php-ext-install calendar
# Needs libicu-dev
RUN docker-php-ext-install intl
# Needs libzip-dev
RUN docker-php-ext-install zip
# Needs libpng-dev
RUN docker-php-ext-install gd
# Needs libxslt-dev
RUN docker-php-ext-install xsl

COPY --from=node /usr/local/lib/node_modules /usr/local/lib/node_modules
COPY --from=node /usr/local/bin/node /usr/local/bin/node
RUN ln -s /usr/local/lib/node_modules/npm/bin/npm-cli.js /usr/local/bin/npm

RUN mkdir -p /usr/src/app
WORKDIR /usr/src/app

COPY package.json /usr/src/app
RUN composer install && npm install

# Sets all files from app folder to the docker container folder
COPY . /var/www/html/

WORKDIR /var/www/html/