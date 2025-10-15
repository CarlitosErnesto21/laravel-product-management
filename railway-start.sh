#!/bin/bash

echo "🚀 Iniciando aplicación Laravel en Railway..."

# Configurar variables de entorno críticas
export APP_ENV=production
export APP_DEBUG=false
export LOG_CHANNEL=stderr

# Temporalmente usar file sessions para evitar errores de conexión al inicio
export SESSION_DRIVER=file
export CACHE_STORE=file

# Crear directorios necesarios con permisos
mkdir -p storage/logs storage/framework/{sessions,views,cache,testing} bootstrap/cache
chmod -R 755 storage bootstrap/cache public

# Test de conexión de base de datos antes de inicializar
echo "🔍 Probando conexión a base de datos..."
if php artisan tinker --execute="DB::connection()->getPdo(); echo 'DB OK';" 2>/dev/null; then
    echo "✅ Conexión a base de datos exitosa"
    # Si la BD funciona, usar configuraciones de base de datos
    export SESSION_DRIVER=database
    export CACHE_STORE=database
else
    echo "⚠️ Problema con conexión a BD, usando archivos temporalmente"
fi

# Inicializar aplicación con nuestro comando personalizado
echo "🔧 Inicializando aplicación..."
php artisan app:initialize

# Ejecutar el servidor
echo "🌟 Iniciando servidor Laravel en puerto $PORT..."
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
