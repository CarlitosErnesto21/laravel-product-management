<template>
    <AppLayout>
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h1 class="text-3xl font-bold text-gray-900">Editar Producto</h1>
                        <div class="flex space-x-3">
                            <Link :href="route('products.show', product.id)"
                                  class="text-gray-600 hover:text-gray-800 font-medium">
                                ← Ver producto
                            </Link>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <form @submit.prevent="submit" enctype="multipart/form-data">
                        <div class="mb-6">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Nombre del Producto *
                            </label>
                            <input
                                id="name"
                                v-model="form.name"
                                type="text"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                :class="{ 'border-red-500': form.errors.name }"
                                required
                            />
                            <div v-if="form.errors.name" class="text-red-500 text-sm mt-1">
                                {{ form.errors.name }}
                            </div>
                        </div>

                        <div class="mb-6">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Descripción
                            </label>
                            <textarea
                                id="description"
                                v-model="form.description"
                                rows="4"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                :class="{ 'border-red-500': form.errors.description }"
                            ></textarea>
                            <div v-if="form.errors.description" class="text-red-500 text-sm mt-1">
                                {{ form.errors.description }}
                            </div>
                        </div>

                        <div class="mb-6">
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                                Precio *
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-gray-500">$</span>
                                <input
                                    id="price"
                                    v-model="form.price"
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    :class="{ 'border-red-500': form.errors.price }"
                                    required
                                />
                            </div>
                            <div v-if="form.errors.price" class="text-red-500 text-sm mt-1">
                                {{ form.errors.price }}
                            </div>
                        </div>

                        <!-- Imagen actual -->
                        <div v-if="product.image_url" class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Imagen Actual
                            </label>
                            <img :src="product.image_url" :alt="product.name" class="w-32 h-32 object-cover rounded-lg border border-gray-300">
                        </div>

                        <div class="mb-6">
                            <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ product.image_url ? 'Cambiar Imagen' : 'Imagen del Producto' }}
                            </label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors duration-200">
                                <div class="space-y-1 text-center">
                                    <font-awesome-icon icon="image" class="mx-auto h-12 w-12 text-gray-400" />
                                    <div class="flex text-sm text-gray-600">
                                        <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                            <span>Subir archivo</span>
                                            <input
                                                id="image"
                                                name="image"
                                                type="file"
                                                accept="image/*"
                                                @change="handleFileChange"
                                                class="sr-only"
                                            />
                                        </label>
                                        <p class="pl-1">o arrastra y suelta</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, JPEG hasta 2MB</p>
                                </div>
                            </div>
                            <div v-if="form.errors.image" class="text-red-500 text-sm mt-1">
                                {{ form.errors.image }}
                            </div>
                            <div v-if="imagePreview" class="mt-4">
                                <p class="text-sm text-gray-700 mb-2">Nueva imagen:</p>
                                <img :src="imagePreview" alt="Preview" class="w-32 h-32 object-cover rounded-lg border border-gray-300">
                            </div>
                        </div>

                        <div class="flex items-center justify-end space-x-4">
                            <Link :href="route('products.show', product.id)"
                                  class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                                Cancelar
                            </Link>
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="inline-flex items-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                <font-awesome-icon v-if="form.processing" icon="refresh" class="w-4 h-4 mr-2 animate-spin" />
                                <font-awesome-icon v-else icon="check" class="w-4 h-4 mr-2" />
                                <span v-if="form.processing">Guardando...</span>
                                <span v-else>Actualizar Producto</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import { ref } from 'vue'

const props = defineProps({
    product: Object
})

const form = useForm({
    name: props.product.name,
    description: props.product.description || '',
    price: props.product.price,
    image: null,
    _method: 'PUT'
})

const imagePreview = ref(null)

const handleFileChange = (event) => {
    const file = event.target.files[0]
    form.image = file

    if (file) {
        const reader = new FileReader()
        reader.onload = (e) => {
            imagePreview.value = e.target.result
        }
        reader.readAsDataURL(file)
    } else {
        imagePreview.value = null
    }
}

const submit = () => {
    form.post(route('products.update', props.product.id))
}
</script>
