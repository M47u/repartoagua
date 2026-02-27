<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Reparto extends Model
{
    protected $fillable = [
        'fecha',
        'fecha_programada',
        'fecha_entrega',
        'cliente_id',
        'repartidor_id',
        'producto_id',
        'cantidad',
        'precio_unitario',
        'total',
        'estado',
        'notas',
        'observaciones',
        'entregado_at',
    ];

    protected $casts = [
        'fecha' => 'date',
        'fecha_programada' => 'date',
        'fecha_entrega' => 'date',
        'cantidad' => 'integer',
        'precio_unitario' => 'decimal:2',
        'total' => 'decimal:2',
        'entregado_at' => 'datetime',
    ];

    /**
     * Cliente del reparto
     */
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    /**
     * Repartidor asignado
     */
    public function repartidor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'repartidor_id');
    }

    /**
     * Producto del reparto
     */
    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    /**
     * Movimiento de cuenta asociado
     */
    public function movimientoCuenta(): MorphOne
    {
        return $this->morphOne(MovimientoCuenta::class, 'referencia', 'referencia_tipo', 'referencia_id');
    }

    /**
     * Scope para filtrar repartos pendientes
     */
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    /**
     * Scope para filtrar repartos entregados
     */
    public function scopeEntregados($query)
    {
        return $query->where('estado', 'entregado');
    }

    /**
     * Scope para filtrar repartos cancelados
     */
    public function scopeCancelados($query)
    {
        return $query->where('estado', 'cancelado');
    }

    /**
     * Verifica si el reparto ya tiene un pago registrado
     */
    public function tienePago(): bool
    {
        // Temporalmente deshabilitado - la columna 'referencia' no existe en la BD
        return false;
        // return \App\Models\Pago::where('referencia', 'Reparto #' . $this->id)->exists();
    }

    /**
     * Marca el reparto como entregado
     */
    public function marcarComoEntregado()
    {
        $this->update([
            'estado' => 'entregado',
            'entregado_at' => now(),
        ]);
    }
}
