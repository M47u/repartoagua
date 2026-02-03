<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Cliente::class);
        
        $clientes = Cliente::latest()
            ->paginate(15);
            
        return view('clientes.index', compact('clientes'));
    }

    public function create()
    {
        $this->authorize('create', Cliente::class);
        
        return view('clientes.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Cliente::class);
        
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'telefono' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'colonia' => 'nullable|string|max:255',
            'ciudad' => 'required|string|max:255',
            'email' => 'nullable|email|unique:clientes,email|max:255',
            'tipo_cliente' => 'required|in:hogar,comercio,empresa',
            'precio_por_bidon' => 'nullable|numeric|min:0',
            'activo' => 'boolean',
        ]);

        Cliente::create($validated);

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente creado exitosamente.');
    }

    public function show(Cliente $cliente)
    {
        $this->authorize('view', $cliente);
        
        $cliente->load([
            'repartos' => function($query) {
                $query->with(['producto', 'repartidor'])->latest();
            },
            'pagos' => function($query) {
                $query->latest();
            },
            'movimientosCuenta' => function($query) {
                $query->latest()->take(20);
            }
        ]);
        
        return view('clientes.show', compact('cliente'));
    }

    public function edit(Cliente $cliente)
    {
        $this->authorize('update', $cliente);
        
        return view('clientes.edit', compact('cliente'));
    }

    public function update(Request $request, Cliente $cliente)
    {
        $this->authorize('update', $cliente);
        
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'telefono' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'colonia' => 'nullable|string|max:255',
            'ciudad' => 'required|string|max:255',
            'email' => 'nullable|email|unique:clientes,email,' . $cliente->id . '|max:255',
            'tipo_cliente' => 'required|in:hogar,comercio,empresa',
            'precio_por_bidon' => 'nullable|numeric|min:0',
            'activo' => 'boolean',
        ]);

        $cliente->update($validated);

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente actualizado exitosamente.');
    }

    public function destroy(Cliente $cliente)
    {
        $this->authorize('delete', $cliente);
        
        // Verificar que no tenga repartos pendientes
        if ($cliente->repartos()->where('estado', 'pendiente')->exists()) {
            return redirect()->route('clientes.index')
                ->with('error', 'No se puede eliminar el cliente porque tiene repartos pendientes.');
        }
        
        $cliente->delete();

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente eliminado exitosamente.');
    }

    public function toggleEstado(Cliente $cliente)
    {
        $this->authorize('update', $cliente);
        
        $cliente->update([
            'activo' => !$cliente->activo
        ]);

        return redirect()->route('clientes.index')
            ->with('success', 'Estado del cliente actualizado exitosamente.');
    }
}
