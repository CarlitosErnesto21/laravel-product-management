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

// Ruta para usuarios no autorizados
Route::middleware('auth')->group(function () {
    Route::get('/unauthorized', [AuthController::class, 'unauthorized'])->name('unauthorized');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Rutas protegidas (requieren autenticación y autorización)
Route::middleware(['auth', 'authorized'])->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');

    // Rutas de productos (protegidas)
    Route::resource('products', ProductController::class);

    // Ruta de prueba para Cloudinary (protegida)
    Route::get('/test-cloudinary', [ProductController::class, 'testCloudinary']);

    // Ruta de prueba para correos (protegida)
    Route::get('/test-email', function () {
        try {
            $emailService = new \App\Services\EmailService();
            $user = Auth::user();

            $emailService->sendCustomEmail($user, 'welcome', [
                'test_mode' => true,
                'test_time' => now()->format('Y-m-d H:i:s'),
                'app_url' => config('app.url')
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Correo de prueba enviado exitosamente a ' . $user->email
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error enviando correo: ' . $e->getMessage()
            ], 500);
        }
    })->name('test.email');
});
