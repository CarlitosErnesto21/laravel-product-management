<template>
    <AppLayout>
        <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-4 sm:p-6 bg-white border-b border-gray-200">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-0">
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Dashboard</h1>
                            <p class="text-sm sm:text-base text-gray-600 mt-1 sm:mt-2">
                                Bienvenido de nuevo, <span class="font-semibold">{{ user.name }}</span>
                            </p>
                        </div>
                        <div class="text-xs sm:text-sm text-gray-500 text-right sm:text-left">
                            <div class="hidden sm:block">
                                {{ new Date().toLocaleDateString('es-ES', {
                                    weekday: 'long',
                                    year: 'numeric',
                                    month: 'long',
                                    day: 'numeric'
                                }) }}
                            </div>
                            <div class="sm:hidden">
                                {{ new Date().toLocaleDateString('es-ES', {
                                    day: 'numeric',
                                    month: 'short',
                                    year: 'numeric'
                                }) }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-4 sm:p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                        <!-- Tarjeta de Productos -->
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-4 sm:p-6 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-base sm:text-lg font-semibold">Productos</h3>
                                    <p class="text-blue-100 mt-1 text-xs sm:text-sm">Gestiona tu inventario</p>
                                </div>
                                <div class="bg-blue-700 bg-opacity-20 rounded-full p-2 sm:p-3">
                                    <font-awesome-icon icon="box" class="w-4 h-4 sm:w-6 sm:h-6 text-white" />
                                </div>
                            </div>
                            <div class="mt-3 sm:mt-4">
                                <Link :href="route('products.index')"
                                      class="inline-flex items-center px-3 sm:px-4 py-2 bg-blue-700 bg-opacity-20 rounded-lg hover:bg-opacity-30 transition-colors duration-200 text-xs sm:text-sm">
                                    <span class="hidden sm:inline">Ver Productos</span>
                                    <span class="sm:hidden">Ver</span>
                                    <font-awesome-icon icon="arrow-right" class="ml-1 sm:ml-2 w-3 h-3 sm:w-4 sm:h-4" />
                                </Link>
                            </div>
                        </div>

                        <!-- Tarjeta de Agregar Producto -->
                        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-4 sm:p-6 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-base sm:text-lg font-semibold">Nuevo Producto</h3>
                                    <p class="text-green-100 mt-1 text-xs sm:text-sm">Añade productos nuevos</p>
                                </div>
                                <div class="bg-green-700 bg-opacity-20 rounded-full p-2 sm:p-3">
                                    <font-awesome-icon icon="plus" class="w-4 h-4 sm:w-6 sm:h-6 text-white" />
                                </div>
                            </div>
                            <div class="mt-3 sm:mt-4">
                                <Link :href="route('products.create')"
                                      class="inline-flex items-center px-3 sm:px-4 py-2 bg-green-700 bg-opacity-20 rounded-lg hover:bg-opacity-30 transition-colors duration-200 text-xs sm:text-sm">
                                    <span class="hidden sm:inline">Crear Producto</span>
                                    <span class="sm:hidden">Crear</span>
                                    <font-awesome-icon icon="arrow-right" class="ml-1 sm:ml-2 w-3 h-3 sm:w-4 sm:h-4" />
                                </Link>
                            </div>
                        </div>

                        <!-- Tarjeta de Vista Pública -->
                        <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-lg p-4 sm:p-6 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-base sm:text-lg font-semibold">Vista Pública</h3>
                                    <p class="text-emerald-100 mt-1 text-xs sm:text-sm">Ver como visitante</p>
                                </div>
                                <div class="bg-emerald-700 bg-opacity-20 rounded-full p-2 sm:p-3">
                                    <font-awesome-icon icon="home" class="w-4 h-4 sm:w-6 sm:h-6 text-white" />
                                </div>
                            </div>
                            <div class="mt-3 sm:mt-4">
                                <Link :href="route('welcome')"
                                      class="inline-flex items-center px-3 sm:px-4 py-2 bg-emerald-700 bg-opacity-20 rounded-lg hover:bg-opacity-30 transition-colors duration-200 text-xs sm:text-sm">
                                    <span class="hidden sm:inline">Ver Página Principal</span>
                                    <span class="sm:hidden">Ver Página</span>
                                    <font-awesome-icon icon="external-link-alt" class="ml-1 sm:ml-2 w-3 h-3 sm:w-4 sm:h-4" />
                                </Link>
                            </div>
                        </div>

                        <!-- Tarjeta de Perfil -->
                        <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg p-4 sm:p-6 text-white">
                            <div class="flex items-center justify-between">
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-base sm:text-lg font-semibold">Mi Perfil</h3>
                                    <p class="text-purple-100 mt-1 text-xs sm:text-sm">Información de cuenta</p>
                                </div>
                                <div class="bg-purple-700 bg-opacity-20 rounded-full p-2 sm:p-3 flex-shrink-0">
                                    <font-awesome-icon icon="user" class="w-4 h-4 sm:w-6 sm:h-6 text-white" />
                                </div>
                            </div>
                            <div class="mt-3 sm:mt-4">
                                <div class="text-xs sm:text-sm">
                                    <p class="truncate"><span class="font-semibold">Email:</span> {{ user.email }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Acciones rápidas -->
                    <div class="mt-6 sm:mt-8">
                        <h2 class="text-lg sm:text-xl font-semibold text-gray-900 mb-3 sm:mb-4">Acciones Rápidas</h2>
                        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 sm:gap-4">
                            <Link :href="route('products.index')"
                                  class="p-3 sm:p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200 text-center">
                                <div class="text-gray-600 mb-1 sm:mb-2">
                                    <font-awesome-icon icon="eye" class="w-6 h-6 sm:w-8 sm:h-8 mx-auto" />
                                </div>
                                <p class="text-xs sm:text-sm font-medium text-gray-900">
                                    <span class="hidden sm:inline">Ver Todos los Productos</span>
                                    <span class="sm:hidden">Ver Productos</span>
                                </p>
                            </Link>

                            <Link :href="route('products.create')"
                                  class="p-3 sm:p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200 text-center">
                                <div class="text-gray-600 mb-1 sm:mb-2">
                                    <font-awesome-icon icon="plus" class="w-6 h-6 sm:w-8 sm:h-8 mx-auto" />
                                </div>
                                <p class="text-xs sm:text-sm font-medium text-gray-900">
                                    <span class="hidden sm:inline">Agregar Producto</span>
                                    <span class="sm:hidden">Agregar</span>
                                </p>
                            </Link>

                            <Link :href="route('welcome')"
                                  class="p-3 sm:p-4 bg-emerald-50 rounded-lg hover:bg-emerald-100 transition-colors duration-200 text-center border border-emerald-200">
                                <div class="text-emerald-600 mb-1 sm:mb-2">
                                    <font-awesome-icon icon="home" class="w-6 h-6 sm:w-8 sm:h-8 mx-auto" />
                                </div>
                                <p class="text-xs sm:text-sm font-medium text-emerald-700">
                                    <span class="hidden sm:inline">Vista Pública</span>
                                    <span class="sm:hidden">Público</span>
                                </p>
                            </Link>

                            <button @click="refreshData"
                                    class="p-3 sm:p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200 text-center">
                                <div class="text-gray-600 mb-1 sm:mb-2">
                                    <font-awesome-icon icon="refresh" class="w-6 h-6 sm:w-8 sm:h-8 mx-auto" />
                                </div>
                                <p class="text-xs sm:text-sm font-medium text-gray-900">Actualizar</p>
                            </button>

                            <Link :href="route('logout')" method="post" as="button"
                                  class="p-3 sm:p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200 text-center">
                                <div class="text-gray-600 mb-1 sm:mb-2">
                                    <font-awesome-icon icon="sign-out-alt" class="w-6 h-6 sm:w-8 sm:h-8 mx-auto" />
                                </div>
                                <p class="text-xs sm:text-sm font-medium text-gray-900">
                                    <span class="hidden sm:inline">Cerrar Sesión</span>
                                    <span class="sm:hidden">Salir</span>
                                </p>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({
    user: Object
})

const refreshData = () => {
    window.location.reload()
}
</script>
