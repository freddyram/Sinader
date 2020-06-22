FROM php:7.3.5-apache-stretch
ENV DEBIAN_FRONTEND=nointeractive
COPY vhost.conf /etc/apache2/sites-available/000-default.conf
RUN apt-get update --quiet \
    && apt-get install -y libpq-dev \
    && apt-get install -y libpng-dev \
    && docker-php-ext-install pdo pdo_pgsql \
    && docker-php-ext-install gd \
    && a2enmod rewrite \
    && apt-get --yes --quiet autoremove \
    && apt-get --quiet clean \
    && rm -rf /var/lib/apt/lists/* /var/tmp/* /tmp/*
WORKDIR /var/www/app
EXPOSE 80