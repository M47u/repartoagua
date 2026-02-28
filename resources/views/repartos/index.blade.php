@extends('layouts.app')

@section('title', 'Repartos')

@section('breadcrumbs')
    <span class="text-slate-400">Repartos</span>
@endsection

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
.leaflet-popup-content {
    margin: 8px 12px;
    line-height: 1.4;
}
.leaflet-popup-content-wrapper {
    border-radius: 8px;
}
.leaflet-routing-container {
    display: none;
}
.route-line {
    z-index: 1000 !important;
    pointer-events: all !important;
}
path.leaflet-interactive {
    pointer-events: all;
}
</style>
@endpush

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-sky-100 rounded-lg flex items-center justify-center">
                <svg class="w-7 h-7 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-slate-900">Gestión de Repartos</h1>
                <p class="text-slate-500 mt-1">Administra y programa entregas</p>
            </div>
        </div>
        
        @can('create', App\Models\Reparto::class)
        <a href="{{ route('repartos.create') }}">
            <x-button variant="primary" size="lg">
                <x-slot:icon>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </x-slot:icon>
                Nuevo Reparto
            </x-button>
        </a>
        @endcan
    </div>

    <!-- Mapa de Repartos -->
    <x-card>
        <x-slot:header>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-sky-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-slate-900">Mapa de Rutas</h2>
                    <p class="text-sm text-slate-500">Visualiza los puntos de entrega</p>
                </div>
            </div>
        </x-slot:header>

        <!-- BOTONES DE CONTROL DEL MAPA -->
        <div class="mb-4 p-4 bg-gradient-to-r from-emerald-50 to-sky-50 rounded-lg border-2 border-emerald-200">
            <div class="flex flex-col sm:flex-row gap-3 items-center">
                @if(auth()->user()->role !== 'repartidor')
                <div class="w-full sm:w-auto relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <select id="filtroRepartidor" 
                            onchange="filtrarPorRepartidor(this.value)" 
                            class="w-full sm:w-64 pl-12 pr-10 py-3.5 text-sm font-medium text-slate-700 bg-white border-2 border-slate-300 rounded-lg shadow-sm hover:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition-all cursor-pointer appearance-none">
                        <option value="">👥 Todos los repartidores</option>
                        @foreach($repartidores as $repartidor)
                            <option value="{{ $repartidor->id }}">🚚 {{ $repartidor->name }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>
                @endif
                
                <button onclick="generarRutaOptima()" class="w-full sm:flex-1 px-6 py-4 text-base bg-gradient-to-r from-emerald-500 to-emerald-600 text-white rounded-lg hover:from-emerald-600 hover:to-emerald-700 transition-all font-bold flex items-center justify-center gap-3 shadow-lg hover:shadow-xl transform hover:scale-105">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                    </svg>
                    <span class="text-lg">🎯 CALCULAR RUTA ÓPTIMA</span>
                </button>
                
                <button onclick="limpiarRuta()" class="w-full sm:w-auto px-5 py-3 text-sm bg-slate-600 text-white rounded-lg hover:bg-slate-700 transition-colors font-medium flex items-center justify-center gap-2 shadow-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Limpiar
                </button>
                
                <button onclick="centrarMapa()" class="w-full sm:w-auto px-5 py-3 text-sm bg-sky-500 text-white rounded-lg hover:bg-sky-600 transition-colors font-medium flex items-center justify-center gap-2 shadow-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Centrar
                </button>
            </div>
        </div>

        <div id="map" class="w-full rounded-lg" style="min-height:280px;height:50vw;max-height:520px;"></div>
        
        <div class="mt-4 flex items-center gap-4 text-sm">
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 bg-amber-500 rounded-full"></div>
                <span class="text-slate-600">Pendiente</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 bg-emerald-500 rounded-full"></div>
                <span class="text-slate-600">Entregado</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                <span class="text-slate-600">Cancelado</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 bg-sky-500 rounded-full"></div>
                <span class="text-slate-600">Repartidor</span>
            </div>
        </div>
    </x-card>

    <!-- Repartos Table -->
    <x-card :padding="false">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="hidden sm:table-cell px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Cliente</th>
                        <th class="hidden md:table-cell px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Producto</th>
                        <th class="hidden sm:table-cell px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Cantidad</th>
                        @if(auth()->user()->role !== 'repartidor')
                        <th class="hidden lg:table-cell px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Total</th>
                        @endif
                        <th class="hidden md:table-cell px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Repartidor</th>
                        <th class="hidden sm:table-cell px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Fecha Prog.</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200">
                    @forelse($repartos as $reparto)
                        <tr class="hover:bg-slate-50 transition-colors reparto-row" data-repartidor-id="{{ $reparto->repartidor_id }}">
                            <td class="hidden sm:table-cell px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">
                                #{{ $reparto->id }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm">
                                    <p class="font-medium text-slate-900">{{ $reparto->cliente->nombre }} {{ $reparto->cliente->apellido }}</p>
                                    <p class="text-slate-500 text-xs">{{ Str::limit($reparto->cliente->direccion, 40) }}</p>
                                    <p class="text-slate-400 text-xs sm:hidden">{{ \Carbon\Carbon::parse($reparto->fecha_programada)->format('d/m/Y') }}</p>
                                </div>
                            </td>
                            <td class="hidden md:table-cell px-6 py-4 whitespace-nowrap text-sm text-slate-900">
                                {{ $reparto->producto->nombre }}
                            </td>
                            <td class="hidden sm:table-cell px-6 py-4 whitespace-nowrap">
                                <x-badge color="info" size="lg">{{ $reparto->cantidad }}</x-badge>
                            </td>
                            @if(auth()->user()->role !== 'repartidor')
                            <td class="hidden lg:table-cell px-6 py-4 whitespace-nowrap text-sm font-semibold text-emerald-600">
                                ${{ number_format($reparto->total, 2) }}
                            </td>
                            @endif
                            <td class="hidden md:table-cell px-6 py-4 whitespace-nowrap text-sm text-slate-900">
                                {{ $reparto->repartidor->name }}
                            </td>
                            <td class="hidden sm:table-cell px-6 py-4 whitespace-nowrap text-sm text-slate-900">
                                {{ \Carbon\Carbon::parse($reparto->fecha_programada)->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($reparto->estado === 'pendiente')
                                    <x-badge color="warning">⏳ Pendiente</x-badge>
                                @elseif($reparto->estado === 'entregado')
                                    <x-badge color="success">✓ Entregado</x-badge>
                                @else
                                    <x-badge color="danger">✗ Cancelado</x-badge>
                                @endif
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-1">
                                    @can('view', $reparto)
                                    <a href="{{ route('repartos.show', $reparto) }}" class="p-2.5 text-sky-600 hover:bg-sky-50 rounded-lg transition-colors min-h-touch min-w-touch flex items-center justify-center" title="Ver">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    @endcan

                                    @if($reparto->estado === 'entregado' && !$reparto->tiene_pago)
                                    @can('createQuick', App\Models\Pago::class)
                                    <button
                                        onclick="abrirModalCobro({{ $reparto->id }})"
                                        class="p-2.5 text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors min-h-touch min-w-touch flex items-center justify-center"
                                        title="Cobrar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                    </button>
                                    @endcan
                                    @endif

                                    @if($reparto->estado === 'entregado' && $reparto->tiene_pago)
                                    <span class="p-2.5 text-emerald-600 flex items-center justify-center min-h-touch min-w-touch" title="Ya cobrado">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </span>
                                    @endif

                                    @can('update', $reparto)
                                    <a href="{{ route('repartos.edit', $reparto) }}" class="p-2.5 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors min-h-touch min-w-touch flex items-center justify-center" title="Editar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    @endcan

                                    @if(auth()->user()->role === 'repartidor' && $reparto->estado === 'pendiente')
                                    <form action="{{ route('repartos.entregar', $reparto->id) }}" method="POST" class="inline-flex">
                                        @csrf
                                        <button type="submit" class="p-2.5 text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors min-h-touch min-w-touch flex items-center justify-center" title="Marcar como Entregado">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </button>
                                    </form>
                                    @endif

                                    @can('delete', $reparto)
                                    <form action="{{ route('repartos.destroy', $reparto) }}" method="POST" class="inline-flex" onsubmit="return confirm('¿Estás seguro de eliminar este reparto?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2.5 text-red-600 hover:bg-red-50 rounded-lg transition-colors min-h-touch min-w-touch flex items-center justify-center" title="Eliminar">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ auth()->user()->role !== 'repartidor' ? '9' : '8' }}" class="px-6 py-12">
                                <div class="text-center">
                                    <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-slate-900">No hay repartos</h3>
                                    <p class="mt-1 text-sm text-slate-500">Comienza creando un nuevo reparto</p>
                                    @can('create', App\Models\Reparto::class)
                                    <div class="mt-6">
                                        <a href="{{ route('repartos.create') }}">
                                            <x-button variant="primary">
                                                <x-slot:icon>
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                    </svg>
                                                </x-slot:icon>
                                                Nuevo Reparto
                                            </x-button>
                                        </a>
                                    </div>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($repartos->hasPages())
        <div class="px-6 py-4 border-t border-slate-200">
            {{ $repartos->links() }}
        </div>
        @endif
    </x-card>
</div>

<!-- Modal de Cobro Rápido -->
<div id="modal-cobro-rapido" class="hidden fixed inset-0 bg-slate-900/50 flex items-center justify-center p-4 backdrop-blur-sm" style="z-index: 9999;">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md max-h-[90vh] overflow-y-auto">
        <!-- Header -->
        <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 px-6 py-5 rounded-t-2xl">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-white">💵 Cobrar Pago</h3>
                        <p class="text-emerald-100 text-sm">Registro rápido</p>
                    </div>
                </div>
                <button onclick="cerrarModalCobro()" class="text-white/80 hover:text-white transition-colors p-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Content -->
        <form id="form-cobro-rapido" class="p-6 space-y-5">
            <input type="hidden" id="reparto-id" name="reparto_id">
            <input type="hidden" id="cliente-id" name="cliente_id">
            
            <!-- Info del Cliente -->
            <div class="bg-sky-50 border-2 border-sky-200 rounded-xl p-4">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <label class="text-xs font-semibold text-sky-700 uppercase tracking-wide">Cliente</label>
                        <p id="cobro-cliente-nombre" class="text-lg font-bold text-slate-900 mt-1">-</p>
                        <p id="cobro-detalle" class="text-sm text-slate-600 mt-1">-</p>
                    </div>
                    <div class="text-right">
                        <label class="text-xs font-semibold text-sky-700 uppercase tracking-wide">Saldo</label>
                        <p id="cobro-saldo-actual" class="text-xl font-bold mt-1">$0.00</p>
                        <p id="cobro-saldo-texto" class="text-xs text-slate-500 mt-1">-</p>
                    </div>
                </div>
            </div>

            <!-- Monto -->
            <div>
                <label for="cobro-monto" class="block text-sm font-semibold text-slate-700 mb-2">
                    Monto a cobrar *
                </label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 font-bold text-lg">$</span>
                    <input 
                        type="number" 
                        id="cobro-monto" 
                        name="monto" 
                        step="0.01" 
                        min="0.01"
                        required
                        class="w-full pl-10 pr-4 py-4 text-lg font-semibold border-2 border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                        placeholder="0.00"
                    >
                </div>
                <button 
                    type="button" 
                    onclick="usarMontoCompleto()" 
                    class="mt-2 text-sm text-emerald-600 hover:text-emerald-700 font-medium flex items-center gap-1"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                    Usar monto completo del reparto
                </button>
            </div>

            <!-- Método de Pago -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-3">
                    ¿Cómo pagó? *
                </label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="relative cursor-pointer">
                        <input type="radio" name="metodo_pago" value="efectivo" class="peer sr-only" required>
                        <div class="h-20 border-2 border-slate-300 rounded-xl flex flex-col items-center justify-center gap-2 transition-all peer-checked:border-emerald-500 peer-checked:bg-emerald-50 peer-checked:shadow-md hover:border-slate-400">
                            <span class="text-3xl">💵</span>
                            <span class="text-sm font-semibold text-slate-700">Efectivo</span>
                        </div>
                    </label>
                    
                    <label class="relative cursor-pointer">
                        <input type="radio" name="metodo_pago" value="transferencia" class="peer sr-only">
                        <div class="h-20 border-2 border-slate-300 rounded-xl flex flex-col items-center justify-center gap-2 transition-all peer-checked:border-emerald-500 peer-checked:bg-emerald-50 peer-checked:shadow-md hover:border-slate-400">
                            <span class="text-3xl">🏦</span>
                            <span class="text-sm font-semibold text-slate-700">Transferencia</span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Notas -->
            <div>
                <label for="cobro-notas" class="block text-sm font-semibold text-slate-700 mb-2">
                    Notas (opcional)
                </label>
                <textarea 
                    id="cobro-notas" 
                    name="notas" 
                    rows="2"
                    class="w-full px-4 py-3 border-2 border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 resize-none"
                    placeholder="Detalles adicionales..."
                ></textarea>
            </div>

            <!-- Botones -->
            <div class="flex gap-3 pt-2">
                <button 
                    type="button" 
                    onclick="cerrarModalCobro()" 
                    class="flex-1 px-6 py-4 bg-slate-100 text-slate-700 rounded-xl font-semibold hover:bg-slate-200 transition-colors"
                >
                    Cancelar
                </button>
                <button 
                    type="button"
                    id="btn-registrar-pago"
                    onclick="procesarCobroRapido()" 
                    class="flex-1 px-6 py-4 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white rounded-xl font-bold hover:from-emerald-600 hover:to-emerald-700 transition-all shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center justify-center gap-2"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Registrar Pago
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
const MAPBOX_TOKEN = '{{ config('services.mapbox.token', env('MAPBOX_ACCESS_TOKEN')) }}';
let map;
let markers = [];
let routePolylines = [];
let repartosData = @json($repartos->items());
let ubicacionActual = null;
let marcadorUbicacionActual = null;

// Inicializar mapa centrado en Formosa, Argentina con Mapbox
function initMap() {
    map = L.map('map').setView([-26.1857, -58.1756], 13);
    
    // Usar tiles de Mapbox (mejor calidad y datos actualizados)
    L.tileLayer('https://api.mapbox.com/styles/v1/mapbox/streets-v12/tiles/{z}/{x}/{y}?access_token=' + MAPBOX_TOKEN, {
        attribution: '© <a href="https://www.mapbox.com/about/maps/">Mapbox</a> © <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        tileSize: 512,
        zoomOffset: -1,
        maxZoom: 19
    }).addTo(map);
    
    cargarMarcadores();
}

function cargarMarcadores(repartidorId = null) {
    // Limpiar marcadores existentes
    markers.forEach(marker => map.removeLayer(marker));
    markers = [];
    
    // Filtrar repartos si es necesario
    let repartosFiltrados = repartosData;
    if (repartidorId) {
        repartosFiltrados = repartosData.filter(r => r.repartidor_id == repartidorId);
    }
    
    // Agregar marcadores para cada reparto
    repartosFiltrados.forEach((reparto, index) => {
        if (reparto.cliente && reparto.cliente.latitude && reparto.cliente.longitude) {
            const lat = parseFloat(reparto.cliente.latitude);
            const lng = parseFloat(reparto.cliente.longitude);
            
            // Determinar color según estado
            let color;
            switch(reparto.estado) {
                case 'pendiente':
                    color = '#f59e0b'; // amber
                    break;
                case 'entregado':
                    color = '#10b981'; // emerald
                    break;
                case 'cancelado':
                    color = '#ef4444'; // red
                    break;
                default:
                    color = '#6b7280'; // gray
            }
            
            // Crear icono personalizado
            const icon = L.divIcon({
                className: 'custom-div-icon',
                html: `<div style="
                    background-color: ${color};
                    width: 24px;
                    height: 24px;
                    border-radius: 50%;
                    border: 3px solid white;
                    box-shadow: 0 2px 4px rgba(0,0,0,0.3);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    color: white;
                    font-weight: bold;
                    font-size: 11px;
                ">${reparto.cantidad}</div>`,
                iconSize: [30, 30],
                iconAnchor: [15, 15]
            });
            
            const marker = L.marker([lat, lng], { icon: icon }).addTo(map);
            
            // Popup con información
            marker.bindPopup(`
                <div style="min-width: 200px;">
                    <h3 class="font-bold text-slate-900 mb-2">Reparto #${reparto.id}</h3>
                    <p class="text-sm text-slate-700"><strong>${reparto.cliente.nombre} ${reparto.cliente.apellido}</strong></p>
                    <p class="text-xs text-slate-500 mb-1">${reparto.cliente.direccion}</p>
                    <p class="text-sm text-slate-700 mt-2">Producto: ${reparto.producto.nombre}</p>
                    <p class="text-sm text-slate-700">Cantidad: ${reparto.cantidad}</p>
                    <p class="text-sm text-slate-700">Repartidor: ${reparto.repartidor.name}</p>
                    <p class="text-xs text-slate-500 mt-1">Estado: <span class="font-semibold">${reparto.estado}</span></p>
                    <a href="/repartos/${reparto.id}" class="inline-block mt-2 px-3 py-1 bg-sky-500 text-white text-xs rounded hover:bg-sky-600 transition-colors">Ver detalles</a>
                </div>
            `);
            
            marker.repartoData = reparto; // Guardar datos del reparto
            markers.push(marker);
        }
    });
}

// Calcular distancia entre dos puntos (fórmula de Haversine)
function calcularDistancia(lat1, lon1, lat2, lon2) {
    const R = 6371; // Radio de la Tierra en km
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLon = (lon2 - lon1) * Math.PI / 180;
    const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
              Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
              Math.sin(dLon/2) * Math.sin(dLon/2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    return R * c;
}

// Obtener ruta desde Mapbox Directions API con tráfico en tiempo real
async function obtenerRutaMapbox(coordenadas) {
    // Formatear coordenadas para Mapbox: "lng,lat;lng,lat;..."
    const coords = coordenadas.map(c => `${c.lng},${c.lat}`).join(';');
    
    // Usar perfil driving-traffic para considerar tráfico en tiempo real
    // alternatives=false: solo la mejor ruta
    // geometries=geojson: respuesta en formato GeoJSON
    // overview=full: geometría completa de la ruta
    // steps=true: instrucciones detalladas
    // exclude=unpaved: evitar calles sin pavimentar
    const url = `https://api.mapbox.com/directions/v5/mapbox/driving-traffic/${coords}` +
                `?alternatives=false` +
                `&geometries=geojson` +
                `&overview=full` +
                `&steps=true` +
                `&exclude=unpaved` +
                `&access_token=${MAPBOX_TOKEN}`;
    
    try {
        console.log('🗺️  Solicitando ruta a Mapbox...');
        const response = await fetch(url);
        
        if (!response.ok) {
            throw new Error(`Error HTTP: ${response.status}`);
        }
        
        const data = await response.json();
        
        if (data.code !== 'Ok' || !data.routes || data.routes.length === 0) {
            throw new Error('No se pudo calcular la ruta');
        }
        
        const route = data.routes[0];
        console.log('✅ Ruta obtenida desde Mapbox');
        console.log('📏 Distancia:', (route.distance / 1000).toFixed(2), 'km');
        console.log('⏱️  Duración:', (route.duration / 60).toFixed(0), 'minutos');
        
        return {
            geometry: route.geometry.coordinates,
            distance: route.distance / 1000, // convertir a km
            duration: route.duration / 60 // convertir a minutos
        };
    } catch (error) {
        console.error('❌ Error al obtener ruta de Mapbox:', error);
        throw error;
    }
}

// Algoritmo del vecino más cercano para calcular ruta óptima
function calcularRutaOptima(puntos, puntoInicio = null) {
    if (puntos.length === 0) return [];
    
    const rutaOptima = [];
    const pendientes = [...puntos];
    
    // Punto de inicio: ubicación actual o primer reparto
    let actual;
    if (puntoInicio) {
        actual = puntoInicio;
    } else {
        actual = pendientes.shift();
        rutaOptima.push(actual);
    }
    
    // Ir al punto más cercano no visitado
    while (pendientes.length > 0) {
        let distanciaMin = Infinity;
        let indiceMin = 0;
        
        pendientes.forEach((punto, index) => {
            const distancia = calcularDistancia(
                actual.lat, actual.lng,
                punto.lat, punto.lng
            );
            
            if (distancia < distanciaMin) {
                distanciaMin = distancia;
                indiceMin = index;
            }
        });
        
        actual = pendientes.splice(indiceMin, 1)[0];
        rutaOptima.push(actual);
    }
    
    return rutaOptima;
}

// Obtener ubicación actual del dispositivo
function obtenerUbicacionActual() {
    return new Promise((resolve, reject) => {
        if (!navigator.geolocation) {
            reject(new Error('Geolocalización no soportada por el navegador'));
            return;
        }
        
        navigator.geolocation.getCurrentPosition(
            (position) => {
                ubicacionActual = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };
                resolve(ubicacionActual);
            },
            (error) => {
                console.error('Error al obtener ubicación:', error);
                reject(error);
            },
            {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0
            }
        );
    });
}

// Marcar ubicación actual en el mapa
function marcarUbicacionActual(lat, lng) {
    // Remover marcador anterior si existe
    if (marcadorUbicacionActual) {
        map.removeLayer(marcadorUbicacionActual);
    }
    
    const iconoActual = L.divIcon({
        className: 'custom-ubicacion-actual',
        html: `<div style="
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            width: 48px;
            height: 48px;
            border-radius: 50%;
            border: 5px solid white;
            box-shadow: 0 4px 20px rgba(59, 130, 246, 0.6);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            animation: pulse 2s infinite;
        ">📍</div>
        <style>
            @keyframes pulse {
                0%, 100% { transform: scale(1); box-shadow: 0 4px 20px rgba(59, 130, 246, 0.6); }
                50% { transform: scale(1.1); box-shadow: 0 6px 30px rgba(59, 130, 246, 0.9); }
            }
        </style>`,
        iconSize: [58, 58],
        iconAnchor: [29, 29]
    });
    
    marcadorUbicacionActual = L.marker([lat, lng], { 
        icon: iconoActual,
        zIndexOffset: 3000 
    }).addTo(map);
    
    marcadorUbicacionActual.bindPopup(`
        <div style="min-width: 200px;">
            <h3 class="font-bold text-blue-600 mb-2 text-lg">📍 Tu Ubicación Actual</h3>
            <p class="text-sm text-slate-700">Punto de partida de la ruta</p>
            <p class="text-xs text-slate-500 mt-2">Lat: ${lat.toFixed(6)}</p>
            <p class="text-xs text-slate-500">Lng: ${lng.toFixed(6)}</p>
        </div>
    `);
    
    routePolylines.push(marcadorUbicacionActual);
}

// Generar ruta óptima
async function generarRutaOptima() {
    console.log('🎯 Iniciando cálculo de ruta óptima...');
    
    // Limpiar ruta anterior
    limpiarRuta();
    
    // Ocultar marcadores naranjas originales durante la ruta
    markers.forEach(marker => {
        marker.setOpacity(0.2);
    });
    
    // Obtener repartos pendientes del repartidor actual
    const repartidorId = document.getElementById('filtroRepartidor')?.value;
    let repartosPendientes = repartosData.filter(r => r.estado === 'pendiente');
    
    console.log('Total repartos:', repartosData.length);
    console.log('Repartos pendientes:', repartosPendientes.length);
    console.log('Datos de repartos:', repartosData);
    
    if (repartidorId) {
        repartosPendientes = repartosPendientes.filter(r => r.repartidor_id == repartidorId);
        console.log('Filtrados por repartidor:', repartosPendientes.length);
    } else if ({{ auth()->user()->role === 'repartidor' ? 'true' : 'false' }}) {
        repartosPendientes = repartosPendientes.filter(r => r.repartidor_id == {{ auth()->id() }});
        console.log('Filtrados por repartidor (logueado):', repartosPendientes.length);
    }
    
    if (repartosPendientes.length === 0) {
        alert('❌ No hay repartos pendientes para calcular la ruta.');
        return;
    }
    
    // Extraer coordenadas válidas
    const puntos = [];
    repartosPendientes.forEach(r => {
        if (r.cliente && r.cliente.latitude && r.cliente.longitude) {
            const lat = parseFloat(r.cliente.latitude);
            const lng = parseFloat(r.cliente.longitude);
            
            // Validar que sean números válidos
            if (!isNaN(lat) && !isNaN(lng) && lat !== 0 && lng !== 0) {
                puntos.push({
                    lat: lat,
                    lng: lng,
                    reparto: r
                });
                console.log(`✅ Punto agregado: ${r.cliente.nombre} (${lat}, ${lng})`);
            } else {
                console.warn(`⚠️ Coordenadas inválidas para ${r.cliente.nombre}: (${lat}, ${lng})`);
            }
        } else {
            console.warn(`⚠️ Sin coordenadas: ${r.cliente?.nombre || 'Cliente desconocido'}`);
        }
    });
    
    console.log('Puntos con coordenadas válidas:', puntos.length);
    
    if (puntos.length === 0) {
        alert('❌ No hay coordenadas válidas para calcular la ruta.\n\nAsegúrate de que los clientes tengan latitud y longitud configuradas.');
        // Restaurar marcadores originales
        markers.forEach(marker => marker.setOpacity(1));
        return;
    }
    
    // Obtener ubicación actual del usuario
    let puntoInicio = null;
    try {
        const ubicacion = await obtenerUbicacionActual();
        console.log('✅ Ubicación actual obtenida:', ubicacion);
        puntoInicio = {
            lat: ubicacion.lat,
            lng: ubicacion.lng,
            esUbicacionActual: true
        };
        marcarUbicacionActual(ubicacion.lat, ubicacion.lng);
    } catch (error) {
        console.warn('⚠️ No se pudo obtener ubicación actual:', error.message);
        const usarUbicacion = confirm(
            '⚠️ No se pudo acceder a tu ubicación actual.\n\n' +
            'Razones posibles:\n' +
            '- Permisos de ubicación denegados\n' +
            '- Navegador no soporta geolocalización\n' +
            '- Conexión no segura (requiere HTTPS)\n\n' +
            '¿Deseas continuar usando el primer punto como inicio?'
        );
        if (!usarUbicacion) {
            markers.forEach(marker => marker.setOpacity(1));
            return;
        }
    }
    
    // Calcular ruta óptima
    const rutaOptima = calcularRutaOptima(puntos, puntoInicio);
    console.log('✅ Ruta óptima calculada:', rutaOptima.length, 'paradas');
    
    // Preparar coordenadas para Mapbox Directions API
    const coordenadasRuta = [];
    if (puntoInicio) {
        coordenadasRuta.push(puntoInicio);
    }
    coordenadasRuta.push(...rutaOptima);
    
    // Obtener ruta desde Mapbox con tráfico en tiempo real
    try {
        const rutaMapbox = await obtenerRutaMapbox(coordenadasRuta);
        
        // Convertir geometría de Mapbox (lng,lat) a Leaflet (lat,lng)
        const coordsLeaflet = rutaMapbox.geometry.map(coord => [coord[1], coord[0]]);
        
        // Crear línea de borde blanco (efecto de sombra)
        const polylineBorder = L.polyline(coordsLeaflet, {
            color: '#ffffff',
            weight: 12,
            opacity: 0.6,
            smoothFactor: 1,
            pane: 'shadowPane'
        }).addTo(map);
        
        // Crear línea principal roja
        const polyline = L.polyline(coordsLeaflet, {
            color: '#ef4444',
            weight: 8,
            opacity: 0.9,
            smoothFactor: 1,
            className: 'route-line',
            pane: 'markerPane'
        }).addTo(map);
        
        routePolylines.push(polylineBorder);
        routePolylines.push(polyline);
        
        // Ajustar vista al polyline
        map.fitBounds(polyline.getBounds(), { padding: [50, 50] });
        
        console.log('✅ Ruta dibujada usando Mapbox Directions API');
        console.log('🚗 Considera tráfico en tiempo real');
        console.log('🛣️  Evita calles sin pavimentar');
        
        // Guardar info de la ruta para mostrar
        rutaOptima.distanciaReal = rutaMapbox.distance;
        rutaOptima.duracionReal = rutaMapbox.duration;
        
    } catch (error) {
        console.error('⚠️ Error al obtener ruta de Mapbox, usando líneas rectas', error);
        
        // Fallback: línea recta si falla Mapbox
        const coordenadas = [];
        if (puntoInicio) {
            coordenadas.push([puntoInicio.lat, puntoInicio.lng]);
        }
        coordenadas.push(...rutaOptima.map(p => [p.lat, p.lng]));
        
        const polylineBorder = L.polyline(coordenadas, {
            color: '#ffffff',
            weight: 12,
            opacity: 0.6,
            smoothFactor: 1,
            pane: 'shadowPane'
        }).addTo(map);
        
        const polyline = L.polyline(coordenadas, {
            color: '#ef4444',
            weight: 8,
            opacity: 0.9,
            smoothFactor: 1,
            className: 'route-line',
            pane: 'markerPane'
        }).addTo(map);
        
        routePolylines.push(polylineBorder);
        routePolylines.push(polyline);
        
        map.fitBounds(polyline.getBounds(), { padding: [50, 50] });
    }
    
    // Agregar números de orden a los marcadores (MUY VISIBLES)
    rutaOptima.forEach((punto, index) => {
        const numeroIcon = L.divIcon({
            className: 'custom-numero-icon',
            html: `<div style="
                background-color: #2563eb;
                color: white;
                width: 40px;
                height: 40px;
                border-radius: 50%;
                border: 5px solid white;
                box-shadow: 0 4px 15px rgba(0,0,0,0.5);
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: 900;
                font-size: 18px;
                font-family: Arial, sans-serif;
            ">${index + 1}</div>`,
            iconSize: [50, 50],
            iconAnchor: [25, 25]
        });
        
        const numeroMarker = L.marker([punto.lat, punto.lng], { 
            icon: numeroIcon,
            zIndexOffset: 2000 
        }).addTo(map);
        
        numeroMarker.bindPopup(`
            <div style="min-width: 220px;">
                <h3 class="font-bold text-blue-600 mb-2 text-lg">🎯 Parada #${index + 1}</h3>
                <p class="text-sm text-slate-700"><strong>${punto.reparto.cliente.nombre} ${punto.reparto.cliente.apellido}</strong></p>
                <p class="text-xs text-slate-500 mb-1">${punto.reparto.cliente.direccion}</p>
                <p class="text-sm text-slate-700 mt-2"><strong>Producto:</strong> ${punto.reparto.producto.nombre}</p>
                <p class="text-sm text-slate-700"><strong>Cantidad:</strong> ${punto.reparto.cantidad}</p>
                <a href="/repartos/${punto.reparto.id}" class="inline-block mt-2 px-3 py-1 bg-sky-500 text-white text-xs rounded hover:bg-sky-600 transition-colors">Ver detalles</a>
            </div>
        `);
        
        routePolylines.push(numeroMarker);
        console.log(`✅ Marcador #${index + 1} agregado para ${punto.reparto.cliente.nombre}`);
    });
    
    console.log('✅ Todos los marcadores numerados agregados');
    
    // Calcular distancia total aproximada (línea recta)
    let distanciaTotal = 0;
    
    // Distancia desde ubicación actual al primer punto
    if (puntoInicio && rutaOptima.length > 0) {
        distanciaTotal += calcularDistancia(
            puntoInicio.lat, puntoInicio.lng,
            rutaOptima[0].lat, rutaOptima[0].lng
        );
    }
    
    // Distancia entre los puntos de entrega
    for (let i = 0; i < rutaOptima.length - 1; i++) {
        distanciaTotal += calcularDistancia(
            rutaOptima[i].lat, rutaOptima[i].lng,
            rutaOptima[i+1].lat, rutaOptima[i+1].lng
        );
    }
    
    console.log('📏 Distancia total (línea recta):', distanciaTotal.toFixed(2), 'km');
    
    // Mostrar información de la ruta
    const mensaje = puntos.length < repartosPendientes.length 
        ? `⚠️ ATENCIÓN: Se encontraron ${repartosPendientes.length} repartos pendientes,\npero solo ${puntos.length} tienen coordenadas válidas.\n\n`
        : '';
    
    const mensajeInicio = puntoInicio 
        ? '📍 Punto de partida: Tu ubicación actual\n'
        : '📍 Punto de partida: Primera parada\n';
    
    // Usar distancia y duración real de Mapbox si están disponibles
    const infoDistancia = rutaOptima.distanciaReal 
        ? `📏 Distancia por calles: ${rutaOptima.distanciaReal.toFixed(2)} km\n⏱️  Tiempo estimado: ${Math.round(rutaOptima.duracionReal)} minutos\n🚗 Considera tráfico en tiempo real\n`
        : `📏 Distancia aproximada: ${distanciaTotal.toFixed(2)} km\n`;
    
    alert(`✅ ¡Ruta óptima calculada con Mapbox!\n\n` +
          mensaje +
          mensajeInicio +
          `📍 Paradas: ${rutaOptima.length}\n` +
          infoDistancia +
          `\nLa ruta se muestra siguiendo las calles con una línea roja\n` +
          `y números AZULES grandes indicando el orden de entrega.\n` +
          `Se evitan calles sin pavimentar.\n\n` +
          `Primera parada: ${rutaOptima[0].reparto.cliente.nombre}\n` +
          `Última parada: ${rutaOptima[rutaOptima.length-1].reparto.cliente.nombre}`);
}

// Limpiar ruta del mapa
function limpiarRuta() {
    // Remover polylines y marcadores de ruta
    routePolylines.forEach(item => {
        if (item) map.removeLayer(item);
    });
    routePolylines = [];
    
    // Restaurar opacidad de marcadores originales
    markers.forEach(marker => marker.setOpacity(1));
    
    console.log('🧹 Ruta limpiada');
}

function filtrarPorRepartidor(repartidorId) {
    limpiarRuta();
    cargarMarcadores(repartidorId || null);
    
    // Filtrar filas de la tabla
    const filas = document.querySelectorAll('.reparto-row');
    filas.forEach(fila => {
        const filaRepartidorId = fila.getAttribute('data-repartidor-id');
        if (!repartidorId || filaRepartidorId == repartidorId) {
            fila.style.display = '';
        } else {
            fila.style.display = 'none';
        }
    });
    
    console.log('🔍 Filtrado por repartidor:', repartidorId || 'todos');
}

function centrarMapa() {
    map.setView([-26.1857, -58.1756], 13);
}

// ============================================
// SISTEMA DE COBRO RÁPIDO
// ============================================
let cobroData = {
    reparto_id: null,
    cliente: null,
    monto_sugerido: 0,
    detalle: ''
};

async function abrirModalCobro(repartoId) {
    try {
        // Obtener información del reparto
        const response = await fetch(`/api/repartos/${repartoId}/cobro-info`);
        
        if (!response.ok) {
            const error = await response.json();
            alert('⚠️ ' + (error.message || 'No se puede cobrar este reparto'));
            return;
        }
        
        const data = await response.json();
        
        cobroData = {
            reparto_id: repartoId,
            cliente: data.cliente,
            monto_sugerido: parseFloat(data.monto_sugerido) || 0,
            detalle: data.detalle
        };
        
        // Llenar el modal con los datos
        document.getElementById('cobro-cliente-nombre').textContent = data.cliente.nombre;
        
        // Aplicar color según el saldo
        const saldoElement = document.getElementById('cobro-saldo-actual');
        const saldo = parseFloat(data.cliente.saldo) || 0;
        saldoElement.textContent = `$${Math.abs(saldo).toFixed(2)}`;
        saldoElement.className = saldo > 0 
            ? 'text-xl font-bold mt-1 text-red-600' 
            : (saldo < 0 ? 'text-xl font-bold mt-1 text-emerald-600' : 'text-xl font-bold mt-1 text-slate-600');
        
        document.getElementById('cobro-saldo-texto').textContent = saldo > 0 
            ? 'debe' 
            : (saldo < 0 ? 'a favor' : 'sin saldo');
        
        document.getElementById('cobro-detalle').textContent = data.detalle;
        document.getElementById('cobro-monto').value = cobroData.monto_sugerido.toFixed(2);
        document.getElementById('cliente-id').value = data.cliente.id;
        document.getElementById('reparto-id').value = repartoId;
        
        // Mostrar modal
        document.getElementById('modal-cobro-rapido').classList.remove('hidden');
    } catch (error) {
        console.error('Error al cargar datos del cobro:', error);
        alert('Error al cargar la información del reparto. Por favor, intenta de nuevo.');
    }
}

function cerrarModalCobro() {
    document.getElementById('modal-cobro-rapido').classList.add('hidden');
    document.getElementById('form-cobro-rapido').reset();
}

async function procesarCobroRapido() {
    const form = document.getElementById('form-cobro-rapido');
    const formData = new FormData(form);
    const btn = document.getElementById('btn-registrar-pago');
    
    // Validar monto
    const monto = parseFloat(formData.get('monto'));
    if (!monto || monto <= 0) {
        alert('Por favor, ingresa un monto válido');
        return;
    }
    
    // Validar método de pago
    if (!formData.get('metodo_pago')) {
        alert('Por favor, selecciona un método de pago');
        return;
    }
    
    // Deshabilitar botón
    btn.disabled = true;
    btn.innerHTML = `
        <svg class="animate-spin h-5 w-5 mr-2 inline" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Procesando...
    `;
    
    try {
        const response = await fetch('{{ route("pagos.cobro-rapido") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                reparto_id: formData.get('reparto_id'),
                cliente_id: formData.get('cliente_id'),
                monto: monto,
                metodo_pago: formData.get('metodo_pago'),
                notas: formData.get('notas') || null
            })
        });
        
        const result = await response.json();
        
        // Si el servidor retorna error (422, 500, etc)
        if (!response.ok) {
            throw new Error(result.message || 'Error al procesar el pago');
        }
        
        if (result.success) {
            // Mostrar mensaje de éxito
            alert('✅ Pago registrado exitosamente\n\nMonto: $' + monto.toFixed(2) + '\nCliente: ' + cobroData.cliente.nombre);
            
            // Cerrar modal
            cerrarModalCobro();
            
            // Recargar página para actualizar datos
            setTimeout(() => location.reload(), 500);
        } else {
            throw new Error(result.message || 'Error al procesar el pago');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('❌ Error al registrar el pago\n\n' + error.message);
    } finally {
        // Rehabilitar botón
        btn.disabled = false;
        btn.innerHTML = `
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            Registrar Pago
        `;
    }
}

function usarMontoCompleto() {
    document.getElementById('cobro-monto').value = cobroData.monto_sugerido.toFixed(2);
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    initMap();
});
</script>
@endpush

@endsection
