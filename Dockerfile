FROM php:8.3-cli-alpine

# Instalar dependencias del sistema y extensiones de PHP necesarias
RUN apk add --no-cache \
    sqlite-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-install pdo_sqlite pcntl

# Instalar Composer oficial
COPY --from=composer:2.8 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copiar archivos del proyecto
COPY . .

# Instalar dependencias de producción
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Variables de entorno por defecto
ENV APP_NAME="KFM Insurance CRM"
ENV APP_ENV=production
ENV APP_KEY=base64:x8skPiUXx7S3AdedhOa6NS83GFg1ermn3bhcVISFTEs=
ENV APP_DEBUG=false
ENV DB_CONNECTION=sqlite
ENV DB_DATABASE=/var/www/html/database/database.sqlite
ENV PORT=80

# Permisos para storage y database
RUN mkdir -p /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database \
    && touch /var/www/html/database/database.sqlite \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database

EXPOSE 80

# Script de arranque de producción
CMD ["sh", "-c", "if [ ! -f .env ]; then cp .env.example .env; fi && touch database/database.sqlite && php artisan migrate --force && if [ ! -f database/.seeded ]; then php artisan db:seed --force && touch database/.seeded; fi && php artisan config:cache && php artisan route:cache && php artisan view:cache && php artisan serve --host=0.0.0.0 --port=80"]
