<template>
    <div class="min-h-screen bg-gray-100 flex items-center justify-center">
        <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8 text-center">
            <div class="mb-6">
                <div class="mx-auto w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Acceso Restringido</h1>
                <p class="text-gray-600 mb-6">
                    Lo sentimos, actualmente el acceso a esta aplicación está restringido.
                    Solo usuarios autorizados pueden ingresar al sistema de gestión de productos.
                </p>
            </div>

            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-yellow-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-sm text-yellow-800">
                        Si crees que esto es un error, contacta al administrador del sistema.
                    </span>
                </div>
            </div>

            <div class="space-y-3">
                <Link
                    href="/"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200 inline-block"
                >
                    Volver al Inicio
                </Link>

                <button
                    @click="logout"
                    class="w-full bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg transition duration-200"
                >
                    Cerrar Sesión
                </button>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-200">
                <p class="text-xs text-gray-500">
                    Sistema de Gestión de Productos v1.0
                </p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3'

const logout = () => {
    router.post('/logout')
}
</script>
