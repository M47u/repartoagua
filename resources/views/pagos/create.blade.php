@extends('layouts.app')

@section('title', 'Registrar Pago')

@section('breadcrumbs')
    <a href="{{ route('pagos.index') }}" class="text-slate-400 hover:text-slate-600">Pagos</a>
    <span class="text-slate-400">/</span>
    <span class="text-slate-600">Registrar</span>
@endsection

@section('content')
<div class="max-w-3xl mx-auto">
    <x-card>
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-slate-900">Registrar Nuevo Pago</h2>
            <p class="text-slate-600 mt-1">Registra un pago recibido de un cliente</p>
        </div>

        <form action="{{ route('pagos.store') }}" method="POST" x-data="pagoForm()">
            @csrf

            <div class="space-y-6">
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
                                x-model="clienteId"
                                @change="cargarSaldoCliente"
                            >
                                <option value="">Seleccionar cliente</option>
                                @foreach($clientes as $cliente)
                                <option value="{{ $cliente->id }}" {{ old('cliente_id') == $cliente->id ? 'selected' : '' }}>
                                    {{ $cliente->nombre }} {{ $cliente->apellido }} - {{ $cliente->telefono ?? $cliente->email }}
                                </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('cliente_id')" class="mt-2" />
                            
                            <!-- Información del saldo del cliente -->
                            <div x-show="saldoCliente !== null" class="mt-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-blue-900">Saldo actual del cliente:</span>
                                    <span class="text-lg font-bold" :class="saldoCliente > 0 ? 'text-green-600' : 'text-red-600'" x-text="'$' + parseFloat(saldoCliente).toFixed(2)"></span>
                                </div>
                                <p class="text-xs text-blue-700 mt-1" x-show="saldoCliente < 0">
                                    El cliente tiene deuda pendiente
                                </p>
                            </div>
                        </div>

                        <div>
                            <x-input-label for="monto" value="Monto *" />
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 font-semibold">$</span>
                                <x-text-input 
                                    type="number" 
                                    id="monto" 
                                    name="monto" 
                                    :value="old('monto')" 
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
                                <option value="efectivo" {{ old('metodo_pago') === 'efectivo' ? 'selected' : '' }}>💵 Efectivo</option>
                                <option value="transferencia" {{ old('metodo_pago') === 'transferencia' ? 'selected' : '' }}>🏦 Transferencia</option>
                                <option value="cuenta_corriente" {{ old('metodo_pago') === 'cuenta_corriente' ? 'selected' : '' }}>📋 Cuenta Corriente</option>
                            </select>
                            <x-input-error :messages="$errors->get('metodo_pago')" class="mt-2" />
                        </div>

                        <div class="md:col-span-2">
                            <x-input-label for="referencia" value="Referencia / Número de Comprobante" />
                            <x-text-input 
                                type="text" 
                                id="referencia" 
                                name="referencia" 
                                :value="old('referencia')" 
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
                            >{{ old('notas') }}</textarea>
                            <x-input-error :messages="$errors->get('notas')" class="mt-2" />
                        </div>
                    </div>
                </div>

                <!-- Información Adicional -->
                <div class="bg-slate-50 p-4 rounded-lg">
                    <h4 class="font-semibold text-slate-900 mb-2">Información Importante</h4>
                    <ul class="text-sm text-slate-600 space-y-1">
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-indigo-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>La fecha del pago se registrará automáticamente con la fecha y hora actual</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-indigo-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>El saldo del cliente se actualizará automáticamente al registrar el pago</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-indigo-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Se creará un movimiento en la cuenta del cliente de forma automática</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Botones de acción -->
            <div class="flex items-center justify-end gap-4 mt-8 pt-6 border-t border-slate-200">
                <a href="{{ route('pagos.index') }}">
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
                    Registrar Pago
                </x-button>
            </div>
        </form>
    </x-card>
</div>

<script>
function pagoForm() {
    return {
        clienteId: '{{ old('cliente_id') }}',
        saldoCliente: null,
        
        async cargarSaldoCliente() {
            if (!this.clienteId) {
                this.saldoCliente = null;
                return;
            }
            
            try {
                const response = await fetch(`/api/clientes/${this.clienteId}/saldo`);
                if (response.ok) {
                    const data = await response.json();
                    this.saldoCliente = data.saldo || 0;
                }
            } catch (error) {
                console.error('Error al cargar saldo:', error);
            }
        },
        
        init() {
            if (this.clienteId) {
                this.cargarSaldoCliente();
            }
        }
    }
}
</script>
@endsection
