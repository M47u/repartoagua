<?php

namespace App\Http\Controllers;

use App\Models\Reparto;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Http\Request;

class RepartoController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Reparto::class);
        
        $query = Reparto::with(['cliente', 'producto', 'repartidor']);
        
        // Si es repartidor, solo ver los suyos
        if (auth()->user()->role === 'repartidor') {
            $query->where('repartidor_id', auth()->id());
        }
        
        $repartos = $query->latest()->paginate(15);
        
        // Agregar información de si tiene pago para cada reparto
        // Temporalmente deshabilitado hasta corregir estructura de BD
        // $repartos->getCollection()->transform(function ($reparto) {
        //     $reparto->tiene_pago = $reparto->tienePago();
        //     return $reparto;
        // });
        
        // Obtener todos los repartidores para el filtro
        $repartidores = User::where('role', 'repartidor')->orderBy('name')->get();
        
        return view('repartos.index', compact('repartos', 'repartidores'));
    }

    public function create()
    {
        $this->authorize('create', Reparto::class);
        
        $clientes = Cliente::where('activo', true)->orderBy('nombre')->get();
        $productos = Producto::where('activo', true)->get();
        $repartidores = User::where('role', 'repartidor')->orderBy('name')->get();
        
        return view('repartos.create', compact('clientes', 'productos', 'repartidores'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Reparto::class);
        
        $validated = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'producto_id' => 'required|exists:productos,id',
            'repartidor_id' => 'required|exists:users,id',
            'cantidad' => 'required|integer|min:1',
            'fecha_programada' => 'required|date',
            'notas' => 'nullable|string|max:500',
        ]);
        
        // Obtener el cliente y producto
        $cliente = Cliente::findOrFail($validated['cliente_id']);
        $producto = Producto::findOrFail($validated['producto_id']);
        
        // Determinar precio_unitario
        $precio_unitario = $cliente->precio_por_bidon ?? $producto->precio_base;
        
        // Calcular total
        $total = $precio_unitario * $validated['cantidad'];
        
        // Crear el reparto
        $reparto = Reparto::create([
            'cliente_id' => $validated['cliente_id'],
            'producto_id' => $validated['producto_id'],
            'repartidor_id' => $validated['repartidor_id'],
            'cantidad' => $validated['cantidad'],
            'precio_unitario' => $precio_unitario,
            'total' => $total,
            'fecha' => now()->toDateString(),
            'fecha_programada' => $validated['fecha_programada'],
            'estado' => 'pendiente',
            'notas' => $validated['notas'] ?? null,
        ]);
        
        return redirect()->route('repartos.index')
            ->with('success', 'Reparto creado exitosamente.');
    }

    public function show(Reparto $reparto)
    {
        $this->authorize('view', $reparto);
        
        $reparto->load(['cliente', 'producto', 'repartidor']);
        
        // Si es repartidor, ocultar precios
        $ocultarPrecios = auth()->user()->role === 'repartidor';
        
        return view('repartos.show', compact('reparto', 'ocultarPrecios'));
    }

    public function edit(Reparto $reparto)
    {
        $this->authorize('update', $reparto);
        
        $clientes = Cliente::where('activo', true)->orderBy('nombre')->get();
        $productos = Producto::where('activo', true)->get();
        $repartidores = User::where('role', 'repartidor')->orderBy('name')->get();
        
        return view('repartos.edit', compact('reparto', 'clientes', 'productos', 'repartidores'));
    }

    public function update(Request $request, Reparto $reparto)
    {
        $this->authorize('update', $reparto);
        
        $validated = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'producto_id' => 'required|exists:productos,id',
            'repartidor_id' => 'required|exists:users,id',
            'cantidad' => 'required|integer|min:1',
            'fecha_programada' => 'required|date',
            'estado' => 'required|in:pendiente,entregado,cancelado',
            'notas' => 'nullable|string|max:500',
        ]);
        
        // Recalcular si cambió cantidad
        if ($validated['cantidad'] != $reparto->cantidad) {
            $validated['total'] = $reparto->precio_unitario * $validated['cantidad'];
        }
        
        $reparto->update($validated);
        
        return redirect()->route('repartos.index')
            ->with('success', 'Reparto actualizado exitosamente.');
    }

    public function destroy(Reparto $reparto)
    {
        $this->authorize('delete', $reparto);
        
        $reparto->delete();
        
        return redirect()->route('repartos.index')
            ->with('success', 'Reparto eliminado exitosamente.');
    }

    public function marcarEntregado($id)
    {
        $reparto = Reparto::findOrFail($id);
        
        // Verificar que sea repartidor y sea su reparto
        if (auth()->user()->role !== 'repartidor' || $reparto->repartidor_id !== auth()->id()) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }
        
        // Verificar que esté pendiente
        if ($reparto->estado !== 'pendiente') {
            return redirect()->route('repartos.index')
                ->with('error', 'Solo se pueden marcar como entregados los repartos pendientes.');
        }
        
        $reparto->update([
            'estado' => 'entregado',
            'fecha_entrega' => now()->toDateString(),
            'entregado_at' => now(),
        ]);
        
        return redirect()->route('repartos.index')
            ->with('success', 'Reparto marcado como entregado exitosamente.');
    }
}
