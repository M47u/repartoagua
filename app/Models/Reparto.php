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
        return $this->morphOne(MovimientoCuenta::class, 'referencia');
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
