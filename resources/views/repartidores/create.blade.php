@extends('layouts.app')

@section('title', 'Crear Repartidor - RepartoAgua')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-truck"></i> Crear Nuevo Repartidor</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('repartidores.store') }}" method="POST">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                                       id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                                @error('nombre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="apellido" class="form-label">Apellido <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('apellido') is-invalid @enderror" 
                                       id="apellido" name="apellido" value="{{ old('apellido') }}" required>
                                @error('apellido')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="telefono" class="form-label">Teléfono <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('telefono') is-invalid @enderror" 
                                   id="telefono" name="telefono" value="{{ old('telefono') }}" required>
                            @error('telefono')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="licencia" class="form-label">Licencia <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('licencia') is-invalid @enderror" 
                                   id="licencia" name="licencia" value="{{ old('licencia') }}" required>
                            @error('licencia')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="vehiculo" class="form-label">Vehículo <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('vehiculo') is-invalid @enderror" 
                                   id="vehiculo" name="vehiculo" value="{{ old('vehiculo') }}" 
                                   placeholder="Ej: Camioneta Ford F-150 2020" required>
                            @error('vehiculo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('repartidores.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Guardar Repartidor
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
