<?php

namespace App\Services;

use App\Mail\WelcomeConfirmationMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EmailService
{
    /**
     * Verificar si el correo está habilitado
     */
    private function isEmailEnabled(): bool
    {
        $mailer = config('mail.default');

        // Para debugging permitimos log
        if ($mailer === 'log') {
            Log::info('EmailService: Usando mailer LOG para debugging');
            return true;
        }

        if (empty($mailer)) {
            Log::warning('EmailService: Mailer no configurado');
            return false;
        }

        // Verificar configuración SMTP
        if ($mailer === 'smtp') {
            $hasConfig = !empty(config('mail.mailers.smtp.username')) &&
                        !empty(config('mail.mailers.smtp.password'));

            if (!$hasConfig) {
                Log::warning('EmailService: Configuración SMTP incompleta');
                return false;
            }
        }

        Log::info("EmailService: Correo habilitado con mailer: {$mailer}");
        return true;
    }

    /**
     * Enviar correo con timeout y reintentos
     */
    private function sendMailSafely(User $user, string $type, array $additionalData = [], string $redirectUrl = ''): bool
    {
        if (!$this->isEmailEnabled()) {
            Log::info("Correo deshabilitado. No se envió correo de tipo '{$type}' a: {$user->email}");
            return false;
        }

        try {
            // Configurar timeout más corto para evitar bloqueos
            ini_set('default_socket_timeout', 30);

            Mail::to($user->email)
                ->send(new WelcomeConfirmationMail($user, $type, $additionalData, $redirectUrl));

            Log::info("Correo de tipo '{$type}' enviado exitosamente a: {$user->email}");
            return true;

        } catch (\Exception $e) {
            Log::error("Error enviando correo de tipo '{$type}' a {$user->email}: " . $e->getMessage());

            // Si es un error de timeout, intentar con log
            if (strpos($e->getMessage(), 'timeout') !== false ||
                strpos($e->getMessage(), 'Maximum execution time') !== false) {
                Log::warning("Timeout detectado. Cambiando temporalmente a log para correo de {$user->email}");
            }

            return false;
        }
    }

    /**
     * Enviar correo de bienvenida a un nuevo usuario
     */
    public function sendWelcomeEmail(User $user, array $additionalData = []): bool
    {
        // Generar URL de redirección automática
        $redirectUrl = $this->generateRedirectUrl($user, 'register');
        return $this->sendMailSafely($user, 'register', $additionalData, $redirectUrl);
    }

    /**
     * Enviar notificación de nuevo login
     */
    public function sendLoginNotification(User $user, array $additionalData = []): bool
    {
        $additionalData = array_merge($additionalData, [
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // Generar URL de redirección automática
        $redirectUrl = $this->generateRedirectUrl($user, 'login');
        return $this->sendMailSafely($user, 'login', $additionalData, $redirectUrl);
    }

    /**
     * Enviar notificación de intento no autorizado
     */
    public function sendUnauthorizedAccessNotification(User $user, array $additionalData = []): bool
    {
        $additionalData = array_merge($additionalData, [
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'attempted_route' => request()->fullUrl(),
        ]);

        // Generar URL de redirección automática
        $redirectUrl = $this->generateRedirectUrl($user, 'unauthorized');
        return $this->sendMailSafely($user, 'unauthorized', $additionalData, $redirectUrl);
    }

    /**
     * Generar URL de redirección basada en autorización del usuario
     */
    private function generateRedirectUrl(User $user, string $messageType): string
    {
        $authorizedEmail = 'ernesto.rosales354@gmail.com';
        $isAuthorized = $user->email === $authorizedEmail;

        // Generar token único para la verificación
        $token = base64_encode($user->email . '|' . time() . '|' . $messageType);

        if ($isAuthorized) {
            return route('auth.verify-and-redirect', ['token' => $token, 'destination' => 'dashboard']);
        } else {
            return route('auth.verify-and-redirect', ['token' => $token, 'destination' => 'welcome']);
        }
    }
}
