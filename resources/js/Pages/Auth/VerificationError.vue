<template>
    <div class="min-h-screen bg-gradient-to-br from-red-50 to-orange-100 flex items-center justify-center p-4">
        <div class="max-w-md w-full">
            <!-- Tarjeta principal -->
            <div class="bg-white rounded-2xl shadow-xl p-8 text-center">
                <!-- Icono de error -->
                <div class="mx-auto w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>

                <!-- Título -->
                <h1 class="text-2xl font-bold text-gray-900 mb-3">
                    Enlace Inválido
                </h1>

                <!-- Mensaje principal -->
                <p class="text-gray-600 mb-6 leading-relaxed">
                    El enlace de verificación que has utilizado es inválido o ha expirado.
                </p>

                <!-- Instrucciones -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6 text-left">
                    <h3 class="font-semibold text-yellow-800 mb-2 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        ¿Qué puedes hacer?
                    </h3>
                    <ul class="text-sm text-yellow-700 space-y-1 ml-4">
                        <li>• Inicia sesión nuevamente para recibir un nuevo enlace</li>
                        <li>• Verifica que el enlace esté completo</li>
                        <li>• Los enlaces expiran después de 24 horas</li>
                    </ul>
                </div>

                <!-- Botones de acción -->
                <div class="space-y-3">
                    <Link
                        :href="route('login')"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                        </svg>
                        Iniciar Sesión
                    </Link>

                    <Link
                        href="/"
                        class="w-full bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-3 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        Volver al Inicio
                    </Link>
                </div>
            </div>

            <!-- Información adicional -->
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-500">
                    Si el problema persiste, contacta al administrador del sistema
                </p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
    error: {
        type: String,
        default: 'Enlace de verificación inválido'
    }
})
</script>
