@extends('layouts.app')

@section('title', 'Vehículos')

@section('breadcrumbs')
    <span class="text-slate-600">Vehículos</span>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-slate-900">Vehículos</h1>
            <p class="text-slate-600 mt-1">Gestión de la flota vehicular</p>
        </div>
        @can('create', App\Models\Vehiculo::class)
        <a href="{{ route('vehiculos.create') }}">
            <x-button variant="primary">
                <x-slot:icon>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </x-slot:icon>
                Nuevo Vehículo
            </x-button>
        </a>
        @endcan
    </div>

    <!-- Filtros -->
    <x-card>
        <form method="GET" action="{{ route('vehiculos.index') }}" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <x-text-input 
                    type="text" 
                    name="search" 
                    placeholder="Buscar por placa, marca o modelo..."
                    value="{{ request('search') }}"
                />
            </div>

            <div class="w-full md:w-48">
                <select name="tipo" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Todos los tipos</option>
                    <option value="camion" {{ request('tipo') === 'camion' ? 'selected' : '' }}>Camión</option>
                    <option value="camioneta" {{ request('tipo') === 'camioneta' ? 'selected' : '' }}>Camioneta</option>
                    <option value="furgoneta" {{ request('tipo') === 'furgoneta' ? 'selected' : '' }}>Furgoneta</option>
                    <option value="auto" {{ request('tipo') === 'auto' ? 'selected' : '' }}>Auto</option>
                    <option value="moto" {{ request('tipo') === 'moto' ? 'selected' : '' }}>Moto</option>
                </select>
            </div>

            <div class="w-full md:w-48">
                <select name="estado" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Todos los estados</option>
                    <option value="disponible" {{ request('estado') === 'disponible' ? 'selected' : '' }}>Disponible</option>
                    <option value="en_uso" {{ request('estado') === 'en_uso' ? 'selected' : '' }}>En Uso</option>
                    <option value="mantenimiento" {{ request('estado') === 'mantenimiento' ? 'selected' : '' }}>Mantenimiento</option>
                    <option value="fuera_servicio" {{ request('estado') === 'fuera_servicio' ? 'selected' : '' }}>Fuera de Servicio</option>
                </select>
            </div>

            <div class="flex gap-2">
                <x-button type="submit" variant="secondary">Filtrar</x-button>
                <a href="{{ route('vehiculos.index') }}">
                    <x-button type="button" variant="outline">Limpiar</x-button>
                </a>
            </div>
        </form>
    </x-card>

    <!-- Estadísticas -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <x-card class="bg-gradient-to-br from-green-500 to-green-600 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Disponibles</p>
                    <p class="text-3xl font-bold mt-1">{{ $vehiculos->where('estado', 'disponible')->count() }}</p>
                </div>
                <svg class="w-12 h-12 text-green-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </x-card>

        <x-card class="bg-gradient-to-br from-blue-500 to-blue-600 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">En Uso</p>
                    <p class="text-3xl font-bold mt-1">{{ $vehiculos->where('estado', 'en_uso')->count() }}</p>
                </div>
                <svg class="w-12 h-12 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            </div>
        </x-card>

        <x-card class="bg-gradient-to-br from-amber-500 to-amber-600 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-amber-100 text-sm font-medium">Mantenimiento</p>
                    <p class="text-3xl font-bold mt-1">{{ $vehiculos->where('estado', 'mantenimiento')->count() }}</p>
                </div>
                <svg class="w-12 h-12 text-amber-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>
        </x-card>

        <x-card class="bg-gradient-to-br from-slate-500 to-slate-600 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-100 text-sm font-medium">Fuera de Servicio</p>
                    <p class="text-3xl font-bold mt-1">{{ $vehiculos->where('estado', 'fuera_servicio')->count() }}</p>
                </div>
                <svg class="w-12 h-12 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                </svg>
            </div>
        </x-card>
    </div>

    <!-- Lista de vehículos -->
    <x-card>
        @if($vehiculos->isEmpty())
        <div class="text-center py-12">
            <svg class="w-16 h-16 text-slate-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0h.01M15 17a2 2 0 104 0m-4 0h.01M9 9h1"></path>
            </svg>
            <p class="text-slate-500 font-medium">No se encontraron vehículos</p>
            <p class="text-slate-400 text-sm mt-1">Intenta ajustar los filtros o agrega un nuevo vehículo</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Placa</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Vehículo</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Tipo</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Capacidad</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Chofer</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Estado</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Km</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-slate-600 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @foreach($vehiculos as $vehiculo)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-4">
                            <div class="font-medium text-slate-900">{{ $vehiculo->placa }}</div>
                        </td>
                        <td class="px-4 py-4">
                            <div class="font-medium text-slate-900">{{ $vehiculo->marca }} {{ $vehiculo->modelo }}</div>
                            <div class="text-sm text-slate-500">{{ $vehiculo->año }}</div>
                        </td>
                        <td class="px-4 py-4">
                            @php
                                $tipoIcons = [
                                    'camion' => '🚛',
                                    'camioneta' => '🚐',
                                    'furgoneta' => '🚙',
                                    'auto' => '🚗',
                                    'moto' => '🏍️',
                                ];
                            @endphp
                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-slate-100 text-slate-700 rounded text-sm">
                                <span>{{ $tipoIcons[$vehiculo->tipo] ?? '🚗' }}</span>
                                {{ ucfirst($vehiculo->tipo) }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-sm text-slate-600">
                            @if($vehiculo->capacidad_bidones)
                            {{ $vehiculo->capacidad_bidones }} bidones
                            @else
                            -
                            @endif
                        </td>
                        <td class="px-4 py-4">
                            @if($vehiculo->choferesActivos->isNotEmpty())
                            <div class="text-sm">
                                @foreach($vehiculo->choferesActivos as $chofer)
                                <div class="text-slate-900">{{ $chofer->nombre_completo }}</div>
                                @endforeach
                            </div>
                            @else
                            <span class="text-sm text-slate-400">Sin asignar</span>
                            @endif
                        </td>
                        <td class="px-4 py-4">
                            @php
                                $estadoColors = [
                                    'disponible' => 'bg-green-100 text-green-700',
                                    'en_uso' => 'bg-blue-100 text-blue-700',
                                    'mantenimiento' => 'bg-amber-100 text-amber-700',
                                    'fuera_servicio' => 'bg-slate-100 text-slate-700',
                                ];
                            @endphp
                            <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-full text-xs font-medium {{ $estadoColors[$vehiculo->estado] ?? 'bg-slate-100 text-slate-700' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $vehiculo->estado === 'disponible' ? 'bg-green-500' : ($vehiculo->estado === 'en_uso' ? 'bg-blue-500' : ($vehiculo->estado === 'mantenimiento' ? 'bg-amber-500' : 'bg-slate-400')) }}"></span>
                                {{ ucfirst(str_replace('_', ' ', $vehiculo->estado)) }}
                            </span>
                            @if($vehiculo->necesitaMantenimiento())
                            <div class="mt-1 text-xs text-amber-600 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                Requiere mantto.
                            </div>
                            @endif
                        </td>
                        <td class="px-4 py-4 text-sm text-slate-600">
                            {{ number_format($vehiculo->kilometraje) }} km
                        </td>
                        <td class="px-4 py-4 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('vehiculos.show', $vehiculo) }}" class="p-2.5 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors min-h-touch min-w-touch flex items-center justify-center" title="Ver detalles">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                                @can('update', $vehiculo)
                                <a href="{{ route('vehiculos.edit', $vehiculo) }}" class="p-2.5 text-slate-600 hover:bg-slate-100 rounded-lg transition-colors min-h-touch min-w-touch flex items-center justify-center" title="Editar">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                @endcan
                                @can('delete', $vehiculo)
                                <form action="{{ route('vehiculos.destroy', $vehiculo) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este vehículo?');" class="inline-flex">
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
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        @if($vehiculos->hasPages())
        <div class="mt-6">
            {{ $vehiculos->links() }}
        </div>
        @endif
        @endif
    </x-card>
</div>
@endsection
