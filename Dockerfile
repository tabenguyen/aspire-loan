FROM php:7.4-fpm-alpine3.14

RUN apk update
RUN apk add --no-cache \
    nginx \
    icu-dev \
    composer \
    oniguruma-dev \
    autoconf automake libtool nasm \
    pcre-dev g++ gcc make sudo \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    libzip-dev \
    openrc supervisor rsyslog \
    nodejs npm \
    shadow \
    tzdata \
    git \
    libxml2-dev \
    oniguruma-dev
RUN docker-php-ext-install intl exif zip iconv sockets mysqli pdo_mysql mbstring soap opcache
RUN pecl install -o -f redis \
    && rm -rf /tmp/pear \
    && docker-php-ext-enable redis 
RUN docker-php-ext-install fileinfo pcntl && docker-php-ext-enable opcache
RUN docker-php-ext-configure gd --with-freetype --with-jpeg && docker-php-ext-install -j$(nproc) gd
RUN pecl install apcu && docker-php-ext-enable apcu

# Install XDebug Extension
RUN pecl install xdebug

# Nginx Settings
ADD ./php7.4/nginx/conf.d/ /etc/nginx/conf.d/
ADD ./php7.4/nginx/nginx.conf /etc/nginx/nginx.conf
# PHP-FPM Settings
ADD ./php7.4/php-fpm/php-fpm.d/www-data.conf /usr/local/etc/php-fpm.d/www-data.conf
ADD ./php7.4/php-fpm/php.ini /usr/local/etc/php/php.ini
# Supervisor Settings
ADD ./php7.4/supervisord/supervisord.conf /etc/supervisord.conf
ADD ./php7.4/supervisord/supervisor.d/ /etc/supervisor.d/
# crontab設定
ADD ./php7.4/cron-schedule /etc/crontabs/root

RUN touch /run/php-fpm.sock

RUN chown -R www-data:www-data /run/nginx
RUN chown www-data:www-data /run/php-fpm.sock

WORKDIR /app

# Add XDebug Command
ADD ./php7.4/xdebug.sh /usr/local/bin/xdebug
RUN chmod +x /usr/local/bin/xdebug

ADD composer.json composer.lock ./
RUN composer install --no-autoloader

COPY --chown=www-data:www-data . .

RUN composer dump-autoload

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]