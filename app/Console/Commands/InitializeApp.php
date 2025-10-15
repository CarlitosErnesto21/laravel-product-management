<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class InitializeApp extends Command
{
    protected $signature = 'app:initialize';
    protected $description = 'Initialize the application for deployment';

    public function handle()
    {
        $this->info('🚀 Inicializando aplicación...');

        // Crear directorios necesarios
        @mkdir(storage_path('logs'), 0755, true);
        @mkdir(storage_path('framework/sessions'), 0755, true);
        @mkdir(storage_path('framework/cache'), 0755, true);
        @mkdir(storage_path('framework/views'), 0755, true);

        // Limpiar caches
        try {
            Artisan::call('config:clear');
            Artisan::call('cache:clear');
            Artisan::call('view:clear');
            Artisan::call('route:clear');
        } catch (\Exception $e) {
            $this->warn('⚠️ Error limpiando caches: ' . $e->getMessage());
        }

        // Test conexión y migraciones
        try {
            DB::connection()->getPdo();
            $this->info('✅ Base de datos conectada');

            Artisan::call('migrate', ['--force' => true]);
            $this->info('✅ Migraciones ejecutadas');
        } catch (\Exception $e) {
            $this->warn('⚠️ Sin conexión a BD: ' . $e->getMessage());
            $this->warn('⚠️ Continuando sin migraciones...');
        }

        // Storage link
        try {
            Artisan::call('storage:link');
        } catch (\Exception $e) {
            // Ignorar si ya existe
        }

        $this->info('✅ Inicialización completada');
        return 0;
    }
}
