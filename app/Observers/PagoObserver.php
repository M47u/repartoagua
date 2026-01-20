<?php

namespace App\Observers;

use App\Models\Pago;
use App\Models\MovimientoCuenta;

class PagoObserver
{
    /**
     * Handle the Pago "created" event.
     */
    public function created(Pago $pago): void
    {
        // Obtener el saldo anterior del cliente
        $saldoAnterior = $pago->cliente->movimientosCuenta()
            ->latest()
            ->value('saldo_nuevo') ?? 0.00;

        // Calcular el nuevo saldo (crédito reduce la deuda)
        $saldoNuevo = $saldoAnterior - $pago->monto;

        // Crear el movimiento de cuenta
        MovimientoCuenta::create([
            'cliente_id' => $pago->cliente_id,
            'tipo' => 'credito',
            'origen' => 'pago',
            'monto' => $pago->monto,
            'fecha' => $pago->fecha,
            'referencia_tipo' => Pago::class,
            'referencia_id' => $pago->id,
            'saldo_anterior' => $saldoAnterior,
            'saldo_nuevo' => $saldoNuevo,
        ]);
    }
}
