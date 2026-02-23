@extends('layouts.app')

@section('title', 'Detalle de Vehículo')

@section('breadcrumbs')
    <a href="{{ route('vehiculos.index') }}" class="text-slate-400 hover:text-slate-600">Vehículos</a>
    <span class="text-slate-400">/</span>
    <span class="text-slate-600">{{ $vehiculo->placa }}</span>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center text-white text-2xl">
                @php
                    $tipoIcons = [
                        'camion' => '🚛',
                        'camioneta' => '🚐',
                        'furgoneta' => '🚙',
                        'auto' => '🚗',
                        'moto' => '🏍️',
                    ];
                @endphp
                {{ $tipoIcons[$vehiculo->tipo] ?? '🚗' }}
            </div>
            <div>
                <h1 class="text-3xl font-bold text-slate-900">{{ $vehiculo->placa }}</h1>
                <p class="text-lg text-slate-600 mt-1">{{ $vehiculo->marca }} {{ $vehiculo->modelo }} {{ $vehiculo->año }}</p>
                <div class="flex items-center gap-2 mt-2">
                    @php
                        $estadoColors = [
                            'disponible' => 'bg-green-100 text-green-700',
                            'en_uso' => 'bg-blue-100 text-blue-700',
                            'mantenimiento' => 'bg-amber-100 text-amber-700',
                            'fuera_servicio' => 'bg-slate-100 text-slate-700',
                        ];
                    @endphp
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-sm font-medium {{ $estadoColors[$vehiculo->estado] ?? 'bg-slate-100 text-slate-700' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $vehiculo->estado === 'disponible' ? 'bg-green-500' : ($vehiculo->estado === 'en_uso' ? 'bg-blue-500' : ($vehiculo->estado === 'mantenimiento' ? 'bg-amber-500' : 'bg-slate-400')) }}"></span>
                        {{ ucfirst(str_replace('_', ' ', $vehiculo->estado)) }}
                    </span>
                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-slate-100 text-slate-700 rounded-full text-sm font-medium">
                        {{ ucfirst($vehiculo->tipo) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="flex gap-2">
            @can('update', $vehiculo)
            <a href="{{ route('vehiculos.edit', $vehiculo) }}">
                <x-button variant="primary">
                    <x-slot:icon>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </x-slot:icon>
                    Editar
                </x-button>
            </a>
            @endcan

            @can('delete', $vehiculo)
            <form action="{{ route('vehiculos.destroy', $vehiculo) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este vehículo?');">
                @csrf
                @method('DELETE')
                <x-button type="submit" variant="danger">
                    <x-slot:icon>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </x-slot:icon>
                    Eliminar
                </x-button>
            </form>
            @endcan
        </div>
    </div>

    <!-- Alertas de Mantenimiento -->
    @if($vehiculo->necesitaMantenimiento())
    <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
        <div class="flex items-start gap-3">
            <svg class="w-6 h-6 text-amber-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            <div class="flex-1">
                <h4 class="font-semibold text-amber-900">Requiere Mantenimiento</h4>
                <p class="text-sm text-amber-700 mt-1">
                    @if($vehiculo->proximo_mantenimiento && $vehiculo->proximo_mantenimiento < now())
                        El mantenimiento está vencido desde el {{ $vehiculo->proximo_mantenimiento->format('d/m/Y') }}
                    @else
                        Es hora de programar el mantenimiento de este vehículo
                    @endif
                </p>
            </div>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Información del Vehículo -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Datos Técnicos -->
            <x-card>
                <h3 class="text-lg font-semibold text-slate-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Datos Técnicos
                </h3>
                <div class="space-y-4">
                    @if($vehiculo->color)
                    <div>
                        <label class="text-sm font-medium text-slate-500">Color</label>
                        <p class="text-slate-900">{{ $vehiculo->color }}</p>
                    </div>
                    @endif

                    @if($vehiculo->numero_motor)
                    <div>
                        <label class="text-sm font-medium text-slate-500">Número de Motor</label>
                        <p class="text-slate-900 font-mono text-sm">{{ $vehiculo->numero_motor }}</p>
                    </div>
                    @endif

                    @if($vehiculo->numero_chasis)
                    <div>
                        <label class="text-sm font-medium text-slate-500">Número de Chasis</label>
                        <p class="text-slate-900 font-mono text-sm">{{ $vehiculo->numero_chasis }}</p>
                    </div>
                    @endif

                    @if($vehiculo->combustible)
                    <div>
                        <label class="text-sm font-medium text-slate-500">Combustible</label>
                        <p class="text-slate-900">{{ ucfirst($vehiculo->combustible) }}</p>
                    </div>
                    @endif

                    <div>
                        <label class="text-sm font-medium text-slate-500">Kilometraje</label>
                        <p class="text-slate-900 text-xl font-semibold">{{ number_format($vehiculo->kilometraje) }} km</p>
                    </div>

                    @if($vehiculo->capacidad_carga)
                    <div>
                        <label class="text-sm font-medium text-slate-500">Capacidad de Carga</label>
                        <p class="text-slate-900">{{ number_format($vehiculo->capacidad_carga, 2) }} kg</p>
                    </div>
                    @endif

                    @if($vehiculo->capacidad_bidones)
                    <div>
                        <label class="text-sm font-medium text-slate-500">Capacidad en Bidones</label>
                        <p class="text-slate-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            {{ $vehiculo->capacidad_bidones }} bidones
                        </p>
                    </div>
                    @endif
                </div>
            </x-card>

            <!-- Fechas Importantes -->
            <x-card>
                <h3 class="text-lg font-semibold text-slate-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Fechas Importantes
                </h3>
                <div class="space-y-4">
                    @if($vehiculo->ultimo_mantenimiento)
                    <div>
                        <label class="text-sm font-medium text-slate-500">Último Mantenimiento</label>
                        <p class="text-slate-900">{{ $vehiculo->ultimo_mantenimiento->format('d/m/Y') }}</p>
                        <p class="text-xs text-slate-500">{{ $vehiculo->ultimo_mantenimiento->diffForHumans() }}</p>
                    </div>
                    @endif

                    @if($vehiculo->proximo_mantenimiento)
                    <div>
                        <label class="text-sm font-medium text-slate-500">Próximo Mantenimiento</label>
                        @if($vehiculo->proximo_mantenimiento < now())
                        <p class="text-red-600 font-medium">{{ $vehiculo->proximo_mantenimiento->format('d/m/Y') }}</p>
                        <p class="text-xs text-red-500">⚠️ Vencido {{ $vehiculo->proximo_mantenimiento->diffForHumans() }}</p>
                        @else
                        <p class="text-slate-900">{{ $vehiculo->proximo_mantenimiento->format('d/m/Y') }}</p>
                        <p class="text-xs text-slate-500">{{ $vehiculo->proximo_mantenimiento->diffForHumans() }}</p>
                        @endif
                    </div>
                    @endif

                    @if($vehiculo->seguro_vencimiento)
                    <div>
                        <label class="text-sm font-medium text-slate-500">Vencimiento del Seguro</label>
                        @if($vehiculo->seguro_vencimiento < now())
                        <p class="text-red-600 font-medium">{{ $vehiculo->seguro_vencimiento->format('d/m/Y') }}</p>
                        <p class="text-xs text-red-500">⚠️ Vencido</p>
                        @else
                        <p class="text-slate-900">{{ $vehiculo->seguro_vencimiento->format('d/m/Y') }}</p>
                        <p class="text-xs text-slate-500">{{ $vehiculo->seguro_vencimiento->diffForHumans() }}</p>
                        @endif
                    </div>
                    @endif

                    @if($vehiculo->revision_tecnica)
                    <div>
                        <label class="text-sm font-medium text-slate-500">Revisión Técnica</label>
                        @if($vehiculo->revision_tecnica < now())
                        <p class="text-red-600 font-medium">{{ $vehiculo->revision_tecnica->format('d/m/Y') }}</p>
                        <p class="text-xs text-red-500">⚠️ Vencida</p>
                        @else
                        <p class="text-slate-900">{{ $vehiculo->revision_tecnica->format('d/m/Y') }}</p>
                        <p class="text-xs text-slate-500">{{ $vehiculo->revision_tecnica->diffForHumans() }}</p>
                        @endif
                    </div>
                    @endif
                </div>
            </x-card>

            <!-- Observaciones -->
            @if($vehiculo->observaciones)
            <x-card>
                <h3 class="text-lg font-semibold text-slate-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                    </svg>
                    Observaciones
                </h3>
                <p class="text-slate-700 text-sm whitespace-pre-line">{{ $vehiculo->observaciones }}</p>
            </x-card>
            @endif
        </div>

        <!-- Choferes y Actividad -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Choferes Asignados -->
            <x-card>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-slate-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Choferes Asignados
                    </h3>
                </div>

                @if($vehiculo->choferesActivos->isNotEmpty())
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($vehiculo->choferesActivos as $chofer)
                    <a href="{{ route('usuarios.show', $chofer) }}" class="block p-4 bg-slate-50 rounded-lg hover:bg-slate-100 transition-colors">
                        <div class="flex items-start gap-3">
                            <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center text-white text-xl font-bold">
                                {{ strtoupper(substr($chofer->name, 0, 1)) }}{{ strtoupper(substr($chofer->apellido ?? '', 0, 1)) }}
                            </div>
                            <div class="flex-1">
                                <div class="font-medium text-slate-900">{{ $chofer->nombre_completo }}</div>
                                <div class="text-sm text-slate-500">{{ $chofer->email }}</div>
                                @if($chofer->telefono)
                                <div class="text-sm text-slate-600 mt-1 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    {{ $chofer->telefono }}
                                </div>
                                @endif
                                @php
                                    $asignacion = $chofer->pivot;
                                @endphp
                                @if($asignacion->fecha_asignacion)
                                <div class="text-xs text-slate-500 mt-1">
                                    Asignado desde {{ \Carbon\Carbon::parse($asignacion->fecha_asignacion)->format('d/m/Y') }}
                                </div>
                                @endif
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
                @else
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-slate-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <p class="text-slate-500 font-medium">Sin choferes asignados</p>
                    <p class="text-slate-400 text-sm mt-1">Este vehículo no tiene choferes asociados actualmente</p>
                    @can('update', $vehiculo)
                    <a href="{{ route('vehiculos.edit', $vehiculo) }}" class="inline-block mt-4 text-sm text-indigo-600 hover:text-indigo-700">
                        Asignar chofer →
                    </a>
                    @endcan
                </div>
                @endif
            </x-card>

            <!-- Historial de Asignaciones -->
            @if($vehiculo->choferes->isNotEmpty())
            <x-card>
                <h3 class="text-lg font-semibold text-slate-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Historial de Asignaciones
                </h3>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Chofer</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Fecha Asignación</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Fecha Desasignación</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach($vehiculo->choferes->sortByDesc('pivot.fecha_asignacion') as $chofer)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3 text-sm text-slate-900">
                                    <a href="{{ route('usuarios.show', $chofer) }}" class="text-indigo-600 hover:text-indigo-900">
                                        {{ $chofer->nombre_completo }}
                                    </a>
                                </td>
                                <td class="px-4 py-3 text-sm text-slate-600">
                                    {{ $chofer->pivot->fecha_asignacion ? \Carbon\Carbon::parse($chofer->pivot->fecha_asignacion)->format('d/m/Y') : '-' }}
                                </td>
                                <td class="px-4 py-3 text-sm text-slate-600">
                                    {{ $chofer->pivot->fecha_desasignacion ? \Carbon\Carbon::parse($chofer->pivot->fecha_desasignacion)->format('d/m/Y') : '-' }}
                                </td>
                                <td class="px-4 py-3">
                                    @if($chofer->pivot->asignacion_activa)
                                    <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">Activo</span>
                                    @else
                                    <span class="px-2 py-1 bg-slate-100 text-slate-600 rounded-full text-xs font-medium">Inactivo</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </x-card>
            @endif
        </div>
    </div>
</div>
@endsection
