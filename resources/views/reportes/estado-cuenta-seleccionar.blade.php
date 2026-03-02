@extends('layouts.app')

@section('title', 'Estado de Cuenta - Seleccionar Cliente')

@section('breadcrumbs')
    <a href="{{ route('reportes.index') }}" class="text-slate-400 hover:text-slate-600">Reportes</a>
    <span class="text-slate-400 mx-2">/</span>
    <span class="text-slate-600">Estado de Cuenta</span>
@endsection

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="text-center">
        <h1 class="text-3xl font-bold text-slate-900">📄 Estado de Cuenta por Cliente</h1>
        <p class="text-slate-500 mt-2">Selecciona un cliente para ver su estado de cuenta detallado</p>
    </div>

    <!-- Selector de Cliente -->
    <x-card>
        <form method="GET" action="{{ route('reportes.estado-cuenta-cliente') }}" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Cliente *</label>
                <select name="cliente_id" required 
                        class="w-full rounded-lg border-slate-300 focus:border-purple-500 focus:ring focus:ring-purple-200">
                    <option value="">Selecciona un cliente...</option>
                    @foreach(\App\Models\Cliente::activos()->orderBy('nombre')->get() as $cliente)
                        <option value="{{ $cliente->id }}">
                            {{ $cliente->nombre }} {{ $cliente->apellido ?? '' }}
                            @if($cliente->saldo_actual > 0)
                                - Deuda: ${{ number_format($cliente->saldo_actual, 2) }}
                            @endif
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Desde</label>
                    <input type="date" name="fecha_inicio" value="{{ now()->startOfMonth()->format('Y-m-d') }}" 
                           class="w-full rounded-lg border-slate-300 focus:border-purple-500 focus:ring focus:ring-purple-200">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Hasta</label>
                    <input type="date" name="fecha_fin" value="{{ now()->format('Y-m-d') }}" 
                           class="w-full rounded-lg border-slate-300 focus:border-purple-500 focus:ring focus:ring-purple-200">
                </div>
            </div>

            <button type="submit" class="w-full px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors font-medium">
                Ver Estado de Cuenta
            </button>
        </form>
    </x-card>

    <!-- Info -->
    <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
        <div class="flex gap-3">
            <svg class="w-5 h-5 text-purple-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div class="text-sm text-purple-800">
                <p class="font-semibold mb-1">Información del Estado de Cuenta:</p>
                <ul class="list-disc list-inside space-y-1 text-purple-700">
                    <li>Ver todos los movimientos de cuenta del cliente</li>
                    <li>Débitos (repartos) y créditos (pagos)</li>
                    <li>Saldo actual y evolución en el tiempo</li>
                    <li>Exportable a PDF para enviar al cliente</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
