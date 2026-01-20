@extends('layouts.app')

@section('title', 'Pedidos - RepartoAgua')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-cart"></i> Pedidos</h1>
        <a href="{{ route('pedidos.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Nuevo Pedido
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio Unit.</th>
                            <th>Total</th>
                            <th>Fecha Pedido</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pedidos as $pedido)
                        <tr>
                            <td>{{ $pedido->id }}</td>
                            <td>{{ $pedido->cliente->nombre ?? 'N/A' }} {{ $pedido->cliente->apellido ?? '' }}</td>
                            <td>{{ $pedido->producto->nombre ?? 'N/A' }}</td>
                            <td>{{ $pedido->cantidad }}</td>
                            <td>${{ number_format($pedido->precio_unitario, 2) }}</td>
                            <td>${{ number_format($pedido->total, 2) }}</td>
                            <td>{{ $pedido->fecha_pedido ? \Carbon\Carbon::parse($pedido->fecha_pedido)->format('d/m/Y') : 'N/A' }}</td>
                            <td>
                                <span class="badge 
                                    @if($pedido->estado == 'pendiente') bg-warning
                                    @elseif($pedido->estado == 'en_proceso') bg-info
                                    @elseif($pedido->estado == 'entregado') bg-success
                                    @else bg-danger
                                    @endif">
                                    {{ ucfirst(str_replace('_', ' ', $pedido->estado)) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('pedidos.edit', $pedido->id) }}" class="btn btn-sm btn-warning" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('pedidos.destroy', $pedido->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar este pedido?')">
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
                            <td colspan="9" class="text-center text-muted">No hay pedidos registrados</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $pedidos->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
