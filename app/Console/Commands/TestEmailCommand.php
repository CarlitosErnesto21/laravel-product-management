<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Mail\WelcomeConfirmationMail;

class TestEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Probar envío de correo electrónico';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $this->info('🔄 Probando envío de correo...');

            // Prueba 1: Correo simple
            $this->info('📧 Enviando correo simple...');
            Mail::raw('Este es un correo de prueba desde Laravel', function ($message) {
                $message->to('ernesto.rosales354@gmail.com')
                        ->subject('🧪 Prueba SMTP Laravel - ' . now()->format('H:i:s'));
            });
            $this->info('✅ Correo simple enviado');

            // Prueba 2: Correo con template
            $user = User::where('email', 'ernesto.rosales354@gmail.com')->first();
            if ($user) {
                $this->info('📧 Enviando correo con template...');
                Mail::to($user->email)->send(new WelcomeConfirmationMail($user, 'login', [
                    'test_mode' => true,
                    'sent_at' => now()->format('Y-m-d H:i:s')
                ]));
                $this->info('✅ Correo con template enviado');
            } else {
                $this->error('❌ Usuario no encontrado');
            }

        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            $this->error('📋 Detalles: ' . $e->getTraceAsString());
        }
    }
}
