<?php

namespace App\Http\Middleware;

use App\Services\EmailService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class CheckAuthorizedUser
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
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response|InertiaResponse
    {
        // Si el usuario no está autenticado, redirigir al login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Si el usuario necesita verificación por correo, redirigir a verificación
        if (session('email_verification_required')) {
            return redirect()->route('email.verification')->with('warning', 'Debes verificar tu correo electrónico para continuar.');
        }

        // Si el usuario autenticado no es el autorizado, mostrar página de acceso restringido
        if (Auth::user()->email !== self::AUTHORIZED_EMAIL) {
            // Enviar notificación de intento de acceso no autorizado (en background)
            $user = Auth::user();
            $routePath = $request->path();
            $routeName = $request->route() ? $request->route()->getName() : null;

            dispatch(function () use ($user, $routePath, $routeName) {
                try {
                    $emailService = new EmailService();
                    $emailService->sendUnauthorizedAccessNotification($user, [
                        'attempted_action' => 'Acceso a ruta protegida: ' . $routePath,
                        'middleware_blocked' => true,
                        'route_name' => $routeName,
                        'blocked_time' => now()->format('Y-m-d H:i:s'),
                    ]);
                } catch (\Exception $e) {
                    Log::warning("No se pudo enviar notificación desde middleware: " . $e->getMessage());
                }
            })->afterResponse();

            return redirect()->route('welcome')->with('warning', 'Acceso restringido. Solo usuarios autorizados pueden acceder a esta área.');
        }

        return $next($request);
    }
}
