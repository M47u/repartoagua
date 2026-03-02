@extends('layouts.app')

@section('title', 'Cuentas por Cobrar')

@section('breadcrumbs')
    <a href="{{ route('reportes.index') }}" class="text-slate-400 hover:text-slate-600">Reportes</a>
    <span class="text-slate-400 mx-2">/</span>
    <span class="text-slate-600">Cuentas por Cobrar</span>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">⏰ Cuentas por Cobrar</h1>
            <p class="text-slate-500 mt-1">Gestión de cartera y seguimiento de cobros</p>
        </div>
        <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-300 rounded-lg text-slate-700 hover:bg-slate-50">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
            </svg>
            Imprimir
        </button>
    </div>

    <!-- Filtros -->
    <x-card title="Filtros">
        <form method="GET" action="{{ route('reportes.cuentas-por-cobrar') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Ordenar por</label>
                <select name="ordenar" class="w-full rounded-lg border-slate-300 focus:border-amber-500 focus:ring focus:ring-amber-200">
                    <option value="saldo_desc" {{ $ordenar === 'saldo_desc' ? 'selected' : '' }}>Mayor deuda primero</option>
                    <option value="saldo_asc" {{ $ordenar === 'saldo_asc' ? 'selected' : '' }}>Menor deuda primero</option>
                    <option value="nombre" {{ $ordenar === 'nombre' ? 'selected' : '' }}>Nombre (A-Z)</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Filtro</label>
                <select name="filtro_deuda" class="w-full rounded-lg border-slate-300 focus:border-amber-500 focus:ring focus:ring-amber-200">
                    <option value="todas" {{ $filtroDeuda === 'todas' ? 'selected' : '' }}>Todos los clientes</option>
                    <option value="con_deuda" {{ $filtroDeuda === 'con_deuda' ? 'selected' : '' }}>Solo con deuda</option>
                    <option value="al_dia" {{ $filtroDeuda === 'al_dia' ? 'selected' : '' }}>Solo al día</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors">
                    Aplicar
                </button>
            </div>
        </form>
    </x-card>

    <!-- KPIs -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <x-stat-card 
            title="Total a Cobrar" 
            :value="'$' . number_format($totalDeuda, 2)" 
            color="amber"
            subtitle="cartera pendiente">
            <x-slot:icon>
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </x-slot:icon>
        </x-stat-card>

        <x-stat-card 
            title="Clientes con Deuda" 
            :value="$clientesConDeuda" 
            color="red"
            subtitle="requieren seguimiento">
            <x-slot:icon>
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </x-slot:icon>
        </x-stat-card>

        <x-stat-card 
            title="Clientes al Día" 
            :value="$clientesAlDia" 
            color="emerald"
            subtitle="sin saldo pendiente">
            <x-slot:icon>
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </x-slot:icon>
        </x-stat-card>
    </div>

    <!-- Lista de Clientes -->
    <x-card title="Detalle de Cuentas" :padding="false">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Cliente</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Tipo</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Contacto</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-slate-600 uppercase tracking-wider">Saldo</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-slate-600 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-slate-600 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($clientes->filter(fn($c) => $c->saldo_calculado > 0) as $cliente)
                        <tr class="hover:bg-slate-50 {{ $cliente->saldo_calculado > 500 ? 'bg-red-50' : '' }}">
                            <td class="px-6 py-4">
                                <div>
                                    <p class="text-sm font-medium text-slate-900">{{ $cliente->nombre }} {{ $cliente->apellido ?? '' }}</p>
                                    @if($cliente->direccion)
                                        <p class="text-xs text-slate-500">{{ $cliente->direccion }}</p>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <x-badge :color="$cliente->tipo_cliente === 'empresa' ? 'purple' : ($cliente->tipo_cliente === 'comercio' ? 'sky' : 'slate')">
                                    {{ ucfirst($cliente->tipo_cliente) }}
                                </x-badge>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600">
                                @if($cliente->telefono)
                                    <div class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                        </svg>
                                        {{ $cliente->telefono }}
                                    </div>
                                @else
                                    <span class="text-slate-400">Sin teléfono</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="text-sm font-semibold {{ $cliente->saldo_calculado > 0 ? 'text-red-600' : 'text-emerald-600' }}">
                                    ${{ number_format($cliente->saldo_calculado, 2) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($cliente->saldo_calculado > 0)
                                    @if($cliente->saldo_calculado > 500)
                                        <x-badge color="red">Atención Urgente</x-badge>
                                    @elseif($cliente->saldo_calculado > 200)
                                        <x-badge color="amber">Deuda Alta</x-badge>
                                    @else
                                        <x-badge color="yellow">Deuda Baja</x-badge>
                                    @endif
                                @else
                                    <x-badge color="emerald">Al Día</x-badge>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('reportes.estado-cuenta-cliente', $cliente->id) }}" 
                                       class="text-purple-600 hover:text-purple-700"
                                       title="Ver estado de cuenta">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </a>
                                    @if($cliente->telefono && $cliente->saldo_calculado > 0)
                                        <a href="tel:{{ $cliente->telefono }}" 
                                           class="text-sky-600 hover:text-sky-700"
                                           title="Llamar">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-slate-500">
                                No hay clientes que coincidan con los filtros
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if($clientes->count() > 0 && $totalDeuda > 0)
                <tfoot class="bg-slate-50 border-t-2 border-slate-300">
                    <tr>
                        <td colspan="3" class="px-6 py-4 font-bold text-slate-900">TOTAL CARTERA</td>
                        <td class="px-6 py-4 text-right font-bold text-red-600">${{ number_format($totalDeuda, 2) }}</td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </x-card>

    <!-- Alertas de Cobranza -->
    @if($clientesConDeuda > 0)
    <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
        <div class="flex gap-3">
            <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            <div class="text-sm text-amber-800">
                <p class="font-semibold">Acciones Recomendadas</p>
                <ul class="list-disc list-inside mt-2 space-y-1">
                    <li>Contactar clientes con deuda alta (>$500)</li>
                    <li>Enviar recordatorios de pago</li>
                    <li>Considerar suspensión temporal de servicio en casos críticos</li>
                </ul>
            </div>
        </div>
    </div>
    @endif

</div>

@push('styles')
<style>
    @media print {
        nav, aside, .no-print, button, form {
            display: none !important;
        }
    }
</style>
@endpush
@endsection
