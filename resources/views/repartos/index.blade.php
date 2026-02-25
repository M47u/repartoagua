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
                <h1 class="text-3xl font-bold text-slate-900">Gestión de Repartos</h1>
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
            <div class="flex items-center justify-between">
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
                <div class="flex gap-2">
                    @if(auth()->user()->role !== 'repartidor')
                    <select id="filtroRepartidor" onchange="filtrarPorRepartidor(this.value)" class="px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
                        <option value="">Todos los repartidores</option>
                        @foreach($repartidores as $repartidor)
                            <option value="{{ $repartidor->id }}">{{ $repartidor->name }}</option>
                        @endforeach
                    </select>
                    @endif
                    <button onclick="centrarMapa()" class="px-3 py-2 text-sm bg-sky-100 text-sky-700 rounded-lg hover:bg-sky-200 transition-colors">
                        🎯 Centrar Mapa
                    </button>
                </div>
            </div>
        </x-slot:header>

        <div id="map" class="w-full h-[500px] rounded-lg"></div>
        
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Cliente</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Producto</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Cantidad</th>
                        @if(auth()->user()->role !== 'repartidor')
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Total</th>
                        @endif
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Repartidor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Fecha Prog.</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200">
                    @forelse($repartos as $reparto)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">
                                #{{ $reparto->id }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm">
                                    <p class="font-medium text-slate-900">{{ $reparto->cliente->nombre }} {{ $reparto->cliente->apellido }}</p>
                                    <p class="text-slate-500 text-xs">{{ Str::limit($reparto->cliente->direccion, 40) }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">
                                {{ $reparto->producto->nombre }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <x-badge color="info" size="lg">{{ $reparto->cantidad }}</x-badge>
                            </td>
                            @if(auth()->user()->role !== 'repartidor')
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-emerald-600">
                                ${{ number_format($reparto->total, 2) }}
                            </td>
                            @endif
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">
                                {{ $reparto->repartidor->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">
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
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    @can('view', $reparto)
                                    <a href="{{ route('repartos.show', $reparto) }}" class="text-sky-600 hover:text-sky-900" title="Ver">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    @endcan

                                    @can('update', $reparto)
                                    <a href="{{ route('repartos.edit', $reparto) }}" class="text-amber-600 hover:text-amber-900" title="Editar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    @endcan

                                    @if(auth()->user()->role === 'repartidor' && $reparto->estado === 'pendiente')
                                    <form action="{{ route('repartos.entregar', $reparto->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-emerald-600 hover:text-emerald-900" title="Marcar como Entregado">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </button>
                                    </form>
                                    @endif

                                    @can('delete', $reparto)
                                    <form action="{{ route('repartos.destroy', $reparto) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de eliminar este reparto?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" title="Eliminar">
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

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
let map;
let markers = [];
let repartosData = @json($repartos->items());

// Inicializar mapa centrado en Formosa, Argentina
function initMap() {
    map = L.map('map').setView([-26.1857, -58.1756], 13);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
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
    repartosFiltrados.forEach(reparto => {
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
            
            markers.push(marker);
        }
    });
}

function filtrarPorRepartidor(repartidorId) {
    cargarMarcadores(repartidorId || null);
}

function centrarMapa() {
    map.setView([-25.3333, -57.6670], 13);
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    initMap();
});
</script>
@endpush

@endsection
