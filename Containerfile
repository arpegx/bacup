FROM php:8.2-cli
COPY . /usr/src/bacup
WORKDIR /usr/src/bacup
EXPOSE 9003
RUN apt-get update && apt-get install -y tree \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && echo ' phar.readonly = "Off" \n xdebug.mode=coverage,debug \n xdebug.start_with_request=yes \n xdebug.client_port=9003 \n xdebug.remote_port=9003 \n xdebug.client_host="host.containers.internal"' >> /usr/local/etc/php/php.ini
ENTRYPOINT ["bash"]