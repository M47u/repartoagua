@extends('layouts.app')

@section('title', 'Repartos por Período')

@section('breadcrumbs')
    <a href="{{ route('reportes.index') }}" class="text-slate-400 hover:text-slate-600">Reportes</a>
    <span class="text-slate-400 mx-2">/</span>
    <span class="text-slate-600">Repartos por Período</span>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">🚚 Repartos por Período</h1>
            <p class="text-slate-500 mt-1">Análisis operativo de entregas realizadas</p>
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
        <form method="GET" action="{{ route('reportes.repartos-por-periodo') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Fecha Inicio</label>
                <input type="date" name="fecha_inicio" value="{{ $fechaInicio }}" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Fecha Fin</label>
                <input type="date" name="fecha_fin" value="{{ $fechaFin }}" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Estado</label>
                <select name="estado" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200">
                    <option value="">Todos</option>
                    <option value="pendiente" {{ $estado === 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="entregado" {{ $estado === 'entregado' ? 'selected' : '' }}>Entregado</option>
                    <option value="cancelado" {{ $estado === 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                    Aplicar
                </button>
            </div>
        </form>
    </x-card>

    <!-- KPIs -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <x-stat-card 
            title="Total Repartos" 
            :value="$totalRepartos" 
            color="indigo"
            subtitle="en el período">
            <x-slot:icon>
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path>
                </svg>
            </x-slot:icon>
        </x-stat-card>

        <x-stat-card 
            title="Total Bidones" 
            :value="number_format($totalBidones, 0, ',', '.')" 
            color="purple"
            subtitle="distribuidos">
            <x-slot:icon>
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
            </x-slot:icon>
        </x-stat-card>

        <x-stat-card 
            title="Valor Total" 
            :value="'$' . number_format($totalValor, 2)" 
            color="emerald"
            subtitle="de repartos">
            <x-slot:icon>
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </x-slot:icon>
        </x-stat-card>

        <x-stat-card 
            title="Tasa Entrega" 
            :value="$totalRepartos > 0 ? number_format(($repartosPorEstado['entregado'] ?? 0) / $totalRepartos * 100, 1) . '%' : '0%'" 
            color="sky"
            subtitle="completados">
            <x-slot:icon>
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </x-slot:icon>
        </x-stat-card>
    </div>

    <!-- Análisis -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Por Estado -->
        <x-card title="Repartos por Estado" :padding="false">
            <div class="p-6 space-y-4">
                @foreach(['pendiente' => 'amber', 'entregado' => 'emerald', 'cancelado' => 'red'] as $estadoKey => $color)
                    @php $cantidad = $repartosPorEstado[$estadoKey] ?? 0; @endphp
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-2">
                                <x-badge :color="$color">{{ ucfirst($estadoKey) }}</x-badge>
                            </div>
                            <span class="text-sm text-slate-600">{{ $cantidad }} repartos</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="flex-1 h-3 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-{{ $color }}-400 to-{{ $color }}-600 rounded-full" 
                                     style="width: {{ $totalRepartos > 0 ? ($cantidad / $totalRepartos * 100) : 0 }}%"></div>
                            </div>
                            <span class="text-sm font-semibold text-{{ $color }}-600">
                                {{ $totalRepartos > 0 ? number_format($cantidad / $totalRepartos * 100, 1) : 0 }}%
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </x-card>

        <!-- Por Producto -->
        <x-card title="Bidones por Producto" :padding="false">
            <div class="p-6 space-y-4">
                @forelse($repartosPorProducto as $producto => $cantidad)
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-medium text-slate-900">{{ $producto }}</span>
                            <span class="text-sm text-slate-600">{{ number_format($cantidad) }} bidones</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="flex-1 h-3 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-purple-400 to-purple-600 rounded-full" 
                                     style="width: {{ $totalBidones > 0 ? ($cantidad / $totalBidones * 100) : 0 }}%"></div>
                            </div>
                            <span class="text-sm font-semibold text-purple-600">
                                {{ $totalBidones > 0 ? number_format($cantidad / $totalBidones * 100, 1) : 0 }}%
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-slate-500 py-4">No hay datos</p>
                @endforelse
            </div>
        </x-card>

    </div>

    <!-- Por Repartidor -->
    @if($repartosPorRepartidor->count() > 0)
    <x-card title="Repartos por Repartidor" :padding="false">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Repartidor</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-slate-600 uppercase tracking-wider">Repartos</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-slate-600 uppercase tracking-wider">% del Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @foreach($repartosPorRepartidor as $repartidor => $cantidad)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ $repartidor }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                    {{ $cantidad }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right text-sm text-slate-600">
                                {{ $totalRepartos > 0 ? number_format($cantidad / $totalRepartos * 100, 1) : 0 }}%
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-card>
    @endif

    <!-- Detalle de Repartos -->
    <x-card title="Detalle de Repartos" :padding="false">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Fecha</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Cliente</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Repartidor</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Producto</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-slate-600 uppercase tracking-wider">Cant.</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-slate-600 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-slate-600 uppercase tracking-wider">Estado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($repartos as $reparto)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4 text-sm text-slate-600">
                                {{ \Carbon\Carbon::parse($reparto->fecha_programada)->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-slate-900">
                                {{ $reparto->cliente->nombre }} {{ $reparto->cliente->apellido ?? '' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600">
                                {{ $reparto->repartidor->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600">
                                {{ $reparto->producto->nombre ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    {{ $reparto->cantidad }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-semibold text-emerald-600">
                                ${{ number_format($reparto->total, 2) }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <x-badge :color="$reparto->estado === 'entregado' ? 'emerald' : ($reparto->estado === 'pendiente' ? 'amber' : 'red')">
                                    {{ ucfirst($reparto->estado) }}
                                </x-badge>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-slate-500">
                                No hay repartos en este período
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if($repartos->count() > 0)
                <tfoot class="bg-slate-50 border-t-2 border-slate-300">
                    <tr>
                        <td colspan="4" class="px-6 py-4 font-bold text-slate-900">TOTALES</td>
                        <td class="px-6 py-4 text-center font-bold text-purple-600">{{ number_format($totalBidones) }}</td>
                        <td class="px-6 py-4 text-right font-bold text-emerald-600">${{ number_format($totalValor, 2) }}</td>
                        <td></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </x-card>

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
