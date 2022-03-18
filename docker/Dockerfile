FROM php:8.1-fpm

EXPOSE 80
EXPOSE 443

WORKDIR /var/www/html

COPY . /var/www/html
COPY docker/entrypoint.sh /entrypoint.sh
COPY docker/php/php.ini /usr/local/etc/php/conf.d/php.ini
COPY docker/php/zz-docker.conf /usr/local/etc/php-fpm.d/zz-docker.conf

RUN apt update -y && apt install -y \
    nginx \
    zlib1g-dev  \
    libzip-dev  \
    libpng-dev  \
    libpq-dev  \
    libxml2-dev \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && docker-php-ext-install zip pdo_pgsql pdo_mysql gd bcmath opcache \
    && apt-get -y autoremove \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* \
    && chmod +x /entrypoint.sh \
    && chmod -R 777 storage

COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf

ENTRYPOINT ["/entrypoint.sh"]
