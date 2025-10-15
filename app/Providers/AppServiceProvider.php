<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;

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

        // Forzar HTTPS en producción
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        // Configurar la base de datos desde DATABASE_URL si existe (Railway)
        $this->configureDatabaseFromUrl();

        // Configurar opciones cURL globales para SSL en Windows
        if (config('app.env') === 'local') {
            // Configurar Guzzle HTTP globalmente
            $this->configureHttpDefaults();
        }
    }

    /**
     * Configurar base de datos para Railway usando las variables que Railway proporciona
     */
    private function configureDatabaseFromUrl(): void
    {
        if (config('app.env') === 'production') {
            // Railway proporciona estas variables específicas
            $mysqlUrl = env('DATABASE_URL');

            if ($mysqlUrl) {
                $parsedUrl = parse_url($mysqlUrl);

                // Configurar la conexión MySQL con los datos parseados de DATABASE_URL
                config([
                    'database.default' => 'mysql',
                    'database.connections.mysql.host' => $parsedUrl['host'] ?? env('DB_HOST'),
                    'database.connections.mysql.port' => $parsedUrl['port'] ?? env('DB_PORT', 3306),
                    'database.connections.mysql.database' => ltrim($parsedUrl['path'], '/') ?? env('DB_DATABASE'),
                    'database.connections.mysql.username' => $parsedUrl['user'] ?? env('DB_USERNAME'),
                    'database.connections.mysql.password' => $parsedUrl['pass'] ?? env('DB_PASSWORD'),
                ]);
            } else {
                // Fallback a las variables individuales
                config(['database.default' => 'mysql']);
            }
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
