<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cliente extends Model
{
    protected $fillable = [
        'nombre',
        'apellido',
        'direccion',
        'colonia',
        'ciudad',
        'telefono',
        'email',
        'tipo_cliente',
        'producto_id',
        'precio_por_bidon',
        'observaciones',
        'activo',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'precio_por_bidon' => 'decimal:2',
        'activo' => 'boolean',
    ];

    /**
     * Producto predeterminado del cliente
     */
    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    /**
     * Repartos del cliente
     */
    public function repartos(): HasMany
    {
        return $this->hasMany(Reparto::class);
    }

    /**
     * Pagos del cliente
     */
    public function pagos(): HasMany
    {
        return $this->hasMany(Pago::class);
    }

    /**
     * Movimientos de cuenta del cliente
     */
    public function movimientosCuenta(): HasMany
    {
        return $this->hasMany(MovimientoCuenta::class);
    }

    /**
     * Calcula el saldo actual del cliente
     */
    public function getSaldoActualAttribute(): float
    {
        return $this->movimientosCuenta()
            ->selectRaw('SUM(CASE WHEN tipo = "debito" THEN monto ELSE -monto END) as saldo')
            ->value('saldo') ?? 0.00;
    }

    /**
     * Alias para saldo_actual
     */
    public function getSaldoAttribute(): float
    {
        return $this->saldo_actual;
    }

    /**
     * Obtiene el estado de cuenta del cliente
     */
    public function getEstadoCuentaAttribute(): string
    {
        return $this->saldo_actual > 0 ? 'con_deuda' : 'al_dia';
    }

    /**
     * Scope para filtrar clientes activos
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }
}
