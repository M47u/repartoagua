@extends('layouts.app')

@section('title', 'Bidones Cobrados')

@section('breadcrumbs')
    <a href="{{ route('reportes.index') }}" class="text-slate-400 hover:text-slate-600">Reportes</a>
    <span class="text-slate-400 mx-2">/</span>
    <span class="text-slate-600">Bidones Cobrados</span>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">📦 Bidones Cobrados</h1>
            <p class="text-slate-500 mt-1">Análisis de bidones efectivamente cobrados</p>
        </div>
        <div class="flex gap-2">
            <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-300 rounded-lg text-slate-700 hover:bg-slate-50">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                Imprimir
            </button>
        </div>
    </div>

    <!-- Filtros -->
    <x-card title="Filtros">
        <form method="GET" action="{{ route('reportes.bidones-cobrados') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Fecha Inicio</label>
                <input type="date" name="fecha_inicio" value="{{ $fechaInicio }}" class="w-full rounded-lg border-slate-300 focus:border-emerald-500 focus:ring focus:ring-emerald-200">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Fecha Fin</label>
                <input type="date" name="fecha_fin" value="{{ $fechaFin }}" class="w-full rounded-lg border-slate-300 focus:border-emerald-500 focus:ring focus:ring-emerald-200">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Método de Pago</label>
                <select name="metodo_pago" class="w-full rounded-lg border-slate-300 focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                    <option value="">Todos</option>
                    <option value="efectivo" {{ $metodoPago == 'efectivo' ? 'selected' : '' }}>Efectivo</option>
                    <option value="transferencia" {{ $metodoPago == 'transferencia' ? 'selected' : '' }}>Transferencia</option>
                    <option value="tarjeta" {{ $metodoPago == 'tarjeta' ? 'selected' : '' }}>Tarjeta</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">
                    Aplicar Filtros
                </button>
            </div>
        </form>
    </x-card>

    <!-- KPIs -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <x-stat-card 
            title="Total Bidones Cobrados" 
            :value="number_format($totalBidones, 0, ',', '.')" 
            color="emerald"
            subtitle="en el período">
            <x-slot:icon>
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </x-slot:icon>
        </x-stat-card>

        <x-stat-card 
            title="Total Cobrado" 
            :value="'$' . number_format($totalMonto, 2, ',', '.')" 
            color="sky"
            subtitle="ingresos del período">
            <x-slot:icon>
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </x-slot:icon>
        </x-stat-card>

        <x-stat-card 
            title="Bidones Entregados" 
            :value="number_format($bidonesEntregados, 0, ',', '.')" 
            color="purple"
            subtitle="en el período">
            <x-slot:icon>
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path>
                </svg>
            </x-slot:icon>
        </x-stat-card>

        <x-stat-card 
            title="Tasa de Cobro" 
            :value="number_format($tasaCobro, 1) . '%'" 
            :color="$tasaCobro >= 85 ? 'emerald' : ($tasaCobro >= 70 ? 'amber' : 'red')"
            :subtitle="$tasaCobro >= 85 ? '¡Excelente!' : ($tasaCobro >= 70 ? 'Aceptable' : 'Necesita atención')">
            <x-slot:icon>
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </x-slot:icon>
        </x-stat-card>
    </div>

    <!-- Gráficos y Tablas -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Por Producto -->
        <x-card title="Bidones Cobrados por Producto" :padding="false">
            <div class="p-6 space-y-4">
                @forelse($agrupadoPorProducto as $producto => $data)
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-medium text-slate-900">{{ $producto }}</span>
                            <span class="text-sm text-slate-600">{{ number_format($data['cantidad']) }} bidones</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="flex-1 h-3 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-emerald-400 to-emerald-600 rounded-full" 
                                     style="width: {{ $totalBidones > 0 ? ($data['cantidad'] / $totalBidones * 100) : 0 }}%"></div>
                            </div>
                            <span class="text-sm font-semibold text-emerald-600">${{ number_format($data['monto'], 2) }}</span>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-slate-500 py-8">No hay datos para mostrar</p>
                @endforelse
            </div>
        </x-card>

        <!-- Por Método de Pago -->
        <x-card title="Distribución por Método de Pago" :padding="false">
            <div class="p-6 space-y-4">
                @forelse($agrupadoPorMetodo as $metodo => $data)
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-2">
                                @if($metodo === 'efectivo')
                                    <span class="text-2xl">💵</span>
                                @elseif($metodo === 'transferencia')
                                    <span class="text-2xl">🏦</span>
                                @elseif($metodo === 'tarjeta')
                                    <span class="text-2xl">💳</span>
                                @else
                                    <span class="text-2xl">💰</span>
                                @endif
                                <span class="font-medium text-slate-900">{{ ucfirst($metodo) }}</span>
                            </div>
                            <span class="text-sm text-slate-600">{{ number_format($data['cantidad']) }} bidones</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="flex-1 h-3 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-sky-400 to-sky-600 rounded-full" 
                                     style="width: {{ $totalBidones > 0 ? ($data['cantidad'] / $totalBidones * 100) : 0 }}%"></div>
                            </div>
                            <span class="text-sm font-semibold text-sky-600">${{ number_format($data['monto'], 2) }}</span>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-slate-500 py-8">No hay datos para mostrar</p>
                @endforelse
            </div>
        </x-card>

    </div>

    <!-- Top Clientes -->
    <x-card title="Top Clientes con Más Bidones Cobrados" :padding="false">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Cliente</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-slate-600 uppercase tracking-wider">Bidones Cobrados</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-slate-600 uppercase tracking-wider">Monto Total</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-slate-600 uppercase tracking-wider">% del Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @php
                        $topClientes = collect($agrupadoPorCliente)->sortByDesc('cantidad')->take(10);
                    @endphp
                    @forelse($topClientes as $cliente => $data)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ $cliente }}</td>
                            <td class="px-6 py-4 text-sm text-center text-slate-600">{{ number_format($data['cantidad']) }}</td>
                            <td class="px-6 py-4 text-sm text-right font-semibold text-emerald-600">${{ number_format($data['monto'], 2) }}</td>
                            <td class="px-6 py-4 text-sm text-right text-slate-600">{{ $totalBidones > 0 ? number_format($data['cantidad'] / $totalBidones * 100, 1) : 0 }}%</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-slate-500">No hay datos para mostrar</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>

    <!-- Detalle Completo -->
    <x-card title="Detalle de Bidones Cobrados" :padding="false">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Fecha</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Cliente</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Producto</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-slate-600 uppercase tracking-wider">Bidones</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-slate-600 uppercase tracking-wider">Monto</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Método Pago</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Repartidor</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($bidonesDetalle as $detalle)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4 text-sm text-slate-600">{{ \Carbon\Carbon::parse($detalle['fecha'])->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ $detalle['cliente'] }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ $detalle['producto'] }}</td>
                            <td class="px-6 py-4 text-sm text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                    {{ $detalle['cantidad'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-right font-semibold text-emerald-600">${{ number_format($detalle['monto'], 2) }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ ucfirst($detalle['metodo_pago']) }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ $detalle['repartidor'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-slate-500">No hay registros para este período</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>

</div>

@push('styles')
<style>
    @media print {
        nav, aside, .no-print, button {
            display: none !important;
        }
    }
</style>
@endpush
@endsection
