# Sistema de Productos con Laravel, Vue.js e Inertia.js

Este proyecto es una aplicación web para gestionar productos con funcionalidad de subida de imágenes usando **Laravel 11**, **Vue.js 3**, **Inertia.js** y **Cloudinary**.

## 🚀 Características

- ✅ CRUD completo de productos
- ✅ Subida de imágenes a Cloudinary con optimización automática
- ✅ Interfaz moderna con Vue.js 3 y Tailwind CSS
- ✅ SPA (Single Page Application) con Inertia.js
- ✅ Base de datos MySQL
- ✅ Componentes Vue reutilizables
- ✅ Responsive design

## 🛠️ Tecnologías Utilizadas

- **Backend**: Laravel 11, PHP 8.2+
- **Frontend**: Vue.js 3, Inertia.js
- **Base de datos**: MySQL 8.0+
- **Almacenamiento de imágenes**: Cloudinary
- **Estilos**: Tailwind CSS
- **Build tool**: Vite

## 📋 Requisitos Previos

- PHP 8.2 o superior
- Composer
- Node.js y npm
- MySQL 8.0+
- Cuenta en Cloudinary (gratuita)

## ⚙️ Instalación

### 1. Instalar dependencias de PHP
```bash
composer install
```

### 2. Instalar dependencias de Node.js
```bash
npm install
```

### 3. Configurar variables de entorno
Edita el archivo `.env` y configura:

#### Base de datos MySQL:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_products
DB_USERNAME=tu_usuario_mysql
DB_PASSWORD=tu_password_mysql
```

#### Configuración de Cloudinary:
```env
CLOUDINARY_CLOUD_NAME=tu_cloud_name
CLOUDINARY_API_KEY=tu_api_key
CLOUDINARY_API_SECRET=tu_api_secret
```

### 4. Generar clave de aplicación
```bash
php artisan key:generate
```

### 5. Ejecutar migraciones
```bash
php artisan migrate
```

### 6. Compilar assets
```bash
# Para desarrollo
npm run dev

# Para producción
npm run build
```

### 7. Iniciar el servidor
```bash
php artisan serve
```

La aplicación estará disponible en `http://localhost:8000`

## 🎯 Configuración de Cloudinary

1. Crea una cuenta gratuita en [Cloudinary](https://cloudinary.com/)
2. Ve a tu Dashboard y copia:
   - Cloud Name
   - API Key  
   - API Secret
3. Pégalos en tu archivo `.env`

## 📁 Estructura del Proyecto

```
├── app/
│   ├── Http/Controllers/
│   │   └── ProductController.php      # Controlador de productos
│   ├── Models/
│   │   └── Product.php                # Modelo de producto
│   └── Http/Middleware/
│       └── HandleInertiaRequests.php  # Middleware de Inertia
├── database/
│   └── migrations/
│       └── create_products_table.php  # Migración de productos
├── resources/
│   ├── js/
│   │   ├── Pages/
│   │   │   └── Products/
│   │   │       └── Index.vue          # Página principal de productos
│   │   ├── Layouts/
│   │   │   └── AppLayout.vue          # Layout principal
│   │   └── app.js                     # Configuración de Inertia + Vue
│   └── views/
│       └── app.blade.php              # Template base de Inertia
└── routes/
    └── web.php                        # Rutas de la aplicación
```

## 🎨 Funcionalidades

### Gestión de Productos
- **Listar productos**: Vista en grid con imágenes, nombre, descripción y precio
- **Crear producto**: Modal con formulario y subida de imagen
- **Editar producto**: Modificar datos y actualizar imagen
- **Eliminar producto**: Confirmar eliminación y limpiar Cloudinary

### Subida de Imágenes
- **Optimización automática**: Las imágenes se redimensionan a 800x600px máximo
- **Preview instantáneo**: Vista previa antes de guardar
- **Gestión inteligente**: Las imágenes antiguas se eliminan automáticamente

## 🚀 Uso

1. Accede a `http://localhost:8000`
2. Haz clic en "Agregar Producto"
3. Completa el formulario:
   - Nombre del producto
   - Descripción (opcional)
   - Precio
   - Imagen (opcional)
4. Haz clic en "Guardar"

Los productos aparecerán en la lista principal con opciones para editar o eliminar.

## 🔧 Comandos Útiles

```bash
# Limpiar caché
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Recrear base de datos
php artisan migrate:fresh

# Ver rutas
php artisan route:list

# Compilar assets para producción
npm run build

# Modo de desarrollo con hot reload
npm run dev
```

## 📝 Licencia

Este proyecto está bajo la Licencia MIT.

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
