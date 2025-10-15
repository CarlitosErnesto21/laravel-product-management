<template>
    <div class="min-h-screen bg-gray-100">
        <!-- Navegación -->
        <nav class="bg-white shadow-lg">
            <div class="max-w-7xl mx-auto px-4">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <h1 class="text-xl font-bold text-gray-900">
                            Sistema de Productos
                        </h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a
                            href="/products"
                            class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200"
                        >
                            Productos
                        </a>
                    </div>
                </div>
            </div>
        </nav>

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

<script>
export default {
    name: 'AppLayout'
}
</script>
