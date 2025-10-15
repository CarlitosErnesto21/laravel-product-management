<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use Inertia\Inertia;

// Rutas públicas
Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('welcome');

// Rutas de autenticación
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Rutas protegidas (requieren autenticación)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Rutas de productos (protegidas)
    Route::resource('products', ProductController::class);

    // Ruta de prueba para Cloudinary (protegida)
    Route::get('/test-cloudinary', [ProductController::class, 'testCloudinary']);
});
