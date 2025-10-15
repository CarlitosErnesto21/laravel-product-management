<template>
    <AppLayout>
        <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">Gestión de Productos</h1>

            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h2 class="text-xl font-semibold text-gray-800">Lista de Productos</h2>
                        <Link
                            :href="route('products.create')"
                            class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200"
                        >
                            <font-awesome-icon icon="plus" class="w-4 h-4 mr-2" />
                            Nuevo Producto
                        </Link>
                    </div>
                </div>

                <!-- Lista de productos -->
                <div class="p-6" v-if="products.length > 0">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div
                            v-for="product in products"
                            :key="product.id"
                            class="bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden hover:shadow-xl transition-shadow duration-300"
                        >
                            <div class="aspect-w-1 aspect-h-1">
                                <img v-if="product.image_url"
                                     :src="product.image_url"
                                     :alt="product.name"
                                     class="w-full h-48 object-cover">
                                <div v-else class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                    <font-awesome-icon icon="image" class="w-16 h-16 text-gray-400" />
                                </div>
                            </div>
                            <div class="p-4">
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ product.name }}</h3>
                                <p class="text-gray-600 text-sm mb-3 line-clamp-2" v-if="product.description">{{ product.description }}</p>
                                <div class="flex items-center justify-between mb-4">
                                    <span class="text-2xl font-bold text-green-600">${{ product.price }}</span>
                                </div>
                                <div class="flex space-x-2">
                                    <Link
                                        :href="route('products.edit', product.id)"
                                        class="flex-1 inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200"
                                    >
                                        <font-awesome-icon icon="pencil" class="w-3 h-3 mr-1" />
                                        Editar
                                    </Link>
                                    <button
                                        @click="deleteProduct(product.id)"
                                        class="flex-1 inline-flex items-center justify-center bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200"
                                    >
                                        <font-awesome-icon icon="trash" class="w-3 h-3 mr-1" />
                                        Eliminar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mensaje cuando no hay productos -->
                <div v-else class="p-12 text-center">
                    <div class="text-gray-400 mb-4">
                        <font-awesome-icon icon="box" class="w-16 h-16 mx-auto" />
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No hay productos registrados</h3>
                    <p class="text-gray-500 mb-6">Comienza agregando tu primer producto al inventario</p>
                    <Link
                        :href="route('products.create')"
                        class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200"
                    >
                        <font-awesome-icon icon="plus" class="w-4 h-4 mr-2" />
                        Crear Primer Producto
                    </Link>
                </div>
            </div>


        </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { router } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    products: {
        type: Array,
        default: () => []
    }
});

const deleteProduct = (productId) => {
    if (confirm('¿Estás seguro de que quieres eliminar este producto?')) {
        router.delete(`/products/${productId}`);
    }
}
</script>
