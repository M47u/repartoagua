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
        'email',
        'password',
        'role',
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
}
