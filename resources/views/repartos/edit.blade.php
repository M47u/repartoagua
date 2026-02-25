@extends('layouts.app')

@section('title', 'Editar Reparto')

@section('breadcrumbs')
    <a href="{{ route('repartos.index') }}" class="text-slate-400 hover:text-slate-600">Repartos</a>
    <span class="text-slate-300 mx-2">/</span>
    <span class="text-slate-700">Editar Reparto #{{ $reparto->id }}</span>
@endsection

@section('content')
<div class="max-w-4xl">
    <x-card>
        <x-slot:header>
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-amber-500 to-amber-600 flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-slate-900">Editar Reparto #{{ $reparto->id }}</h2>
                    <p class="text-slate-500 text-sm">Modifica los detalles de la entrega</p>
                </div>
            </div>
        </x-slot:header>

        <form action="{{ route('repartos.update', $reparto) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Estado Actual -->
            <div class="p-4 bg-slate-50 rounded-lg border border-slate-200">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-sm font-medium text-slate-500">Estado Actual:</span>
                        @if($reparto->estado === 'pendiente')
                            <x-badge color="warning" size="lg" class="ml-2">Pendiente</x-badge>
                        @elseif($reparto->estado === 'entregado')
                            <x-badge color="success" size="lg" class="ml-2">Entregado</x-badge>
                        @else
                            <x-badge color="danger" size="lg" class="ml-2">Cancelado</x-badge>
                        @endif
                    </div>
                    <div class="text-right text-sm">
                        <p class="text-slate-500">Creado {{ $reparto->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>

            <!-- Cliente -->
            <div>
                <x-input-label for="cliente_id" value="Cliente *" />
                <select id="cliente_id" 
                        name="cliente_id" 
                        required
                        class="w-full mt-1 rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500 @error('cliente_id') border-red-500 @enderror">
                    <option value="">Seleccionar cliente</option>
                    @foreach($clientes as $cliente)
                        <option value="{{ $cliente->id }}" {{ old('cliente_id', $reparto->cliente_id) == $cliente->id ? 'selected' : '' }}>
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
                        required
                        class="w-full mt-1 rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500 @error('producto_id') border-red-500 @enderror">
                    <option value="">Seleccionar producto</option>
                    @foreach($productos as $producto)
                        <option value="{{ $producto->id }}" {{ old('producto_id', $reparto->producto_id) == $producto->id ? 'selected' : '' }}>
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
                        value="{{ old('cantidad', $reparto->cantidad) }}"
                        required
                        class="mt-1 w-full" />
                    <x-input-error :messages="$errors->get('cantidad')" class="mt-2" />
                </div>

                <div>
                    <x-input-label value="Precio Unitario" />
                    <div class="mt-1 px-4 py-2 bg-slate-100 rounded-lg border border-slate-300">
                        <span class="text-slate-900 font-semibold">${{ number_format($reparto->precio_unitario, 2) }}</span>
                    </div>
                    <p class="mt-1 text-xs text-slate-500">Precio original del reparto</p>
                </div>

                <div>
                    <x-input-label value="Total Original" />
                    <div class="mt-1 px-4 py-2 bg-emerald-50 rounded-lg border-2 border-emerald-200">
                        <span class="text-emerald-700 font-bold text-lg">${{ number_format($reparto->total, 2) }}</span>
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
                        <option value="{{ $repartidor->id }}" {{ old('repartidor_id', $reparto->repartidor_id) == $repartidor->id ? 'selected' : '' }}>
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
                    value="{{ old('fecha_programada', $reparto->fecha_programada) }}"
                    required
                    class="mt-1 w-full" />
                <x-input-error :messages="$errors->get('fecha_programada')" class="mt-2" />
            </div>

            <!-- Estado -->
            <div>
                <x-input-label for="estado" value="Estado *" />
                <select id="estado" 
                        name="estado" 
                        required
                        class="w-full mt-1 rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500 @error('estado') border-red-500 @enderror">
                    <option value="pendiente" {{ old('estado', $reparto->estado) === 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="entregado" {{ old('estado', $reparto->estado) === 'entregado' ? 'selected' : '' }}>Entregado</option>
                    <option value="cancelado" {{ old('estado', $reparto->estado) === 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                </select>
                <x-input-error :messages="$errors->get('estado')" class="mt-2" />
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
                    placeholder="Instrucciones especiales, detalles de entrega, etc.">{{ old('notas', $reparto->notas) }}</textarea>
                <x-input-error :messages="$errors->get('notas')" class="mt-2" />
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
                    Guardar Cambios
                </x-button>
            </div>
        </form>
    </x-card>
</div>
@endsection
