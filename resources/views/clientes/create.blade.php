@extends('layouts.app')

@section('title', 'Crear Cliente')

@section('breadcrumbs')
    <a href="{{ route('clientes.index') }}" class="text-slate-400 hover:text-slate-600">Clientes</a>
    <span class="text-slate-400">/</span>
    <span class="text-slate-600">Nuevo Cliente</span>
@endsection

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-3">
        <div class="w-12 h-12 bg-sky-100 rounded-lg flex items-center justify-center">
            <svg class="w-7 h-7 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
            </svg>
        </div>
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Nuevo Cliente</h1>
            <p class="text-slate-500 mt-1">Registra un nuevo cliente en el sistema</p>
        </div>
    </div>

    <!-- Form -->
    <form action="{{ route('clientes.store') }}" method="POST">
        @csrf

        <x-card>
            <div class="space-y-6">
                <!-- Información Personal -->
                <div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">Información Personal</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nombre -->
                        <div>
                            <label for="nombre" class="block text-sm font-medium text-slate-700 mb-2">
                                Nombre <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="nombre" 
                                   name="nombre" 
                                   value="{{ old('nombre') }}" 
                                   class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent @error('nombre') border-red-500 @enderror"
                                   required>
                            @error('nombre')
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
                                   value="{{ old('apellido') }}" 
                                   class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent @error('apellido') border-red-500 @enderror"
                                   required>
                            @error('apellido')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Teléfono -->
                        <div>
                            <label for="telefono" class="block text-sm font-medium text-slate-700 mb-2">
                                Teléfono <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="telefono" 
                                   name="telefono" 
                                   value="{{ old('telefono') }}" 
                                   class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent @error('telefono') border-red-500 @enderror"
                                   required>
                            @error('telefono')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-slate-700 mb-2">
                                Email
                            </label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent @error('email') border-red-500 @enderror">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Dirección -->
                <div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">Dirección</h3>
                    <div class="space-y-6">
                        <!-- Dirección -->
                        <div>
                            <label for="direccion" class="block text-sm font-medium text-slate-700 mb-2">
                                Dirección <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="direccion" 
                                   name="direccion" 
                                   value="{{ old('direccion') }}" 
                                   placeholder="Calle y número"
                                   class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent @error('direccion') border-red-500 @enderror"
                                   required>
                            @error('direccion')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Colonia -->
                            <div>
                                <label for="colonia" class="block text-sm font-medium text-slate-700 mb-2">
                                    Colonia
                                </label>
                                <input type="text" 
                                       id="colonia" 
                                       name="colonia" 
                                       value="{{ old('colonia') }}" 
                                       class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent @error('colonia') border-red-500 @enderror">
                                @error('colonia')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Ciudad -->
                            <div>
                                <label for="ciudad" class="block text-sm font-medium text-slate-700 mb-2">
                                    Ciudad <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="ciudad" 
                                       name="ciudad" 
                                       value="{{ old('ciudad') }}" 
                                       class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent @error('ciudad') border-red-500 @enderror"
                                       required>
                                @error('ciudad')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información del Servicio -->
                <div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">Información del Servicio</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Tipo de Cliente -->
                        <div>
                            <label for="tipo_cliente" class="block text-sm font-medium text-slate-700 mb-2">
                                Tipo de Cliente <span class="text-red-500">*</span>
                            </label>
                            <select id="tipo_cliente" 
                                    name="tipo_cliente" 
                                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent @error('tipo_cliente') border-red-500 @enderror"
                                    required>
                                <option value="hogar" {{ old('tipo_cliente', 'hogar') == 'hogar' ? 'selected' : '' }}>🏠 Hogar</option>
                                <option value="comercio" {{ old('tipo_cliente', 'hogar') == 'comercio' ? 'selected' : '' }}>🏢 Comercio</option>
                                <option value="empresa" {{ old('tipo_cliente', 'hogar') == 'empresa' ? 'selected' : '' }}>🏭 Empresa</option>
                            </select>
                            @error('tipo_cliente')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Precio por Bidón -->
                        <div>
                            <label for="precio_por_bidon" class="block text-sm font-medium text-slate-700 mb-2">
                                Precio por Bidón
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500">$</span>
                                <input type="number" 
                                       id="precio_por_bidon" 
                                       name="precio_por_bidon" 
                                       value="{{ old('precio_por_bidon') }}" 
                                       step="0.01"
                                       min="0"
                                       placeholder="0.00"
                                       class="w-full pl-8 pr-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent @error('precio_por_bidon') border-red-500 @enderror">
                            </div>
                            @error('precio_por_bidon')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-slate-500">Dejar vacío para usar el precio predeterminado</p>
                        </div>

                        <!-- Estado -->
                        <div class="md:col-span-2">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" 
                                       name="activo" 
                                       value="1" 
                                       {{ old('activo', true) ? 'checked' : '' }}
                                       class="w-5 h-5 text-sky-600 border-slate-300 rounded focus:ring-2 focus:ring-sky-500">
                                <span class="text-sm font-medium text-slate-700">Cliente Activo</span>
                            </label>
                        </div>
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
                              class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent @error('observaciones') border-red-500 @enderror"
                              placeholder="Notas adicionales sobre el cliente...">{{ old('observaciones') }}</textarea>
                    @error('observaciones')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </x-card>

        <!-- Actions -->
        <div class="flex items-center justify-between gap-4">
            <a href="{{ route('clientes.index') }}">
                <x-button variant="secondary" type="button">
                    <x-slot:icon>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </x-slot:icon>
                    Cancelar
                </x-button>
            </a>

            <x-button variant="primary" type="submit">
                <x-slot:icon>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </x-slot:icon>
                Guardar Cliente
            </x-button>
        </div>
    </form>
</div>
@endsection
