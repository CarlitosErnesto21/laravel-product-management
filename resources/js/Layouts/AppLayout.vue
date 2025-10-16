<template>
    <div class="min-h-screen bg-gray-100">
        <!-- Navegación -->
        <nav class="bg-white shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <Link href="/" class="text-lg sm:text-xl font-bold text-gray-900 truncate">
                            <span class="hidden sm:inline">Sistema de Productos</span>
                            <span class="sm:hidden">Productos</span>
                        </Link>

                        <div class="hidden md:flex ml-4 lg:ml-8 space-x-2 lg:space-x-4" v-if="$page.props.auth.user && $page.props.auth.isAuthorizedUser">
                            <Link
                                :href="route('dashboard')"
                                class="text-gray-700 hover:text-blue-600 px-2 lg:px-3 py-2 rounded-md text-xs lg:text-sm font-medium transition-colors duration-200"
                                :class="{ 'text-blue-600 font-semibold': route().current('dashboard') }"
                            >
                                Dashboard
                            </Link>
                            <Link
                                :href="route('products.index')"
                                class="text-gray-700 hover:text-blue-600 px-2 lg:px-3 py-2 rounded-md text-xs lg:text-sm font-medium transition-colors duration-200"
                                :class="{ 'text-blue-600 font-semibold': route().current('products.*') }"
                            >
                                Productos
                            </Link>
                        </div>
                    </div>

                    <div class="flex items-center space-x-2 sm:space-x-4">
                        <!-- Botón hamburguesa para móvil -->
                        <div class="md:hidden" v-if="$page.props.auth.user && $page.props.auth.isAuthorizedUser">
                            <button
                                @click="showMobileMenu = !showMobileMenu"
                                class="text-gray-700 hover:text-blue-600 focus:outline-none focus:text-blue-600 p-2"
                            >
                                <font-awesome-icon :icon="showMobileMenu ? 'times' : 'bars'" class="w-5 h-5" />
                            </button>
                        </div>

                        <div v-if="!$page.props.auth.user" class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                            <Link
                                :href="route('login')"
                                class="text-gray-700 hover:text-blue-600 px-2 sm:px-3 py-1 sm:py-2 rounded-md text-xs sm:text-sm font-medium transition-colors duration-200 text-center"
                            >
                                <span class="hidden sm:inline">Iniciar Sesión</span>
                                <span class="sm:hidden">Login</span>
                            </Link>
                            <Link
                                :href="route('register')"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-2 sm:px-4 py-1 sm:py-2 rounded-md text-xs sm:text-sm font-medium transition-colors duration-200 text-center"
                            >
                                <span class="hidden sm:inline">Registrarse</span>
                                <span class="sm:hidden">Registro</span>
                            </Link>
                        </div>

                        <div v-else-if="$page.props.auth.isAuthorizedUser" class="relative hidden md:block">
                            <button
                                @click="showUserMenu = !showUserMenu"
                                class="flex items-center text-xs lg:text-sm text-gray-700 hover:text-blue-600 focus:outline-none focus:text-blue-600 transition-colors duration-200"
                            >
                                <div class="bg-blue-100 rounded-full p-1 mr-1 lg:mr-2">
                                    <font-awesome-icon icon="user" class="w-3 h-3 lg:w-4 lg:h-4 text-blue-600" />
                                </div>
                                <span class="mr-1 lg:mr-2 truncate max-w-24 lg:max-w-none">{{ $page.props.auth.user.name }}</span>
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
                                    class="flex items-center w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors duration-200"
                                >
                                    <font-awesome-icon icon="sign-out-alt" class="w-4 h-4 mr-2" />
                                    Cerrar Sesión
                                </Link>
                            </div>
                        </div>

                        <!-- Menú simplificado para usuarios no autorizados -->
                        <div v-else-if="$page.props.auth.user && !$page.props.auth.isAuthorizedUser" class="hidden md:block">
                            <Link
                                :href="route('logout')"
                                method="post"
                                as="button"
                                class="text-red-600 hover:text-red-700 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200"
                            >
                                <font-awesome-icon icon="sign-out-alt" class="w-4 h-4 mr-2" />
                                Cerrar Sesión
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Menú móvil para usuarios autorizados -->
                <div v-if="$page.props.auth.user && $page.props.auth.isAuthorizedUser" class="md:hidden" v-show="showMobileMenu">
                    <div class="px-2 pt-2 pb-3 space-y-1 border-t border-gray-200">
                        <Link
                            :href="route('dashboard')"
                            class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50 transition-colors duration-200"
                            :class="{ 'text-blue-600 bg-blue-50': route().current('dashboard') }"
                            @click="showMobileMenu = false"
                        >
                            <font-awesome-icon icon="tachometer-alt" class="w-4 h-4 mr-2" />
                            Dashboard
                        </Link>
                        <Link
                            :href="route('products.index')"
                            class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50 transition-colors duration-200"
                            :class="{ 'text-blue-600 bg-blue-50': route().current('products.*') }"
                            @click="showMobileMenu = false"
                        >
                            <font-awesome-icon icon="box" class="w-4 h-4 mr-2" />
                            Productos
                        </Link>
                        <div class="border-t border-gray-200 pt-2 mt-2">
                            <div class="flex items-center px-3 py-2">
                                <div class="bg-blue-100 rounded-full p-2 mr-3">
                                    <font-awesome-icon icon="user" class="w-4 h-4 text-blue-600" />
                                </div>
                                <div class="text-sm">
                                    <div class="font-medium text-gray-900 truncate">{{ $page.props.auth.user.name }}</div>
                                    <div class="text-gray-500 text-xs truncate">{{ $page.props.auth.user.email }}</div>
                                </div>
                            </div>
                            <Link
                                :href="route('logout')"
                                method="post"
                                as="button"
                                class="w-full text-left block px-3 py-2 rounded-md text-base font-medium text-red-600 hover:text-red-700 hover:bg-red-50 transition-colors duration-200"
                                @click="showMobileMenu = false"
                            >
                                <font-awesome-icon icon="sign-out-alt" class="w-4 h-4 mr-2" />
                                Cerrar Sesión
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Menú móvil simplificado para usuarios no autorizados -->
                <div v-else-if="$page.props.auth.user && !$page.props.auth.isAuthorizedUser" class="md:hidden">
                    <div class="px-2 pt-2 pb-3 border-t border-gray-200">
                        <div class="flex items-center justify-between px-3 py-2">
                            <div class="flex items-center">
                                <div class="bg-yellow-100 rounded-full p-2 mr-3">
                                    <font-awesome-icon icon="user" class="w-4 h-4 text-yellow-600" />
                                </div>
                                <div class="text-sm">
                                    <div class="font-medium text-gray-900 truncate">{{ $page.props.auth.user.name }}</div>
                                    <div class="text-yellow-600 text-xs truncate">Acceso Restringido</div>
                                </div>
                            </div>
                            <Link
                                :href="route('logout')"
                                method="post"
                                as="button"
                                class="text-red-600 hover:text-red-700 px-3 py-1 rounded-md text-sm font-medium transition-colors duration-200"
                            >
                                <font-awesome-icon icon="sign-out-alt" class="w-4 h-4" />
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Flash Messages -->
        <div v-if="$page.props.flash && $page.props.flash.success" class="bg-green-100 border border-green-400 text-green-700 px-3 sm:px-4 py-3 rounded mx-2 sm:mx-4 mt-4 text-sm">
            {{ $page.props.flash.success }}
        </div>

        <div v-if="$page.props.flash && $page.props.flash.error" class="bg-red-100 border border-red-400 text-red-700 px-3 sm:px-4 py-3 rounded mx-2 sm:mx-4 mt-4 text-sm">
            {{ $page.props.flash.error }}
        </div>

        <!-- Contenido principal -->
        <main class="py-4 sm:py-6 lg:py-8 px-2 sm:px-4 lg:px-0">
            <slot />
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 mt-auto">
            <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-8 py-3 sm:py-4">
                <p class="text-center text-xs sm:text-sm text-gray-600">
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
const showMobileMenu = ref(false)
</script>
