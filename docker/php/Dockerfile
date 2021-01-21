FROM php:7.4-fpm

RUN apt-get update \
  && apt-get install -y --no-install-recommends libpq-dev wget gnupg libfreetype6-dev libjpeg62-turbo-dev libpng-dev unzip libsqlite3-dev
RUN docker-php-ext-install mysqli pdo_pgsql pdo_sqlite pdo_mysql gd

# GD
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
  && docker-php-ext-install -j "$(nproc)" gd

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
