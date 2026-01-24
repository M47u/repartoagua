@extends('layouts.app')

@section('title', 'Repartos')

@section('breadcrumbs')
    <span class="text-slate-400">Repartos</span>
@endsection

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

    <!-- Filters -->
    <x-card :padding="false">
        <div class="p-6">
            <div class="flex flex-col md:flex-row gap-4">
                <!-- Date Picker -->
                <div class="flex-1">
                    <input 
                        type="date" 
                        value="{{ request('fecha', today()->format('Y-m-d')) }}"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent"
                    >
                </div>

                <!-- Filters -->
                <div class="flex gap-2 flex-wrap">
                    <select class="px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
                        <option value="">Estados...</option>
                        <option value="pendiente">Pendientes</option>
                        <option value="en_camino">En camino</option>
                        <option value="entregado">Entregados</option>
                    </select>

                    @if(auth()->user()->role !== 'repartidor')
                    <select class="px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
                        <option value="">Todos los repartidores</option>
                        @foreach($repartidores ?? [] as $repartidor)
                            <option value="{{ $repartidor->id }}">{{ $repartidor->name }}</option>
                        @endforeach
                    </select>
                    @endif
                </div>
            </div>
        </div>
    </x-card>

    <!-- Repartos List -->
    @if(auth()->user()->role !== 'repartidor')
        <!-- Vista Administrativa: Agrupado por Repartidor -->
        @forelse($repartidoresDia ?? [] as $repartidor)
            <x-card :title="$repartidor->name" :padding="false">
                <x-slot:icon>
                    <div class="w-10 h-10 bg-gradient-to-br from-sky-400 to-sky-600 rounded-full flex items-center justify-center">
                        <span class="text-white font-semibold text-sm">{{ strtoupper(substr($repartidor->name, 0, 2)) }}</span>
                    </div>
                </x-slot:icon>

                <x-slot:actions>
                    <x-badge color="info">
                        {{ $repartidor->repartos->count() }} repartos
                    </x-badge>
                </x-slot:actions>

                <div class="divide-y divide-slate-100">
                    @foreach($repartidor->repartos as $reparto)
                        <div class="p-4 hover:bg-slate-50 transition-colors">
                            <div class="flex items-center justify-between gap-4">
                                <div class="flex items-center gap-4 flex-1">
                                    <div class="text-center">
                                        <p class="text-xl font-bold text-slate-900">{{ \Carbon\Carbon::parse($reparto->fecha)->format('H:i') }}</p>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-semibold text-slate-900">{{ $reparto->cliente->nombre }}</p>
                                        <p class="text-sm text-slate-500">{{ Str::limit($reparto->cliente->direccion, 50) }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <x-badge color="info">{{ $reparto->cantidad }}</x-badge>
                                    <x-badge :color="$reparto->estado === 'entregado' ? 'success' : ($reparto->estado === 'pendiente' ? 'warning' : 'info')">
                                        {{ ucfirst($reparto->estado) }}
                                    </x-badge>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-card>
        @empty
            <x-card>
                <x-empty-state
                    title="No hay repartos programados"
                    description="Comienza creando un nuevo reparto para la fecha seleccionada"
                    action-url="{{ route('repartos.create') }}"
                    action-text="Crear Reparto"
                >
                    <x-slot:icon>
                        <svg class="w-16 h-16 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path>
                        </svg>
                    </x-slot:icon>
                </x-empty-state>
            </x-card>
        @endforelse
    @else
        <!-- Vista Repartidor: Solo sus repartos -->
        <x-card :padding="false">
            <div class="divide-y divide-slate-100">
                @forelse($repartos ?? [] as $reparto)
                    <div class="p-6">
                        <!-- Similar al dashboard de repartidor -->
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
    @endif
</div>
@endsection
