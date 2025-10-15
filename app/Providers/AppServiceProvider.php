<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        // Configurar opciones cURL globales para SSL en Windows
        if (config('app.env') === 'local') {
            // Configurar Guzzle HTTP globalmente
            $this->configureHttpDefaults();
        }
    }

    /**
     * Configurar opciones HTTP por defecto para desarrollo local
     */
    private function configureHttpDefaults(): void
    {
        // Configurar opciones por defecto para cURL en entorno local
        if (function_exists('curl_setopt_array')) {
            ini_set('curl.cainfo', '');
            ini_set('openssl.cafile', '');
        }

        // Configurar client HTTP por defecto
        config([
            'http.verify' => false,
            'http.timeout' => 60,
        ]);
    }
}
