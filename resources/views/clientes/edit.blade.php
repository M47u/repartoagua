@extends('layouts.app')

@section('title', 'Editar Cliente')

@section('breadcrumbs')
    <a href="{{ route('clientes.index') }}" class="text-slate-400 hover:text-slate-600">Clientes</a>
    <span class="text-slate-400">/</span>
    <span class="text-slate-600">Editar</span>
@endsection

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
#map {
    min-height: 280px;
    height: 40vw;
    max-height: 480px;
    border-radius: 0.5rem;
    z-index: 1;
}
@media (min-width: 640px) {
    #map { min-height: 380px; }
}
.leaflet-popup-content {
    font-size: 14px;
}
</style>
@endpush

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-3">
        <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center">
            <svg class="w-7 h-7 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
        </div>
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-slate-900">Editar Cliente</h1>
            <p class="text-slate-500 mt-1">Modifica la información del cliente</p>
        </div>
    </div>

    <!-- Form -->
    <form action="{{ route('clientes.update', $cliente) }}" method="POST">
        @csrf
        @method('PUT')

        <x-card>
            <div class="space-y-6">
                <!-- Información Personal -->
                <div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">Información Personal</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nombre -->
                        <div>
                            <label for="nombre" class="block text-sm font-medium text-slate-700 mb-2">
                                Nombre <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="nombre" 
                                   name="nombre" 
                                   value="{{ old('nombre', $cliente->nombre) }}" 
                                   class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent @error('nombre') border-red-500 @enderror"
                                   required>
                            @error('nombre')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Apellido -->
                        <div>
                            <label for="apellido" class="block text-sm font-medium text-slate-700 mb-2">
                                Apellido <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="apellido" 
                                   name="apellido" 
                                   value="{{ old('apellido', $cliente->apellido) }}" 
                                   class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent @error('apellido') border-red-500 @enderror"
                                   required>
                            @error('apellido')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Teléfono -->
                        <div>
                            <label for="telefono" class="block text-sm font-medium text-slate-700 mb-2">
                                Teléfono <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="telefono" 
                                   name="telefono" 
                                   value="{{ old('telefono', $cliente->telefono) }}" 
                                   class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent @error('telefono') border-red-500 @enderror"
                                   required>
                            @error('telefono')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-slate-700 mb-2">
                                Email
                            </label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email', $cliente->email) }}" 
                                   class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent @error('email') border-red-500 @enderror">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Dirección -->
                <div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">Dirección</h3>
                    <div class="space-y-6">
                        <!-- Dirección -->
                        <div>
                            <label for="direccion" class="block text-sm font-medium text-slate-700 mb-2">
                                Dirección <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="direccion" 
                                   name="direccion" 
                                   value="{{ old('direccion', $cliente->direccion) }}" 
                                   placeholder="Calle y número"
                                   class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent @error('direccion') border-red-500 @enderror"
                                   required>
                            @error('direccion')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Colonia -->
                            <div>
                                <label for="colonia" class="block text-sm font-medium text-slate-700 mb-2">
                                    Colonia
                                </label>
                                <input type="text" 
                                       id="colonia" 
                                       name="colonia" 
                                       value="{{ old('colonia', $cliente->colonia) }}" 
                                       class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent @error('colonia') border-red-500 @enderror">
                                @error('colonia')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Ciudad -->
                            <div>
                                <label for="ciudad" class="block text-sm font-medium text-slate-700 mb-2">
                                    Ciudad <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="ciudad" 
                                       name="ciudad" 
                                       value="{{ old('ciudad', $cliente->ciudad) }}" 
                                       class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent @error('ciudad') border-red-500 @enderror"
                                       required>
                                @error('ciudad')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Latitud -->
                            <div>
                                <label for="latitude" class="block text-sm font-medium text-slate-700 mb-2">
                                    Latitud
                                </label>
                                <input type="number" 
                                       id="latitude" 
                                       name="latitude" 
                                       value="{{ old('latitude', $cliente->latitude) }}" 
                                       step="0.00000001"
                                       min="-90"
                                       max="90"
                                       placeholder="-26.185700"
                                       class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent @error('latitude') border-red-500 @enderror">
                                @error('latitude')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-slate-500">Coordenada geográfica (ej: -26.1857)</p>
                            </div>

                            <!-- Longitud -->
                            <div>
                                <label for="longitude" class="block text-sm font-medium text-slate-700 mb-2">
                                    Longitud
                                </label>
                                <input type="number" 
                                       id="longitude" 
                                       name="longitude" 
                                       value="{{ old('longitude', $cliente->longitude) }}" 
                                       step="0.00000001"
                                       min="-180"
                                       max="180"
                                       placeholder="-58.175600"
                                       class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent @error('longitude') border-red-500 @enderror">
                                @error('longitude')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-slate-500">Coordenada geográfica (ej: -58.1756)</p>
                            </div>
                        </div>

                        <!-- Mapa Interactivo -->
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <label class="block text-sm font-medium text-slate-700">
                                    Ubicación en el Mapa
                                </label>
                                <button type="button"
                                        onclick="buscarEnMapa()"
                                        class="px-3 py-2 min-h-touch bg-sky-100 text-sky-700 rounded-lg hover:bg-sky-200 transition-colors text-sm font-medium inline-flex items-center justify-center">
                                    📍 Buscar dirección en mapa
                                </button>
                            </div>
                            <div id="map" class="border border-slate-300 shadow-sm"></div>
                            <p class="text-xs text-slate-500">
                                💡 <strong>Cómo usar:</strong> Ingresa la dirección y haz clic en "Buscar dirección en mapa" para geocodificar automáticamente, 
                                o haz clic directamente en el mapa para establecer la ubicación. También puedes arrastrar el marcador 📍 para ajustar la posición.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Información del Servicio -->
                <div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">Información del Servicio</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Tipo de Cliente -->
                        <div>
                            <label for="tipo_cliente" class="block text-sm font-medium text-slate-700 mb-2">
                                Tipo de Cliente <span class="text-red-500">*</span>
                            </label>
                            <select id="tipo_cliente" 
                                    name="tipo_cliente" 
                                    class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent @error('tipo_cliente') border-red-500 @enderror"
                                    required>
                                <option value="hogar" {{ old('tipo_cliente', $cliente->tipo_cliente) == 'hogar' ? 'selected' : '' }}>🏠 Hogar</option>
                                <option value="comercio" {{ old('tipo_cliente', $cliente->tipo_cliente) == 'comercio' ? 'selected' : '' }}>🏢 Comercio</option>
                                <option value="empresa" {{ old('tipo_cliente', $cliente->tipo_cliente) == 'empresa' ? 'selected' : '' }}>🏭 Empresa</option>
                            </select>
                            @error('tipo_cliente')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Producto Predeterminado -->
                        <div>
                            <label for="producto_id" class="block text-sm font-medium text-slate-700 mb-2">
                                Producto
                            </label>
                            <select id="producto_id"
                                    name="producto_id"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent @error('producto_id') border-red-500 @enderror">
                                <option value="">— Sin producto predeterminado —</option>
                                @foreach($productos as $producto)
                                    <option value="{{ $producto->id }}"
                                            {{ old('producto_id', $cliente->producto_id) == $producto->id ? 'selected' : '' }}>
                                        {{ $producto->nombre }} — ${{ number_format($producto->precio_base, 2) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('producto_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-slate-500">Se usará como producto predeterminado al crear repartos</p>
                        </div>

                        <!-- Estado -->
                        <div class="md:col-span-2">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" 
                                       name="activo" 
                                       value="1" 
                                       {{ old('activo', $cliente->activo) ? 'checked' : '' }}
                                       class="w-5 h-5 text-sky-600 border-slate-300 rounded focus:ring-2 focus:ring-sky-500">
                                <span class="text-sm font-medium text-slate-700">Cliente Activo</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Observaciones -->
                <div>
                    <label for="observaciones" class="block text-sm font-medium text-slate-700 mb-2">
                        Observaciones
                    </label>
                    <textarea id="observaciones" 
                              name="observaciones" 
                              rows="3" 
                              class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent @error('observaciones') border-red-500 @enderror"
                              placeholder="Notas adicionales sobre el cliente...">{{ old('observaciones', $cliente->observaciones) }}</textarea>
                    @error('observaciones')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </x-card>

        <!-- Actions -->
        <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-3">
            <a href="{{ route('clientes.index') }}" class="w-full sm:w-auto">
                <x-button variant="secondary" type="button" class="w-full sm:w-auto">
                    <x-slot:icon>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </x-slot:icon>
                    Cancelar
                </x-button>
            </a>

            <x-button variant="primary" type="submit" class="w-full sm:w-auto">
                <x-slot:icon>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </x-slot:icon>
                Actualizar Cliente
            </x-button>
        </div>
    </form>
</div>

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
let map;
let marker;

// Inicializar el mapa cuando se carga la página
document.addEventListener('DOMContentLoaded', function() {
    // Centro inicial en Formosa, Argentina
    const defaultLat = -26.1857;
    const defaultLng = -58.1756;
    
    // Obtener coordenadas actuales del cliente (si existen)
    const latInput = document.getElementById('latitude');
    const lngInput = document.getElementById('longitude');
    const hasCoords = latInput.value && lngInput.value;
    
    const initialLat = hasCoords ? parseFloat(latInput.value) : defaultLat;
    const initialLng = hasCoords ? parseFloat(lngInput.value) : defaultLng;
    const initialZoom = hasCoords ? 16 : 13;
    
    // Inicializar mapa
    map = L.map('map').setView([initialLat, initialLng], initialZoom);
    
    // Agregar capa de tiles de OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 19
    }).addTo(map);
    
    // Si ya hay coordenadas guardadas, mostrar marcador
    if (hasCoords) {
        agregarMarcador(initialLat, initialLng);
    }
    
    // Permitir hacer clic en el mapa para establecer ubicación
    map.on('click', function(e) {
        agregarMarcador(e.latlng.lat, e.latlng.lng);
    });
});

// Función para agregar o mover marcador
function agregarMarcador(lat, lng) {
    // Si ya existe un marcador, removerlo
    if (marker) {
        map.removeLayer(marker);
    }
    
    // Crear nuevo marcador draggable (arrastrable)
    marker = L.marker([lat, lng], {
        draggable: true
    }).addTo(map);
    
    // Actualizar campos cuando se arrastra el marcador
    marker.on('dragend', function(e) {
        const position = e.target.getLatLng();
        actualizarCoordenadas(position.lat, position.lng);
    });
    
    // Popup con información
    marker.bindPopup(`<b>Ubicación del Cliente</b><br>Lat: ${lat.toFixed(6)}<br>Lng: ${lng.toFixed(6)}`).openPopup();
    
    // Actualizar campos de coordenadas
    actualizarCoordenadas(lat, lng);
}

// Actualizar campos de input con las coordenadas
function actualizarCoordenadas(lat, lng) {
    document.getElementById('latitude').value = lat.toFixed(8);
    document.getElementById('longitude').value = lng.toFixed(8);
}

// Buscar dirección en el mapa usando geocodificación
function buscarEnMapa() {
    const direccion = document.getElementById('direccion').value;
    const colonia = document.getElementById('colonia').value;
    const ciudad = document.getElementById('ciudad').value;
    
    if (!direccion || !ciudad) {
        alert('Por favor, completa la dirección y ciudad primero.');
        return;
    }
    
    // Construir dirección completa para Argentina
    let direccionCompleta = direccion;
    if (colonia) direccionCompleta += ', ' + colonia;
    direccionCompleta += ', ' + ciudad + ', Formosa, Argentina';
    
    // Mostrar indicador de carga
    const btnBuscar = event.target;
    const textoOriginal = btnBuscar.innerHTML;
    btnBuscar.innerHTML = '🔍 Buscando...';
    btnBuscar.disabled = true;
    
    // Usar la API de Nominatim (OpenStreetMap) para geocodificación gratuita
    const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(direccionCompleta)}&limit=1`;
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data && data.length > 0) {
                const lat = parseFloat(data[0].lat);
                const lng = parseFloat(data[0].lon);
                
                // Centrar mapa y agregar marcador
                map.setView([lat, lng], 16);
                agregarMarcador(lat, lng);
                
                // Mostrar mensaje de éxito
                alert('✓ Ubicación encontrada en el mapa. Puedes ajustar el marcador arrastrándolo si es necesario.');
            } else {
                alert('⚠️ No se encontró la dirección exacta. Por favor, haz clic en el mapa para marcar la ubicación manualmente.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('❌ Error al buscar la dirección. Intenta hacer clic en el mapa para marcar la ubicación manualmente.');
        })
        .finally(() => {
            btnBuscar.innerHTML = textoOriginal;
            btnBuscar.disabled = false;
        });
}
</script>
@endpush

@endsection
