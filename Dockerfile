# Étape 1 : image PHP avec Apache
FROM php:8.3-apache

# Étape 2 : install des extensions nécessaires
RUN apt-get update && apt-get install -y \
    git unzip libicu-dev libzip-dev libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install intl pdo pdo_mysql zip gd opcache

# Étape 3 : activation de mod_rewrite (URL clean pour Symfony)
RUN a2enmod rewrite

# Étape 4 : installation de Composer depuis l’image officielle
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Étape 5 : copie de l’application dans le dossier Apache
COPY . /var/www/html

# Étape 6 : on se place dans le dossier
WORKDIR /var/www/html

# Étape 7 : droits corrects pour Symfony
RUN chown -R www-data:www-data var vendor public

# Étape 8 : install des dépendances sans dev (prod only)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Étape 9 : variables d’environnement
ENV APP_ENV=prod

# Étape 10 : Symfony doit servir les fichiers via Apache depuis /public
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

# Étape 11 : config Apache pour Symfony (URL rewriting)
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!/var/www/html/public!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Étape 12 : expose le port
EXPOSE 80
