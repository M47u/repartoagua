<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'apellido',
        'email',
        'password',
        'role',
        'telefono',
        'dni',
        'direccion',
        'ciudad',
        'fecha_ingreso',
        'fecha_nacimiento',
        'observaciones',
        'activo',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'fecha_ingreso' => 'date',
            'fecha_nacimiento' => 'date',
            'activo' => 'boolean',
        ];
    }

    /**
     * Verifica si el usuario es administrador
     */
    public function isAdministrador(): bool
    {
        return $this->role === 'administrador';
    }

    /**
     * Verifica si el usuario es administrativo
     */
    public function isAdministrativo(): bool
    {
        return $this->role === 'administrativo';
    }

    /**
     * Verifica si el usuario es repartidor
     */
    public function isRepartidor(): bool
    {
        return $this->role === 'repartidor';
    }

    /**
     * Verifica si el usuario es chofer
     */
    public function isChofer(): bool
    {
        return $this->role === 'chofer';
    }

    /**
     * Verifica si el usuario es gerente
     */
    public function isGerente(): bool
    {
        return $this->role === 'gerente';
    }

    /**
     * Repartos asignados al repartidor
     */
    public function repartos()
    {
        return $this->hasMany(\App\Models\Reparto::class, 'repartidor_id');
    }

    /**
     * Pagos registrados por el usuario
     */
    public function pagos()
    {
        return $this->hasMany(\App\Models\Pago::class, 'registrado_por');
    }

    /**
     * Vehículos asignados al chofer
     */
    public function vehiculos()
    {
        return $this->belongsToMany(\App\Models\Vehiculo::class, 'chofer_vehiculo')
            ->withPivot(['fecha_asignacion', 'fecha_desasignacion', 'asignacion_activa', 'observaciones'])
            ->withTimestamps();
    }

    /**
     * Vehículos actualmente asignados al chofer
     */
    public function vehiculosActivos()
    {
        return $this->vehiculos()->wherePivot('asignacion_activa', true);
    }

    /**
     * Scope para filtrar usuarios activos
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Scope para filtrar por rol
     */
    public function scopeRole($query, $role)
    {
        return $query->where('role', $role);
    }

    /**
     * Obtiene el nombre completo del usuario
     */
    public function getNombreCompletoAttribute(): string
    {
        return trim("{$this->name} {$this->apellido}");
    }
}
