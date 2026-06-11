FROM node:20-alpine AS node-builder
WORKDIR /app

COPY package*.json ./
RUN npm ci --no-audit --no-fund --quiet

COPY vite.config.js ./
COPY tailwind.config.js* ./
COPY postcss.config.js* ./
COPY resources/ ./resources/
COPY public/ ./public/
RUN npm run build

FROM php:8.3-fpm-alpine AS composer-builder
WORKDIR /app

RUN apk add --no-cache git unzip libzip-dev
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction \
    --no-progress \
    --no-scripts

FROM php:8.3-fpm-alpine AS production
WORKDIR /var/www/html

RUN apk add --no-cache \
    nginx \
    supervisor \
    curl \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    icu-dev \
    oniguruma-dev \
    shadow

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        zip \
        opcache \
        gd \
        intl \
        pcntl \
        exif \
        bcmath

RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del .build-deps

COPY docker/php.ini $PHP_INI_DIR/conf.d/laravel.ini
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisord.conf

COPY --chown=www-data:www-data . .
COPY --from=composer-builder --chown=www-data:www-data /app/vendor/ ./vendor/
COPY --from=node-builder --chown=www-data:www-data /app/public/build/ ./public/build/
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN composer dump-autoload --optimize --no-dev --classmap-authoritative \
    && php artisan vendor:publish --tag=laravel-assets --ansi --force

RUN chmod -R 775 storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

COPY docker/docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
