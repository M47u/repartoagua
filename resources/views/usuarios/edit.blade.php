@extends('layouts.app')

@section('title', 'Editar Usuario')

@section('breadcrumbs')
    <a href="{{ route('usuarios.index') }}" class="text-slate-400 hover:text-slate-600">Usuarios</a>
    <span class="text-slate-400">/</span>
    <span class="text-slate-600">Editar: {{ $usuario->nombre_completo }}</span>
@endsection

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-3">
        <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
            <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
        </div>
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Editar Usuario</h1>
            <p class="text-slate-500 mt-1">Actualiza la información de {{ $usuario->nombre_completo }}</p>
        </div>
    </div>

    <!-- Form -->
    <form action="{{ route('usuarios.update', $usuario) }}" method="POST">
        @csrf
        @method('PUT')

        <x-card>
            <div class="space-y-8">
                <!-- Información Personal -->
                <div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Información Personal
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nombre -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-slate-700 mb-2">
                                Nombre <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $usuario->name) }}" 
                                   class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('name') border-red-500 @enderror"
                                   required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Apellido -->
                        <div>
                            <label for="apellido" class="block text-sm font-medium text-slate-700 mb-2">
                                Apellido <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="apellido" 
                                   name="apellido" 
                                   value="{{ old('apellido', $usuario->apellido) }}" 
                                   class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('apellido') border-red-500 @enderror"
                                   required>
                            @error('apellido')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- DNI -->
                        <div>
                            <label for="dni" class="block text-sm font-medium text-slate-700 mb-2">
                                DNI / Cédula
                            </label>
                            <input type="text" 
                                   id="dni" 
                                   name="dni" 
                                   value="{{ old('dni', $usuario->dni) }}" 
                                   class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('dni') border-red-500 @enderror">
                            @error('dni')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Fecha Nacimiento -->
                        <div>
                            <label for="fecha_nacimiento" class="block text-sm font-medium text-slate-700 mb-2">
                                Fecha de Nacimiento
                            </label>
                            <input type="date" 
                                   id="fecha_nacimiento" 
                                   name="fecha_nacimiento" 
                                   value="{{ old('fecha_nacimiento', $usuario->fecha_nacimiento?->format('Y-m-d')) }}" 
                                   max="{{ date('Y-m-d') }}"
                                   class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('fecha_nacimiento') border-red-500 @enderror">
                            @error('fecha_nacimiento')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Contacto -->
                <div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Información de Contacto
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-slate-700 mb-2">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email', $usuario->email) }}" 
                                   class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('email') border-red-500 @enderror"
                                   required>
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Teléfono -->
                        <div>
                            <label for="telefono" class="block text-sm font-medium text-slate-700 mb-2">
                                Teléfono
                            </label>
                            <input type="text" 
                                   id="telefono" 
                                   name="telefono" 
                                   value="{{ old('telefono', $usuario->telefono) }}" 
                                   class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('telefono') border-red-500 @enderror">
                            @error('telefono')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Dirección -->
                        <div>
                            <label for="direccion" class="block text-sm font-medium text-slate-700 mb-2">
                                Dirección
                            </label>
                            <input type="text" 
                                   id="direccion" 
                                   name="direccion" 
                                   value="{{ old('direccion', $usuario->direccion) }}" 
                                   class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('direccion') border-red-500 @enderror">
                            @error('direccion')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Ciudad -->
                        <div>
                            <label for="ciudad" class="block text-sm font-medium text-slate-700 mb-2">
                                Ciudad
                            </label>
                            <input type="text" 
                                   id="ciudad" 
                                   name="ciudad" 
                                   value="{{ old('ciudad', $usuario->ciudad) }}" 
                                   class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('ciudad') border-red-500 @enderror">
                            @error('ciudad')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Datos del Sistema -->
                <div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        Datos del Sistema
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Rol -->
                        <div>
                            <label for="role" class="block text-sm font-medium text-slate-700 mb-2">
                                Rol <span class="text-red-500">*</span>
                            </label>
                            <select id="role" 
                                    name="role" 
                                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('role') border-red-500 @enderror"
                                    required
                                    onchange="toggleVehiculos()">
                                <option value="">Seleccionar rol...</option>
                                <option value="administrador" {{ old('role', $usuario->role) == 'administrador' ? 'selected' : '' }}>👨‍💼 Administrador</option>
                                <option value="gerente" {{ old('role', $usuario->role) == 'gerente' ? 'selected' : '' }}>👔 Gerente</option>
                                <option value="administrativo" {{ old('role', $usuario->role) == 'administrativo' ? 'selected' : '' }}>📋 Administrativo</option>
                                <option value="chofer" {{ old('role', $usuario->role) == 'chofer' ? 'selected' : '' }}>🚗 Chofer</option>
                                <option value="repartidor" {{ old('role', $usuario->role) == 'repartidor' ? 'selected' : '' }}>📦 Repartidor</option>
                            </select>
                            @error('role')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Fecha Ingreso -->
                        <div>
                            <label for="fecha_ingreso" class="block text-sm font-medium text-slate-700 mb-2">
                                Fecha de Ingreso
                            </label>
                            <input type="date" 
                                   id="fecha_ingreso" 
                                   name="fecha_ingreso" 
                                   value="{{ old('fecha_ingreso', $usuario->fecha_ingreso?->format('Y-m-d')) }}" 
                                   class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('fecha_ingreso') border-red-500 @enderror">
                            @error('fecha_ingreso')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-slate-700 mb-2">
                                Nueva Contraseña
                            </label>
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('password') border-red-500 @enderror"
                                   minlength="8">
                            <p class="mt-1 text-xs text-slate-500">Déjalo en blanco si no deseas cambiar la contraseña</p>
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirmar Password -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-2">
                                Confirmar Contraseña
                            </label>
                            <input type="password" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                   minlength="8">
                        </div>

                        <!-- Estado -->
                        <div class="md:col-span-2">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" 
                                       name="activo" 
                                       value="1" 
                                       {{ old('activo', $usuario->activo) ? 'checked' : '' }}
                                       class="w-4 h-4 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500">
                                <span class="text-sm font-medium text-slate-700">Usuario activo</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Vehículos (solo para choferes) -->
                <div id="vehiculos-section" class="hidden">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        Vehículos Asignados
                    </h3>
                    <div class="bg-slate-50 rounded-lg p-4">
                        <p class="text-sm text-slate-600 mb-3">Selecciona los vehículos que se asignarán a este chofer:</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach($vehiculos as $vehiculo)
                            <label class="flex items-center gap-3 p-3 bg-white border border-slate-200 rounded-lg hover:border-indigo-300 cursor-pointer transition-colors">
                                <input type="checkbox" 
                                       name="vehiculos[]" 
                                       value="{{ $vehiculo->id }}"
                                       {{ in_array($vehiculo->id, old('vehiculos', $vehiculosAsignados)) ? 'checked' : '' }}
                                       class="w-4 h-4 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500">
                                <div class="flex-1">
                                    <div class="font-medium text-slate-900">{{ $vehiculo->marca }} {{ $vehiculo->modelo }}</div>
                                    <div class="text-sm text-slate-500">{{ $vehiculo->placa }} - {{ ucfirst($vehiculo->tipo) }}</div>
                                </div>
                            </label>
                            @endforeach
                        </div>
                        @if($vehiculos->isEmpty())
                        <p class="text-sm text-slate-500 text-center py-4">No hay vehículos disponibles</p>
                        @endif
                    </div>
                </div>

                <!-- Observaciones -->
                <div>
                    <label for="observaciones" class="block text-sm font-medium text-slate-700 mb-2">
                        Observaciones
                    </label>
                    <textarea id="observaciones" 
                              name="observaciones" 
                              rows="3" 
                              class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('observaciones') border-red-500 @enderror">{{ old('observaciones', $usuario->observaciones) }}</textarea>
                    @error('observaciones')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </x-card>

        <!-- Acciones -->
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('usuarios.index') }}">
                <x-button variant="secondary" size="lg">
                    Cancelar
                </x-button>
            </a>
            <x-button type="submit" variant="primary" size="lg">
                <x-slot:icon>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </x-slot:icon>
                Actualizar Usuario
            </x-button>
        </div>
    </form>
</div>

@push('scripts')
<script>
function toggleVehiculos() {
    const role = document.getElementById('role').value;
    const vehiculosSection = document.getElementById('vehiculos-section');
    
    if (role === 'chofer') {
        vehiculosSection.classList.remove('hidden');
    } else {
        vehiculosSection.classList.add('hidden');
    }
}

// Ejecutar al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    toggleVehiculos();
});
</script>
@endpush
@endsection
