#!/bin/bash

echo "🚀 Iniciando proceso de build para Railway..."

# Configurar entorno
export APP_ENV=production
export APP_DEBUG=false

# Instalar dependencias de PHP (sin --no-dev para Railway)
echo "📦 Instalando dependencias de PHP..."
composer install --optimize-autoloader --no-interaction

# Instalar dependencias de Node.js
echo "📦 Instalando dependencias de Node.js..."
npm ci

# Compilar assets
echo "🏗️ Compilando assets..."
npm run build

# Limpiar todos los caches
echo "🧹 Limpiando caches..."
php artisan config:clear || true
php artisan cache:clear || true
php artisan view:clear || true
php artisan route:clear || true
php artisan event:clear || true

# Crear directorios necesarios antes de dar permisos
echo "📁 Creando directorios necesarios..."
mkdir -p storage/logs
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/framework/cache/data
mkdir -p storage/framework/testing
mkdir -p bootstrap/cache

# Dar permisos correctos
echo "🔐 Configurando permisos..."
chmod -R 755 storage
chmod -R 755 bootstrap/cache
chmod -R 755 public

# Generar APP_KEY si no existe
echo "🔑 Verificando APP_KEY..."
if [ -z "$APP_KEY" ]; then
    echo "⚠️ APP_KEY no encontrada, generando nueva..."
    php artisan key:generate --force
else
    echo "✅ APP_KEY encontrada"
fi

echo "✅ Build completado exitosamente!"
