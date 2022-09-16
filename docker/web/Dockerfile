ARG PHP_VERSION=8.1

FROM php:${PHP_VERSION}-apache as app_php

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod +x /usr/local/bin/install-php-extensions &&  \
    install-php-extensions pdo_pgsql intl

RUN apt update && apt upgrade -yqq git vim

#COPY ./apache/sites-enabled/application.conf /etc/apache2/sites-available/000-default.conf

EXPOSE 80