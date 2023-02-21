FROM php:8.2-apache

RUN apt-get update && apt-get install -y libpq-dev libicu-dev libssl-dev \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql intl

COPY apache/ssl.conf /etc/apache2/sites-available/

RUN a2enmod ssl

RUN service apache2 restart

EXPOSE 80 443