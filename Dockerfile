FROM php:8.3-apache

# Instalando extensões PHP necessárias para o PostgreSQL
RUN apt-get update \
    && apt-get install -y \
    libzip-dev \
    zip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libpq-dev \
    && docker-php-ext-configure gd \
    --with-freetype \
    --with-jpeg \
    && docker-php-ext-install gd \
    && docker-php-ext-install zip \
    && docker-php-ext-install pdo pdo_mysql mysqli

RUN curl -sS https://getcomposer.org/installer \
    | php -- --install-dir=/usr/local/bin --filename=composer

# Copiando o arquivo de configuração do Apache
COPY apache-config.conf /etc/apache2/sites-available/000-default.conf

# Ativando mod_rewrite do Apache
RUN a2enmod rewrite

WORKDIR /var/www/

# Reiniciando o Apache
RUN service apache2 restart