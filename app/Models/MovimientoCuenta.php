<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class MovimientoCuenta extends Model
{
    protected $table = 'movimientos_cuenta';

    protected $fillable = [
        'cliente_id',
        'tipo',
        'origen',
        'monto',
        'fecha',
        'referencia_tipo',
        'referencia_id',
        'saldo_anterior',
        'saldo_nuevo',
    ];

    protected $casts = [
        'fecha' => 'date',
        'monto' => 'decimal:2',
        'saldo_anterior' => 'decimal:2',
        'saldo_nuevo' => 'decimal:2',
    ];

    /**
     * Cliente del movimiento
     */
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    /**
     * Referencia polimórfica (Reparto o Pago)
     */
    public function referencia(): MorphTo
    {
        return $this->morphTo('referencia', 'referencia_tipo', 'referencia_id');
    }
}
