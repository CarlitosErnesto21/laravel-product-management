# Proyecto Laravel - Sistema de Subida de Imágenes de Productos

Este proyecto es una aplicación Laravel para gestionar productos con capacidad de subir imágenes usando Cloudinary y MySQL como base de datos.

## Funcionalidades principales:
- Gestión de productos (CRUD)
- Subida de imágenes usando Cloudinary
- Base de datos MySQL
- Interfaz web para administrar productos

## Tecnologías utilizadas:
- PHP 8.2+
- Laravel 10+
- MySQL 8.0+
- Cloudinary para almacenamiento de imágenes
- Bootstrap para estilos
- Composer para dependencias

## Estructura del proyecto:
- Modelo Product con campos: name, description, price, image_url
- Controlador ProductController para CRUD
- Migraciones para tabla products
- Vistas con formularios de subida de imágenes
- Integración con Cloudinary SDK

## Configuración requerida:
- Variables de entorno para Cloudinary (CLOUD_NAME, API_KEY, API_SECRET)
- Configuración de base de datos MySQL
- Permisos de escritura para storage