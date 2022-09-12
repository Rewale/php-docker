FROM php:7.4-fpm
# Пользователь для доступа к содержимого volume
# По умолчанию 1000
ARG UID=1000
USER $UID

# Конфиг
ADD php.ini /usr/local/etc/php/conf.d/40-custom.ini

WORKDIR /var/www
CMD ["php-fpm"]

