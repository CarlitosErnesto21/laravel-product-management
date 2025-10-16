<?php

namespace App\Console\Commands;

use App\Services\EmailService;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendTestEmail extends Command
{
    protected $signature = 'test:send-email {email}';
    protected $description = 'Enviar correo de prueba directo';

    public function handle()
    {
        $email = $this->argument('email');

        // Buscar o crear usuario
        $user = User::firstOrCreate(
            ['email' => $email],
            ['name' => 'Usuario de Prueba', 'password' => bcrypt('password')]
        );

        $this->info("🔄 Enviando correo a: {$email}");

        // Usar EmailService para enviar
        $emailService = new EmailService();
        $result = $emailService->sendLoginNotification($user);

        if ($result) {
            $this->info("✅ Correo enviado exitosamente");
        } else {
            $this->error("❌ Error enviando correo");
        }

        // Mostrar últimos logs
        $this->info("\n📋 Últimos logs:");
        $logPath = storage_path('logs/laravel.log');
        if (file_exists($logPath)) {
            $logs = file($logPath);
            $lastLogs = array_slice($logs, -3);
            foreach ($lastLogs as $log) {
                $this->line(trim($log));
            }
        }
    }
}
