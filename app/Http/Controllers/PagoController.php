<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Cliente;
use App\Models\Reparto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PagoController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Pago::class);
        
        $pagos = Pago::with(['cliente'])
            ->latest()
            ->paginate(15);
        
        return view('pagos.index', compact('pagos'));
    }

    public function create()
    {
        $this->authorize('create', Pago::class);
        
        $clientes = Cliente::where('activo', true)
            ->orderBy('nombre')
            ->get();
        
        return view('pagos.create', compact('clientes'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Pago::class);
        
        $validated = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'monto' => 'required|numeric|min:0.01',
            'metodo_pago' => 'required|in:efectivo,transferencia,cuenta_corriente',
            'referencia' => 'nullable|string|max:255',
            'notas' => 'nullable|string|max:500',
        ]);
        
        // Crear el pago
        $pago = Pago::create([
            'cliente_id' => $validated['cliente_id'],
            'monto' => $validated['monto'],
            'metodo_pago' => $validated['metodo_pago'],
            'referencia' => $validated['referencia'] ?? null,
            'notas' => $validated['notas'] ?? null,
            'fecha' => now()->toDateString(),
            'fecha_pago' => now(),
            'registrado_por' => auth()->id(),
        ]);
        
        // El MovimientoCuenta se crea automáticamente en el PagoObserver
        
        return redirect()->route('pagos.index')
            ->with('success', 'Pago registrado exitosamente.');
    }

    public function show(Pago $pago)
    {
        $this->authorize('view', $pago);
        
        $pago->load(['cliente', 'movimientoCuenta']);
        
        return view('pagos.show', compact('pago'));
    }

    public function edit(Pago $pago)
    {
        $this->authorize('update', $pago);
        
        $clientes = Cliente::where('activo', true)
            ->orderBy('nombre')
            ->get();
        
        return view('pagos.edit', compact('pago', 'clientes'));
    }

    public function update(Request $request, Pago $pago)
    {
        $this->authorize('update', $pago);
        
        $validated = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'monto' => 'required|numeric|min:0.01',
            'metodo_pago' => 'required|in:efectivo,transferencia,cuenta_corriente',
            'referencia' => 'nullable|string|max:255',
            'notas' => 'nullable|string|max:500',
        ]);
        
        $pago->update($validated);
        
        return redirect()->route('pagos.index')
            ->with('success', 'Pago actualizado exitosamente.');
    }

    public function destroy(Pago $pago)
    {
        $this->authorize('delete', $pago);
        
        $pago->delete();
        
        return redirect()->route('pagos.index')
            ->with('success', 'Pago eliminado exitosamente.');
    }

    /**
     * Obtiene información del reparto para el cobro rápido
     */
    public function getCobroInfo(Reparto $reparto)
    {
        $this->authorize('createQuick', Pago::class);

        // Verificar si ya tiene pago
        if ($reparto->tienePago()) {
            return response()->json([
                'success' => false,
                'message' => 'Este reparto ya tiene un pago registrado.',
            ], 422);
        }

        $reparto->load(['cliente', 'producto']);

        return response()->json([
            'reparto_id' => $reparto->id,
            'cliente' => [
                'id' => $reparto->cliente->id,
                'nombre' => $reparto->cliente->nombre . ' ' . $reparto->cliente->apellido,
                'saldo' => $reparto->cliente->saldo_actual ?? 0,
            ],
            'monto_sugerido' => $reparto->total,
            'detalle' => $reparto->cantidad . 'x ' . $reparto->producto->nombre,
        ]);
    }

    /**
     * Registra un pago rápido desde el módulo de repartos
     */
    public function cobroRapido(Request $request)
    {
        $this->authorize('createQuick', Pago::class);

        $validated = $request->validate([
            'reparto_id' => 'required|exists:repartos,id',
            'cliente_id' => 'required|exists:clientes,id',
            'monto' => 'required|numeric|min:0.01',
            'metodo_pago' => 'required|in:efectivo,transferencia,cuenta_corriente',
            'notas' => 'nullable|string|max:500',
        ]);

        // Verificar que el reparto no tenga ya un pago registrado
        $reparto = Reparto::findOrFail($validated['reparto_id']);
        if ($reparto->tienePago()) {
            return response()->json([
                'success' => false,
                'message' => 'Este reparto ya tiene un pago registrado.',
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Crear el pago
            $pago = Pago::create([
                'cliente_id' => $validated['cliente_id'],
                'monto' => $validated['monto'],
                'metodo_pago' => $validated['metodo_pago'],
                'referencia' => 'Reparto #' . $validated['reparto_id'],
                'notas' => $validated['notas'] ?? null,
                'fecha' => now()->toDateString(),
                'fecha_pago' => now(),
                'registrado_por' => auth()->id(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pago registrado exitosamente',
                'pago_id' => $pago->id,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar el pago: ' . $e->getMessage(),
            ], 500);
        }
    }
}
