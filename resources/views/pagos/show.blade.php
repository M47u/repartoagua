@extends('layouts.app')

@section('title', 'Detalle de Pago')

@section('breadcrumbs')
    <a href="{{ route('pagos.index') }}" class="text-slate-400 hover:text-slate-600">Pagos</a>
    <span class="text-slate-400">/</span>
    <span class="text-slate-600">#{{ $pago->id }}</span>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center text-white text-2xl">
                💰
            </div>
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Pago #{{ $pago->id }}</h1>
                <p class="text-lg text-slate-600 mt-1">${{ number_format($pago->monto, 2) }}</p>
                <div class="flex items-center gap-2 mt-2">
                    @php
                        $metodoBadges = [
                            'efectivo' => 'bg-green-100 text-green-700',
                            'transferencia' => 'bg-blue-100 text-blue-700',
                            'cuenta_corriente' => 'bg-amber-100 text-amber-700',
                        ];
                        $metodoIcons = [
                            'efectivo' => '💵',
                            'transferencia' => '🏦',
                            'cuenta_corriente' => '📋',
                        ];
                    @endphp
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-sm font-medium {{ $metodoBadges[$pago->metodo_pago] ?? 'bg-slate-100 text-slate-700' }}">
                        <span>{{ $metodoIcons[$pago->metodo_pago] ?? '💰' }}</span>
                        {{ ucfirst(str_replace('_', ' ', $pago->metodo_pago)) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="flex gap-2">
            @can('update', $pago)
            <a href="{{ route('pagos.edit', $pago) }}">
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

            <a href="{{ route('pagos.index') }}">
                <x-button variant="outline">
                    <x-slot:icon>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </x-slot:icon>
                    Volver
                </x-button>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Información del Pago -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Detalles del Pago -->
            <x-card>
                <h3 class="text-lg font-semibold text-slate-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    Detalles del Pago
                </h3>
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-slate-500">Monto</label>
                            <p class="text-2xl font-bold text-green-600">${{ number_format($pago->monto, 2) }}</p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-slate-500">Método de Pago</label>
                            <p class="text-slate-900 capitalize">{{ str_replace('_', ' ', $pago->metodo_pago) }}</p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-slate-500">Fecha de Pago</label>
                            <p class="text-slate-900">{{ $pago->fecha_pago->format('d/m/Y H:i') }}</p>
                            <p class="text-xs text-slate-500">{{ $pago->fecha_pago->diffForHumans() }}</p>
                        </div>

                        @if($pago->referencia)
                        <div>
                            <label class="text-sm font-medium text-slate-500">Referencia</label>
                            <p class="text-slate-900">{{ $pago->referencia }}</p>
                        </div>
                        @endif
                    </div>

                    @if($pago->notas)
                    <div>
                        <label class="text-sm font-medium text-slate-500">Notas / Observaciones</label>
                        <p class="text-slate-700 mt-1 whitespace-pre-line">{{ $pago->notas }}</p>
                    </div>
                    @endif
                </div>
            </x-card>

            <!-- Información del Cliente -->
            <x-card>
                <h3 class="text-lg font-semibold text-slate-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Cliente
                </h3>
                <div class="flex items-start gap-4">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center text-white text-xl font-bold">
                        {{ strtoupper(substr($pago->cliente->nombre, 0, 1)) }}{{ strtoupper(substr($pago->cliente->apellido ?? '', 0, 1)) }}
                    </div>
                    <div class="flex-1">
                        <a href="{{ route('clientes.show', $pago->cliente) }}" class="text-lg font-semibold text-indigo-600 hover:text-indigo-900">
                            {{ $pago->cliente->nombre }} {{ $pago->cliente->apellido }}
                        </a>
                        <div class="mt-2 space-y-1 text-sm">
                            @if($pago->cliente->telefono)
                            <p class="text-slate-600 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                {{ $pago->cliente->telefono }}
                            </p>
                            @endif
                            @if($pago->cliente->email)
                            <p class="text-slate-600 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                {{ $pago->cliente->email }}
                            </p>
                            @endif
                            @if($pago->cliente->direccion)
                            <p class="text-slate-600 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                {{ $pago->cliente->direccion }}
                            </p>
                            @endif
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('clientes.show', $pago->cliente) }}" class="text-sm text-indigo-600 hover:text-indigo-700">
                                Ver perfil completo →
                            </a>
                        </div>
                    </div>
                </div>
            </x-card>

            <!-- Movimiento Contable -->
            @if($pago->movimientoCuenta)
            <x-card>
                <h3 class="text-lg font-semibold text-slate-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    Movimiento Contable
                </h3>
                <div class="bg-green-50 p-4 rounded-lg">
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-slate-600">Tipo de movimiento:</span>
                            <span class="text-slate-900 font-medium ml-2">{{ ucfirst($pago->movimientoCuenta->tipo) }}</span>
                        </div>
                        <div>
                            <span class="text-slate-600">Monto:</span>
                            <span class="text-green-600 font-bold ml-2">${{ number_format($pago->movimientoCuenta->monto, 2) }}</span>
                        </div>
                        @if($pago->movimientoCuenta->saldo_anterior !== null)
                        <div>
                            <span class="text-slate-600">Saldo anterior:</span>
                            <span class="text-slate-900 font-medium ml-2">${{ number_format($pago->movimientoCuenta->saldo_anterior, 2) }}</span>
                        </div>
                        @endif
                        @if($pago->movimientoCuenta->saldo_nuevo !== null)
                        <div>
                            <span class="text-slate-600">Saldo nuevo:</span>
                            <span class="text-slate-900 font-medium ml-2">${{ number_format($pago->movimientoCuenta->saldo_nuevo, 2) }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </x-card>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Información de Auditoría -->
            <x-card>
                <h3 class="text-lg font-semibold text-slate-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Información de Registro
                </h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <label class="text-slate-500 font-medium">ID del Pago</label>
                        <p class="text-slate-900">#{{ $pago->id }}</p>
                    </div>

                    <div>
                        <label class="text-slate-500 font-medium">Registrado por</label>
                        <p class="text-slate-900">{{ $pago->registradoPor->nombre_completo ?? 'Sistema' }}</p>
                    </div>

                    <div>
                        <label class="text-slate-500 font-medium">Fecha de creación</label>
                        <p class="text-slate-900">{{ $pago->created_at->format('d/m/Y H:i') }}</p>
                        <p class="text-xs text-slate-500">{{ $pago->created_at->diffForHumans() }}</p>
                    </div>

                    @if($pago->created_at != $pago->updated_at)
                    <div>
                        <label class="text-slate-500 font-medium">Última actualización</label>
                        <p class="text-slate-900">{{ $pago->updated_at->format('d/m/Y H:i') }}</p>
                        <p class="text-xs text-slate-500">{{ $pago->updated_at->diffForHumans() }}</p>
                    </div>
                    @endif
                </div>
            </x-card>

            <!-- Acciones Rápidas -->
            <x-card>
                <h3 class="text-lg font-semibold text-slate-900 mb-4">Acciones Rápidas</h3>
                <div class="space-y-2">
                    <a href="{{ route('clientes.show', $pago->cliente) }}" class="flex items-center gap-2 p-3 rounded-lg hover:bg-slate-50 transition-colors">
                        <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span class="text-sm text-slate-700">Ver cliente</span>
                    </a>
                    
                    <a href="{{ route('pagos.index', ['cliente_id' => $pago->cliente_id]) }}" class="flex items-center gap-2 p-3 rounded-lg hover:bg-slate-50 transition-colors">
                        <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <span class="text-sm text-slate-700">Pagos del cliente</span>
                    </a>
                </div>
            </x-card>
        </div>
    </div>
</div>
@endsection
