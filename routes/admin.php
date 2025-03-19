<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
    
    // Rutas de gestión de usuarios
    Route::resource('users', App\Http\Controllers\Admin\UserController::class);
});