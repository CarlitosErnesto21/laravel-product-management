<?php

namespace App\Http\Controllers;

use App\Models\User;
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

            // Log de auditoría
            Log::info("Login exitoso para: {$user->email}, autorizado: " . ($isAuthorized ? 'sí' : 'no'));

            // Redirigir directamente según autorización
            if ($isAuthorized) {
                return redirect()->route('dashboard')->with('success', '¡Bienvenido al Dashboard!');
            } else {
                return redirect()->route('welcome')->with('info', 'Bienvenido, puedes explorar nuestros productos.');
            }
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

        // Log de auditoría
        Log::info("Registro exitoso para: {$user->email}");

        // Todos los usuarios nuevos van a la página principal
        return redirect()->route('welcome')->with('success', '¡Cuenta creada exitosamente! Bienvenido.');
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

}
