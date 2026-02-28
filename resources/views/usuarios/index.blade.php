@extends('layouts.app')

@section('title', 'Gestión de Usuarios')

@section('breadcrumbs')
    <span class="text-slate-400">Usuarios</span>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-slate-900">Gestión de Usuarios</h1>
                <p class="text-slate-500 mt-1">Administra los usuarios del sistema</p>
            </div>
        </div>
        
        @can('create', App\Models\User::class)
        <a href="{{ route('usuarios.create') }}">
            <x-button variant="primary" size="lg">
                <x-slot:icon>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </x-slot:icon>
                Nuevo Usuario
            </x-button>
        </a>
        @endcan
    </div>

    <!-- Filters -->
    <x-card :padding="false">
        <div class="p-6">
            <form method="GET" action="{{ route('usuarios.index') }}">
                <div class="flex flex-col md:flex-row gap-4">
                    <!-- Search -->
                    <div class="flex-1">
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <input 
                                type="text" 
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Buscar por nombre, apellido, email o DNI..." 
                                class="w-full pl-10 pr-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent min-h-touch"
                            >
                        </div>
                    </div>

                    <!-- Filters -->
                    <div class="flex gap-2 flex-wrap">
                        <select name="role" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent min-h-touch">
                            <option value="">Todos los roles</option>
                            <option value="administrador" {{ request('role') == 'administrador' ? 'selected' : '' }}>👨‍💼 Administrador</option>
                            <option value="gerente" {{ request('role') == 'gerente' ? 'selected' : '' }}>👔 Gerente</option>
                            <option value="administrativo" {{ request('role') == 'administrativo' ? 'selected' : '' }}>📋 Administrativo</option>
                            <option value="chofer" {{ request('role') == 'chofer' ? 'selected' : '' }}>🚗 Chofer</option>
                            <option value="repartidor" {{ request('role') == 'repartidor' ? 'selected' : '' }}>📦 Repartidor</option>
                        </select>

                        <select name="activo" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent min-h-touch">
                            <option value="">Todos los estados</option>
                            <option value="1" {{ request('activo') === '1' ? 'selected' : '' }}>✅ Activos</option>
                            <option value="0" {{ request('activo') === '0' ? 'selected' : '' }}>❌ Inactivos</option>
                        </select>

                        <button type="submit" class="px-4 py-3 min-h-touch bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors inline-flex items-center justify-center">
                            Filtrar
                        </button>

                        @if(request()->hasAny(['search', 'role', 'activo']))
                        <a href="{{ route('usuarios.index') }}" class="px-4 py-3 min-h-touch bg-slate-200 text-slate-700 rounded-lg hover:bg-slate-300 transition-colors inline-flex items-center justify-center">
                            Limpiar
                        </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </x-card>

    <!-- Table -->
    <x-card :padding="false">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Usuario</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Rol</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Contacto</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Fecha Ingreso</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-slate-600 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($usuarios as $usuario)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <!-- Usuario -->
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold">
                                    {{ strtoupper(substr($usuario->name, 0, 1)) }}{{ strtoupper(substr($usuario->apellido ?? '', 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-semibold text-slate-900">{{ $usuario->nombre_completo }}</div>
                                    <div class="text-sm text-slate-500">{{ $usuario->email }}</div>
                                    @if($usuario->dni)
                                    <div class="text-xs text-slate-400">DNI: {{ $usuario->dni }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <!-- Rol -->
                        <td class="px-6 py-4">
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
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium {{ $rolColors[$usuario->role] ?? 'bg-slate-100 text-slate-700' }}">
                                <span>{{ $rolIcons[$usuario->role] ?? '👤' }}</span>
                                {{ ucfirst($usuario->role) }}
                            </span>
                        </td>

                        <!-- Contacto -->
                        <td class="px-6 py-4">
                            <div class="text-sm">
                                @if($usuario->telefono)
                                <div class="flex items-center gap-2 text-slate-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    {{ $usuario->telefono }}
                                </div>
                                @endif
                                @if($usuario->ciudad)
                                <div class="flex items-center gap-2 text-slate-500 text-xs mt-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    {{ $usuario->ciudad }}
                                </div>
                                @endif
                            </div>
                        </td>

                        <!-- Fecha Ingreso -->
                        <td class="px-6 py-4">
                            @if($usuario->fecha_ingreso)
                            <div class="text-sm text-slate-600">
                                {{ $usuario->fecha_ingreso->format('d/m/Y') }}
                            </div>
                            <div class="text-xs text-slate-400">
                                {{ $usuario->fecha_ingreso->diffForHumans() }}
                            </div>
                            @else
                            <span class="text-slate-400 text-sm">-</span>
                            @endif
                        </td>

                        <!-- Estado -->
                        <td class="px-6 py-4">
                            @if($usuario->activo)
                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">
                                <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                                Activo
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-slate-100 text-slate-600 rounded-full text-xs font-medium">
                                <span class="w-1.5 h-1.5 bg-slate-400 rounded-full"></span>
                                Inactivo
                            </span>
                            @endif
                        </td>

                        <!-- Acciones -->
                        <td class="px-4 py-4">
                            <div class="flex items-center justify-end gap-1">
                                @can('view', $usuario)
                                <a href="{{ route('usuarios.show', $usuario) }}" class="p-2.5 text-slate-600 hover:bg-slate-100 rounded-lg transition-colors min-h-touch min-w-touch flex items-center justify-center" title="Ver detalles">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                                @endcan

                                @can('update', $usuario)
                                <a href="{{ route('usuarios.edit', $usuario) }}" class="p-2.5 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors min-h-touch min-w-touch flex items-center justify-center" title="Editar">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>

                                <form action="{{ route('usuarios.toggle-estado', $usuario) }}" method="POST" class="inline-flex">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="p-2.5 {{ $usuario->activo ? 'text-amber-600 hover:bg-amber-50' : 'text-green-600 hover:bg-green-50' }} rounded-lg transition-colors min-h-touch min-w-touch flex items-center justify-center" title="{{ $usuario->activo ? 'Desactivar' : 'Activar' }}">
                                        @if($usuario->activo)
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                        </svg>
                                        @else
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        @endif
                                    </button>
                                </form>
                                @endcan

                                @can('delete', $usuario)
                                <form action="{{ route('usuarios.destroy', $usuario) }}" method="POST" class="inline-flex" onsubmit="return confirm('¿Estás seguro de eliminar este usuario? Esta acción no se puede deshacer.');">
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
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center gap-2">
                                <svg class="w-12 h-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                <p class="text-slate-500 font-medium">No se encontraron usuarios</p>
                                <p class="text-slate-400 text-sm">Intenta ajustar los filtros o crea un nuevo usuario</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($usuarios->hasPages())
        <div class="px-6 py-4 border-t border-slate-200">
            {{ $usuarios->links() }}
        </div>
        @endif
    </x-card>
</div>
@endsection
