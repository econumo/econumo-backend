FROM php:7.3-fpm-alpine as dev

ENV PHP_DISPLAY_ERRORS 0
ENV PHP_ERROR_REPORTING 22527

WORKDIR /var/www/

RUN sed -i "s/127.0.0.1:9000/0.0.0.0:9000/g" /usr/local/etc/php-fpm.d/www.conf
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

# custom ini
COPY build/docker/app/configs /

RUN apk --update add git postgresql-dev \
    && apk add --no-cache --virtual .build-deps $PHPIZE_DEPS curl-dev \
    && docker-php-ext-configure bcmath --enable-bcmath \
    && docker-php-ext-configure opcache --enable-opcache \
    && docker-php-ext-configure pdo_pgsql --with-pdo-pgsql \
    && docker-php-ext-configure mbstring --enable-mbstring \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && docker-php-ext-install -j$(nproc) bcmath opcache json curl iconv pdo_pgsql \
    && apk del -f .build-deps \
    && rm -rf /tmp/* /var/cache/apk/*

# composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
# RUN composer global require hirak/prestissimo --no-plugins --no-scripts


FROM dev as prod

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

COPY composer.json composer.lock symfony.lock /var/www/
RUN composer install --no-scripts --prefer-dist --no-autoloader

COPY . /var/www
# Finish composer
RUN composer dump-autoload --optimize

RUN chown www-data:www-data /var/www/ -R

# cleanup
RUN composer clear-cache \
#    && composer global remove hirak/prestissimo \
    && rm /usr/local/bin/composer \
    && apk del git \
    && pecl uninstall xdebug \
    && rm /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && rm /usr/local/etc/php-fpm.d/xdebug.ini

ENTRYPOINT ["php-fpm", "-F"]