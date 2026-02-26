<?php

namespace App\Policies;

use App\Models\Producto;
use App\Models\User;

class ProductoPolicy
{
    /**
     * Determine if the user can view any productos.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['administrador', 'administrativo', 'gerente']);
    }

    /**
     * Determine if the user can view the producto.
     */
    public function view(User $user, Producto $producto): bool
    {
        return in_array($user->role, ['administrador', 'administrativo', 'gerente']);
    }

    /**
     * Determine if the user can create productos.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['administrador', 'administrativo']);
    }

    /**
     * Determine if the user can update the producto.
     */
    public function update(User $user, Producto $producto): bool
    {
        return in_array($user->role, ['administrador', 'administrativo']);
    }

    /**
     * Determine if the user can delete the producto.
     */
    public function delete(User $user, Producto $producto): bool
    {
        // Solo administradores pueden eliminar
        if ($user->role !== 'administrador') {
            return false;
        }

        // No se puede eliminar si tiene repartos asociados
        return $producto->repartos()->count() === 0;
    }
}
