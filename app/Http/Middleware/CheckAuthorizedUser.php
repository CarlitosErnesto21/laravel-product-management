<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAuthorizedUser
{
    /**
     * Email del usuario autorizado
     */
    const AUTHORIZED_EMAIL = 'ernesto.rosales354@gmail.com';

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Si el usuario no está autenticado, redirigir al login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Si el usuario autenticado no es el autorizado, redirigir a welcome
        if (Auth::user()->email !== self::AUTHORIZED_EMAIL) {
            return redirect()->route('welcome')->with('warning', 'Acceso restringido. Solo usuarios autorizados pueden acceder a esta área.');
        }

        return $next($request);
    }
}
