FROM composer:latest AS composer_builder
WORKDIR /app
COPY . .

RUN composer install --no-dev --no-interaction --prefer-dist --ignore-platform-reqs

FROM node:22 AS node_builder
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
COPY --from=composer_builder /app/vendor ./vendor
RUN npm run build

FROM php:8.4-fpm-alpine

WORKDIR /var/www

RUN apk add --no-cache \
    git curl libpng-dev libonig-dev libxml2-dev libicu-dev \
    libzip-dev libjpeg62-turbo-dev libfreetype6-dev \
    g++ make zip unzip

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd intl opcache

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . /var/www

COPY --from=composer_builder /app/vendor /var/www/vendor

COPY --from=node_builder /app/public/build /var/www/public/build

RUN chmod -R 755 /var/www/storage /var/www/bootstrap/cache \
    && chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

USER www-data

EXPOSE 9000

CMD ["php-fpm"]
