FROM php:7.4-apache

# Instalar dependencias necesarias
RUN apt-get update && apt-get install -y \
    git \
    openssl \
    libpng-dev \
    libzip-dev \
    && docker-php-ext-install mysqli pdo pdo_mysql gd zip mbstring

# Clonar tu repositorio
RUN git clone https://github.com/Agustin75/proyecto-intermodular-daw-2.git /var/www/html

# Copiar archivo de configuración unificado
COPY config/apache.conf /etc/apache2/apache2.conf

# Copiar certificados SSL
COPY config/ssl.crt /etc/ssl/certs/ssl.crt
COPY config/ssl.key /etc/ssl/private/ssl.key

# Activar módulos necesarios
RUN a2enmod ssl rewrite headers

EXPOSE 80 443
