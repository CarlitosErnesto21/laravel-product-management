<template>
    <AppLayout>
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h1 class="text-3xl font-bold text-gray-900">{{ product.name }}</h1>
                        <div class="flex space-x-3">
                            <Link :href="route('products.index')"
                                  class="text-gray-600 hover:text-gray-800 font-medium">
                                ← Volver a productos
                            </Link>
                            <Link :href="route('products.edit', product.id)"
                                  class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                                Editar
                            </Link>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Imagen -->
                        <div>
                            <div class="aspect-w-1 aspect-h-1 bg-gray-200 rounded-lg overflow-hidden">
                                <img v-if="product.image_url"
                                     :src="product.image_url"
                                     :alt="product.name"
                                     class="w-full h-96 object-cover object-center">
                                <div v-else class="w-full h-96 bg-gray-200 flex items-center justify-center">
                                    <font-awesome-icon icon="image" class="w-24 h-24 text-gray-400" />
                                </div>
                            </div>
                        </div>

                        <!-- Información del producto -->
                        <div class="space-y-6">
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900 mb-4">Información del Producto</h2>

                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Nombre
                                        </label>
                                        <p class="text-lg text-gray-900">{{ product.name }}</p>
                                    </div>

                                    <div v-if="product.description">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Descripción
                                        </label>
                                        <p class="text-gray-700 leading-relaxed">{{ product.description }}</p>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Precio
                                        </label>
                                        <p class="text-3xl font-bold text-green-600">${{ product.price }}</p>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-200">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                Creado
                                            </label>
                                            <p class="text-sm text-gray-600">{{ formatDate(product.created_at) }}</p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                Actualizado
                                            </label>
                                            <p class="text-sm text-gray-600">{{ formatDate(product.updated_at) }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Acciones -->
                            <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3 pt-6 border-t border-gray-200">
                                <Link :href="route('products.edit', product.id)"
                                      class="flex-1 inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                                    <font-awesome-icon icon="pencil" class="w-4 h-4 mr-2" />
                                    Editar Producto
                                </Link>
                                <button @click="deleteProduct"
                                        class="flex-1 inline-flex items-center justify-center bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                                    <font-awesome-icon icon="trash" class="w-4 h-4 mr-2" />
                                    Eliminar Producto
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
