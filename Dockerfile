FROM php:7.4-fpm
USER 1000

# Конфиг
ADD php.ini /usr/local/etc/php/conf.d/40-custom.ini

WORKDIR /var/www
CMD ["php-fpm"]

