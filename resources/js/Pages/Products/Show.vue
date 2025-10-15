<template>
    <AppLayout>
        <div class="max-w-4xl mx-auto px-3 sm:px-4 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-4 sm:p-6 bg-white border-b border-gray-200">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-0">
                        <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 truncate pr-4" :title="product.name">{{ product.name }}</h1>
                        <div class="flex justify-start sm:justify-end">
                            <Link :href="route('products.index')"
                                  class="text-sm sm:text-base text-gray-600 hover:text-gray-800 font-medium">
                                ← Volver a productos
                            </Link>
                        </div>
                    </div>
                </div>

                <div class="p-4 sm:p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8">
                        <!-- Imagen -->
                        <div class="order-1 lg:order-none">
                            <div class="aspect-w-1 aspect-h-1 bg-gray-200 rounded-lg overflow-hidden">
                                <img v-if="product.image_url"
                                     :src="product.image_url"
                                     :alt="product.name"
                                     class="w-full h-64 sm:h-80 lg:h-96 object-cover object-center">
                                <div v-else class="w-full h-64 sm:h-80 lg:h-96 bg-gray-200 flex items-center justify-center">
                                    <font-awesome-icon icon="image" class="w-16 h-16 sm:w-20 sm:h-20 lg:w-24 lg:h-24 text-gray-400" />
                                </div>
                            </div>
                        </div>

                        <!-- Información del producto -->
                        <div class="space-y-4 sm:space-y-6">
                            <div>
                                <h2 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-900 mb-3 sm:mb-4">Información del Producto</h2>

                                <div class="space-y-3 sm:space-y-4">
                                    <div>
                                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                                            Nombre
                                        </label>
                                        <p class="text-base sm:text-lg text-gray-900">{{ product.name }}</p>
                                    </div>

                                    <div v-if="product.description">
                                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                                            Descripción
                                        </label>
                                        <p class="text-sm sm:text-base text-gray-700 leading-relaxed">{{ product.description }}</p>
                                    </div>

                                    <div>
                                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                                            Precio
                                        </label>
                                        <p class="text-2xl sm:text-3xl font-bold text-green-600">${{ product.price }}</p>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 pt-3 sm:pt-4 border-t border-gray-200">
                                        <div>
                                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                                                Creado
                                            </label>
                                            <p class="text-xs sm:text-sm text-gray-600">{{ formatDate(product.created_at) }}</p>
                                        </div>
                                        <div>
                                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                                                Actualizado
                                            </label>
                                            <p class="text-xs sm:text-sm text-gray-600">{{ formatDate(product.updated_at) }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Acciones -->
                            <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3 pt-4 sm:pt-6 border-t border-gray-200">
                                <Link :href="route('products.edit', product.id)"
                                      class="flex-1 inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white px-3 sm:px-4 py-2 rounded-lg font-medium transition-colors duration-200 text-sm sm:text-base">
                                    <font-awesome-icon icon="pencil" class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2" />
                                    <span class="hidden sm:inline">Editar Producto</span>
                                    <span class="sm:hidden">Editar</span>
                                </Link>
                                <button @click="deleteProduct"
                                        class="flex-1 inline-flex items-center justify-center bg-red-600 hover:bg-red-700 text-white px-3 sm:px-4 py-2 rounded-lg font-medium transition-colors duration-200 text-sm sm:text-base">
                                    <font-awesome-icon icon="trash" class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2" />
                                    <span class="hidden sm:inline">Eliminar Producto</span>
                                    <span class="sm:hidden">Eliminar</span>
                                </button>
                            </div>
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
import { router } from '@inertiajs/vue3'

const props = defineProps({
    product: Object
})

const formatDate = (dateString) => {
    const date = new Date(dateString)
    return date.toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    })
}

const deleteProduct = () => {
    if (confirm(`¿Estás seguro de que quieres eliminar "${props.product.name}"?`)) {
        router.delete(route('products.destroy', props.product.id), {
            onSuccess: () => {
                router.visit(route('products.index'))
            }
        })
    }
}
</script>
