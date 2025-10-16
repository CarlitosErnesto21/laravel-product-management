<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\EmailService;
use Illuminate\Console\Command;

class TestEmailContentCommand extends Command
{
    protected $signature = 'test:email-content';
    protected $description = 'Probar el contenido exacto del correo de verificación';

    public function handle()
    {
        $user = User::where('email', 'ernesto.rosales354@gmail.com')->first();

        if (!$user) {
            $this->error('Usuario no encontrado');
            return;
        }

        $this->info("=== DATOS DEL USUARIO ===");
        $this->info("Email: {$user->email}");
        $this->info("Nombre: {$user->name}");

        // Probar generación de URL
        $token = base64_encode($user->email . '|' . time() . '|login');
        $url = route('auth.verify-and-redirect', ['token' => $token, 'destination' => 'dashboard']);

        $this->info("=== URL GENERADA ===");
        $this->info("Token: {$token}");
        $this->info("URL: {$url}");

        // Probar decodificación
        $decoded = base64_decode($token);
        $parts = explode('|', $decoded);
        $this->info("=== DECODIFICACIÓN ===");
        $this->info("Email: {$parts[0]}");
        $this->info("Timestamp: {$parts[1]}");
        $this->info("Message Type: {$parts[2]}");

        // Verificar configuración de app.url
        $this->info("=== CONFIGURACIÓN ===");
        $this->info("APP_URL: " . config('app.url'));
        $this->info("APP_ENV: " . config('app.env'));

        return 0;
    }
}
