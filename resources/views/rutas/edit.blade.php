@extends('layouts.app')

@section('title', 'Editar Ruta - RepartoAgua')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-warning">
                    <h5 class="mb-0"><i class="bi bi-pencil"></i> Editar Ruta</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('rutas.update', $ruta->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre de la Ruta <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                                   id="nombre" name="nombre" value="{{ old('nombre', $ruta->nombre) }}" 
                                   placeholder="Ej: Ruta Centro - Mañana" required>
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                                      id="descripcion" name="descripcion" rows="3">{{ old('descripcion', $ruta->descripcion) }}</textarea>
                            @error('descripcion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="repartidor_id" class="form-label">Repartidor <span class="text-danger">*</span></label>
                            <select class="form-select @error('repartidor_id') is-invalid @enderror" 
                                    id="repartidor_id" name="repartidor_id" required>
                                <option value="">Seleccionar repartidor...</option>
                                @foreach($repartidores ?? [] as $repartidor)
                                    <option value="{{ $repartidor->id }}" 
                                        {{ old('repartidor_id', $ruta->repartidor_id) == $repartidor->id ? 'selected' : '' }}>
                                        {{ $repartidor->nombre }} {{ $repartidor->apellido }}
                                    </option>
                                @endforeach
                            </select>
                            @error('repartidor_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="fecha" class="form-label">Fecha <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('fecha') is-invalid @enderror" 
                                       id="fecha" name="fecha" value="{{ old('fecha', $ruta->fecha) }}" required>
                                @error('fecha')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="hora_inicio" class="form-label">Hora Inicio</label>
                                <input type="time" class="form-control @error('hora_inicio') is-invalid @enderror" 
                                       id="hora_inicio" name="hora_inicio" value="{{ old('hora_inicio', $ruta->hora_inicio ? \Carbon\Carbon::parse($ruta->hora_inicio)->format('H:i') : '') }}">
                                @error('hora_inicio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="hora_fin" class="form-label">Hora Fin</label>
                                <input type="time" class="form-control @error('hora_fin') is-invalid @enderror" 
                                       id="hora_fin" name="hora_fin" value="{{ old('hora_fin', $ruta->hora_fin ? \Carbon\Carbon::parse($ruta->hora_fin)->format('H:i') : '') }}">
                                @error('hora_fin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="estado" class="form-label">Estado <span class="text-danger">*</span></label>
                            <select class="form-select @error('estado') is-invalid @enderror" 
                                    id="estado" name="estado" required>
                                <option value="">Seleccionar estado...</option>
                                <option value="pendiente" {{ old('estado', $ruta->estado) == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                <option value="en_progreso" {{ old('estado', $ruta->estado) == 'en_progreso' ? 'selected' : '' }}>En Progreso</option>
                                <option value="completada" {{ old('estado', $ruta->estado) == 'completada' ? 'selected' : '' }}>Completada</option>
                                <option value="cancelada" {{ old('estado', $ruta->estado) == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                            </select>
                            @error('estado')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('rutas.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Actualizar Ruta
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
