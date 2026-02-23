<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Cliente;
use Illuminate\Http\Request;

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
}
