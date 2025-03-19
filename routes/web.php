<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\TenantLoginController;
use App\Http\Controllers\Auth\TenantRegisterController;

// Rutas públicas
Route::get('/', function () {
    return view('welcome');
});

// Rutas para crear nuevos tenants
Route::get('/register-tenant', [TenantController::class, 'showRegistrationForm'])->name('tenant.register');
Route::post('/register-tenant', [TenantController::class, 'register'])->name('tenant.register.submit');

// Rutas específicas de tenant
Route::prefix('{tenant}')->middleware('tenant')->group(function () {
    // Autenticación del tenant
    Route::get('/login', [TenantLoginController::class, 'showLoginForm'])->name('tenant.login');
    Route::post('/login', [TenantLoginController::class, 'login'])->name('tenant.login.submit');
    Route::post('/logout', [TenantLoginController::class, 'logout'])->name('tenant.logout');
    
    // Registro de usuario en tenant
    Route::get('/register', [TenantRegisterController::class, 'showRegistrationForm'])->name('tenant.user.register');
    Route::post('/register', [TenantRegisterController::class, 'register'])->name('tenant.user.register.submit');
    
    // Dashboard y otras rutas protegidas
    Route::middleware('tenant.auth')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('tenant.dashboard');
        // Añade aquí más rutas protegidas
    });
});
Auth::routes([
    'register' => false, // Desactivamos el registro público
]);

// Ruta especial para el primer registro
Route::middleware(['web', 'guest', 'first.user'])->group(function () {
    Route::get('register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
