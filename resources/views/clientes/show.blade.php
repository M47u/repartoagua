@extends('layouts.app')

@section('title', 'Detalles del Cliente')

@section('breadcrumbs')
    <a href="{{ route('clientes.index') }}" class="text-slate-400 hover:text-slate-600">Clientes</a>
    <span class="text-slate-300 mx-2">/</span>
    <span class="text-slate-700">{{ $cliente->nombre }}</span>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header del Cliente -->
    <x-card>
        <div class="flex flex-col md:flex-row md:items-center gap-6">
            <!-- Avatar -->
            <div class="w-24 h-24 rounded-full flex items-center justify-center flex-shrink-0 text-white font-bold text-3xl
                {{ $cliente->tipo_cliente === 'hogar' ? 'bg-gradient-to-br from-sky-400 to-sky-600' : '' }}
                {{ $cliente->tipo_cliente === 'comercio' ? 'bg-gradient-to-br from-purple-400 to-purple-600' : '' }}
                {{ $cliente->tipo_cliente === 'empresa' ? 'bg-gradient-to-br from-amber-400 to-amber-600' : '' }}
            ">
                {{ strtoupper(substr($cliente->nombre, 0, 2)) }}
            </div>

            <!-- Info -->
            <div class="flex-1">
                <h1 class="text-3xl font-bold text-slate-900 mb-2">{{ $cliente->nombre }} {{ $cliente->apellido }}</h1>
                
                <div class="flex flex-wrap items-center gap-3">
                    @if($cliente->tipo_cliente === 'hogar')
                        <x-badge color="info" size="lg">🏠 Hogar</x-badge>
                    @elseif($cliente->tipo_cliente === 'comercio')
                        <x-badge color="secondary" size="lg">🏢 Comercio</x-badge>
                    @else
                        <x-badge color="warning" size="lg">🏭 Empresa</x-badge>
                    @endif

                    <x-badge :color="$cliente->activo ? 'success' : 'danger'" size="lg">
                        {{ $cliente->activo ? 'Activo' : 'Inactivo' }}
                    </x-badge>
                </div>
            </div>

            <!-- Acciones -->
            <div class="flex flex-col gap-2">
                <a href="{{ route('clientes.edit', $cliente) }}">
                    <x-button variant="primary" class="w-full">
                        <x-slot:icon>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </x-slot:icon>
                        Editar
                    </x-button>
                </a>
            </div>
        </div>
    </x-card>

    <!-- Información General -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Datos de Contacto -->
        <x-card title="Datos de Contacto">
            <x-slot:icon>
                <svg class="w-6 h-6 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                </svg>
            </x-slot:icon>

            <div class="space-y-3">
                <div>
                    <label class="text-sm font-medium text-slate-500">Teléfono</label>
                    <p class="text-slate-900 font-medium">{{ $cliente->telefono ?? 'No registrado' }}</p>
                </div>

                @if($cliente->email)
                <div>
                    <label class="text-sm font-medium text-slate-500">Email</label>
                    <p class="text-slate-900 font-medium">
                        <a href="mailto:{{ $cliente->email }}" class="text-sky-600 hover:text-sky-700 hover:underline">
                            {{ $cliente->email }}
                        </a>
                    </p>
                </div>
                @endif

                <div>
                    <label class="text-sm font-medium text-slate-500">Dirección</label>
                    <p class="text-slate-900 font-medium">{{ $cliente->direccion }}</p>
                </div>

                @if($cliente->colonia)
                <div>
                    <label class="text-sm font-medium text-slate-500">Colonia</label>
                    <p class="text-slate-900 font-medium">{{ $cliente->colonia }}</p>
                </div>
                @endif

                @if($cliente->ciudad)
                <div>
                    <label class="text-sm font-medium text-slate-500">Ciudad</label>
                    <p class="text-slate-900 font-medium">{{ $cliente->ciudad }}</p>
                </div>
                @endif
            </div>
        </x-card>

        <!-- Información Comercial -->
        <x-card title="Información Comercial">
            <x-slot:icon>
                <svg class="w-6 h-6 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </x-slot:icon>

            <div class="space-y-3">
                <div>
                    <label class="text-sm font-medium text-slate-500">Tipo de Cliente</label>
                    <p class="text-slate-900 font-medium">
                        @if($cliente->tipo_cliente === 'hogar')
                            🏠 Hogar
                        @elseif($cliente->tipo_cliente === 'comercio')
                            🏢 Comercio
                        @else
                            🏭 Empresa
                        @endif
                    </p>
                </div>

                <div>
                    <label class="text-sm font-medium text-slate-500">Producto</label>
                    @if($cliente->producto)
                        <p class="text-slate-900 font-semibold">{{ $cliente->producto->nombre }}</p>
                        <p class="text-slate-500 text-sm">${{ number_format($cliente->producto->precio_base, 2) }}</p>
                    @else
                        <p class="text-slate-500 italic">Sin producto predeterminado</p>
                    @endif
                </div>

                <div>
                    <label class="text-sm font-medium text-slate-500">Saldo en Cuenta Corriente</label>
                    <p class="font-bold text-2xl {{ $cliente->saldo > 0 ? 'text-red-600' : ($cliente->saldo < 0 ? 'text-emerald-600' : 'text-slate-600') }}">
                        ${{ number_format(abs($cliente->saldo), 2) }}
                        @if($cliente->saldo > 0)
                            <span class="text-sm font-normal text-red-500">(Debe)</span>
                        @elseif($cliente->saldo < 0)
                            <span class="text-sm font-normal text-emerald-500">(A favor)</span>
                        @else
                            <span class="text-sm font-normal text-slate-500">(Sin saldo)</span>
                        @endif
                    </p>
                </div>

                @if($cliente->observaciones)
                <div>
                    <label class="text-sm font-medium text-slate-500">Observaciones</label>
                    <p class="text-slate-900">{{ $cliente->observaciones }}</p>
                </div>
                @endif
            </div>
        </x-card>
    </div>
</div>
@endsection
