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
        public array $additionalData = []
    ) {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subjects = [
            'welcome' => '¡Bienvenido/a al Sistema de Gestión de Productos!',
            'login' => 'Nuevo inicio de sesión detectado',
            'register' => 'Cuenta creada exitosamente',
            'unauthorized' => 'Intento de acceso no autorizado',
        ];

        return new Envelope(
            subject: $subjects[$this->messageType] ?? 'Notificación del Sistema',
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
