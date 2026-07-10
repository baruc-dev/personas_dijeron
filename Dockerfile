FROM php:8.2-apache

# Instalar la extensión MySQLi
RUN docker-php-ext-install mysqli

# Habilitar mod_rewrite para Apache (opcional)
RUN a2enmod rewrite

RUN docker-php-ext-install mysqli pdo pdo_mysql
