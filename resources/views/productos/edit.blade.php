@extends('layouts.app')

@section('title', 'Editar Producto - RepartoAgua')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-warning">
                    <h5 class="mb-0"><i class="bi bi-pencil"></i> Editar Producto</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('productos.update', $producto->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre del Producto <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                                   id="nombre" name="nombre" value="{{ old('nombre', $producto->nombre) }}" required>
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                                      id="descripcion" name="descripcion" rows="3">{{ old('descripcion', $producto->descripcion) }}</textarea>
                            @error('descripcion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="precio" class="form-label">Precio <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" class="form-control @error('precio') is-invalid @enderror" 
                                           id="precio" name="precio" value="{{ old('precio', $producto->precio) }}" required>
                                    @error('precio')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label for="stock" class="form-label">Stock <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('stock') is-invalid @enderror" 
                                       id="stock" name="stock" value="{{ old('stock', $producto->stock) }}" required>
                                @error('stock')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="unidad" class="form-label">Unidad <span class="text-danger">*</span></label>
                                <select class="form-select @error('unidad') is-invalid @enderror" 
                                        id="unidad" name="unidad" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="pieza" {{ old('unidad', $producto->unidad) == 'pieza' ? 'selected' : '' }}>Pieza</option>
                                    <option value="litro" {{ old('unidad', $producto->unidad) == 'litro' ? 'selected' : '' }}>Litro</option>
                                    <option value="garrafon" {{ old('unidad', $producto->unidad) == 'garrafon' ? 'selected' : '' }}>Garrafón</option>
                                </select>
                                @error('unidad')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input @error('activo') is-invalid @enderror" type="checkbox" 
                                       id="activo" name="activo" value="1" {{ old('activo', $producto->activo) ? 'checked' : '' }}>
                                <label class="form-check-label" for="activo">
                                    Producto Activo
                                </label>
                                @error('activo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('productos.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Actualizar Producto
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
