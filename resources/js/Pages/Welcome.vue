<template>
    <AppLayout>
        <div class="max-w-6xl mx-auto px-3 sm:px-6 lg:px-8">
            <!-- Hero Section -->
            <div class="text-center py-8 sm:py-12 lg:py-16">
                <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-4 sm:mb-6 leading-tight">
                    Sistema de Gestión de Productos
                </h1>
                <p class="text-sm sm:text-base md:text-lg lg:text-xl text-gray-600 mb-6 sm:mb-8 max-w-xs sm:max-w-lg md:max-w-2xl lg:max-w-3xl mx-auto px-4 sm:px-0">
                    Gestiona tu inventario de productos de manera eficiente con nuestro sistema completo de administración,
                    incluyendo subida de imágenes con Cloudinary y una interfaz moderna con Vue.js.
                </p>

                <div v-if="!$page.props.auth.user" class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center max-w-md sm:max-w-none mx-auto px-4 sm:px-0">
                    <Link
                        :href="route('register')"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 sm:px-8 py-2.5 sm:py-3 rounded-lg text-base sm:text-lg font-medium transition-colors duration-200 text-center"
                    >
                        Comenzar Ahora
                    </Link>
                    <Link
                        :href="route('login')"
                        class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 sm:px-8 py-2.5 sm:py-3 rounded-lg text-base sm:text-lg font-medium transition-colors duration-200 text-center"
                    >
                        Iniciar Sesión
                    </Link>
                </div>

                <div v-else class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center max-w-md sm:max-w-none mx-auto px-4 sm:px-0">
                    <Link
                        :href="route('dashboard')"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 sm:px-8 py-2.5 sm:py-3 rounded-lg text-base sm:text-lg font-medium transition-colors duration-200 text-center"
                    >
                        Ir al Dashboard
                    </Link>
                    <Link
                        :href="route('products.index')"
                        class="bg-green-600 hover:bg-green-700 text-white px-6 sm:px-8 py-2.5 sm:py-3 rounded-lg text-base sm:text-lg font-medium transition-colors duration-200 text-center"
                    >
                        Ver Productos
                    </Link>
                </div>
            </div>

            <!-- Features Section -->
            <div class="py-8 sm:py-12 lg:py-16 bg-gray-50 -mx-3 sm:-mx-6 lg:-mx-8 rounded-lg">
                <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                    <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-center text-gray-900 mb-8 sm:mb-12">
                        Características Principales
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8">
                        <!-- Feature 1 -->
                        <div class="text-center p-4 sm:p-6 hover:bg-white hover:shadow-lg rounded-lg transition-all duration-300">
                            <div class="bg-blue-100 rounded-full p-3 sm:p-4 w-12 h-12 sm:w-16 sm:h-16 mx-auto mb-3 sm:mb-4 flex items-center justify-center">
                                <font-awesome-icon icon="box" class="w-6 h-6 sm:w-8 sm:h-8 text-blue-600" />
                            </div>
                            <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-2">Gestión de Productos</h3>
                            <p class="text-sm sm:text-base text-gray-600">CRUD completo para administrar tu inventario de productos de forma eficiente.</p>
                        </div>

                        <!-- Feature 2 -->
                        <div class="text-center p-4 sm:p-6 hover:bg-white hover:shadow-lg rounded-lg transition-all duration-300">
                            <div class="bg-green-100 rounded-full p-3 sm:p-4 w-12 h-12 sm:w-16 sm:h-16 mx-auto mb-3 sm:mb-4 flex items-center justify-center">
                                <font-awesome-icon icon="image" class="w-6 h-6 sm:w-8 sm:h-8 text-green-600" />
                            </div>
                            <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-2">Subida de Imágenes</h3>
                            <p class="text-sm sm:text-base text-gray-600">Integración con Cloudinary para almacenamiento y gestión optimizada de imágenes.</p>
                        </div>

                        <!-- Feature 3 -->
                        <div class="text-center p-4 sm:p-6 hover:bg-white hover:shadow-lg rounded-lg transition-all duration-300">
                            <div class="bg-purple-100 rounded-full p-3 sm:p-4 w-12 h-12 sm:w-16 sm:h-16 mx-auto mb-3 sm:mb-4 flex items-center justify-center">
                                <font-awesome-icon icon="user" class="w-6 h-6 sm:w-8 sm:h-8 text-purple-600" />
                            </div>
                            <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-2">Autenticación Segura</h3>
                            <p class="text-sm sm:text-base text-gray-600">Sistema completo de registro, login y gestión de usuarios.</p>
                        </div>

                        <!-- Feature 4 -->
                        <div class="text-center p-4 sm:p-6 hover:bg-white hover:shadow-lg rounded-lg transition-all duration-300">
                            <div class="bg-yellow-100 rounded-full p-3 sm:p-4 w-12 h-12 sm:w-16 sm:h-16 mx-auto mb-3 sm:mb-4 flex items-center justify-center">
                                <font-awesome-icon icon="tachometer-alt" class="w-6 h-6 sm:w-8 sm:h-8 text-yellow-600" />
                            </div>
                            <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-2">Interfaz Moderna</h3>
                            <p class="text-sm sm:text-base text-gray-600">Desarrollado con Vue.js, Inertia.js y Tailwind CSS para una experiencia fluida.</p>
                        </div>

                        <!-- Feature 5 -->
                        <div class="text-center p-4 sm:p-6 hover:bg-white hover:shadow-lg rounded-lg transition-all duration-300">
                            <div class="bg-red-100 rounded-full p-3 sm:p-4 w-12 h-12 sm:w-16 sm:h-16 mx-auto mb-3 sm:mb-4 flex items-center justify-center">
                                <font-awesome-icon icon="home" class="w-6 h-6 sm:w-8 sm:h-8 text-red-600" />
                            </div>
                            <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-2">Listo para Producción</h3>
                            <p class="text-sm sm:text-base text-gray-600">Desplegado en Railway con todas las configuraciones optimizadas.</p>
                        </div>

                        <!-- Feature 6 -->
                        <div class="text-center p-4 sm:p-6 hover:bg-white hover:shadow-lg rounded-lg transition-all duration-300">
                            <div class="bg-indigo-100 rounded-full p-3 sm:p-4 w-12 h-12 sm:w-16 sm:h-16 mx-auto mb-3 sm:mb-4 flex items-center justify-center">
                                <font-awesome-icon icon="check" class="w-6 h-6 sm:w-8 sm:h-8 text-indigo-600" />
                            </div>
                            <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-2">Fácil de Usar</h3>
                            <p class="text-sm sm:text-base text-gray-600">Interfaz intuitiva y fácil navegación para una gestión eficiente.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tech Stack -->
            <div class="py-8 sm:py-12 lg:py-16 text-center">
                <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-900 mb-6 sm:mb-8">Tecnologías Utilizadas</h2>
                <div class="flex flex-wrap justify-center gap-2 sm:gap-3 md:gap-4 text-xs sm:text-sm px-4 sm:px-0">
                    <span class="bg-red-100 text-red-800 px-2 sm:px-3 py-1 rounded-full">Laravel 10+</span>
                    <span class="bg-green-100 text-green-800 px-2 sm:px-3 py-1 rounded-full">Vue.js 3</span>
                    <span class="bg-purple-100 text-purple-800 px-2 sm:px-3 py-1 rounded-full">Inertia.js</span>
                    <span class="bg-blue-100 text-blue-800 px-2 sm:px-3 py-1 rounded-full">Tailwind CSS</span>
                    <span class="bg-yellow-100 text-yellow-800 px-2 sm:px-3 py-1 rounded-full">MySQL</span>
                    <span class="bg-orange-100 text-orange-800 px-2 sm:px-3 py-1 rounded-full">Cloudinary</span>
                    <span class="bg-gray-100 text-gray-800 px-2 sm:px-3 py-1 rounded-full">Railway</span>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
</script>
