@extends('layouts.app')

@section('title', 'Rutas - RepartoAgua')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-map"></i> Rutas</h1>
        <a href="{{ route('rutas.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Nueva Ruta
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
                            <th>Repartidor</th>
                            <th>Fecha</th>
                            <th>Hora Inicio</th>
                            <th>Hora Fin</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rutas as $ruta)
                        <tr>
                            <td>{{ $ruta->id }}</td>
                            <td>{{ $ruta->nombre }}</td>
                            <td>{{ $ruta->repartidor->nombre ?? 'N/A' }} {{ $ruta->repartidor->apellido ?? '' }}</td>
                            <td>{{ $ruta->fecha ? \Carbon\Carbon::parse($ruta->fecha)->format('d/m/Y') : 'N/A' }}</td>
                            <td>{{ $ruta->hora_inicio ? \Carbon\Carbon::parse($ruta->hora_inicio)->format('H:i') : 'N/A' }}</td>
                            <td>{{ $ruta->hora_fin ? \Carbon\Carbon::parse($ruta->hora_fin)->format('H:i') : 'N/A' }}</td>
                            <td>
                                <span class="badge 
                                    @if($ruta->estado == 'pendiente') bg-secondary
                                    @elseif($ruta->estado == 'en_progreso') bg-primary
                                    @elseif($ruta->estado == 'completada') bg-success
                                    @else bg-danger
                                    @endif">
                                    {{ ucfirst(str_replace('_', ' ', $ruta->estado)) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('rutas.edit', $ruta->id) }}" class="btn btn-sm btn-warning" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('rutas.destroy', $ruta->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar esta ruta?')">
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
                            <td colspan="8" class="text-center text-muted">No hay rutas registradas</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $rutas->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
