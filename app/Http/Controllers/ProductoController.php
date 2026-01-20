<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function index()
    {
        // Solo admin y administrativo
        if (!in_array(auth()->user()->role, ['administrador', 'administrativo'])) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }
        
        $productos = Producto::latest()->paginate(15);
        return view('productos.index', compact('productos'));
    }

    public function create()
    {
        if (!in_array(auth()->user()->role, ['administrador', 'administrativo'])) {
            abort(403);
        }
        
        return view('productos.create');
    }

    public function store(Request $request)
    {
        if (!in_array(auth()->user()->role, ['administrador', 'administrativo'])) {
            abort(403);
        }
        
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:500',
            'precio_base' => 'required|numeric|min:0',
            'activo' => 'boolean',
        ]);

        Producto::create($validated);

        return redirect()->route('productos.index')
            ->with('success', 'Producto creado exitosamente.');
    }

    public function show(Producto $producto)
    {
        if (!in_array(auth()->user()->role, ['administrador', 'administrativo'])) {
            abort(403);
        }
        
        $producto->load('repartos.cliente');
        return view('productos.show', compact('producto'));
    }

    public function edit(Producto $producto)
    {
        if (!in_array(auth()->user()->role, ['administrador', 'administrativo'])) {
            abort(403);
        }
        
        return view('productos.edit', compact('producto'));
    }

    public function update(Request $request, Producto $producto)
    {
        if (!in_array(auth()->user()->role, ['administrador', 'administrativo'])) {
            abort(403);
        }
        
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:500',
            'precio_base' => 'required|numeric|min:0',
            'activo' => 'boolean',
        ]);

        $producto->update($validated);

        return redirect()->route('productos.index')
            ->with('success', 'Producto actualizado exitosamente.');
    }

    public function destroy(Producto $producto)
    {
        if (!in_array(auth()->user()->role, ['administrador', 'administrativo'])) {
            abort(403);
        }
        
        $producto->delete();

        return redirect()->route('productos.index')
            ->with('success', 'Producto eliminado exitosamente.');
    }
}
