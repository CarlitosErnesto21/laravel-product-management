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

                <div v-else-if="$page.props.auth.isAuthorizedUser" class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center max-w-md sm:max-w-none mx-auto px-4 sm:px-0">
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

                <div v-else-if="$page.props.auth.user && !$page.props.auth.isAuthorizedUser" class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center max-w-md sm:max-w-none mx-auto px-4 sm:px-0">
                    <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-6 py-3 rounded-lg text-center">
                        <p class="font-medium">Acceso Restringido</p>
                        <p class="text-sm mt-1">¡Ups! No tienes permiso para acceder a esta sección.</p>
                    </div>
                </div>
            </div>

            <!-- Productos Section -->
            <div class="py-8 sm:py-12 lg:py-16">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-8 sm:mb-12">
                        <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-900 mb-3 sm:mb-4">
                            Nuestros Productos
                        </h2>
                        <p class="text-sm sm:text-base md:text-lg text-gray-600 max-w-2xl mx-auto">
                            Descubre nuestra selección de productos gestionados con el sistema más avanzado
                        </p>
                    </div>

                    <div v-if="products && products.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
                        <div
                            v-for="product in products"
                            :key="product.id"
                            class="bg-white rounded-lg shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden group"
                        >
                            <!-- Imagen del producto -->
                            <div class="relative aspect-square bg-gray-100 overflow-hidden">
                                <img
                                    v-if="product.image_url"
                                    :src="product.image_url"
                                    :alt="product.name"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                    loading="lazy"
                                />
                                <div v-else class="w-full h-full flex items-center justify-center">
                                    <font-awesome-icon icon="image" class="w-12 h-12 text-gray-400" />
                                </div>
                                <!-- Badge de precio -->
                                <div class="absolute top-2 right-2 bg-blue-600 text-white px-2 py-1 rounded-md text-sm font-semibold">
                                    ${{ Number(product.price).toFixed(2) }}
                                </div>
                            </div>

                            <!-- Contenido del producto -->
                            <div class="p-4">
                                <h3 class="font-semibold text-gray-900 mb-2 text-sm sm:text-base line-clamp-1">
                                    {{ product.name }}
                                </h3>
                                <p class="text-gray-600 text-xs sm:text-sm line-clamp-2 mb-3">
                                    {{ product.description }}
                                </p>
                                <div class="flex items-center justify-between">
                                    <span class="text-lg font-bold text-blue-600">
                                        ${{ Number(product.price).toFixed(2) }}
                                    </span>
                                    <div class="flex items-center text-xs text-gray-500">
                                        <font-awesome-icon icon="eye" class="w-3 h-3 mr-1" />
                                        Ver detalles
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Mensaje cuando no hay productos -->
                    <div v-if="!products || products.length === 0" class="text-center py-12">
                        <div class="bg-gray-100 rounded-full p-4 w-20 h-20 mx-auto mb-6 flex items-center justify-center">
                            <font-awesome-icon icon="box" class="w-10 h-10 text-gray-400" />
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">No hay productos disponibles</h3>
                        <p class="text-gray-600 mb-6 max-w-md mx-auto">
                            Los productos se mostrarán aquí una vez que sean agregados al sistema.
                            ¡Pronto tendremos productos increíbles para ti!
                        </p>

                        <div v-if="$page.props.auth.isAuthorizedUser" class="mt-6">
                            <Link
                                :href="route('products.create')"
                                class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-lg hover:shadow-xl"
                            >
                                <font-awesome-icon icon="plus" class="w-5 h-5 mr-2" />
                                Agregar Primer Producto
                            </Link>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Features Section -->
            <div class="py-6 sm:py-8 lg:py-12 bg-gray-50 -mx-3 sm:-mx-6 lg:-mx-8 rounded-lg">
                <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                    <h2 class="text-lg sm:text-xl md:text-2xl font-bold text-center text-gray-900 mb-6 sm:mb-8">
                        Características Principales
                    </h2>

                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4 lg:gap-6">
                        <!-- Feature 1 -->
                        <div class="text-center p-3 sm:p-4 hover:bg-white hover:shadow-md rounded-lg transition-all duration-300">
                            <div class="bg-blue-100 rounded-full p-2 w-10 h-10 sm:w-12 sm:h-12 mx-auto mb-2 sm:mb-3 flex items-center justify-center">
                                <font-awesome-icon icon="box" class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600" />
                            </div>
                            <h3 class="text-xs sm:text-sm font-semibold text-gray-900 mb-1 sm:mb-2">Gestión</h3>
                            <p class="text-xs text-gray-600 leading-tight">CRUD completo de productos</p>
                        </div>

                        <!-- Feature 2 -->
                        <div class="text-center p-3 sm:p-4 hover:bg-white hover:shadow-md rounded-lg transition-all duration-300">
                            <div class="bg-green-100 rounded-full p-2 w-10 h-10 sm:w-12 sm:h-12 mx-auto mb-2 sm:mb-3 flex items-center justify-center">
                                <font-awesome-icon icon="image" class="w-4 h-4 sm:w-5 sm:h-5 text-green-600" />
                            </div>
                            <h3 class="text-xs sm:text-sm font-semibold text-gray-900 mb-1 sm:mb-2">Imágenes</h3>
                            <p class="text-xs text-gray-600 leading-tight">Cloudinary integrado</p>
                        </div>

                        <!-- Feature 3 -->
                        <div class="text-center p-3 sm:p-4 hover:bg-white hover:shadow-md rounded-lg transition-all duration-300">
                            <div class="bg-purple-100 rounded-full p-2 w-10 h-10 sm:w-12 sm:h-12 mx-auto mb-2 sm:mb-3 flex items-center justify-center">
                                <font-awesome-icon icon="user" class="w-4 h-4 sm:w-5 sm:h-5 text-purple-600" />
                            </div>
                            <h3 class="text-xs sm:text-sm font-semibold text-gray-900 mb-1 sm:mb-2">Seguridad</h3>
                            <p class="text-xs text-gray-600 leading-tight">Autenticación robusta</p>
                        </div>

                        <!-- Feature 4 -->
                        <div class="text-center p-3 sm:p-4 hover:bg-white hover:shadow-md rounded-lg transition-all duration-300">
                            <div class="bg-yellow-100 rounded-full p-2 w-10 h-10 sm:w-12 sm:h-12 mx-auto mb-2 sm:mb-3 flex items-center justify-center">
                                <font-awesome-icon icon="tachometer-alt" class="w-4 h-4 sm:w-5 sm:h-5 text-yellow-600" />
                            </div>
                            <h3 class="text-xs sm:text-sm font-semibold text-gray-900 mb-1 sm:mb-2">Moderno</h3>
                            <p class="text-xs text-gray-600 leading-tight">Vue.js + Inertia.js</p>
                        </div>

                        <!-- Feature 5 -->
                        <div class="text-center p-3 sm:p-4 hover:bg-white hover:shadow-md rounded-lg transition-all duration-300">
                            <div class="bg-red-100 rounded-full p-2 w-10 h-10 sm:w-12 sm:h-12 mx-auto mb-2 sm:mb-3 flex items-center justify-center">
                                <font-awesome-icon icon="home" class="w-4 h-4 sm:w-5 sm:h-5 text-red-600" />
                            </div>
                            <h3 class="text-xs sm:text-sm font-semibold text-gray-900 mb-1 sm:mb-2">Producción</h3>
                            <p class="text-xs text-gray-600 leading-tight">Railway optimizado</p>
                        </div>

                        <!-- Feature 6 -->
                        <div class="text-center p-3 sm:p-4 hover:bg-white hover:shadow-md rounded-lg transition-all duration-300">
                            <div class="bg-indigo-100 rounded-full p-2 w-10 h-10 sm:w-12 sm:h-12 mx-auto mb-2 sm:mb-3 flex items-center justify-center">
                                <font-awesome-icon icon="check" class="w-4 h-4 sm:w-5 sm:h-5 text-indigo-600" />
                            </div>
                            <h3 class="text-xs sm:text-sm font-semibold text-gray-900 mb-1 sm:mb-2">Intuitivo</h3>
                            <p class="text-xs text-gray-600 leading-tight">Fácil navegación</p>
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

// Props
defineProps({
    products: {
        type: Array,
        default: () => []
    }
})
</script>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.aspect-square {
    aspect-ratio: 1 / 1;
}
</style>
