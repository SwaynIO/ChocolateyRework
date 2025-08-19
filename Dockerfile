FROM php:8.2-fpm

# Arguments de build
ARG WWWGROUP=1000
ARG WWWUSER=1000

# Variables d'environnement
ENV DEBIAN_FRONTEND=noninteractive
ENV TZ=Europe/Paris

# Installation des dépendances système
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    nginx \
    supervisor \
    cron \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libwebp-dev \
    libxpm-dev \
    libmagickwand-dev \
    && rm -rf /var/lib/apt/lists/*

# Installation des extensions PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp --with-xpm \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
        intl \
        opcache

# Installation d'ImageMagick
RUN pecl install imagick \
    && docker-php-ext-enable imagick

# Installation de Redis
RUN pecl install redis \
    && docker-php-ext-enable redis

# Installation de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Création de l'utilisateur www
RUN groupadd --force -g $WWWGROUP chocolatey \
    && useradd -ms /bin/bash --no-user-group -g $WWWGROUP -u $WWWUSER chocolatey

# Configuration PHP
COPY docker/php/local.ini /usr/local/etc/php/conf.d/local.ini
COPY docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# Configuration des permissions
RUN mkdir -p /var/www/html \
    && chown -R chocolatey:chocolatey /var/www/html

# Définition du répertoire de travail
WORKDIR /var/www/html

# Copie des fichiers de l'application
COPY --chown=chocolatey:chocolatey . .

# Installation des dépendances Composer
USER chocolatey
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Retour à root pour les configurations finales
USER root

# Configuration des permissions pour le cache et les logs
RUN mkdir -p storage/logs storage/framework/cache storage/framework/sessions storage/framework/views \
    && chown -R chocolatey:chocolatey storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Script de démarrage
COPY docker/start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

# Exposition du port
EXPOSE 9000

# Commande par défaut
CMD ["/usr/local/bin/start.sh"]