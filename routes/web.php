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



// Rutas para usuarios autenticados (sin restricción de autorización)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Rutas protegidas (requieren autenticación y autorización)
Route::middleware(['auth', 'authorized'])->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');

    // Rutas de productos (protegidas)
    Route::resource('products', ProductController::class);


});
