<template>
    <AppLayout>
        <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">Gestión de Productos</h1>

            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h2 class="text-xl font-semibold text-gray-800">Lista de Productos</h2>
                        <button
                            @click="showCreateForm = true"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200"
                        >
                            Agregar Producto
                        </button>
                    </div>
                </div>

                <!-- Lista de productos -->
                <div class="p-6" v-if="products.length > 0">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div
                            v-for="product in products"
                            :key="product.id"
                            class="bg-gray-50 rounded-lg p-4 hover:shadow-md transition-shadow duration-200"
                        >
                            <div v-if="product.image_url" class="mb-4">
                                <img
                                    :src="product.image_url"
                                    :alt="product.name"
                                    class="w-full h-48 object-cover rounded-lg"
                                >
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ product.name }}</h3>
                            <p class="text-gray-600 mb-2">{{ product.description }}</p>
                            <p class="text-lg font-bold text-blue-600">${{ product.price }}</p>
                            <div class="mt-4 flex space-x-2">
                                <button
                                    @click="editProduct(product)"
                                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm transition-colors duration-200"
                                >
                                    Editar
                                </button>
                                <button
                                    @click="deleteProduct(product.id)"
                                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm transition-colors duration-200"
                                >
                                    Eliminar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mensaje cuando no hay productos -->
                <div v-else class="p-6 text-center text-gray-500">
                    <p>No hay productos registrados</p>
                </div>
            </div>

            <!-- Modal para crear/editar producto -->
            <div v-if="showCreateForm" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4">
                    <h3 class="text-xl font-semibold mb-4">
                        {{ editingProduct ? 'Editar Producto' : 'Agregar Producto' }}
                    </h3>

                    <form @submit.prevent="submitForm">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nombre del Producto
                            </label>
                            <input
                                v-model="form.name"
                                type="text"
                                required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Descripción
                            </label>
                            <textarea
                                v-model="form.description"
                                rows="3"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            ></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Precio
                            </label>
                            <input
                                v-model="form.price"
                                type="number"
                                step="0.01"
                                required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Imagen del Producto
                            </label>
                            <input
                                @change="handleImageUpload"
                                type="file"
                                accept="image/*"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                            <div v-if="imagePreview" class="mt-2">
                                <img :src="imagePreview" alt="Preview" class="w-full h-32 object-cover rounded-lg">
                            </div>
                        </div>

                        <div class="flex justify-end space-x-2">
                            <button
                                type="button"
                                @click="cancelForm"
                                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors duration-200"
                            >
                                Cancelar
                            </button>
                            <button
                                type="submit"
                                :disabled="submitting"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors duration-200 disabled:opacity-50"
                            >
                                {{ submitting ? 'Guardando...' : (editingProduct ? 'Actualizar' : 'Guardar') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        </div>
    </AppLayout>
</template>

<script>
import { router } from '@inertiajs/vue3';
import AppLayout from '../../Layouts/AppLayout.vue';

export default {
    name: 'ProductIndex',
    components: {
        AppLayout
    },
    props: {
        products: {
            type: Array,
            default: () => []
        }
    },
    data() {
        return {
            showCreateForm: false,
            editingProduct: null,
            submitting: false,
            imagePreview: null,
            form: {
                name: '',
                description: '',
                price: '',
                image: null
            }
        }
    },
    methods: {
        editProduct(product) {
            this.editingProduct = product;
            this.form = {
                name: product.name,
                description: product.description,
                price: product.price,
                image: null
            };
            this.imagePreview = product.image_url;
            this.showCreateForm = true;
        },

        cancelForm() {
            this.showCreateForm = false;
            this.editingProduct = null;
            this.resetForm();
        },

        resetForm() {
            this.form = {
                name: '',
                description: '',
                price: '',
                image: null
            };
            this.imagePreview = null;
        },

        handleImageUpload(event) {
            const file = event.target.files[0];
            if (file) {
                this.form.image = file;

                // Crear preview
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.imagePreview = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        },

        async submitForm() {
            this.submitting = true;

            const formData = new FormData();
            formData.append('name', this.form.name);
            formData.append('description', this.form.description);
            formData.append('price', this.form.price);

            if (this.form.image) {
                formData.append('image', this.form.image);
            }

            try {
                if (this.editingProduct) {
                    formData.append('_method', 'PUT');
                    router.post(`/products/${this.editingProduct.id}`, formData, {
                        onSuccess: () => {
                            this.cancelForm();
                        },
                        onError: (errors) => {
                            console.error('Error actualizando producto:', errors);
                        }
                    });
                } else {
                    router.post('/products', formData, {
                        onSuccess: () => {
                            this.cancelForm();
                        },
                        onError: (errors) => {
                            console.error('Error creando producto:', errors);
                        }
                    });
                }
            } catch (error) {
                console.error('Error:', error);
            } finally {
                this.submitting = false;
            }
        },

        deleteProduct(productId) {
            if (confirm('¿Estás seguro de que quieres eliminar este producto?')) {
                router.delete(`/products/${productId}`);
            }
        }
    }
}
</script>
