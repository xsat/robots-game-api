FROM php:7.4.2-fpm
RUN apt-get update && apt-get install -y \
    git \
    libzip-dev \
    zip \
    unzip
RUN echo "cgi.fix_pathinfo=0;" > /usr/local/etc/php-fpm.d/php.ini
RUN pecl install mongodb-1.7.1 \
    && docker-php-ext-enable mongodb

WORKDIR /usr/share/nginx/robots
ADD . .
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer
