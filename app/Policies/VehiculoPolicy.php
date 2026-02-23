<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vehiculo;

class VehiculoPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Todos los roles pueden ver la lista de vehículos
        return in_array($user->role, ['administrador', 'administrativo', 'gerente', 'chofer']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Vehiculo $vehiculo): bool
    {
        // Admin, administrativo y gerente pueden ver todos
        if (in_array($user->role, ['administrador', 'administrativo', 'gerente'])) {
            return true;
        }
        
        // Choferes solo pueden ver los vehículos asignados a ellos
        if ($user->role === 'chofer') {
            return $vehiculo->choferesActivos()->where('user_id', $user->id)->exists();
        }
        
        return false;
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
    public function update(User $user, Vehiculo $vehiculo): bool
    {
        return in_array($user->role, ['administrador', 'gerente', 'administrativo']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Vehiculo $vehiculo): bool
    {
        return in_array($user->role, ['administrador', 'gerente']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Vehiculo $vehiculo): bool
    {
        return $user->role === 'administrador';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Vehiculo $vehiculo): bool
    {
        return $user->role === 'administrador';
    }
}
