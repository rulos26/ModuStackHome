<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('home');
    }
    return redirect()->route('login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Rutas del módulo de correo (solo para rol root)
Route::middleware(['auth', 'role:root'])->prefix('correo')->name('correo.')->group(function () {
    Route::get('/prueba', [App\Http\Controllers\CorreoController::class, 'index'])->name('prueba');
    Route::post('/enviar', [App\Http\Controllers\CorreoController::class, 'enviar'])->name('enviar');
});

// Rutas del módulo de usuarios (con políticas de autorización)
Route::middleware(['auth'])->group(function () {
    Route::resource('usuarios', App\Http\Controllers\UsuarioController::class)->parameters([
        'usuarios' => 'usuario'
    ]);
    
    // Ruta de perfil (accesible para todos los usuarios autenticados)
    Route::get('/perfil', [App\Http\Controllers\PerfilController::class, 'index'])->name('perfil.index');
    Route::put('/perfil', [App\Http\Controllers\PerfilController::class, 'update'])->name('perfil.update');
});
