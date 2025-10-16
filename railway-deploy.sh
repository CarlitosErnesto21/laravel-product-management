#!/bin/bash

# Script de despliegue para Railway
# Este script se ejecuta automáticamente en Railway

echo "🚀 Iniciando despliegue en Railway..."

# Verificar variables de entorno críticas
echo "🔍 Verificando configuración de correo..."
if [ -z "$MAIL_MAILER" ] || [ -z "$MAIL_HOST" ] || [ -z "$MAIL_USERNAME" ] || [ -z "$MAIL_PASSWORD" ]; then
    echo "⚠️  ADVERTENCIA: Variables de correo no configuradas. Los correos no funcionarán."
    echo "   Configura: MAIL_MAILER, MAIL_HOST, MAIL_USERNAME, MAIL_PASSWORD"
    echo "   MAIL_FROM_ADDRESS, MAIL_FROM_NAME"
else
    echo "✅ Configuración de correo detectada"
fi

# Limpiar cache
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo "✅ Cache limpiado"

# Ejecutar migraciones
php artisan migrate --force

echo "✅ Migraciones ejecutadas"

# Ejecutar seeder del usuario autorizado
php artisan db:seed --class=AuthorizedUserSeeder --force

echo "✅ Usuario autorizado creado/actualizado"

# Optimizar aplicación para producción
php artisan config:cache
php artisan route:cache

echo "✅ Aplicación optimizada"

# Construir assets con Vite
npm run build

echo "✅ Assets compilados"

echo "🎉 Despliegue completado exitosamente"
