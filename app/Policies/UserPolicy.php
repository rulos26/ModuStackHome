<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     * Solo root y admin pueden ver la lista de usuarios.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['root', 'admin']);
    }

    /**
     * Determine whether the user can view the model.
     * Todos pueden ver su propio perfil, root y admin pueden ver todos.
     */
    public function view(User $user, User $model): bool
    {
        return $user->id === $model->id || $user->hasAnyRole(['root', 'admin']);
    }

    /**
     * Determine whether the user can create models.
     * Solo root y admin pueden crear usuarios.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['root', 'admin']);
    }

    /**
     * Determine whether the user can update the model.
     * - Root y admin pueden editar cualquier usuario
     * - Cliente solo puede editar su propio perfil
     */
    public function update(User $user, User $model): bool
    {
        // Root y admin pueden editar todos
        if ($user->hasAnyRole(['root', 'admin'])) {
            return true;
        }
        
        // Cliente solo puede editar su propio perfil
        return $user->id === $model->id && $user->hasRole('cliente');
    }

    /**
     * Determine whether the user can delete the model.
     * Solo root puede eliminar usuarios.
     */
    public function delete(User $user, User $model): bool
    {
        // Solo root puede eliminar usuarios
        // No se puede eliminar a sí mismo
        return $user->hasRole('root') && $user->id !== $model->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        return $user->hasRole('root');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        return $user->hasRole('root') && $user->id !== $model->id;
    }
}
