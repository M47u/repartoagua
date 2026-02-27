@extends('layouts.app')

@section('title', 'Dashboard Administrativo')

@section('breadcrumbs')
    <span class="text-slate-400">Dashboard</span>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Welcome Section -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">¡Hola, {{ Auth::user()->name }}!</h1>
            <p class="text-slate-500 mt-1">{{ \Carbon\Carbon::now()->translatedFormat('l, d \d\e F \d\e Y') }}</p>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Repartos de Hoy -->
        <x-stat-card
            title="Repartos de Hoy"
            value="{{ $repartosHoy ?? 24 }}"
            color="sky"
            trend="+12%"
            :trend-up="true"
            subtitle="vs. ayer"
        >
            <x-slot:icon>
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path>
                </svg>
            </x-slot:icon>
        </x-stat-card>

        <!-- Pendientes de Entrega -->
        <x-stat-card
            title="Pendientes de Entrega"
            value="{{ $repartosPendientes ?? 8 }}"
            color="amber"
            subtitle="en proceso"
        >
            <x-slot:icon>
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </x-slot:icon>
        </x-stat-card>

        <!-- Ingresos del Mes -->
        <x-stat-card
            title="Ingresos del Mes"
            value="${{ number_format($ingresosMes ?? 45280, 0, ',', '.') }}"
            color="emerald"
            trend="+8%"
            :trend-up="true"
            subtitle="vs. mes anterior"
        >
            <x-slot:icon>
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </x-slot:icon>
        </x-stat-card>

        <!-- Clientes con Deuda -->
        <x-stat-card
            title="Clientes con Deuda"
            value="{{ $clientesDeuda ?? 12 }}"
            color="red"
            subtitle="requieren atención"
        >
            <x-slot:icon>
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </x-slot:icon>
        </x-stat-card>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Repartos de Hoy -->
        <x-card title="Repartos de Hoy" :padding="false">
            <x-slot:icon>
                <svg class="w-6 h-6 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path>
                </svg>
            </x-slot:icon>

            <x-slot:actions>
                <a href="{{ route('repartos.index') }}" class="text-sm font-medium text-sky-600 hover:text-sky-700">
                    Ver todos →
                </a>
            </x-slot:actions>

            <div class="divide-y divide-slate-100">
                @forelse($repartosRecientes ?? [] as $reparto)
                    <div class="flex items-center justify-between p-4 hover:bg-slate-50 transition-colors">
                        <div class="flex items-center gap-4 flex-1">
                            <div class="w-12 h-12 bg-gradient-to-br from-sky-400 to-sky-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-slate-900">{{ $reparto->cliente->nombre }}</p>
                                <div class="flex items-center gap-2 mt-1">
                                    <p class="text-sm text-slate-500">{{ $reparto->repartidor->name }}</p>
                                    <span class="text-slate-400">•</span>
                                    <p class="text-sm text-slate-500">{{ $reparto->producto->nombre ?? 'Producto' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <x-badge :color="$reparto->estado === 'entregado' ? 'success' : ($reparto->estado === 'pendiente' ? 'warning' : 'info')">
                                {{ ucfirst($reparto->estado) }}
                            </x-badge>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-slate-500">
                        <p>No hay repartos programados para hoy</p>
                    </div>
                @endforelse
            </div>

            <x-slot:footer>
                <a href="{{ route('repartos.create') }}" class="inline-flex items-center gap-2 text-sm font-medium text-sky-600 hover:text-sky-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Nuevo Reparto
                </a>
            </x-slot:footer>
        </x-card>

        <!-- Top 5 Clientes del Mes -->
        <x-card title="Top 5 Clientes del Mes" :padding="false">
            <x-slot:icon>
                <svg class="w-6 h-6 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </x-slot:icon>

            <div class="p-6 space-y-4">
                @forelse($topClientes ?? [] as $index => $cliente)
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-sky-400 to-sky-600 rounded-full flex items-center justify-center flex-shrink-0">
                            <span class="text-white font-bold text-sm">{{ $index + 1 }}</span>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-slate-900">{{ $cliente->nombre }}</p>
                            <p class="text-sm text-slate-500">{{ $cliente->total_bidones ?? 0 }} bidones</p>
                        </div>
                        <div class="w-24 bg-slate-100 rounded-full h-2">
                            <div class="bg-gradient-to-r from-sky-500 to-sky-600 h-2 rounded-full" style="width: {{ min(100, ($cliente->total_bidones ?? 0) / 50 * 100) }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-slate-500">No hay datos disponibles</p>
                @endforelse
            </div>
        </x-card>
    </div>

    <!-- Second Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Actividad Reciente -->
        <x-card title="Actividad Reciente" :padding="false">
            <x-slot:icon>
                <svg class="w-6 h-6 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </x-slot:icon>

            <div class="p-6 space-y-6">
                @forelse($actividadReciente ?? [] as $actividad)
                    <div class="flex gap-4">
                        <div class="flex flex-col items-center">
                            <div class="w-10 h-10 {{ $actividad['color'] ?? 'bg-sky-100' }} rounded-full flex items-center justify-center">
                                {!! $actividad['icon'] ?? '<svg class="w-5 h-5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>' !!}
                            </div>
                            @if(!$loop->last)
                                <div class="w-0.5 h-full bg-slate-200 mt-2"></div>
                            @endif
                        </div>
                        <div class="flex-1 pb-6">
                            <p class="font-medium text-slate-900">{{ $actividad['title'] ?? '' }}</p>
                            <p class="text-sm text-slate-500 mt-1">{{ $actividad['description'] ?? '' }}</p>
                            <p class="text-xs text-slate-400 mt-2">{{ $actividad['time'] ?? '' }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-slate-500 py-8">No hay actividad reciente</p>
                @endforelse
            </div>
        </x-card>

        <!-- Repartidores Activos -->
        <x-card title="Repartidores Activos" :padding="false">
            <x-slot:icon>
                <svg class="w-6 h-6 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </x-slot:icon>

            <div class="divide-y divide-slate-100">
                @forelse($repartidoresActivos ?? [] as $repartidor)
                    <div class="flex items-center justify-between p-4 hover:bg-slate-50 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-gradient-to-br from-sky-400 to-sky-600 rounded-full flex items-center justify-center">
                                <span class="text-white font-semibold">{{ strtoupper(substr($repartidor->name, 0, 2)) }}</span>
                            </div>
                            <div>
                                <p class="font-semibold text-slate-900">{{ $repartidor->name }}</p>
                                <p class="text-sm text-slate-500">
                                    {{ $repartidor->repartos_completados ?? 0 }} / {{ $repartidor->repartos_asignados ?? 0 }} completados
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></div>
                            <span class="text-xs font-medium text-emerald-600">Activo</span>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-slate-500">
                        <p>No hay repartidores activos</p>
                    </div>
                @endforelse
            </div>
        </x-card>
    </div>
</div>
@endsection
