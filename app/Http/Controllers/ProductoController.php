<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductoRequest;
use App\Models\Producto;

class ProductoController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Producto::class);
        
        $productos = Producto::withCount('repartos')->latest()->paginate(15);
        return view('productos.index', compact('productos'));
    }

    public function create()
    {
        $this->authorize('create', Producto::class);
        
        return view('productos.create');
    }

    public function store(ProductoRequest $request)
    {
        $this->authorize('create', Producto::class);
        
        $data = [
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio_base' => $request->precio_base,
            'activo' => $request->has('activo') ? 1 : 0,
        ];

        Producto::create($data);

        return redirect()->route('productos.index')
            ->with('success', 'Producto creado exitosamente.');
    }

    public function show(Producto $producto)
    {
        $this->authorize('view', $producto);
        
        $producto->load(['repartos' => function($query) {
            $query->with('cliente')->latest()->take(10);
        }]);
        
        return view('productos.show', compact('producto'));
    }

    public function edit(Producto $producto)
    {
        $this->authorize('update', $producto);
        
        return view('productos.edit', compact('producto'));
    }

    public function update(ProductoRequest $request, Producto $producto)
    {
        $this->authorize('update', $producto);
        
        $data = [
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio_base' => $request->precio_base,
            'activo' => $request->has('activo') ? 1 : 0,
        ];

        $producto->update($data);

        return redirect()->route('productos.index')
            ->with('success', 'Producto actualizado exitosamente.');
    }

    public function destroy(Producto $producto)
    {
        $this->authorize('delete', $producto);
        
        if ($producto->repartos()->count() > 0) {
            return redirect()->route('productos.index')
                ->with('error', 'No se puede eliminar este producto porque tiene repartos asociados.');
        }
        
        $producto->delete();

        return redirect()->route('productos.index')
            ->with('success', 'Producto eliminado exitosamente.');
    }
}
