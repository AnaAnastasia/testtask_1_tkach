FROM php:8-fpm-alpine

RUN apk --no-cache add --virtual .build-deps $PHPIZE_DEPS && docker-php-ext-install mysqli pdo pdo_mysql

RUN apk --no-cache add --virtual .build-deps $PHPIZE_DEPS linux-headers \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && apk del .build-deps