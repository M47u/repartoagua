@extends('layouts.app')

@section('title', 'Editar Pago')

@section('breadcrumbs')
    <a href="{{ route('pagos.index') }}" class="text-slate-400 hover:text-slate-600">Pagos</a>
    <span class="text-slate-400">/</span>
    <span class="text-slate-600">#{{ $pago->id }}</span>
@endsection

@section('content')
<div class="max-w-3xl mx-auto">
    <x-card>
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-slate-900">Editar Pago #{{ $pago->id }}</h2>
            <p class="text-slate-600 mt-1">Modifica los datos del pago registrado</p>
        </div>

        <form action="{{ route('pagos.update', $pago) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <!-- Alerta de información -->
                <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                    <div class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-amber-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <div>
                            <h4 class="font-semibold text-amber-900">Precaución al editar</h4>
                            <p class="text-sm text-amber-700 mt-1">Cambiar el monto o cliente afectará el saldo y movimientos contables. Verifica que los cambios sean correctos.</p>
                        </div>
                    </div>
                </div>

                <!-- Información del Pago -->
                <div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Datos del Pago
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <x-input-label for="cliente_id" value="Cliente *" />
                            <select 
                                name="cliente_id" 
                                id="cliente_id" 
                                class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500" 
                                required
                            >
                                <option value="">Seleccionar cliente</option>
                                @foreach($clientes as $cliente)
                                <option value="{{ $cliente->id }}" {{ old('cliente_id', $pago->cliente_id) == $cliente->id ? 'selected' : '' }}>
                                    {{ $cliente->nombre }} {{ $cliente->apellido }} - {{ $cliente->telefono ?? $cliente->email }}
                                </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('cliente_id')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="monto" value="Monto *" />
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 font-semibold">$</span>
                                <x-text-input 
                                    type="number" 
                                    id="monto" 
                                    name="monto" 
                                    :value="old('monto', $pago->monto)" 
                                    required 
                                    step="0.01" 
                                    min="0.01"
                                    class="pl-8"
                                    placeholder="0.00"
                                />
                            </div>
                            <x-input-error :messages="$errors->get('monto')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="metodo_pago" value="Método de Pago *" />
                            <select name="metodo_pago" id="metodo_pago" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="">Seleccionar método</option>
                                <option value="efectivo" {{ old('metodo_pago', $pago->metodo_pago) === 'efectivo' ? 'selected' : '' }}>💵 Efectivo</option>
                                <option value="transferencia" {{ old('metodo_pago', $pago->metodo_pago) === 'transferencia' ? 'selected' : '' }}>🏦 Transferencia</option>
                                <option value="cuenta_corriente" {{ old('metodo_pago', $pago->metodo_pago) === 'cuenta_corriente' ? 'selected' : '' }}>📋 Cuenta Corriente</option>
                            </select>
                            <x-input-error :messages="$errors->get('metodo_pago')" class="mt-2" />
                        </div>

                        <div class="md:col-span-2">
                            <x-input-label for="referencia" value="Referencia / Número de Comprobante" />
                            <x-text-input 
                                type="text" 
                                id="referencia" 
                                name="referencia" 
                                :value="old('referencia', $pago->referencia)" 
                                placeholder="Ej: Transferencia #12345, Recibo #001"
                            />
                            <x-input-error :messages="$errors->get('referencia')" class="mt-2" />
                            <p class="text-xs text-slate-500 mt-1">Número de referencia, comprobante o documento del pago</p>
                        </div>

                        <div class="md:col-span-2">
                            <x-input-label for="notas" value="Notas / Observaciones" />
                            <textarea 
                                id="notas" 
                                name="notas" 
                                rows="3" 
                                class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Detalles adicionales sobre el pago..."
                            >{{ old('notas', $pago->notas) }}</textarea>
                            <x-input-error :messages="$errors->get('notas')" class="mt-2" />
                        </div>
                    </div>
                </div>

                <!-- Información de Auditoría -->
                <div class="bg-slate-50 p-4 rounded-lg">
                    <h4 class="font-semibold text-slate-900 mb-3">Información de Registro</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-slate-500">Fecha de registro:</span>
                            <span class="text-slate-900 font-medium ml-2">{{ $pago->fecha_pago->format('d/m/Y H:i') }}</span>
                        </div>
                        <div>
                            <span class="text-slate-500">Registrado por:</span>
                            <span class="text-slate-900 font-medium ml-2">{{ $pago->registradoPor->nombre_completo ?? 'Sistema' }}</span>
                        </div>
                        @if($pago->created_at != $pago->updated_at)
                        <div class="md:col-span-2">
                            <span class="text-slate-500">Última actualización:</span>
                            <span class="text-slate-900 font-medium ml-2">{{ $pago->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Botones de acción -->
            <div class="flex items-center justify-end gap-4 mt-8 pt-6 border-t border-slate-200">
                <a href="{{ route('pagos.show', $pago) }}">
                    <x-button type="button" variant="outline">
                        Cancelar
                    </x-button>
                </a>
                <x-button type="submit" variant="primary">
                    <x-slot:icon>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </x-slot:icon>
                    Actualizar Pago
                </x-button>
            </div>
        </form>
    </x-card>
</div>
@endsection
