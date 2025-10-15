<template>
    <AppLayout>
        <div class="max-w-md mx-3 sm:mx-auto bg-white rounded-lg shadow-md p-4 sm:p-6">
            <div class="text-center mb-4 sm:mb-6">
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Iniciar Sesión</h2>
                <p class="text-sm sm:text-base text-gray-600 mt-1 sm:mt-2">Ingresa a tu cuenta</p>
            </div>

            <form @submit.prevent="submit">
                <div class="mb-3 sm:mb-4">
                    <label for="email" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">
                        Correo Electrónico
                    </label>
                    <input
                        id="email"
                        v-model="form.email"
                        type="email"
                        class="w-full px-3 py-2 sm:py-2.5 text-sm sm:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        :class="{ 'border-red-500': form.errors.email }"
                        required
                        autofocus
                    />
                    <div v-if="form.errors.email" class="text-red-500 text-xs sm:text-sm mt-1">
                        {{ form.errors.email }}
                    </div>
                </div>

                <div class="mb-3 sm:mb-4">
                    <label for="password" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">
                        Contraseña
                    </label>
                    <input
                        id="password"
                        v-model="form.password"
                        type="password"
                        class="w-full px-3 py-2 sm:py-2.5 text-sm sm:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        :class="{ 'border-red-500': form.errors.password }"
                        required
                    />
                    <div v-if="form.errors.password" class="text-red-500 text-xs sm:text-sm mt-1">
                        {{ form.errors.password }}
                    </div>
                </div>

                <div class="mb-4 sm:mb-6">
                    <label class="flex items-center">
                        <input
                            v-model="form.remember"
                            type="checkbox"
                            class="h-3 w-3 sm:h-4 sm:w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                        />
                        <span class="ml-2 text-xs sm:text-sm text-gray-700">Recordarme</span>
                    </label>
                </div>

                <button
                    type="submit"
                    :disabled="form.processing"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 sm:py-2.5 px-4 text-sm sm:text-base rounded-md transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <span v-if="form.processing">Iniciando sesión...</span>
                    <span v-else>Iniciar Sesión</span>
                </button>
            </form>

            <div class="mt-4 sm:mt-6 text-center">
                <p class="text-xs sm:text-sm text-gray-600">
                    ¿No tienes cuenta?
                    <Link :href="route('register')" class="text-blue-600 hover:text-blue-500 font-medium">
                        Regístrate aquí
                    </Link>
                </p>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const form = useForm({
    email: '',
    password: '',
    remember: false
})

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    })
}
</script>
