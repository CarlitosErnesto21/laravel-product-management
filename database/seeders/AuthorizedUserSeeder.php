<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AuthorizedUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $authorizedEmail = 'ernesto@gmail.com';

        // Verificar si el usuario ya existe
        $existingUser = User::where('email', $authorizedEmail)->first();

        if (!$existingUser) {
            User::create([
                'name' => 'Carlos Ernesto Rosales',
                'email' => $authorizedEmail,
                'password' => Hash::make('Carlitos123'),
                'email_verified_at' => now(),
            ]);

            $this->command->info('Usuario autorizado creado exitosamente.');
        } else {
            // Actualizar la contraseña si el usuario ya existe
            $existingUser->update([
                'password' => Hash::make('Carlitos123'),
            ]);

            $this->command->info('Contraseña del usuario autorizado actualizada.');
        }
    }
}
