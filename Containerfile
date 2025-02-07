FROM php:8.2-cli
COPY . /usr/src/bacup
WORKDIR /usr/src/bacup
CMD [ "php", "./bacup" ]