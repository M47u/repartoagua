<?php

namespace App\Observers;

use App\Models\Reparto;
use App\Models\MovimientoCuenta;
use Illuminate\Support\Facades\DB;

class RepartoObserver
{
    /**
     * Handle the Reparto "created" event.
     */
    public function created(Reparto $reparto): void
    {
        DB::transaction(function () use ($reparto) {
            // Obtener el saldo anterior del cliente con bloqueo
            $saldoAnterior = $reparto->cliente->movimientosCuenta()
                ->lockForUpdate()
                ->latest()
                ->value('saldo_nuevo') ?? 0.00;

            // Calcular el nuevo saldo (débito aumenta la deuda)
            $saldoNuevo = $saldoAnterior + $reparto->total;

            // Crear el movimiento de cuenta
            MovimientoCuenta::create([
                'cliente_id' => $reparto->cliente_id,
                'tipo' => 'debito',
                'origen' => 'reparto',
                'monto' => $reparto->total,
                'fecha' => $reparto->fecha,
                'referencia_tipo' => Reparto::class,
                'referencia_id' => $reparto->id,
                'saldo_anterior' => $saldoAnterior,
                'saldo_nuevo' => $saldoNuevo,
            ]);
        });
    }
}
