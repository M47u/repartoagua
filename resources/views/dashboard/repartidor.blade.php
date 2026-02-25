@extends('layouts.app')

@section('title', 'Mi Dashboard')

@section('breadcrumbs')
    <span class="text-slate-400">Mis Repartos</span>
@endsection

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Welcome Hero -->
    <div class="bg-gradient-to-br from-sky-500 to-sky-700 rounded-2xl p-8 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold mb-2">¡Hola, {{ explode(' ', Auth::user()->name)[0] }}!</h1>
                <p class="text-sky-100 text-lg">{{ \Carbon\Carbon::now()->translatedFormat('l, d \d\e F \d\e Y') }}</p>
            </div>
            <div class="hidden md:block">
                <svg class="w-24 h-24 text-sky-300 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path>
                </svg>
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="mt-6">
            <div class="flex items-center justify-between text-sm mb-2">
                <span>Progreso del día</span>
                <span class="font-semibold">{{ $completados ?? 0 }} de {{ $total ?? 0 }} repartos</span>
            </div>
            <div class="w-full bg-sky-600 rounded-full h-3">
                <div class="bg-white rounded-full h-3 transition-all duration-500" style="width: {{ $total > 0 ? (($completados ?? 0) / $total * 100) : 0 }}%"></div>
            </div>
        </div>

        <!-- Botón de Ruta Óptima -->
        @if($stats['repartos_pendientes'] > 0)
        <div class="mt-6">
            <a href="{{ route('repartos.index') }}" class="block w-full sm:w-auto">
                <button class="w-full sm:w-auto px-6 py-3 bg-white text-sky-700 font-bold rounded-lg hover:bg-sky-50 transition-all duration-200 flex items-center justify-center gap-3 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                    </svg>
                    <span>Ver Ruta Óptima en Mapa</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </button>
            </a>
            <p class="text-sky-100 text-sm mt-2 text-center sm:text-left">
                🎯 Haz clic aquí para calcular la ruta más eficiente para tus {{ $stats['repartos_pendientes'] }} entregas pendientes
            </p>
        </div>
        @endif
    </div>

    <!-- Repartos del Día -->
    <x-card title="Mis Repartos de Hoy" :padding="false">
        <x-slot:icon>
            <svg class="w-6 h-6 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
            </svg>
        </x-slot:icon>

        <div class="divide-y divide-slate-100">
            @forelse($repartos ?? [] as $reparto)
                <div class="p-6 hover:bg-slate-50 transition-colors">
                    <!-- Header -->
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-4">
                            <div class="text-center">
                                <p class="text-3xl font-bold text-slate-900">{{ \Carbon\Carbon::parse($reparto->fecha)->format('H:i') }}</p>
                                <p class="text-xs text-slate-500 mt-1">Hora estimada</p>
                            </div>
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <h3 class="text-lg font-semibold text-slate-900">{{ $reparto->cliente->nombre }}</h3>
                                    @if($reparto->cliente->tipo === 'hogar')
                                        <span class="text-slate-400" title="Hogar">🏠</span>
                                    @elseif($reparto->cliente->tipo === 'comercio')
                                        <span class="text-slate-400" title="Comercio">🏢</span>
                                    @else
                                        <span class="text-slate-400" title="Empresa">🏭</span>
                                    @endif
                                </div>
                                <div class="flex items-center gap-2 text-slate-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <p class="text-sm">{{ $reparto->cliente->direccion }}</p>
                                </div>
                                @if($reparto->cliente->telefono)
                                    <div class="flex items-center gap-2 text-slate-600 mt-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                        </svg>
                                        <p class="text-sm">{{ $reparto->cliente->telefono }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <x-badge :color="$reparto->estado === 'entregado' ? 'success' : ($reparto->estado === 'pendiente' ? 'warning' : 'info')" size="lg">
                            {{ ucfirst($reparto->estado) }}
                        </x-badge>
                    </div>

                    <!-- Detalles -->
                    <div class="flex items-center gap-4 mb-4 p-4 bg-slate-50 rounded-lg">
                        <div class="flex items-center gap-2">
                            <svg class="w-6 h-6 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                            </svg>
                            <div>
                                <p class="text-sm text-slate-500">Cantidad</p>
                                <p class="text-lg font-bold text-slate-900">{{ $reparto->cantidad }} bidones</p>
                            </div>
                        </div>
                    </div>

                    <!-- Observaciones -->
                    @if($reparto->observaciones)
                        <div class="mb-4 p-3 bg-amber-50 border border-amber-200 rounded-lg">
                            <p class="text-sm font-medium text-amber-900 mb-1">📝 Observaciones:</p>
                            <p class="text-sm text-amber-800">{{ $reparto->observaciones }}</p>
                        </div>
                    @endif

                    <!-- Acciones -->
                    @if($reparto->estado === 'pendiente')
                        <div class="flex flex-col sm:flex-row gap-3">
                            <x-button 
                                variant="success" 
                                size="lg"
                                class="flex-1"
                                onclick="document.getElementById('entregar-form-{{ $reparto->id }}').submit()"
                            >
                                <x-slot:icon>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </x-slot:icon>
                                Marcar como Entregado
                            </x-button>

                            <x-button 
                                variant="outline" 
                                size="lg"
                                x-data=""
                                @click="$dispatch('open-modal', 'observacion-{{ $reparto->id }}')"
                            >
                                <x-slot:icon>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </x-slot:icon>
                                Agregar Observación
                            </x-button>

                            <form id="entregar-form-{{ $reparto->id }}" action="{{ route('repartos.entregar', $reparto) }}" method="POST" class="hidden">
                                @csrf
                                @method('PATCH')
                            </form>
                        </div>
                    @elseif($reparto->estado === 'entregado')
                        <div class="flex items-center gap-2 text-emerald-600 p-3 bg-emerald-50 rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="font-medium">Entregado exitosamente</span>
                        </div>
                    @endif
                </div>
            @empty
                <x-empty-state
                    title="No tienes repartos asignados"
                    description="Cuando se te asignen repartos, aparecerán aquí"
                >
                    <x-slot:icon>
                        <svg class="w-16 h-16 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path>
                        </svg>
                    </x-slot:icon>
                </x-empty-state>
            @endforelse
        </div>
    </x-card>
</div>
@endsection
