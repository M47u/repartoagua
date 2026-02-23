@extends('layouts.app')

@section('title', 'Pagos')

@section('breadcrumbs')
    <span class="text-slate-600">Pagos</span>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Pagos</h1>
            <p class="text-slate-600 mt-1">Registro de pagos de clientes</p>
        </div>
        @can('create', App\Models\Pago::class)
        <a href="{{ route('pagos.create') }}">
            <x-button variant="primary">
                <x-slot:icon>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </x-slot:icon>
                Registrar Pago
            </x-button>
        </a>
        @endcan
    </div>

    <!-- Filtros -->
    <x-card>
        <form method="GET" action="{{ route('pagos.index') }}" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <x-text-input 
                    type="text" 
                    name="search" 
                    placeholder="Buscar por cliente, referencia..."
                    value="{{ request('search') }}"
                />
            </div>

            <div class="w-full md:w-48">
                <select name="metodo_pago" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Todos los métodos</option>
                    <option value="efectivo" {{ request('metodo_pago') === 'efectivo' ? 'selected' : '' }}>Efectivo</option>
                    <option value="transferencia" {{ request('metodo_pago') === 'transferencia' ? 'selected' : '' }}>Transferencia</option>
                    <option value="cuenta_corriente" {{ request('metodo_pago') === 'cuenta_corriente' ? 'selected' : '' }}>Cuenta Corriente</option>
                </select>
            </div>

            <div class="flex gap-2">
                <x-button type="submit" variant="secondary">Filtrar</x-button>
                <a href="{{ route('pagos.index') }}">
                    <x-button type="button" variant="outline">Limpiar</x-button>
                </a>
            </div>
        </form>
    </x-card>

    <!-- Estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <x-card class="bg-gradient-to-br from-green-500 to-green-600 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Total del Día</p>
                    <p class="text-3xl font-bold mt-1">${{ number_format($pagos->where('fecha_pago', '>=', now()->startOfDay())->sum('monto'), 2) }}</p>
                </div>
                <svg class="w-12 h-12 text-green-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </x-card>

        <x-card class="bg-gradient-to-br from-blue-500 to-blue-600 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total del Mes</p>
                    <p class="text-3xl font-bold mt-1">${{ number_format($pagos->where('fecha_pago', '>=', now()->startOfMonth())->sum('monto'), 2) }}</p>
                </div>
                <svg class="w-12 h-12 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                </svg>
            </div>
        </x-card>

        <x-card class="bg-gradient-to-br from-indigo-500 to-indigo-600 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-indigo-100 text-sm font-medium">Total de Pagos</p>
                    <p class="text-3xl font-bold mt-1">{{ $pagos->total() }}</p>
                </div>
                <svg class="w-12 h-12 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
            </div>
        </x-card>
    </div>

    <!-- Lista de pagos -->
    <x-card>
        @if($pagos->isEmpty())
        <div class="text-center py-12">
            <svg class="w-16 h-16 text-slate-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <p class="text-slate-500 font-medium">No se encontraron pagos</p>
            <p class="text-slate-400 text-sm mt-1">Registra el primer pago de un cliente</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Fecha</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Cliente</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Método</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Referencia</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-slate-600 uppercase">Monto</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-slate-600 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @foreach($pagos as $pago)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-4 text-sm text-slate-600">
                            {{ $pago->fecha_pago->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-4 py-4">
                            <a href="{{ route('clientes.show', $pago->cliente) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">
                                {{ $pago->cliente->nombre }} {{ $pago->cliente->apellido }}
                            </a>
                        </td>
                        <td class="px-4 py-4">
                            @php
                                $metodoBadges = [
                                    'efectivo' => 'bg-green-100 text-green-700',
                                    'transferencia' => 'bg-blue-100 text-blue-700',
                                    'cuenta_corriente' => 'bg-amber-100 text-amber-700',
                                ];
                                $metodoIcons = [
                                    'efectivo' => '💵',
                                    'transferencia' => '🏦',
                                    'cuenta_corriente' => '📋',
                                ];
                            @endphp
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium {{ $metodoBadges[$pago->metodo_pago] ?? 'bg-slate-100 text-slate-700' }}">
                                <span>{{ $metodoIcons[$pago->metodo_pago] ?? '💰' }}</span>
                                {{ ucfirst(str_replace('_', ' ', $pago->metodo_pago)) }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-sm text-slate-600">
                            {{ $pago->referencia ?? '-' }}
                        </td>
                        <td class="px-4 py-4 text-right">
                            <span class="text-lg font-semibold text-green-600">${{ number_format($pago->monto, 2) }}</span>
                        </td>
                        <td class="px-4 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('pagos.show', $pago) }}" class="text-indigo-600 hover:text-indigo-900" title="Ver detalles">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                                @can('update', $pago)
                                <a href="{{ route('pagos.edit', $pago) }}" class="text-slate-600 hover:text-slate-900" title="Editar">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        @if($pagos->hasPages())
        <div class="mt-6">
            {{ $pagos->links() }}
        </div>
        @endif
        @endif
    </x-card>
</div>
@endsection
