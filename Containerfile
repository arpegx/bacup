FROM php:8.2-cli
COPY . /usr/src/bacup
WORKDIR /usr/src/bacup
EXPOSE 9003
RUN apt-get update && apt-get install -y tree \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && echo 'phar.readonly = "Off"' >> /usr/local/etc/php/php.ini \
    && echo 'xdebug.mode=coverage,debug' >> /usr/local/etc/php/php.ini \
    && echo 'xdebug.log_level = 0' >> /usr/local/etc/php/php.ini \
    && echo 'xdebug.start_with_request=yes' >> /usr/local/etc/php/php.ini \
    && echo 'xdebug.client_host="host.containers.internal"' >> /usr/local/etc/php/php.ini
ENTRYPOINT ["bash"]