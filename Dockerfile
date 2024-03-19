FROM php:apache-bookworm

RUN apt-get update && \
    apt-get install -y libpq-dev && \
    docker-php-ext-install pdo pdo_pgsql

RUN a2enmod rewrite

COPY apache2.conf /etc/apache2/sites-available/000-default.conf