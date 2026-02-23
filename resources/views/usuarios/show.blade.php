@extends('layouts.app')

@section('title', 'Detalle de Usuario')

@section('breadcrumbs')
    <a href="{{ route('usuarios.index') }}" class="text-slate-400 hover:text-slate-600">Usuarios</a>
    <span class="text-slate-400">/</span>
    <span class="text-slate-600">{{ $usuario->nombre_completo }}</span>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center text-white text-2xl font-bold">
                {{ strtoupper(substr($usuario->name, 0, 1)) }}{{ strtoupper(substr($usuario->apellido ?? '', 0, 1)) }}
            </div>
            <div>
                <h1 class="text-3xl font-bold text-slate-900">{{ $usuario->nombre_completo }}</h1>
                <div class="flex items-center gap-2 mt-1">
                    @php
                        $rolColors = [
                            'administrador' => 'bg-red-100 text-red-700',
                            'gerente' => 'bg-purple-100 text-purple-700',
                            'administrativo' => 'bg-blue-100 text-blue-700',
                            'chofer' => 'bg-green-100 text-green-700',
                            'repartidor' => 'bg-orange-100 text-orange-700',
                        ];
                        $rolIcons = [
                            'administrador' => '👨‍💼',
                            'gerente' => '👔',
                            'administrativo' => '📋',
                            'chofer' => '🚗',
                            'repartidor' => '📦',
                        ];
                    @endphp
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-sm font-medium {{ $rolColors[$usuario->role] ?? 'bg-slate-100 text-slate-700' }}">
                        <span>{{ $rolIcons[$usuario->role] ?? '👤' }}</span>
                        {{ ucfirst($usuario->role) }}
                    </span>
                    @if($usuario->activo)
                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-medium">
                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                        Activo
                    </span>
                    @else
                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-slate-100 text-slate-600 rounded-full text-sm font-medium">
                        <span class="w-1.5 h-1.5 bg-slate-400 rounded-full"></span>
                        Inactivo
                    </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="flex gap-2">
            @can('update', $usuario)
            <a href="{{ route('usuarios.edit', $usuario) }}">
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

            @can('delete', $usuario)
            <form action="{{ route('usuarios.destroy', $usuario) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este usuario?');">
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Información Personal -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Datos Personales -->
            <x-card>
                <h3 class="text-lg font-semibold text-slate-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Información Personal
                </h3>
                <div class="space-y-4">
                    <div>
                        <label class="text-sm font-medium text-slate-500">Email</label>
                        <p class="text-slate-900">{{ $usuario->email }}</p>
                    </div>

                    @if($usuario->dni)
                    <div>
                        <label class="text-sm font-medium text-slate-500">DNI / Cédula</label>
                        <p class="text-slate-900">{{ $usuario->dni }}</p>
                    </div>
                    @endif

                    @if($usuario->telefono)
                    <div>
                        <label class="text-sm font-medium text-slate-500">Teléfono</label>
                        <p class="text-slate-900 flex items-center gap-2">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            {{ $usuario->telefono }}
                        </p>
                    </div>
                    @endif

                    @if($usuario->fecha_nacimiento)
                    <div>
                        <label class="text-sm font-medium text-slate-500">Fecha de Nacimiento</label>
                        <p class="text-slate-900">{{ $usuario->fecha_nacimiento->format('d/m/Y') }}</p>
                        <p class="text-xs text-slate-500">{{ $usuario->fecha_nacimiento->age }} años</p>
                    </div>
                    @endif

                    @if($usuario->direccion)
                    <div>
                        <label class="text-sm font-medium text-slate-500">Dirección</label>
                        <p class="text-slate-900">{{ $usuario->direccion }}</p>
                    </div>
                    @endif

                    @if($usuario->ciudad)
                    <div>
                        <label class="text-sm font-medium text-slate-500">Ciudad</label>
                        <p class="text-slate-900 flex items-center gap-2">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            {{ $usuario->ciudad }}
                        </p>
                    </div>
                    @endif

                    @if($usuario->fecha_ingreso)
                    <div>
                        <label class="text-sm font-medium text-slate-500">Fecha de Ingreso</label>
                        <p class="text-slate-900">{{ $usuario->fecha_ingreso->format('d/m/Y') }}</p>
                        <p class="text-xs text-slate-500">{{ $usuario->fecha_ingreso->diffForHumans() }}</p>
                    </div>
                    @endif
                </div>
            </x-card>

            <!-- Vehículos Asignados (solo choferes) -->
            @if($usuario->role === 'chofer' && $usuario->vehiculosActivos->isNotEmpty())
            <x-card>
                <h3 class="text-lg font-semibold text-slate-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    Vehículos Asignados
                </h3>
                <div class="space-y-3">
                    @foreach($usuario->vehiculosActivos as $vehiculo)
                    <a href="{{ route('vehiculos.show', $vehiculo) }}" class="block p-3 bg-slate-50 rounded-lg hover:bg-slate-100 transition-colors">
                        <div class="font-medium text-slate-900">{{ $vehiculo->marca }} {{ $vehiculo->modelo }}</div>
                        <div class="text-sm text-slate-500">{{ $vehiculo->placa }}</div>
                        <div class="text-xs text-slate-400 mt-1">{{ ucfirst($vehiculo->tipo) }} - {{ ucfirst($vehiculo->estado) }}</div>
                    </a>
                    @endforeach
                </div>
            </x-card>
            @endif

            <!-- Observaciones -->
            @if($usuario->observaciones)
            <x-card>
                <h3 class="text-lg font-semibold text-slate-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                    </svg>
                    Observaciones
                </h3>
                <p class="text-slate-700 text-sm whitespace-pre-line">{{ $usuario->observaciones }}</p>
            </x-card>
            @endif
        </div>

        <!-- Actividad Reciente -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Repartos Recientes (solo repartidores) -->
            @if($usuario->role === 'repartidor' && $usuario->repartos->isNotEmpty())
            <x-card>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-slate-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                        Repartos Recientes
                    </h3>
                    <a href="{{ route('repartos.index', ['repartidor_id' => $usuario->id]) }}" class="text-sm text-indigo-600 hover:text-indigo-700">Ver todos →</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Fecha</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Cliente</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Producto</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Estado</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-slate-600 uppercase">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach($usuario->repartos as $reparto)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3 text-sm text-slate-600">{{ $reparto->fecha->format('d/m/Y') }}</td>
                                <td class="px-4 py-3 text-sm text-slate-900">{{ $reparto->cliente->nombre }} {{ $reparto->cliente->apellido }}</td>
                                <td class="px-4 py-3 text-sm text-slate-600">{{ $reparto->producto->nombre }} ({{ $reparto->cantidad }})</td>
                                <td class="px-4 py-3">
                                    @if($reparto->estado === 'entregado')
                                    <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">Entregado</span>
                                    @else
                                    <span class="px-2 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-medium">Pendiente</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-slate-900 text-right font-medium">${{ number_format($reparto->total, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </x-card>
            @endif

            <!-- Pagos Registrados (personal administrativo) -->
            @if(in_array($usuario->role, ['administrador', 'administrativo', 'gerente']) && $usuario->pagos->isNotEmpty())
            <x-card>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-slate-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Pagos Registrados
                    </h3>
                    <a href="{{ route('pagos.index', ['registrado_por' => $usuario->id]) }}" class="text-sm text-indigo-600 hover:text-indigo-700">Ver todos →</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Fecha</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Cliente</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Método</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-slate-600 uppercase">Monto</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach($usuario->pagos as $pago)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3 text-sm text-slate-600">{{ $pago->fecha->format('d/m/Y') }}</td>
                                <td class="px-4 py-3 text-sm text-slate-900">{{ $pago->cliente->nombre }} {{ $pago->cliente->apellido }}</td>
                                <td class="px-4 py-3 text-sm text-slate-600">{{ ucfirst(str_replace('_', ' ', $pago->metodo_pago)) }}</td>
                                <td class="px-4 py-3 text-sm text-slate-900 text-right font-medium">${{ number_format($pago->monto, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </x-card>
            @endif

            <!-- Mensaje si no hay actividad -->
            @if(
                ($usuario->role === 'repartidor' && $usuario->repartos->isEmpty()) ||
                (in_array($usuario->role, ['administrador', 'administrativo', 'gerente']) && $usuario->pagos->isEmpty())
            )
            <x-card>
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-slate-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p class="text-slate-500 font-medium">Sin actividad reciente</p>
                    <p class="text-slate-400 text-sm mt-1">Este usuario aún no ha realizado actividades en el sistema</p>
                </div>
            </x-card>
            @endif
        </div>
    </div>
</div>
@endsection
