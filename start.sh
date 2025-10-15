#!/bin/bash

echo "🚀 Iniciando aplicación Laravel en Railway con Frankenphp..."

# Configurar variables de entorno críticas
export APP_ENV=production
export APP_DEBUG=false
export LOG_CHANNEL=stderr
export LOG_LEVEL=debug

# Verificar APP_KEY
if [ -z "$APP_KEY" ]; then
    echo "⚠️ Generando APP_KEY..."
    php artisan key:generate --force --no-interaction
fi

# Crear directorios necesarios
mkdir -p storage/logs
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/framework/cache/data
mkdir -p bootstrap/cache

# Configurar permisos
chmod -R 755 storage bootstrap/cache public

# Limpiar y optimizar
echo "🧹 Optimizando aplicación..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Ejecutar migraciones
echo "🗃️ Ejecutando migraciones..."
php artisan migrate --force --no-interaction

# Crear storage link
php artisan storage:link || echo "Storage link ya existe"

# Cachear para producción
echo "⚡ Cacheando configuraciones..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Aplicación lista para servir!"

# Ejecutar el servidor
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
