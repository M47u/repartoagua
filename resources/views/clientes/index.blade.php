@extends('layouts.app')

@section('title', 'Gestión de Clientes')

@section('breadcrumbs')
    <span class="text-slate-400">Clientes</span>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-sky-100 rounded-lg flex items-center justify-center">
                <svg class="w-7 h-7 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Gestión de Clientes</h1>
                <p class="text-slate-500 mt-1">Administra la información de tus clientes</p>
            </div>
        </div>
        
        <a href="{{ route('clientes.create') }}">
            <x-button variant="primary" size="lg">
                <x-slot:icon>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </x-slot:icon>
                Nuevo Cliente
            </x-button>
        </a>
    </div>

    <!-- Filters -->
    <x-card :padding="false">
        <div class="p-6">
            <div class="flex flex-col md:flex-row gap-4">
                <!-- Search -->
                <div class="flex-1">
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <input 
                            type="text" 
                            placeholder="Buscar cliente por nombre, teléfono o dirección..." 
                            class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent"
                        >
                    </div>
                </div>

                <!-- Filters -->
                <div class="flex gap-2 flex-wrap">
                    <select class="px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
                        <option value="">Todos los tipos</option>
                        <option value="hogar">🏠 Hogar</option>
                        <option value="comercio">🏢 Comercio</option>
                        <option value="empresa">🏭 Empresa</option>
                    </select>

                    <select class="px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
                        <option value="">Todos los estados</option>
                        <option value="activo">Activos</option>
                        <option value="inactivo">Inactivos</option>
                    </select>
                </div>
            </div>
        </div>
    </x-card>

    <!-- Table -->
    <x-card :padding="false">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Cliente</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Tipo</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Contacto</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Dirección</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Saldo</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-slate-600 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($clientes as $cliente)
                    <tr class="hover:bg-slate-50 transition-colors {{ $cliente->activo ? '' : 'opacity-50' }}">
                        <!-- Cliente -->
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0
                                    {{ $cliente->tipo === 'hogar' ? 'bg-sky-100 text-sky-700' : '' }}
                                    {{ $cliente->tipo === 'comercio' ? 'bg-purple-100 text-purple-700' : '' }}
                                    {{ $cliente->tipo === 'empresa' ? 'bg-amber-100 text-amber-700' : '' }}
                                ">
                                    <span class="font-semibold text-sm">{{ strtoupper(substr($cliente->nombre, 0, 2)) }}</span>
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-900">{{ $cliente->nombre }}</p>
                                    @if($cliente->apellido)
                                        <p class="text-sm text-slate-500">{{ $cliente->apellido }}</p>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <!-- Tipo -->
                        <td class="px-6 py-4">
                            @if($cliente->tipo_cliente === 'hogar')
                                <x-badge color="info">
                                    🏠 Hogar
                                </x-badge>
                            @elseif($cliente->tipo_cliente === 'comercio')
                                <x-badge color="secondary">
                                    🏢 Comercio
                                </x-badge>
                            @else
                                <x-badge color="warning">
                                    🏭 Empresa
                                </x-badge>
                            @endif
                        </td>

                        <!-- Contacto -->
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2 text-slate-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                <span class="text-sm">{{ $cliente->telefono ?? 'No registrado' }}</span>
                            </div>
                        </td>

                        <!-- Dirección -->
                        <td class="px-6 py-4">
                            <p class="text-sm text-slate-600 max-w-xs truncate">
                                {{ $cliente->direccion }}
                                @if($cliente->colonia), {{ $cliente->colonia }}@endif
                            </p>
                            @if($cliente->ciudad)
                                <p class="text-xs text-slate-400">{{ $cliente->ciudad }}</p>
                            @endif
                        </td>

                        <!-- Saldo -->
                        <td class="px-6 py-4">
                            @if($cliente->saldo_actual > 0)
                                <div class="flex items-center gap-1 text-red-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                    <span class="font-semibold">${{ number_format($cliente->saldo_actual, 2) }}</span>
                                </div>
                            @elseif($cliente->saldo_actual < 0)
                                <span class="font-semibold text-emerald-600">${{ number_format(abs($cliente->saldo_actual), 2) }}</span>
                            @else
                                <span class="text-slate-500">$0.00</span>
                            @endif
                        </td>

                        <!-- Estado -->
                        <td class="px-6 py-4">
                            <div x-data="{ 
                                clienteId: {{ $cliente->id }}, 
                                activo: {{ $cliente->activo ? 'true' : 'false' }},
                                submitForm() {
                                    $refs.toggleForm.submit();
                                }
                            }"
                            @confirm-action.window="if ($event.detail.id === 'toggle-' + clienteId) submitForm()">
                                <form 
                                    x-ref="toggleForm"
                                    action="{{ route('clientes.toggle-estado', $cliente) }}" 
                                    method="POST" 
                                    class="inline-block"
                                >
                                    @csrf
                                    @method('PATCH')
                                    <button 
                                        type="button"
                                        @click="$dispatch('open-modal', { id: 'toggle-' + clienteId })"
                                        class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors cursor-pointer hover:opacity-80"
                                        :class="activo ? 'bg-emerald-500' : 'bg-slate-300'"
                                        :title="activo ? 'Desactivar cliente' : 'Activar cliente'"
                                    >
                                        <span 
                                            class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform"
                                            :class="activo ? 'translate-x-6' : 'translate-x-1'"
                                        ></span>
                                    </button>
                                </form>

                                <x-confirm-modal 
                                    :id="'toggle-' . $cliente->id"
                                    :title="$cliente->activo ? '¿Desactivar cliente?' : '¿Activar cliente?'"
                                    :message="$cliente->activo ? '¿Estás seguro de que deseas desactivar a ' . $cliente->nombre . '? No aparecerá en las búsquedas activas.' : '¿Estás seguro de que deseas activar a ' . $cliente->nombre . '?'"
                                    :confirm-text="$cliente->activo ? 'Desactivar' : 'Activar'"
                                    cancel-text="Cancelar"
                                    :confirm-color="$cliente->activo ? 'red' : 'emerald'"
                                />
                            </div>
                        </td>

                        <!-- Acciones -->
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('clientes.show', $cliente) }}" 
                                   class="p-2 text-sky-600 hover:bg-sky-50 rounded-lg transition-colors" 
                                   title="Ver detalles">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>

                                @can('update', $cliente)
                                <a href="{{ route('clientes.edit', $cliente) }}" 
                                   class="p-2 text-slate-600 hover:bg-slate-100 rounded-lg transition-colors" 
                                   title="Editar">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                @endcan

                                @can('delete', $cliente)
                                <form action="{{ route('clientes.destroy', $cliente) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" 
                                            title="Eliminar"
                                            onclick="return confirm('¿Estás seguro de que deseas eliminar este cliente?')">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12">
                            <x-empty-state
                                title="No hay clientes registrados"
                                description="Comienza agregando tu primer cliente para gestionar tus repartos"
                                action-url="{{ route('clientes.create') }}"
                                action-text="Crear Cliente"
                            >
                                <x-slot:icon>
                                    <svg class="w-16 h-16 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </x-slot:icon>
                            </x-empty-state>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($clientes->hasPages())
        <div class="px-6 py-4 border-t border-slate-200">
            {{ $clientes->links() }}
        </div>
        @endif
    </x-card>
</div>
@endsection
