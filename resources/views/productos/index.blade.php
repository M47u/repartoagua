@extends('layouts.app')

@section('title', 'Productos - RepartoAgua')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-box-seam"></i> Productos</h1>
        <a href="{{ route('productos.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Nuevo Producto
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th>Unidad</th>
                            <th>Activo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($productos as $producto)
                        <tr>
                            <td>{{ $producto->id }}</td>
                            <td>{{ $producto->nombre }}</td>
                            <td>{{ Str::limit($producto->descripcion, 50) }}</td>
                            <td>${{ number_format($producto->precio, 2) }}</td>
                            <td>
                                <span class="badge bg-{{ $producto->stock > 10 ? 'success' : ($producto->stock > 0 ? 'warning' : 'danger') }}">
                                    {{ $producto->stock }}
                                </span>
                            </td>
                            <td>{{ ucfirst($producto->unidad) }}</td>
                            <td>
                                @if($producto->activo)
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-danger">Inactivo</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('productos.edit', $producto->id) }}" class="btn btn-sm btn-warning" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('productos.destroy', $producto->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar este producto?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Eliminar">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">No hay productos registrados</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $productos->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
