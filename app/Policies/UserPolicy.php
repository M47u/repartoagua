<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['administrador', 'gerente']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        // Administradores y gerentes pueden ver todos
        if (in_array($user->role, ['administrador', 'gerente'])) {
            return true;
        }
        
        // Usuarios pueden ver su propio perfil
        return $user->id === $model->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['administrador', 'gerente']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        // Administradores y gerentes pueden actualizar todos
        if (in_array($user->role, ['administrador', 'gerente'])) {
            return true;
        }
        
        // Usuarios pueden actualizar su propio perfil (datos básicos)
        return $user->id === $model->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        // Solo administradores pueden eliminar
        if ($user->role !== 'administrador') {
            return false;
        }
        
        // No se puede eliminar a sí mismo
        return $user->id !== $model->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        return $user->role === 'administrador';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        return $user->role === 'administrador' && $user->id !== $model->id;
    }
}
