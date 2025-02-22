FROM php:8.2-cli
COPY . /usr/src/bacup
WORKDIR /usr/src/bacup
RUN apt-get update && apt-get install -y tree
RUN echo "phar.readonly = Off" >> /usr/local/etc/php/php.ini
ENTRYPOINT ["bash"]