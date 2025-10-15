<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

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
