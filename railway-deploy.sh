#!/bin/bash

# Script de despliegue para Railway
# Este script se ejecuta automáticamente en Railway

echo "🚀 Iniciando despliegue en Railway..."

# Limpiar cache
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo "✅ Cache limpiado"

# Ejecutar migraciones
php artisan migrate --force

echo "✅ Migraciones ejecutadas"

# Optimizar aplicación para producción
php artisan config:cache
php artisan route:cache

echo "✅ Aplicación optimizada"

# Construir assets con Vite
npm run build

echo "✅ Assets compilados"

echo "🎉 Despliegue completado exitosamente"
