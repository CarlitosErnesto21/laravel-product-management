<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class InitializeApp extends Command
{
    protected $signature = 'app:initialize';
    protected $description = 'Initialize the application for Railway deployment';

    public function handle()
    {
        $this->info('🚀 Inicializando aplicación...');

        // Limpiar caches
        $this->info('🧹 Limpiando caches...');
        try {
            Artisan::call('config:clear');
            Artisan::call('cache:clear');
            Artisan::call('view:clear');
            Artisan::call('route:clear');
            $this->info('✅ Caches limpiados');
        } catch (\Exception $e) {
            $this->warn('⚠️ Error limpiando caches: ' . $e->getMessage());
        }

        // Debug configuración de base de datos
        $this->info('🔍 Verificando configuración de base de datos...');
        $this->line('DB_CONNECTION: ' . env('DB_CONNECTION', 'not set'));
        $this->line('DB_HOST: ' . env('DB_HOST', 'not set'));
        $this->line('DB_PORT: ' . env('DB_PORT', 'not set'));
        $this->line('DB_DATABASE: ' . env('DB_DATABASE', 'not set'));
        $this->line('DB_USERNAME: ' . env('DB_USERNAME', 'not set'));
        $this->line('Default connection: ' . config('database.default'));

        // Ejecutar migraciones
        $this->info('🗃️ Ejecutando migraciones...');
        try {
            Artisan::call('migrate', ['--force' => true]);
            $this->info('✅ Migraciones ejecutadas exitosamente');
            $this->line(Artisan::output());
        } catch (\Exception $e) {
            $this->error('❌ Error ejecutando migraciones: ' . $e->getMessage());
            return 1;
        }

        // Crear storage link
        $this->info('🔗 Creando enlace de storage...');
        try {
            Artisan::call('storage:link');
            $this->info('✅ Enlace de storage creado');
        } catch (\Exception $e) {
            $this->warn('⚠️ Storage link ya existe o error: ' . $e->getMessage());
        }

        // Cachear configuraciones
        $this->info('⚡ Cacheando configuraciones...');
        try {
            Artisan::call('config:cache');
            Artisan::call('route:cache');
            Artisan::call('view:cache');
            $this->info('✅ Configuraciones cacheadas');
        } catch (\Exception $e) {
            $this->warn('⚠️ Error cacheando: ' . $e->getMessage());
        }

        // Verificar estado de migraciones
        $this->info('🔍 Verificando estado de migraciones...');
        try {
            Artisan::call('migrate:status');
            $this->line(Artisan::output());
        } catch (\Exception $e) {
            $this->warn('⚠️ No se pudo verificar migraciones: ' . $e->getMessage());
        }

        $this->info('✅ Aplicación inicializada correctamente!');
        return 0;
    }
}
