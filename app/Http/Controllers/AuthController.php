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

            // Enviar correo de verificación con enlace de redirección (directo)
            try {
                $this->emailService->sendLoginNotification($user, [
                    'login_time' => now()->format('Y-m-d H:i:s'),
                    'app_url' => config('app.url'),
                    'is_authorized' => $isAuthorized
                ]);
                Log::info("Correo de login enviado exitosamente a: {$user->email}");
            } catch (\Exception $e) {
                Log::error("Error enviando correo de login para {$user->email}: " . $e->getMessage());
            }

            // Log de auditoría
            Log::info("Login exitoso para: {$user->email}, autorizado: " . ($isAuthorized ? 'sí' : 'no'));

            // Marcar que necesita verificación por correo
            session(['email_verification_required' => true, 'user_authorized' => $isAuthorized]);

            // Redirigir a vista de verificación (sin middleware de autorización)
            return redirect()->route('email.verification')->with('success', 'Revisa tu correo electrónico para continuar.');
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

        // Enviar correo de verificación con enlace de redirección (directo)
        try {
            $this->emailService->sendWelcomeEmail($user, [
                'registration_time' => now()->format('Y-m-d H:i:s'),
                'app_url' => config('app.url'),
                'first_login' => true,
                'is_authorized' => $isAuthorized
            ]);
            Log::info("Correo de registro enviado exitosamente a: {$user->email}");
        } catch (\Exception $e) {
            Log::error("Error enviando correo de registro para {$user->email}: " . $e->getMessage());
        }

        // Log de auditoría
        Log::info("Registro exitoso para: {$user->email}, autorizado: " . ($isAuthorized ? 'sí' : 'no'));

        // Marcar que necesita verificación por correo
        session(['email_verification_required' => true, 'user_authorized' => $isAuthorized]);

        // Redirigir a vista de verificación (sin middleware de autorización)
        return redirect()->route('email.verification')->with('success', 'Cuenta creada. Revisa tu correo electrónico para continuar.');
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

            // Limpiar flag de verificación pendiente
            session()->forget(['email_verification_required', 'user_authorized']);

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
     * Acceso directo para casos donde el correo no llegue
     */
    public function directAccess(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return redirect()->route('login')->withErrors(['error' => 'Debes estar autenticado.']);
            }

            $destination = $request->input('destination', 'auto');
            $isAuthorized = $user->email === self::AUTHORIZED_EMAIL;

            // Limpiar flags de verificación
            session()->forget(['email_verification_required', 'user_authorized']);

            // Registrar el acceso directo
            Log::info("Acceso directo usado por: {$user->email} (Autorizado: " . ($isAuthorized ? 'Sí' : 'No') . ")");

            // Redirigir según autorización
            if ($destination === 'dashboard' || ($destination === 'auto' && $isAuthorized)) {
                if ($isAuthorized) {
                    return redirect()->route('dashboard')->with('success', '🚀 Acceso directo al Dashboard activado.');
                } else {
                    return redirect()->route('welcome')->with('warning', '⚠️ Acceso limitado. No tienes permisos de administrador.');
                }
            } else {
                return redirect()->route('welcome')->with('info', '👋 ¡Bienvenido! Explora nuestro catálogo de productos.');
            }

        } catch (\Exception $e) {
            Log::error('Error en acceso directo: ' . $e->getMessage());
            return redirect()->route('email.verification')->withErrors(['error' => 'Error procesando acceso directo.']);
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
                return back()->withErrors(['error' => 'Debes estar autenticado.']);
            }

            // Enviar nuevo correo
            $emailService = new EmailService();
            $isAuthorized = $user->email === self::AUTHORIZED_EMAIL;
            
            $success = $emailService->sendLoginNotification($user, [
                'resend' => true,
                'authorized' => $isAuthorized
            ]);

            if ($success) {
                return back()->with('success', '📧 Correo de verificación reenviado exitosamente.');
            } else {
                return back()->with('warning', '⚠️ No se pudo enviar el correo. Usa el acceso directo.');
            }

        } catch (\Exception $e) {
            Log::error('Error reenviando correo: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Error reenviando correo de verificación.']);
        }
    }

}
