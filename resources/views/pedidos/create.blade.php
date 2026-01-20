@extends('layouts.app')

@section('title', 'Crear Pedido - RepartoAgua')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-cart-plus"></i> Crear Nuevo Pedido</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('pedidos.store') }}" method="POST" id="formPedido">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="cliente_id" class="form-label">Cliente <span class="text-danger">*</span></label>
                                <select class="form-select @error('cliente_id') is-invalid @enderror" 
                                        id="cliente_id" name="cliente_id" required>
                                    <option value="">Seleccionar cliente...</option>
                                    @foreach($clientes ?? [] as $cliente)
                                        <option value="{{ $cliente->id }}" {{ old('cliente_id') == $cliente->id ? 'selected' : '' }}>
                                            {{ $cliente->nombre }} {{ $cliente->apellido }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('cliente_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="producto_id" class="form-label">Producto <span class="text-danger">*</span></label>
                                <select class="form-select @error('producto_id') is-invalid @enderror" 
                                        id="producto_id" name="producto_id" required>
                                    <option value="">Seleccionar producto...</option>
                                    @foreach($productos ?? [] as $producto)
                                        <option value="{{ $producto->id }}" data-precio="{{ $producto->precio }}" 
                                            {{ old('producto_id') == $producto->id ? 'selected' : '' }}>
                                            {{ $producto->nombre }} - ${{ number_format($producto->precio, 2) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('producto_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="cantidad" class="form-label">Cantidad <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('cantidad') is-invalid @enderror" 
                                       id="cantidad" name="cantidad" value="{{ old('cantidad', 1) }}" min="1" required>
                                @error('cantidad')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="precio_unitario" class="form-label">Precio Unitario <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" class="form-control @error('precio_unitario') is-invalid @enderror" 
                                           id="precio_unitario" name="precio_unitario" value="{{ old('precio_unitario') }}" required>
                                    @error('precio_unitario')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label for="total" class="form-label">Total</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" class="form-control" 
                                           id="total" name="total" value="{{ old('total', 0) }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="ruta_id" class="form-label">Ruta (Opcional)</label>
                                <select class="form-select @error('ruta_id') is-invalid @enderror" 
                                        id="ruta_id" name="ruta_id">
                                    <option value="">Sin asignar...</option>
                                    @foreach($rutas ?? [] as $ruta)
                                        <option value="{{ $ruta->id }}" {{ old('ruta_id') == $ruta->id ? 'selected' : '' }}>
                                            {{ $ruta->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('ruta_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="estado" class="form-label">Estado <span class="text-danger">*</span></label>
                                <select class="form-select @error('estado') is-invalid @enderror" 
                                        id="estado" name="estado" required>
                                    <option value="">Seleccionar estado...</option>
                                    <option value="pendiente" {{ old('estado', 'pendiente') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                    <option value="en_proceso" {{ old('estado') == 'en_proceso' ? 'selected' : '' }}>En Proceso</option>
                                    <option value="entregado" {{ old('estado') == 'entregado' ? 'selected' : '' }}>Entregado</option>
                                    <option value="cancelado" {{ old('estado') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                                </select>
                                @error('estado')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="fecha_pedido" class="form-label">Fecha Pedido <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('fecha_pedido') is-invalid @enderror" 
                                       id="fecha_pedido" name="fecha_pedido" value="{{ old('fecha_pedido', date('Y-m-d')) }}" required>
                                @error('fecha_pedido')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="fecha_entrega" class="form-label">Fecha Entrega</label>
                                <input type="date" class="form-control @error('fecha_entrega') is-invalid @enderror" 
                                       id="fecha_entrega" name="fecha_entrega" value="{{ old('fecha_entrega') }}">
                                @error('fecha_entrega')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notas" class="form-label">Notas</label>
                            <textarea class="form-control @error('notas') is-invalid @enderror" 
                                      id="notas" name="notas" rows="3">{{ old('notas') }}</textarea>
                            @error('notas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('pedidos.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Guardar Pedido
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const productoSelect = document.getElementById('producto_id');
    const cantidadInput = document.getElementById('cantidad');
    const precioUnitarioInput = document.getElementById('precio_unitario');
    const totalInput = document.getElementById('total');

    // Actualizar precio cuando se selecciona un producto
    productoSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const precio = selectedOption.getAttribute('data-precio');
        if (precio) {
            precioUnitarioInput.value = parseFloat(precio).toFixed(2);
            calcularTotal();
        }
    });

    // Calcular total cuando cambia cantidad o precio
    cantidadInput.addEventListener('input', calcularTotal);
    precioUnitarioInput.addEventListener('input', calcularTotal);

    function calcularTotal() {
        const cantidad = parseFloat(cantidadInput.value) || 0;
        const precioUnitario = parseFloat(precioUnitarioInput.value) || 0;
        const total = cantidad * precioUnitario;
        totalInput.value = total.toFixed(2);
    }
});
</script>
@endsection
