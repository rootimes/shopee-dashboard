FROM composer:2.9.3 AS composer_builder
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-scripts \
    --ignore-platform-reqs \
    --prefer-dist
COPY . .
RUN composer dump-autoload --optimize

FROM node:22 AS node_builder
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
COPY --from=composer_builder /app/vendor ./vendor
RUN npm run build

FROM php:8.4-fpm

WORKDIR /var/www

RUN apt update && apt install -y \
    git curl libpng-dev libonig-dev libxml2-dev libicu-dev \
    libzip-dev libjpeg62-turbo-dev libfreetype6-dev \
    g++ make zip unzip \
    && apt clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd intl opcache

COPY . .
COPY --from=composer_builder /app/vendor ./vendor
COPY --from=node_builder /app/public/build ./public/build
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh

RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT [ "/usr/local/bin/entrypoint.sh" ]

EXPOSE 9000

CMD ["php-fpm"]
