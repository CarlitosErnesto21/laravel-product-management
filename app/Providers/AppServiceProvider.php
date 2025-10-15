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

        // Configurar la base de datos desde DATABASE_URL si existe (Railway)
        $this->configureDatabaseFromUrl();

        // Configurar opciones cURL globales para SSL en Windows
        if (config('app.env') === 'local') {
            // Configurar Guzzle HTTP globalmente
            $this->configureHttpDefaults();
        }
    }

    /**
     * Configurar base de datos para Railway (variables ya están configuradas por Railway)
     */
    private function configureDatabaseFromUrl(): void
    {
        // Railway ya proporciona las variables individuales, no necesitamos parsear DATABASE_URL
        // Solo asegurémonos de que la conexión por defecto sea mysql en producción
        if (config('app.env') === 'production') {
            config(['database.default' => 'mysql']);
        }
    }    /**
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
