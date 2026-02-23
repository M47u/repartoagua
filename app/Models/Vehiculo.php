<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Vehiculo extends Model
{
    protected $fillable = [
        'placa',
        'marca',
        'modelo',
        'año',
        'color',
        'tipo',
        'capacidad_carga',
        'capacidad_bidones',
        'numero_motor',
        'numero_chasis',
        'fecha_compra',
        'fecha_ultimo_mantenimiento',
        'fecha_proximo_mantenimiento',
        'kilometraje',
        'estado',
        'observaciones',
        'activo',
    ];

    protected $casts = [
        'año' => 'integer',
        'capacidad_carga' => 'integer',
        'capacidad_bidones' => 'integer',
        'fecha_compra' => 'date',
        'fecha_ultimo_mantenimiento' => 'date',
        'fecha_proximo_mantenimiento' => 'date',
        'kilometraje' => 'decimal:2',
        'activo' => 'boolean',
    ];

    /**
     * Choferes asignados al vehículo
     */
    public function choferes(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'chofer_vehiculo')
            ->withPivot(['fecha_asignacion', 'fecha_desasignacion', 'asignacion_activa', 'observaciones'])
            ->withTimestamps();
    }

    /**
     * Choferes actualmente asignados
     */
    public function choferesActivos(): BelongsToMany
    {
        return $this->choferes()->wherePivot('asignacion_activa', true);
    }

    /**
     * Scope para filtrar vehículos activos
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Scope para filtrar vehículos disponibles
     */
    public function scopeDisponibles($query)
    {
        return $query->where('estado', 'disponible')->where('activo', true);
    }

    /**
     * Scope para filtrar vehículos en uso
     */
    public function scopeEnUso($query)
    {
        return $query->where('estado', 'en_uso');
    }

    /**
     * Scope para filtrar vehículos en mantenimiento
     */
    public function scopeEnMantenimiento($query)
    {
        return $query->where('estado', 'mantenimiento');
    }

    /**
     * Verifica si el vehículo necesita mantenimiento
     */
    public function necesitaMantenimiento(): bool
    {
        if (!$this->fecha_proximo_mantenimiento) {
            return false;
        }
        
        return $this->fecha_proximo_mantenimiento <= now()->addDays(7);
    }

    /**
     * Obtiene el nombre completo del vehículo
     */
    public function getNombreCompletoAttribute(): string
    {
        return "{$this->marca} {$this->modelo} ({$this->placa})";
    }

    /**
     * Verifica si el vehículo tiene choferes asignados
     */
    public function tieneChoferesAsignados(): bool
    {
        return $this->choferesActivos()->exists();
    }
}
