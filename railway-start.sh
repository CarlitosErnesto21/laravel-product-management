#!/bin/bash

echo "🚀 Iniciando aplicación Laravel en Railway..."

# Configurar variables de entorno críticas
export APP_ENV=production
export APP_DEBUG=false
export LOG_CHANNEL=stderr

# Crear directorios necesarios con permisos
mkdir -p storage/logs storage/framework/{sessions,views,cache,testing} bootstrap/cache
chmod -R 755 storage bootstrap/cache public

# Inicializar aplicación con nuestro comando personalizado
echo "🔧 Inicializando aplicación..."
php artisan app:initialize

# Ejecutar el servidor
echo "🌟 Iniciando servidor Laravel en puerto $PORT..."
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
