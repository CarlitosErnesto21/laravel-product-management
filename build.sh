#!/bin/bash

# Instalar dependencias de PHP
composer install --optimize-autoloader --no-dev

# Instalar dependencias de Node.js
npm ci

# Compilar assets
npm run build

# Optimizar configuración para producción
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Ejecutar migraciones
php artisan migrate --force

# Crear enlace simbólico para storage
php artisan storage:link