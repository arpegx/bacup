FROM php:8.2-cli
COPY . /usr/src/bacup
WORKDIR /usr/src/bacup
RUN apt-get update && apt-get install -y tree \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && echo ' phar.readonly = "Off" \n xdebug.mode=coverage ' >> /usr/local/etc/php/php.ini
ENTRYPOINT ["bash"]