FROM node:24-alpine AS node-build
WORKDIR /app
COPY package.json package-lock.json .npmrc ./
RUN npm ci
COPY . .
RUN npm run build

FROM composer:latest AS composer-build
WORKDIR /app
COPY composer.json composer.lock ./
ARG APP_ENV=production
RUN if [ "$APP_ENV" = "production" ]; then \
        composer install --no-dev --optimize-autoloader --no-scripts --no-interaction; \
    else \
        composer install --optimize-autoloader --no-scripts --no-interaction; \
    fi

FROM php:8.4-fpm-alpine
RUN apk add --no-cache freetype-dev libpng-dev libjpeg-turbo-dev libzip-dev zlib-dev oniguruma-dev unzip curl git supervisor \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) pdo pdo_mysql bcmath ctype fileinfo mbstring openssl tokenizer xml gd zip \
    && docker-php-ext-enable opcache \
    && apk del freetype-dev libpng-dev libjpeg-turbo-dev libzip-dev zlib-dev oniguruma-dev

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY --from=composer-build /app/vendor /var/www/html/vendor

WORKDIR /var/www/html

COPY --chown=www-data:www-data . .
COPY --from=node-build --chown=www-data:www-data /app/public/build /var/www/html/public/build

RUN mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache public/build \
    && chmod -R 775 storage bootstrap/cache \
    && composer dump-autoload --no-dev --optimize

USER www-data

EXPOSE 9000

CMD ["php-fpm"]
