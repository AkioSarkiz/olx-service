FROM php:8.3-fpm

RUN apt update \
    && apt install -y python3 python3-pip sendmail \
    && docker-php-ext-install pdo pdo_mysql mysqli \
    && pip3 install beautifulsoup4==4.12.3 requests==2.31.0 --break-system-packages \
    && apt-get clean autoclean \
    && apt-get autoremove --yes \
    && rm -rf /var/lib/{apt,dpkg,cache,log}/

RUN rm -rf /var/www/*

COPY ./src/ /var/www

WORKDIR /var/www
