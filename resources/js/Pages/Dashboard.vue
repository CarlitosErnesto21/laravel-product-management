<template>
    <AppLayout>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
                            <p class="text-gray-600 mt-2">
                                Bienvenido de nuevo, <span class="font-semibold">{{ user.name }}</span>
                            </p>
                        </div>
                        <div class="text-sm text-gray-500">
                            {{ new Date().toLocaleDateString('es-ES', {
                                weekday: 'long',
                                year: 'numeric',
                                month: 'long',
                                day: 'numeric'
                            }) }}
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Tarjeta de Productos -->
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-6 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold">Productos</h3>
                                    <p class="text-blue-100 mt-1">Gestiona tu inventario</p>
                                </div>
                                <div class="bg-white bg-opacity-20 rounded-full p-3">
                                    <font-awesome-icon icon="box" class="w-6 h-6" style="color: blue !important;" />
                                </div>
                            </div>
                            <div class="mt-4">
                                <Link :href="route('products.index')"
                                      class="inline-flex items-center px-4 py-2 bg-blue-700 bg-opacity-20 rounded-lg hover:bg-opacity-30 transition-colors duration-200">
                                    Ver Productos
                                    <font-awesome-icon icon="arrow-right" class="ml-2 w-4 h-4" />
                                </Link>
                            </div>
                        </div>

                        <!-- Tarjeta de Agregar Producto -->
                        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-6 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold">Nuevo Producto</h3>
                                    <p class="text-green-100 mt-1">Añade productos nuevos</p>
                                </div>
                                <div class="bg-white bg-opacity-20 rounded-full p-3">
                                    <font-awesome-icon icon="plus" class="w-6 h-6" style="color: green !important;" />
                                </div>
                            </div>
                            <div class="mt-4">
                                <Link :href="route('products.create')"
                                      class="inline-flex items-center px-4 py-2 bg-green-700 bg-opacity-20 rounded-lg hover:bg-opacity-30 transition-colors duration-200">
                                    Crear Producto
                                    <font-awesome-icon icon="arrow-right" class="ml-2 w-4 h-4" />
                                </Link>
                            </div>
                        </div>

                        <!-- Tarjeta de Perfil -->
                        <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg p-6 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold">Mi Perfil</h3>
                                    <p class="text-purple-100 mt-1">Información de cuenta</p>
                                </div>
                                <div class="bg-white bg-opacity-20 rounded-full p-3">
                                    <font-awesome-icon icon="user" class="w-6 h-6" style="color: purple !important;" />
                                </div>
                            </div>
                            <div class="mt-4">
                                <div class="text-sm">
                                    <p><span class="font-semibold">Email:</span> {{ user.email }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Acciones rápidas -->
                    <div class="mt-8">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Acciones Rápidas</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            <Link :href="route('products.index')"
                                  class="p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200 text-center">
                                <div class="text-gray-600 mb-2">
                                    <font-awesome-icon icon="eye" class="w-8 h-8 mx-auto" />
                                </div>
                                <p class="text-sm font-medium text-gray-900">Ver Todos los Productos</p>
                            </Link>

                            <Link :href="route('products.create')"
                                  class="p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200 text-center">
                                <div class="text-gray-600 mb-2">
                                    <font-awesome-icon icon="plus" class="w-8 h-8 mx-auto" />
                                </div>
                                <p class="text-sm font-medium text-gray-900">Agregar Producto</p>
                            </Link>

                            <button @click="refreshData"
                                    class="p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200 text-center">
                                <div class="text-gray-600 mb-2">
                                    <font-awesome-icon icon="refresh" class="w-8 h-8 mx-auto" />
                                </div>
                                <p class="text-sm font-medium text-gray-900">Actualizar</p>
                            </button>

                            <Link :href="route('logout')" method="post" as="button"
                                  class="p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200 text-center">
                                <div class="text-gray-600 mb-2">
                                    <font-awesome-icon icon="sign-out-alt" class="w-8 h-8 mx-auto" />
                                </div>
                                <p class="text-sm font-medium text-gray-900">Cerrar Sesión</p>
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
