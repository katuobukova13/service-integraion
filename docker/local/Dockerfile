FROM php:8.1-fpm

EXPOSE 80
EXPOSE 443

WORKDIR /var/www/html

COPY . /var/www/html
COPY docker/local/entrypoint.sh /entrypoint.sh
COPY docker/local/php.ini /usr/local/etc/php/conf.d/php.ini
COPY docker/local/zz-docker.conf /usr/local/etc/php-fpm.d/zz-docker.conf

RUN apt update -y && apt install -y \
    nginx \
    && docker-php-ext-install pdo_mysql opcache \
    && apt-get -y autoremove \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* \
    && chmod +x /entrypoint.sh \
    && chmod -R 777 storage

COPY docker/local/nginx.conf /etc/nginx/nginx.conf

ENTRYPOINT ["/entrypoint.sh"]
