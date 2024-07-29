FROM composer:2.7 as build
WORKDIR /app
COPY . /app
RUN composer install

FROM php:8.2-rc-apache-buster
RUN docker-php-ext-install pdo pdo_mysql

COPY --from=build /app /var/www/
COPY cloud-run-files/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY cloud-run-files/.env.cloud-run /app/.env

RUN chmod 777 -R /var/www/storage/ && \
    chown -R www-data:www-data /var/www/ && \
    a2enmod rewrite 