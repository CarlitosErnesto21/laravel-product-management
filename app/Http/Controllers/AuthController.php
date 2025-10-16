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

            // Determinar si es usuario autorizado
            $isAuthorized = $user->email === self::AUTHORIZED_EMAIL;
            $emailService = $this->emailService; // Capturar la referencia para el closure

            // Enviar correo de verificación con enlace de redirección (en background)
            dispatch(function () use ($user, $isAuthorized, $emailService) {
                try {
                    $emailService->sendLoginNotification($user, [
                        'login_time' => now()->format('Y-m-d H:i:s'),
                        'app_url' => config('app.url'),
                        'is_authorized' => $isAuthorized
                    ]);
                } catch (\Exception $e) {
                    Log::warning("No se pudo enviar notificación de login para {$user->email}: " . $e->getMessage());
                }
            })->afterResponse();

            // Temporal: Logging para debug
            Log::info("Login exitoso para: {$user->email}, autorizado: " . ($isAuthorized ? 'sí' : 'no'));

            // Mostrar vista de verificación pendiente en lugar de redirigir directamente
            return Inertia::render('Auth/EmailVerification', [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
                'isAuthorized' => $isAuthorized,
                'appName' => config('app.name'),
                'verificationType' => 'login'
            ]);
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

        // Determinar si es usuario autorizado
        $isAuthorized = $user->email === self::AUTHORIZED_EMAIL;
        $emailService = $this->emailService; // Capturar la referencia para el closure

        // Enviar correo de verificación con enlace de redirección (en background)
        dispatch(function () use ($user, $isAuthorized, $emailService) {
            try {
                $emailService->sendWelcomeEmail($user, [
                    'registration_time' => now()->format('Y-m-d H:i:s'),
                    'app_url' => config('app.url'),
                    'first_login' => true,
                    'is_authorized' => $isAuthorized
                ]);
            } catch (\Exception $e) {
                Log::warning("No se pudo enviar correo de registro para {$user->email}: " . $e->getMessage());
            }
        })->afterResponse();

        // Temporal: Logging para debug
        Log::info("Registro exitoso para: {$user->email}, autorizado: " . ($isAuthorized ? 'sí' : 'no'));

        // Mostrar vista de verificación pendiente en lugar de redirigir directamente
        return Inertia::render('Auth/EmailVerification', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'isAuthorized' => $isAuthorized,
            'appName' => config('app.name'),
            'verificationType' => 'register'
        ]);
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
        // Si hay un usuario autenticado y no es el autorizado, enviar notificación (en background)
        if (Auth::check() && Auth::user()->email !== self::AUTHORIZED_EMAIL) {
            $user = Auth::user();
            dispatch(function () use ($user) {
                try {
                    $emailService = new EmailService();
                    $emailService->sendUnauthorizedAccessNotification($user, [
                        'attempted_action' => 'Acceso directo a página no autorizada',
                        'direct_access' => true,
                        'blocked_time' => now()->format('Y-m-d H:i:s'),
                    ]);
                } catch (\Exception $e) {
                    Log::warning("No se pudo enviar notificación de acceso directo no autorizado: " . $e->getMessage());
                }
            })->afterResponse();
        }

        return Inertia::render('Auth/Unauthorized');
    }

    /**
     * Verificar token y redirigir al usuario
     */
    public function verifyAndRedirect(Request $request, $token)
    {
        try {
            // Decodificar y validar el token
            $decoded = base64_decode($token);
            $parts = explode('|', $decoded);

            if (count($parts) !== 3) {
                throw new \Exception('Token inválido');
            }

            [$email, $timestamp, $messageType] = $parts;

            // Verificar que el token no haya expirado (24 horas)
            if (time() - $timestamp > 86400) {
                return redirect()->route('login')->withErrors(['token' => 'El enlace de verificación ha expirado.']);
            }

            // Buscar el usuario
            $user = User::where('email', $email)->first();
            if (!$user) {
                return redirect()->route('login')->withErrors(['token' => 'Usuario no encontrado.']);
            }

            // Autenticar al usuario si no está logueado
            if (!Auth::check()) {
                Auth::login($user);
            }

            // Determinar destino basado en autorización
            $destination = $request->query('destination', 'auto');
            $isAuthorized = $user->email === self::AUTHORIZED_EMAIL;

            if ($destination === 'auto') {
                $destination = $isAuthorized ? 'dashboard' : 'welcome';
            }

            // Redirigir según el destino
            if ($destination === 'dashboard' && $isAuthorized) {
                return redirect()->route('dashboard')->with('success', '✅ Verificación exitosa. ¡Bienvenido al Dashboard!');
            } else {
                return redirect()->route('welcome')->with('info', '✅ Verificación exitosa. Explora nuestro catálogo de productos.');
            }

        } catch (\Exception $e) {
            Log::error('Error en verificación de token: ' . $e->getMessage());
            return redirect()->route('login')->withErrors(['token' => 'Enlace de verificación inválido.']);
        }
    }

    /**
     * Reenviar correo de verificación
     */
    public function resendVerification(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['error' => 'Usuario no autenticado'], 401);
            }

            $isAuthorized = $user->email === self::AUTHORIZED_EMAIL;
            $emailService = $this->emailService;

            // Enviar nuevo correo de verificación (en background)
            dispatch(function () use ($user, $isAuthorized, $emailService) {
                try {
                    $emailService->sendLoginNotification($user, [
                        'login_time' => now()->format('Y-m-d H:i:s'),
                        'app_url' => config('app.url'),
                        'is_authorized' => $isAuthorized,
                        'resent' => true
                    ]);
                } catch (\Exception $e) {
                    Log::warning("No se pudo reenviar correo de verificación para {$user->email}: " . $e->getMessage());
                }
            })->afterResponse();

            return response()->json(['message' => 'Correo de verificación reenviado exitosamente']);

        } catch (\Exception $e) {
            Log::error('Error reenviando verificación: ' . $e->getMessage());
            return response()->json(['error' => 'Error interno del servidor'], 500);
        }
    }
}
