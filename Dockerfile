# Image de base : PHP 8.4 en mode FPM (FastCGI — reçoit les requêtes PHP depuis Nginx)
FROM php:8.4-fpm

# Installation des dépendances système et extensions PHP requises par Laravel
RUN apt-get update && apt-get install -y \
    git curl zip unzip libzip-dev libpng-dev libonig-dev \  # Outils CLI + libs pour zip, images, regex multibyte
    && docker-php-ext-install pdo_mysql zip gd opcache mbstring pcntl \  # Extensions : MySQL, archives, images, perf, strings, queues
    && pecl install redis && docker-php-ext-enable redis \  # Extension Redis (cache, sessions, queue — Exo 4/5)
    && apt-get clean && rm -rf /var/lib/apt/lists/*  # Nettoyage pour réduire la taille de l'image

# Copie du binaire Composer depuis l'image officielle (évite de l'installer manuellement)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Répertoire de travail dans le conteneur (monté en volume en dev — voir docker-compose)
WORKDIR /var/www/html

# Copie du code source dans l'image (utilisé au build ; en dev le volume .:/var/www/html écrase ce contenu)
COPY . .

# Installation des dépendances PHP (prod) + permissions sur storage/ et bootstrap/cache/
RUN composer install --no-dev --optimize-autoloader \
    && chown -R www-data:www-data storage bootstrap/cache

# En dev : le volume monté écrase vendor — relancer `docker compose exec app composer install` après le premier up
USER www-data          # Exécute php-fpm avec l'utilisateur non-root www-data (sécurité)
EXPOSE 9000            # Port FastCGI écouté par php-fpm (Nginx s'y connecte via app:9000)
CMD ["php-fpm"]        # Processus principal du conteneur « app » et « queue »
