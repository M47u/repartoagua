<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Pago extends Model
{
    protected $fillable = [
        'fecha',
        'fecha_pago',
        'cliente_id',
        'monto',
        'metodo',
        'metodo_pago',
        'referencia',
        'notas',
        'observaciones',
        'registrado_por',
    ];

    protected $casts = [
        'fecha' => 'date',
        'fecha_pago' => 'datetime',
        'monto' => 'decimal:2',
    ];

    /**
     * Cliente del pago
     */
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    /**
     * Usuario que registró el pago
     */
    public function registradoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registrado_por');
    }

    /**
     * Movimiento de cuenta asociado
     */
    public function movimientoCuenta(): MorphOne
    {
        return $this->morphOne(MovimientoCuenta::class, 'referencia');
    }
}
