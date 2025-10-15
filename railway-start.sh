#!/bin/bash

echo "🚀 Iniciando aplicación Laravel en Railway..."

# Configurar variables de entorno críticas
export APP_ENV=production
export APP_DEBUG=false
export LOG_CHANNEL=stderr

# Cargar configuraciones override si existen
if [ -f ".env.override" ]; then
    echo "📋 Cargando configuraciones override..."
    set -a
    source .env.override
    set +a
fi

# Forzar configuraciones estables
export SESSION_DRIVER=file
export CACHE_STORE=file
export QUEUE_CONNECTION=sync

# Crear directorios necesarios con permisos
mkdir -p storage/logs storage/framework/{sessions,views,cache,testing} bootstrap/cache
chmod -R 755 storage bootstrap/cache public

# Forzar uso de archivos hasta resolver problema de BD completamente
echo "🔍 Configurando drivers para máxima estabilidad..."
echo "📁 Usando file drivers para sesiones y cache (más estable)"

# Inicializar aplicación con nuestro comando personalizado
echo "🔧 Inicializando aplicación..."
php artisan app:initialize

# Ejecutar el servidor
echo "🌟 Iniciando servidor Laravel en puerto $PORT..."
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
