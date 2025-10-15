<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::get('/', function () {
    return redirect()->route('products.index');
});

Route::resource('products', ProductController::class);

// Ruta de prueba para Cloudinary
Route::get('/test-cloudinary', [ProductController::class, 'testCloudinary']);
