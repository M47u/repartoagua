@extends('layouts.app')

@section('title', 'Repartidores - RepartoAgua')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-truck"></i> Repartidores</h1>
        <a href="{{ route('repartidores.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Nuevo Repartidor
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre Completo</th>
                            <th>Teléfono</th>
                            <th>Licencia</th>
                            <th>Vehículo</th>
                            <th>Activo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($repartidores as $repartidor)
                        <tr>
                            <td>{{ $repartidor->id }}</td>
                            <td>{{ $repartidor->nombre }} {{ $repartidor->apellido }}</td>
                            <td>{{ $repartidor->telefono }}</td>
                            <td>{{ $repartidor->licencia }}</td>
                            <td>{{ $repartidor->vehiculo }}</td>
                            <td>
                                @if($repartidor->activo)
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-danger">Inactivo</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('repartidores.edit', $repartidor->id) }}" class="btn btn-sm btn-warning" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('repartidores.destroy', $repartidor->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar este repartidor?')">
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
                            <td colspan="7" class="text-center text-muted">No hay repartidores registrados</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $repartidores->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
