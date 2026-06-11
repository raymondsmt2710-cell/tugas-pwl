FROM alpine:3.20 AS composer-builder
WORKDIR /app

RUN apk add --no-cache \
    php83 \
    php83-phar \
    php83-openssl \
    php83-zlib \
    php83-curl \
    php83-mbstring \
    php83-xml \
    php83-tokenizer \
    php83-xmlwriter \
    php83-intl \
    php83-zip \
    curl \
    git \
    unzip

RUN ln -sf /usr/bin/php83 /usr/bin/php
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction \
    --no-progress \
    --no-scripts

FROM alpine:3.20 AS production
WORKDIR /var/www/html

RUN apk add --no-cache \
    nginx \
    supervisor \
    curl \
    shadow \
    php83 \
    php83-fpm \
    php83-pdo_mysql \
    php83-zip \
    php83-opcache \
    php83-gd \
    php83-intl \
    php83-pcntl \
    php83-exif \
    php83-bcmath \
    php83-pecl-redis \
    php83-openssl \
    php83-mbstring \
    php83-xml \
    php83-session \
    php83-sockets \
    php83-curl \
    php83-tokenizer \
    php83-xmlwriter \
    php83-simplexml \
    php83-dom \
    php83-fileinfo \
    php83-phar \
    php83-iconv

RUN ln -sf /usr/bin/php83 /usr/bin/php

RUN getent group www-data || addgroup -S -g 82 www-data \
    && getent passwd www-data || adduser -S -G www-data -u 82 www-data

COPY docker/php.ini /etc/php83/conf.d/laravel.ini
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisord.conf

COPY --chown=www-data:www-data . .
COPY --from=composer-builder --chown=www-data:www-data /app/vendor/ ./vendor/
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
