<template>
    <div class="min-h-screen bg-gray-100">
        <!-- Navegación -->
        <nav class="bg-white shadow-lg">
            <div class="max-w-7xl mx-auto px-4">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <Link href="/" class="text-xl font-bold text-gray-900">
                            Sistema de Productos
                        </Link>

                        <div class="hidden md:flex ml-8 space-x-4" v-if="$page.props.auth.user">
                            <Link
                                :href="route('dashboard')"
                                class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200"
                                :class="{ 'text-blue-600 font-semibold': route().current('dashboard') }"
                            >
                                Dashboard
                            </Link>
                            <Link
                                :href="route('products.index')"
                                class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200"
                                :class="{ 'text-blue-600 font-semibold': route().current('products.*') }"
                            >
                                Productos
                            </Link>
                        </div>
                    </div>

                    <div class="flex items-center space-x-4">
                        <div v-if="!$page.props.auth.user" class="flex space-x-2">
                            <Link
                                :href="route('login')"
                                class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200"
                            >
                                Iniciar Sesión
                            </Link>
                            <Link
                                :href="route('register')"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200"
                            >
                                Registrarse
                            </Link>
                        </div>

                        <div v-else class="relative">
                            <button
                                @click="showUserMenu = !showUserMenu"
                                class="flex items-center text-sm text-gray-700 hover:text-blue-600 focus:outline-none focus:text-blue-600 transition-colors duration-200"
                            >
                                <div class="bg-blue-100 rounded-full p-1 mr-2">
                                    <font-awesome-icon icon="user" class="w-4 h-4 text-blue-600" />
                                </div>
                                <span class="mr-2">{{ $page.props.auth.user.name }}</span>
                                <font-awesome-icon icon="chevron-down" class="w-3 h-3" />
                            </button>

                            <div
                                v-show="showUserMenu"
                                class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 border border-gray-200"
                                @click.away="showUserMenu = false"
                            >
                                <Link
                                    :href="route('dashboard')"
                                    class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200"
                                >
                                    <font-awesome-icon icon="tachometer-alt" class="w-4 h-4 mr-2" />
                                    Dashboard
                                </Link>
                                <Link
                                    :href="route('logout')"
                                    method="post"
                                    as="button"
                                    class="flex items-center w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200"
                                >
                                    <font-awesome-icon icon="sign-out-alt" class="w-4 h-4 mr-2" />
                                    Cerrar Sesión
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Flash Messages -->
        <div v-if="$page.props.flash && $page.props.flash.success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mx-4 mt-4">
            {{ $page.props.flash.success }}
        </div>

        <div v-if="$page.props.flash && $page.props.flash.error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mx-4 mt-4">
            {{ $page.props.flash.error }}
        </div>

        <!-- Contenido principal -->
        <main class="py-8">
            <slot />
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 mt-auto">
            <div class="max-w-7xl mx-auto px-4 py-4">
                <p class="text-center text-sm text-gray-600">
                    © 2025 Sistema de Productos con Laravel, Vue.js e Inertia.js
                </p>
            </div>
        </footer>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'

const showUserMenu = ref(false)
</script>
