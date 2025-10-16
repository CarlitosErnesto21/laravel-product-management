<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeConfirmationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public User $user,
        public string $messageType = 'welcome',
        public array $additionalData = [],
        public string $redirectUrl = ''
    ) {
        // Si no se proporciona URL de redirección, generarla automáticamente
        if (empty($this->redirectUrl)) {
            $this->redirectUrl = $this->generateRedirectUrl();
        }
    }

    /**
     * Generar URL de redirección basada en autorización del usuario
     */
    private function generateRedirectUrl(): string
    {
        $authorizedEmail = 'ernesto.rosales354@gmail.com';
        $isAuthorized = $this->user->email === $authorizedEmail;

        // Generar token único para la verificación
        $token = base64_encode($this->user->email . '|' . time() . '|' . $this->messageType);

        if ($isAuthorized) {
            return route('auth.verify-and-redirect', ['token' => $token, 'destination' => 'dashboard']);
        } else {
            return route('auth.verify-and-redirect', ['token' => $token, 'destination' => 'welcome']);
        }
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subjects = [
            'welcome' => '✉️ Verificación de acceso - Sistema de Productos',
            'login' => '🔐 Verificación de inicio de sesión',
            'register' => '✅ Verificación de cuenta nueva',
            'unauthorized' => '⚠️ Verificación de acceso limitado',
        ];

        return new Envelope(
            subject: $subjects[$this->messageType] ?? 'Verificación del Sistema',
            from: config('mail.from.address'),
            replyTo: [config('mail.from.address')],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.welcome-confirmation',
            with: [
                'user' => $this->user,
                'messageType' => $this->messageType,
                'additionalData' => $this->additionalData,
                'appName' => config('app.name'),
                'appUrl' => config('app.url'),
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
