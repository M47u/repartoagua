<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Producto extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
        'precio_base',
        'activo',
    ];

    protected $casts = [
        'precio_base' => 'decimal:2',
        'activo' => 'boolean',
    ];

    /**
     * Repartos del producto
     */
    public function repartos(): HasMany
    {
        return $this->hasMany(Reparto::class);
    }

    /**
     * Scope para filtrar productos activos
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }
}
