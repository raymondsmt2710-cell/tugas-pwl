FROM alpine:3.21 AS composer-builder
WORKDIR /app

RUN apk add --no-cache \
    php84 \
    php84-phar \
    php84-openssl \
    php84-curl \
    php84-mbstring \
    php84-xml \
    php84-tokenizer \
    php84-xmlwriter \
    php84-intl \
    php84-zip \
    php84-iconv \
    php84-dom \
    php84-session \
    php84-fileinfo \
    php84-ctype \
    curl \
    git \
    unzip

RUN ln -sf /usr/bin/php84 /usr/bin/php
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction \
    --no-progress \
    --no-scripts \
    --ignore-platform-reqs

FROM alpine:3.21 AS production
WORKDIR /var/www/html

RUN apk add --no-cache \
    nginx \
    supervisor \
    curl \
    shadow \
    php84 \
    php84-fpm \
    php84-pdo_mysql \
    php84-zip \
    php84-opcache \
    php84-gd \
    php84-intl \
    php84-pcntl \
    php84-exif \
    php84-bcmath \
    php84-pecl-redis \
    php84-openssl \
    php84-mbstring \
    php84-xml \
    php84-session \
    php84-sockets \
    php84-curl \
    php84-tokenizer \
    php84-xmlwriter \
    php84-simplexml \
    php84-dom \
    php84-fileinfo \
    php84-phar \
    php84-iconv \
    php84-ctype

RUN ln -sf /usr/bin/php84 /usr/bin/php

RUN getent group www-data || addgroup -S -g 82 www-data \
    && getent passwd www-data || adduser -S -G www-data -u 82 www-data

RUN mkdir -p /var/log/supervisor /var/log/nginx \
    && sed -i 's/^user = nobody/user = www-data/' /etc/php84/php-fpm.d/www.conf \
    && sed -i 's/^group = nobody/group = www-data/' /etc/php84/php-fpm.d/www.conf

COPY docker/php.ini /etc/php84/conf.d/laravel.ini
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisord.conf

COPY --chown=www-data:www-data . .
COPY --from=composer-builder --chown=www-data:www-data /app/vendor/ ./vendor/
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN composer dump-autoload --optimize --no-dev --classmap-authoritative --ignore-platform-reqs

RUN chmod -R 775 storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

COPY docker/docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
