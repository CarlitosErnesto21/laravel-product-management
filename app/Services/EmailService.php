<?php

namespace App\Services;

use App\Mail\WelcomeConfirmationMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EmailService
{
    /**
     * Enviar correo de bienvenida a un nuevo usuario
     */
    public function sendWelcomeEmail(User $user, array $additionalData = []): bool
    {
        try {
            Mail::to($user->email)->send(
                new WelcomeConfirmationMail($user, 'welcome', $additionalData)
            );

            Log::info("Correo de bienvenida enviado a: {$user->email}");
            return true;
        } catch (\Exception $e) {
            Log::error("Error enviando correo de bienvenida a {$user->email}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Enviar notificación de nuevo login
     */
    public function sendLoginNotification(User $user, array $additionalData = []): bool
    {
        try {
            $additionalData = array_merge($additionalData, [
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            Mail::to($user->email)->send(
                new WelcomeConfirmationMail($user, 'login', $additionalData)
            );

            Log::info("Notificación de login enviada a: {$user->email}");
            return true;
        } catch (\Exception $e) {
            Log::error("Error enviando notificación de login a {$user->email}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Enviar notificación de intento no autorizado
     */
    public function sendUnauthorizedAccessNotification(User $user, array $additionalData = []): bool
    {
        try {
            $additionalData = array_merge($additionalData, [
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'attempted_route' => request()->fullUrl(),
            ]);

            Mail::to($user->email)->send(
                new WelcomeConfirmationMail($user, 'unauthorized', $additionalData)
            );

            Log::warning("Notificación de acceso no autorizado enviada a: {$user->email}");
            return true;
        } catch (\Exception $e) {
            Log::error("Error enviando notificación de acceso no autorizado a {$user->email}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Enviar correo personalizado
     */
    public function sendCustomEmail(User $user, string $messageType, array $additionalData = []): bool
    {
        try {
            Mail::to($user->email)->send(
                new WelcomeConfirmationMail($user, $messageType, $additionalData)
            );

            Log::info("Correo personalizado ({$messageType}) enviado a: {$user->email}");
            return true;
        } catch (\Exception $e) {
            Log::error("Error enviando correo personalizado a {$user->email}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Verificar configuración de correo
     */
    public function testMailConfiguration(): bool
    {
        try {
            $config = [
                'host' => config('mail.mailers.smtp.host'),
                'port' => config('mail.mailers.smtp.port'),
                'username' => config('mail.mailers.smtp.username'),
                'encryption' => config('mail.mailers.smtp.encryption'),
                'from_address' => config('mail.from.address'),
                'from_name' => config('mail.from.name'),
            ];

            // Verificar que todos los campos requeridos estén configurados
            foreach (['host', 'port', 'username', 'from_address'] as $field) {
                if (empty($config[$field])) {
                    Log::error("Configuración de correo incompleta: {$field} no está configurado");
                    return false;
                }
            }

            Log::info("Configuración de correo verificada correctamente", $config);
            return true;
        } catch (\Exception $e) {
            Log::error("Error verificando configuración de correo: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Enviar correo de prueba
     */
    public function sendTestEmail(string $email = null): bool
    {
        try {
            $testEmail = $email ?: config('mail.from.address');

            // Crear usuario temporal para la prueba
            $testUser = new User([
                'name' => 'Usuario de Prueba',
                'email' => $testEmail,
            ]);

            Mail::to($testEmail)->send(
                new WelcomeConfirmationMail($testUser, 'welcome', [
                    'is_test' => true,
                    'test_time' => now()->format('Y-m-d H:i:s')
                ])
            );

            Log::info("Correo de prueba enviado a: {$testEmail}");
            return true;
        } catch (\Exception $e) {
            Log::error("Error enviando correo de prueba: " . $e->getMessage());
            return false;
        }
    }
}
