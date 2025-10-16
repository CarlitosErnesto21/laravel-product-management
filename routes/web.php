<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use App\Models\Product;
use Inertia\Inertia;

// Rutas públicas
Route::get('/', function () {
    $products = Product::latest()->get();

    return Inertia::render('Welcome', [
        'products' => $products
    ]);
})->name('welcome');

// Rutas de autenticación
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Rutas de verificación de correo (sin middleware de autorización)
Route::get('/auth/verify-and-redirect/{token}', [AuthController::class, 'verifyAndRedirect'])->name('auth.verify-and-redirect');

// Rutas para usuarios autenticados (sin restricción de autorización)
Route::middleware('auth')->group(function () {
    Route::get('/email-verification', function() {
        // Esta ruta solo se muestra después del login/registro
        // El usuario ya está autenticado pero puede no estar autorizado
        return Inertia::render('Auth/EmailVerification', [
            'user' => [
                'id' => Auth::user()->id,
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
            ],
            'isAuthorized' => Auth::user()->email === 'ernesto.rosales354@gmail.com',
            'appName' => config('app.name'),
            'verificationType' => 'pending'
        ]);
    })->name('email.verification');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Ruta para acceso directo (bypass del correo)
    Route::post('/auth/direct-access', [AuthController::class, 'directAccess'])->name('auth.direct-access');
    
    // Ruta para reenviar correo de verificación
    Route::post('/auth/resend-verification', [AuthController::class, 'resendVerification'])->name('auth.resend-verification');
});

// Rutas protegidas (requieren autenticación y autorización)
Route::middleware(['auth', 'authorized'])->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');

    // Rutas de productos (protegidas)
    Route::resource('products', ProductController::class);


});
