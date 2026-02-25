@extends('layouts.app')

@section('title', 'Nuevo Reparto')

@section('breadcrumbs')
    <a href="{{ route('repartos.index') }}" class="text-slate-400 hover:text-slate-600">Repartos</a>
    <span class="text-slate-300 mx-2">/</span>
    <span class="text-slate-700">Nuevo Reparto</span>
@endsection

@section('content')
<div class="max-w-4xl">
    <x-card>
        <x-slot:header>
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-sky-500 to-sky-600 flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-slate-900">Programar Nuevo Reparto</h2>
                    <p class="text-slate-500 text-sm">Registra una nueva entrega programada</p>
                </div>
            </div>
        </x-slot:header>

        <form action="{{ route('repartos.store') }}" method="POST" class="space-y-6" x-data="repartoForm()">
            @csrf

            <!-- Cliente -->
            <div>
                <x-input-label for="cliente_id" value="Cliente *" />
                <select id="cliente_id" 
                        name="cliente_id" 
                        x-model="cliente_id"
                        @change="calcularTotal()"
                        required
                        class="w-full mt-1 rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500 @error('cliente_id') border-red-500 @enderror">
                    <option value="">Seleccionar cliente</option>
                    @foreach($clientes as $cliente)
                        <option value="{{ $cliente->id }}" 
                                data-precio="{{ $cliente->precio_por_bidon }}"
                                {{ old('cliente_id') == $cliente->id ? 'selected' : '' }}>
                            {{ $cliente->nombre }} {{ $cliente->apellido }} - {{ $cliente->direccion }}
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('cliente_id')" class="mt-2" />
            </div>

            <!-- Producto -->
            <div>
                <x-input-label for="producto_id" value="Producto *" />
                <select id="producto_id" 
                        name="producto_id" 
                        x-model="producto_id"
                        @change="calcularTotal()"
                        required
                        class="w-full mt-1 rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500 @error('producto_id') border-red-500 @enderror">
                    <option value="">Seleccionar producto</option>
                    @foreach($productos as $producto)
                        <option value="{{ $producto->id }}" 
                                data-precio="{{ $producto->precio_base }}"
                                {{ old('producto_id') == $producto->id ? 'selected' : '' }}>
                            {{ $producto->nombre }} - ${{ number_format($producto->precio_base, 2) }}
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('producto_id')" class="mt-2" />
            </div>

            <!-- Cantidad y Precio -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <x-input-label for="cantidad" value="Cantidad *" />
                    <x-text-input 
                        id="cantidad" 
                        name="cantidad" 
                        type="number" 
                        min="1"
                        value="{{ old('cantidad', 1) }}"
                        x-model="cantidad"
                        @input="calcularTotal()"
                        required
                        class="mt-1 w-full" />
                    <x-input-error :messages="$errors->get('cantidad')" class="mt-2" />
                </div>

                <div>
                    <x-input-label value="Precio Unitario" />
                    <div class="mt-1 px-4 py-2 bg-slate-100 rounded-lg border border-slate-300">
                        <span class="text-slate-900 font-semibold" x-text="'$' + precioUnitario.toFixed(2)">$0.00</span>
                    </div>
                    <p class="mt-1 text-xs text-slate-500">Precio del cliente o producto</p>
                </div>

                <div>
                    <x-input-label value="Total" />
                    <div class="mt-1 px-4 py-2 bg-emerald-50 rounded-lg border-2 border-emerald-200">
                        <span class="text-emerald-700 font-bold text-lg" x-text="'$' + total.toFixed(2)">$0.00</span>
                    </div>
                </div>
            </div>

            <!-- Repartidor -->
            <div>
                <x-input-label for="repartidor_id" value="Repartidor *" />
                <select id="repartidor_id" 
                        name="repartidor_id" 
                        required
                        class="w-full mt-1 rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500 @error('repartidor_id') border-red-500 @enderror">
                    <option value="">Seleccionar repartidor</option>
                    @foreach($repartidores as $repartidor)
                        <option value="{{ $repartidor->id }}" {{ old('repartidor_id') == $repartidor->id ? 'selected' : '' }}>
                            {{ $repartidor->name }}
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('repartidor_id')" class="mt-2" />
            </div>

            <!-- Fecha Programada -->
            <div>
                <x-input-label for="fecha_programada" value="Fecha Programada *" />
                <x-text-input 
                    id="fecha_programada" 
                    name="fecha_programada" 
                    type="date" 
                    value="{{ old('fecha_programada', date('Y-m-d')) }}"
                    min="{{ date('Y-m-d') }}"
                    required
                    class="mt-1 w-full" />
                <x-input-error :messages="$errors->get('fecha_programada')" class="mt-2" />
            </div>

            <!-- Notas -->
            <div>
                <x-input-label for="notas" value="Notas / Observaciones" />
                <textarea 
                    id="notas" 
                    name="notas" 
                    rows="3"
                    maxlength="500"
                    class="w-full mt-1 rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500 @error('notas') border-red-500 @enderror"
                    placeholder="Instrucciones especiales, detalles de entrega, etc.">{{ old('notas') }}</textarea>
                <x-input-error :messages="$errors->get('notas')" class="mt-2" />
                <p class="mt-1 text-xs text-slate-500">Máximo 500 caracteres</p>
            </div>

            <!-- Botones -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200">
                <a href="{{ route('repartos.index') }}">
                    <x-button variant="secondary" type="button">
                        Cancelar
                    </x-button>
                </a>
                <x-button variant="primary" type="submit">
                    <x-slot:icon>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </x-slot:icon>
                    Programar Reparto
                </x-button>
            </div>
        </form>
    </x-card>
</div>

<script>
function repartoForm() {
    return {
        cliente_id: '{{ old("cliente_id") }}',
        producto_id: '{{ old("producto_id") }}',
        cantidad: {{ old('cantidad', 1) }},
        precioUnitario: 0,
        total: 0,

        init() {
            this.$nextTick(() => {
                this.calcularTotal();
            });
        },

        calcularTotal() {
            // Obtener precio del cliente (si tiene) o del producto
            let precioCliente = null;
            let precioProducto = 0;

            if (this.cliente_id) {
                const clienteOption = document.querySelector(`#cliente_id option[value="${this.cliente_id}"]`);
                if (clienteOption) {
                    const precio = clienteOption.getAttribute('data-precio');
                    if (precio) {
                        precioCliente = parseFloat(precio);
                    }
                }
            }

            if (this.producto_id) {
                const productoOption = document.querySelector(`#producto_id option[value="${this.producto_id}"]`);
                if (productoOption) {
                    precioProducto = parseFloat(productoOption.getAttribute('data-precio')) || 0;
                }
            }

            // Prioridad: precio del cliente, si no hay, usar precio del producto
            this.precioUnitario = precioCliente !== null ? precioCliente : precioProducto;
            this.total = this.precioUnitario * this.cantidad;
        }
    }
}
</script>
@endsection
