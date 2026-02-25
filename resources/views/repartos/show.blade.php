@extends('layouts.app')

@section('title', 'Detalle del Reparto')

@section('breadcrumbs')
    <a href="{{ route('repartos.index') }}" class="text-slate-400 hover:text-slate-600">Repartos</a>
    <span class="text-slate-300 mx-2">/</span>
    <span class="text-slate-700">Reparto #{{ $reparto->id }}</span>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header con Estado -->
    <x-card>
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <h1 class="text-3xl font-bold text-slate-900">Reparto #{{ $reparto->id }}</h1>
                    @if($reparto->estado === 'pendiente')
                        <x-badge color="warning" size="lg">⏳ Pendiente</x-badge>
                    @elseif($reparto->estado === 'entregado')
                        <x-badge color="success" size="lg">✓ Entregado</x-badge>
                    @else
                        <x-badge color="danger" size="lg">✗ Cancelado</x-badge>
                    @endif
                </div>
                <p class="text-slate-500">Programado para: <span class="font-semibold">{{ \Carbon\Carbon::parse($reparto->fecha_programada)->format('d/m/Y') }}</span></p>
            </div>

            @can('update', $reparto)
            <div class="flex gap-2">
                <a href="{{ route('repartos.edit', $reparto) }}">
                    <x-button variant="primary">
                        <x-slot:icon>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </x-slot:icon>
                        Editar
                    </x-button>
                </a>
            </div>
            @endcan
        </div>
    </x-card>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Información del Cliente -->
        <x-card title="Cliente">
            <x-slot:icon>
                <svg class="w-6 h-6 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </x-slot:icon>

            <div class="space-y-3">
                <div>
                    <label class="text-sm font-medium text-slate-500">Nombre</label>
                    <p class="text-slate-900 font-semibold text-lg">{{ $reparto->cliente->nombre }} {{ $reparto->cliente->apellido }}</p>
                </div>

                <div>
                    <label class="text-sm font-medium text-slate-500">Dirección de Entrega</label>
                    <p class="text-slate-900 font-medium">{{ $reparto->cliente->direccion }}</p>
                    @if($reparto->cliente->colonia)
                        <p class="text-slate-600 text-sm">{{ $reparto->cliente->colonia }}, {{ $reparto->cliente->ciudad }}</p>
                    @endif
                </div>

                @if($reparto->cliente->telefono)
                <div>
                    <label class="text-sm font-medium text-slate-500">Teléfono</label>
                    <p class="text-slate-900 font-medium">{{ $reparto->cliente->telefono }}</p>
                </div>
                @endif

                <div class="pt-2">
                    <a href="{{ route('clientes.show', $reparto->cliente) }}" class="text-sky-600 hover:text-sky-700 font-medium text-sm">
                        Ver perfil completo →
                    </a>
                </div>
            </div>
        </x-card>

        <!-- Detalles del Pedido -->
        <x-card title="Detalles del Pedido">
            <x-slot:icon>
                <svg class="w-6 h-6 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
            </x-slot:icon>

            <div class="space-y-3">
                <div>
                    <label class="text-sm font-medium text-slate-500">Producto</label>
                    <p class="text-slate-900 font-semibold text-lg">{{ $reparto->producto->nombre }}</p>
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="text-sm font-medium text-slate-500">Cantidad</label>
                        <p class="text-slate-900 font-bold text-2xl">{{ $reparto->cantidad }}</p>
                    </div>

                    @unless($ocultarPrecios)
                    <div>
                        <label class="text-sm font-medium text-slate-500">P. Unitario</label>
                        <p class="text-slate-900 font-semibold">${{ number_format($reparto->precio_unitario, 2) }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-slate-500">Total</label>
                        <p class="text-emerald-600 font-bold text-xl">${{ number_format($reparto->total, 2) }}</p>
                    </div>
                    @endunless
                </div>

                @if($reparto->notas)
                <div class="pt-2 mt-2 border-t border-slate-200">
                    <label class="text-sm font-medium text-slate-500">Notas</label>
                    <p class="text-slate-700 mt-1">{{ $reparto->notas }}</p>
                </div>
                @endif
            </div>
        </x-card>

        <!-- Repartidor Asignado -->
        <x-card title="Repartidor Asignado">
            <x-slot:icon>
                <svg class="w-6 h-6 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </x-slot:icon>

            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-sky-400 to-sky-600 flex items-center justify-center text-white font-bold text-2xl">
                    {{ strtoupper(substr($reparto->repartidor->name, 0, 2)) }}
                </div>
                <div>
                    <p class="text-slate-900 font-semibold text-lg">{{ $reparto->repartidor->name }}</p>
                    <p class="text-slate-500 text-sm">{{ $reparto->repartidor->email }}</p>
                </div>
            </div>
        </x-card>

        <!-- Información de Fechas -->
        <x-card title="Información de Fechas">
            <x-slot:icon>
                <svg class="w-6 h-6 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </x-slot:icon>

            <div class="space-y-3">
                <div>
                    <label class="text-sm font-medium text-slate-500">Fecha de Registro</label>
                    <p class="text-slate-900 font-medium">{{ $reparto->created_at->format('d/m/Y H:i') }}</p>
                    <p class="text-xs text-slate-500">{{ $reparto->created_at->diffForHumans() }}</p>
                </div>

                <div>
                    <label class="text-sm font-medium text-slate-500">Fecha Programada</label>
                    <p class="text-slate-900 font-semibold text-lg">{{ \Carbon\Carbon::parse($reparto->fecha_programada)->format('d/m/Y') }}</p>
                </div>

                @if($reparto->fecha_entrega)
                <div>
                    <label class="text-sm font-medium text-slate-500">Fecha de Entrega</label>
                    <p class="text-emerald-600 font-semibold">{{ \Carbon\Carbon::parse($reparto->fecha_entrega)->format('d/m/Y') }}</p>
                </div>
                @endif
            </div>
        </x-card>
    </div>
</div>
@endsection
