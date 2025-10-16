<template>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center p-4">
        <div class="max-w-md w-full">
            <!-- Tarjeta principal -->
            <div class="bg-white rounded-2xl shadow-xl p-8 text-center">
                <!-- Icono animado -->
                <div class="mx-auto w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mb-6 animate-pulse">
                    <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>

                <!-- Título -->
                <h1 class="text-2xl font-bold text-gray-900 mb-3">
                    ¡Verificación Enviada!
                </h1>

                <!-- Mensaje principal -->
                <p class="text-gray-600 mb-6 leading-relaxed">
                    Hemos enviado un correo de verificación a:
                </p>

                <!-- Email destacado -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <p class="font-semibold text-gray-800 break-all">
                        {{ user.email }}
                    </p>
                </div>

                <!-- Instrucciones -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6 text-left">
                    <h3 class="font-semibold text-blue-800 mb-2 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Próximos pasos:
                    </h3>
                    <ol class="text-sm text-blue-700 space-y-1">
                        <li>1. Revisa tu bandeja de entrada</li>
                        <li>2. Busca el correo de "{{ appName }}"</li>
                        <li>3. Haz clic en el enlace de verificación</li>
                        <li>4. Serás redirigido automáticamente</li>
                    </ol>
                </div>

                <!-- Indicador de tipo de usuario -->
                <div v-if="isAuthorized" class="bg-green-50 border border-green-200 rounded-lg p-3 mb-6">
                    <p class="text-sm text-green-700">
                        ✅ <strong>Usuario autorizado:</strong> Serás redirigido al Dashboard
                    </p>
                </div>

                <div v-else class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mb-6">
                    <p class="text-sm text-yellow-700">
                        ℹ️ <strong>Acceso limitado:</strong> Serás redirigido a la página principal
                    </p>
                </div>

                <!-- Botones de acción -->
                <div class="space-y-3">
                    <button
                        @click="checkEmail"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Ya revisé mi correo
                    </button>

                    <button
                        @click="resendEmail"
                        :disabled="resendCooldown > 0"
                        class="w-full bg-gray-200 hover:bg-gray-300 disabled:bg-gray-100 disabled:text-gray-400 text-gray-700 font-medium py-3 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                        <span v-if="resendCooldown > 0">
                            Reenviar en {{ resendCooldown }}s
                        </span>
                        <span v-else>
                            Reenviar correo
                        </span>
                    </button>

                    <!-- Botón de acceso directo para casos de emergencia -->
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <p class="text-sm text-gray-600 mb-3">
                            ¿No recibes el correo después de varios minutos?
                        </p>
                        <button
                            @click="directAccess"
                            class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-3 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center"
                        >
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            🚀 Acceso Directo al Sistema
                        </button>
                        <p class="text-xs text-gray-500 mt-2">
                            Bypass para problemas de correo electrónico
                        </p>
                    </div>
                </div>

                <!-- Enlaces adicionales -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="flex justify-center space-x-4 text-sm">
                        <Link
                            href="/"
                            class="text-gray-500 hover:text-gray-700 transition-colors duration-200"
                        >
                            Volver al inicio
                        </Link>
                        <Link
                            :href="route('logout')"
                            method="post"
                            as="button"
                            class="text-red-500 hover:text-red-700 transition-colors duration-200"
                        >
                            Cerrar sesión
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Información adicional -->
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-500">
                    ¿No recibiste el correo? Revisa tu carpeta de spam o promociones
                </p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { Link, router } from '@inertiajs/vue3'

// Props
const props = defineProps({
    user: {
        type: Object,
        required: true
    },
    isAuthorized: {
        type: Boolean,
        default: false
    },
    appName: {
        type: String,
        default: 'Sistema de Productos'
    },
    verificationType: {
        type: String,
        default: 'login' // 'login' o 'register'
    }
})

// Estado reactivo
const resendCooldown = ref(0)
const isLoading = ref(false)

// Inicializar cooldown
onMounted(() => {
    startCooldown(60) // 60 segundos de cooldown inicial
})

// Funciones
const startCooldown = (seconds) => {
    resendCooldown.value = seconds
    const interval = setInterval(() => {
        resendCooldown.value--
        if (resendCooldown.value <= 0) {
            clearInterval(interval)
        }
    }, 1000)
}

const checkEmail = () => {
    // Simular verificación exitosa - en producción esto sería automático
    window.open('https://mail.google.com', '_blank')
}

const resendEmail = async () => {
    if (resendCooldown.value > 0) return

    isLoading.value = true

    try {
        await router.post(route('auth.resend-verification'), {}, {
            preserveState: true,
            preserveScroll: true,
            onSuccess: () => {
                startCooldown(60)
                // Mostrar notificación de éxito
            },
            onError: (errors) => {
                console.error('Error reenviando correo:', errors)
            }
        })
    } catch (error) {
        console.error('Error:', error)
    } finally {
        isLoading.value = false
    }
}

const directAccess = async () => {
    isLoading.value = true

    try {
        // Redirigir directamente según autorización
        if (props.isAuthorized) {
            await router.post(route('auth.direct-access'), {
                destination: 'dashboard'
            })
        } else {
            await router.post(route('auth.direct-access'), {
                destination: 'welcome'
            })
        }
    } catch (error) {
        console.error('Error en acceso directo:', error)
    } finally {
        isLoading.value = false
    }
}
</script>

<style scoped>
@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: .5;
    }
}

.animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}
</style>
