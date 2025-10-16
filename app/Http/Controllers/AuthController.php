<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class AuthController extends Controller
{
    /**
     * Email del usuario autorizado
     */
    const AUTHORIZED_EMAIL = 'ernesto.rosales354@gmail.com';

    /**
     * Servicio de correos
     */
    protected EmailService $emailService;

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    /**
     * Mostrar formulario de login
     */
    public function showLoginForm()
    {
        return Inertia::render('Auth/Login');
    }

    /**
     * Procesar login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();

            // Enviar notificación de login exitoso
            try {
                $this->emailService->sendLoginNotification($user, [
                    'login_time' => now()->format('Y-m-d H:i:s'),
                    'app_url' => config('app.url')
                ]);
            } catch (\Exception $e) {
                Log::warning("No se pudo enviar notificación de login para {$user->email}: " . $e->getMessage());
            }

            // Verificar si el usuario es el autorizado
            if ($user->email !== self::AUTHORIZED_EMAIL) {
                // Enviar notificación de intento no autorizado
                try {
                    $this->emailService->sendUnauthorizedAccessNotification($user, [
                        'attempted_action' => 'Acceso a dashboard',
                        'blocked_time' => now()->format('Y-m-d H:i:s'),
                    ]);
                } catch (\Exception $e) {
                    Log::warning("No se pudo enviar notificación de acceso no autorizado para {$user->email}: " . $e->getMessage());
                }

                return redirect()->route('unauthorized');
            }

            return redirect()->intended(route('dashboard'))->with('success', '¡Bienvenido de vuelta! Has iniciado sesión correctamente.');
        }

        throw ValidationException::withMessages([
            'email' => __('Las credenciales no coinciden con nuestros registros.'),
        ]);
    }

    /**
     * Mostrar formulario de registro
     */
    public function showRegisterForm()
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Procesar registro
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        // Enviar correo de bienvenida
        try {
            $this->emailService->sendWelcomeEmail($user, [
                'registration_time' => now()->format('Y-m-d H:i:s'),
                'app_url' => config('app.url'),
                'first_login' => true
            ]);
        } catch (\Exception $e) {
            Log::warning("No se pudo enviar correo de bienvenida para {$user->email}: " . $e->getMessage());
        }

        // Verificar si el usuario registrado es el autorizado
        if ($user->email !== self::AUTHORIZED_EMAIL) {
            // Enviar notificación de intento no autorizado para nuevo registro
            try {
                $this->emailService->sendUnauthorizedAccessNotification($user, [
                    'attempted_action' => 'Registro de nueva cuenta',
                    'account_created' => true,
                    'blocked_time' => now()->format('Y-m-d H:i:s'),
                ]);
            } catch (\Exception $e) {
                Log::warning("No se pudo enviar notificación de registro no autorizado para {$user->email}: " . $e->getMessage());
            }

            return redirect()->route('unauthorized')->with('info', 'Tu cuenta ha sido creada exitosamente. Se ha enviado un correo de confirmación.');
        }

        return redirect()->route('dashboard')->with('success', '¡Cuenta creada exitosamente! Bienvenido al sistema.');
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Dashboard principal
     */
    public function dashboard()
    {
        return Inertia::render('Dashboard', [
            'user' => Auth::user()
        ]);
    }

    /**
     * Mostrar página de acceso no autorizado
     */
    public function unauthorized()
    {
        // Si hay un usuario autenticado y no es el autorizado, enviar notificación
        if (Auth::check() && Auth::user()->email !== self::AUTHORIZED_EMAIL) {
            try {
                $this->emailService->sendUnauthorizedAccessNotification(Auth::user(), [
                    'attempted_action' => 'Acceso directo a página no autorizada',
                    'direct_access' => true,
                    'blocked_time' => now()->format('Y-m-d H:i:s'),
                ]);
            } catch (\Exception $e) {
                Log::warning("No se pudo enviar notificación de acceso directo no autorizado: " . $e->getMessage());
            }
        }

        return Inertia::render('Auth/Unauthorized');
    }
}
