@extends('layouts.app')

@section('title', 'Análisis Geográfico')

@section('breadcrumbs')
    <a href="{{ route('reportes.index') }}" class="text-slate-400 hover:text-slate-600">Reportes</a>
    <span class="text-slate-400 mx-2">/</span>
    <span class="text-slate-600">Análisis Geográfico</span>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">🗺️ Análisis Geográfico</h1>
            <p class="text-slate-500 mt-1">Mapa de calor de repartos por ubicación</p>
        </div>
        <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-300 rounded-lg text-slate-700 hover:bg-slate-50 no-print">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
            </svg>
            Imprimir
        </button>
    </div>

    <!-- Filtros -->
    <x-card title="Filtros">
        <form method="GET" action="{{ route('reportes.analisis-geografico') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Fecha Inicio</label>
                <input type="date" name="fecha_inicio" value="{{ $fechaInicio }}" 
                       class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Fecha Fin</label>
                <input type="date" name="fecha_fin" value="{{ $fechaFin }}" 
                       class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    Aplicar
                </button>
            </div>
        </form>
    </x-card>

    <!-- KPIs -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <x-stat-card 
            title="Bidones Distribuidos" 
            :value="$totalBidones" 
            color="blue"
            subtitle="{{ $totalRepartos }} repartos geolocalizados">
            <x-slot:icon>
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
            </x-slot:icon>
        </x-stat-card>

        <x-stat-card 
            title="Zonas Activas" 
            :value="$zonasActivas" 
            color="emerald"
            subtitle="barrios con entregas">
            <x-slot:icon>
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                </svg>
            </x-slot:icon>
        </x-stat-card>

        <x-stat-card 
            title="Clientes Geolocalizados" 
            :value="$clientesConUbicacion" 
            color="purple"
            subtitle="clientes con coordenadas">
            <x-slot:icon>
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </x-slot:icon>
        </x-stat-card>
    </div>

    <!-- Mapa de Calor -->
    @if(count($heatmapData) > 0)
    <x-card title="Mapa de Calor de Repartos" class="no-print">
        <div id="map" class="w-full rounded-lg shadow-inner" style="height: 600px;"></div>
        <div class="mt-4 flex items-center gap-4 text-sm text-slate-600">
            <div class="flex items-center gap-2">
                <div class="w-4 h-4 rounded-full bg-blue-400"></div>
                <span>Bajo consumo (1-4 bidones)</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-4 h-4 rounded-full bg-yellow-400"></div>
                <span>Consumo medio (5-9 bidones)</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-4 h-4 rounded-full bg-red-500"></div>
                <span>Alto consumo (10+ bidones)</span>
            </div>
        </div>
    </x-card>
    @else
    <x-card title="Mapa de Calor de Repartos">
        <div class="text-center py-12">
            <svg class="w-16 h-16 mx-auto text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
            </svg>
            <p class="text-slate-500 font-medium">No hay datos de ubicación disponibles</p>
            <p class="text-sm text-slate-400 mt-1">Agrega coordenadas a tus clientes para ver el mapa de calor</p>
        </div>
    </x-card>
    @endif

    <!-- Repartos por Zona -->
    <x-card title="Repartos por Zona" :padding="false">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">#</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Zona/Barrio</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-slate-600 uppercase tracking-wider">Bidones</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-slate-600 uppercase tracking-wider">Repartos</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-slate-600 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-slate-600 uppercase tracking-wider">% del Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @php
                        $totalGeneral = $repartosPorZona->sum('total');
                    @endphp
                    @forelse($repartosPorZona as $zona => $datos)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4 text-sm font-bold text-slate-500">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-medium text-slate-900">{{ $zona }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                    {{ $datos['bidones'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $datos['repartos'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right font-semibold text-blue-600">${{ number_format($datos['total'], 2) }}</td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <span class="text-sm font-medium text-slate-700">
                                        {{ $totalGeneral > 0 ? number_format($datos['total'] / $totalGeneral * 100, 1) : 0 }}%
                                    </span>
                                    <div class="w-20 h-2 bg-slate-200 rounded-full overflow-hidden">
                                        <div class="h-full bg-gradient-to-r from-blue-500 to-purple-500 rounded-full" 
                                             style="width: {{ $totalGeneral > 0 ? ($datos['total'] / $totalGeneral * 100) : 0 }}%"></div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-slate-500">No hay datos para mostrar</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>
</div>

@push('styles')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    @media print {
        .no-print {
            display: none !important;
        }
    }
    .leaflet-popup-content {
        margin: 12px;
        min-width: 200px;
    }
    .leaflet-popup-content h3 {
        font-weight: 600;
        margin-bottom: 8px;
        color: #1e293b;
    }
</style>
@endpush

@push('scripts')
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<!-- Leaflet.heat Plugin -->
<script src="https://unpkg.com/leaflet.heat@0.2.0/dist/leaflet-heat.js"></script>

<script>
    @if(count($heatmapData) > 0)
    // Token de Mapbox
    const MAPBOX_TOKEN = '{{ config('services.mapbox.token', env('MAPBOX_ACCESS_TOKEN')) }}';
    
    // Datos del mapa de calor
    const heatmapData = @json($heatmapData);

    // Calcular centro del mapa (promedio de todas las ubicaciones)
    const avgLat = heatmapData.reduce((sum, point) => sum + point.lat, 0) / heatmapData.length;
    const avgLng = heatmapData.reduce((sum, point) => sum + point.lng, 0) / heatmapData.length;

    // Inicializar mapa
    const map = L.map('map').setView([avgLat, avgLng], 13);

    // Usar tiles de Mapbox (igual que en repartos)
    L.tileLayer('https://api.mapbox.com/styles/v1/mapbox/streets-v12/tiles/{z}/{x}/{y}?access_token=' + MAPBOX_TOKEN, {
        attribution: '© <a href="https://www.mapbox.com/about/maps/">Mapbox</a> © <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        tileSize: 512,
        zoomOffset: -1,
        maxZoom: 19
    }).addTo(map);

    // Preparar datos para el heatmap (lat, lng, intensidad basada en bidones)
    const maxBidones = Math.max(...heatmapData.map(p => p.bidones));
    const heatData = heatmapData.map(point => [
        point.lat,
        point.lng,
        (point.bidones / maxBidones) * 10 // Intensidad normalizada basada en consumo de bidones
    ]);

    // Agregar capa de calor
    L.heatLayer(heatData, {
        radius: 25,
        blur: 35,
        maxZoom: 17,
        max: 1.0,
        gradient: {
            0.0: 'blue',
            0.4: 'cyan',
            0.6: 'lime',
            0.7: 'yellow',
            1.0: 'red'
        }
    }).addTo(map);

    // Agregar marcadores individuales
    heatmapData.forEach(point => {
        const marker = L.circleMarker([point.lat, point.lng], {
            radius: Math.min(6 + (point.bidones / 2), 16),
            fillColor: point.bidones >= 10 ? '#ef4444' : (point.bidones >= 5 ? '#fbbf24' : '#60a5fa'),
            color: '#fff',
            weight: 2,
            opacity: 1,
            fillOpacity: 0.8
        }).addTo(map);

        marker.bindPopup(`
            <div>
                <h3 class="font-semibold text-slate-900">${point.nombre}</h3>
                <p class="text-sm text-slate-600 mt-1">${point.direccion || 'Sin dirección'}</p>
                ${point.colonia ? `<p class="text-xs text-slate-500 mt-1">📍 ${point.colonia}</p>` : ''}
                <div class="mt-2 pt-2 border-t border-slate-200 space-y-1">
                    <p class="text-sm"><span class="font-semibold text-emerald-600">${point.bidones}</span> bidones distribuidos</p>
                    <p class="text-xs text-slate-500">${point.repartos} reparto${point.repartos !== 1 ? 's' : ''} • $${point.total.toLocaleString('es-MX', {minimumFractionDigits: 2})}</p>
                </div>
            </div>
        `);
    });
    @endif
</script>
@endpush

@endsection
