<?php

namespace App\Policies;

use App\Models\Reparto;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RepartoPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // Todos los roles pueden ver
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Reparto $reparto): bool
    {
        // Admin/Administrativo ven todos, repartidor solo los suyos
        if (in_array($user->role, ['administrador', 'administrativo'])) {
            return true;
        }
        
        return $user->role === 'repartidor' && $reparto->repartidor_id === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['administrador', 'administrativo']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Reparto $reparto): bool
    {
        // Admin puede actualizar todo
        if ($user->role === 'administrador') {
            return true;
        }
        
        // Repartidor solo puede marcar como entregado si es suyo y está pendiente
        if ($user->role === 'repartidor') {
            return $reparto->repartidor_id === $user->id && $reparto->estado === 'pendiente';
        }
        
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Reparto $reparto): bool
    {
        return $user->role === 'administrador';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Reparto $reparto): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Reparto $reparto): bool
    {
        return false;
    }
}
