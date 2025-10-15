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

# Detectar y configurar variables de MySQL de Railway automáticamente
echo "🔍 Detectando variables de MySQL de Railway..."

# Railway puede usar diferentes nombres para las variables MySQL
# Detectar automáticamente las variables correctas
for var in $(env | grep -i mysql); do
    echo "Variable MySQL encontrada: $var"
done

echo "🔍 Variables de base de datos detectadas:"
echo "DB_HOST: $DB_HOST"
echo "DB_PORT: $DB_PORT"
echo "DB_DATABASE: $DB_DATABASE"
echo "DB_USERNAME: $DB_USERNAME"
echo "MYSQLHOST: $MYSQLHOST"
echo "MYSQLPORT: $MYSQLPORT"
echo "MYSQLDATABASE: $MYSQLDATABASE"
echo "MYSQLUSER: $MYSQLUSER"
echo "MYSQL_URL: $MYSQL_URL"

# Si existe MYSQL_URL, intentar parsearla
if [ ! -z "$MYSQL_URL" ]; then
    echo "🔗 Parseando MYSQL_URL..."
    echo "MYSQL_URL: $MYSQL_URL"
fi

# Intentar conexión directa con mysql si está disponible
if command -v mysql &> /dev/null; then
    echo "🔌 Probando conexión directa a MySQL..."
    mysql -h "$DB_HOST" -P "$DB_PORT" -u "$DB_USERNAME" -p"$DB_PASSWORD" -e "SELECT 1;" 2>/dev/null && echo "✅ Conexión MySQL exitosa" || echo "❌ Error en conexión MySQL directa"
fi

# Probar conexión a través de Laravel
echo "🔌 Probando conexión Laravel -> MySQL..."
php -r "
try {
    \$pdo = new PDO('mysql:host=$DB_HOST;port=$DB_PORT;dbname=$DB_DATABASE', '$DB_USERNAME', '$DB_PASSWORD', [
        PDO::ATTR_TIMEOUT => 10,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    echo '✅ Conexión PDO exitosa\n';
    \$pdo = null;
} catch (Exception \$e) {
    echo '❌ Error PDO: ' . \$e->getMessage() . '\n';
}
"

# Inicializar aplicación con nuestro comando personalizado
echo "🔧 Inicializando aplicación..."
php artisan app:initialize

# Ejecutar el servidor
echo "🌟 Iniciando servidor Laravel en puerto $PORT..."
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
