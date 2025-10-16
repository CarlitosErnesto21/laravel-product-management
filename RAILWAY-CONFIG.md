# Variables de Entorno para Railway

Este archivo documenta las variables de entorno que DEBES configurar en Railway para que la aplicación funcione correctamente.

## 🚀 Variables Críticas de Railway

### Aplicación
```bash
APP_NAME="Sistema de Productos"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://laravel-tarea.up.railway.app
APP_KEY=base64:t0CXKbCf0vWoFi3mN7J3qkpOrehqxH14K8Ewp+/DFdg=
```

### Base de Datos (Automática con Railway MySQL)
```bash
DB_CONNECTION=mysql
DB_HOST=[Railway proporcionará]
DB_PORT=[Railway proporcionará]
DB_DATABASE=[Railway proporcionará]
DB_USERNAME=[Railway proporcionará]
DB_PASSWORD=[Railway proporcionará]
```

### 📧 Configuración de Correo SMTP (Gmail)
```bash
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=ernesto.rosales354@gmail.com
MAIL_PASSWORD=vjokaqpvvyudlthw
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=ernesto.rosales354@gmail.com
MAIL_FROM_NAME="Sistema de Productos"
MAIL_TIMEOUT=120
```

## 🔧 Configuración Alternativa (si Gmail falla):
```bash
# Opción 1: Usar puerto 465 con SSL
MAIL_PORT=465
MAIL_ENCRYPTION=ssl

# Opción 2: Deshabilitar temporalmente los correos
MAIL_MAILER=log
```

### ☁️ Cloudinary (Para imágenes)
```bash
CLOUDINARY_CLOUD_NAME=dsevlfmaq
CLOUDINARY_API_KEY=633324148814975
CLOUDINARY_API_SECRET=sUHktazAs_E7b0g_V0azsHGaO6E
```

### Otras configuraciones
```bash
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
```

## 📋 Checklist de Configuración

- [ ] Configurar todas las variables de correo
- [ ] Verificar que la base de datos esté conectada
- [ ] Confirmar que Cloudinary funciona
- [ ] Probar el envío de correos con `/test-email`
- [ ] Verificar que el usuario autorizado puede acceder

## 🧪 Pruebas de Funcionalidad

### Probar Correos
1. Inicia sesión como usuario autorizado
2. Ve a: `https://laravel-tarea.up.railway.app/test-email`
3. Deberías recibir un correo de prueba

### Probar Sistema de Restricción
1. Registra una cuenta con cualquier otro email
2. Deberías ser redirigido a la página de "Acceso Restringido"
3. Los correos se envían en background (no bloquean la aplicación)

## 🚨 Solución de Problemas

### Si los correos no funcionan:
1. **Verificar logs:** Los errores se registran en los logs de Railway
2. **Deshabilitar temporalmente:** Cambiar `MAIL_MAILER=log` en Railway
3. **Verificar Gmail:** Asegurar que la contraseña de aplicación sea válida
4. **Timeout:** Los correos ahora se envían en background y no bloquean la app

### Gmail Troubleshooting:
- La contraseña debe ser una "Contraseña de aplicación" de Google
- 2FA debe estar habilitado en la cuenta de Google
- Verificar que Gmail no esté bloqueando las conexiones desde Railway

## 🔐 Usuario Autorizado
- **Email:** ernesto.rosales354@gmail.com
- **Contraseña:** Carlitos123

## 📱 Funcionalidades de Correo

### Correos que se envían automáticamente:
1. **Registro:** Correo de bienvenida + notificación si no es usuario autorizado
2. **Login:** Notificación de inicio de sesión + alerta si no es usuario autorizado
3. **Acceso no autorizado:** Notificación cuando alguien trata de acceder sin permisos

### Tipos de correo:
- ✅ **Welcome:** Correo de bienvenida para nuevos usuarios
- 🔐 **Login:** Notificación de inicio de sesión
- ⚠️ **Unauthorized:** Alerta de intento de acceso no autorizado

## 🛠️ Comandos de Despliegue

El script `railway-deploy.sh` verifica automáticamente la configuración y:
1. Limpia caché
2. Ejecuta migraciones
3. Crea el usuario autorizado
4. Optimiza la aplicación
5. Compila assets

¡Tu aplicación está lista para producción! 🚀
