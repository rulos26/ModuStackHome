<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Registrar Gate para verificar roles (para AdminLTE menu)
        // El formato 'role:nombre_rol' será parseado por el GateFilter
        Gate::before(function ($user, $ability) {
            // Si el ability tiene el formato 'role:nombre_rol', verificar el rol
            if (str_starts_with($ability, 'role:')) {
                $role = substr($ability, 5); // Remover 'role:' del inicio
                return $user->hasRole($role);
            }
            return null; // Dejar que otros Gates se ejecuten normalmente
        });
    }
}
