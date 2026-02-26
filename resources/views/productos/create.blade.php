@extends('layouts.app')

@section('title', 'Crear Producto - RepartoAgua')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">📦 Crear Nuevo Producto</h1>
        <p class="mt-1 text-sm text-gray-600">Completa la información del producto</p>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <form action="{{ route('productos.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

            <!-- Nombre -->
            <div>
                <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">
                    Nombre del Producto <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="nombre" 
                       name="nombre" 
                       value="{{ old('nombre') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors @error('nombre') border-red-500 @enderror" 
                       placeholder="Ej: Bidón 20L"
                       required>
                @error('nombre')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Descripción -->
            <div>
                <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-2">
                    Descripción
                </label>
                <textarea id="descripcion" 
                          name="descripcion" 
                          rows="3"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors @error('descripcion') border-red-500 @enderror" 
                          placeholder="Descripción detallada del producto">{{ old('descripcion') }}</textarea>
                @error('descripcion')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">Opcional: Información adicional sobre el producto</p>
            </div>

            <!-- Precio Base -->
            <div>
                <label for="precio_base" class="block text-sm font-medium text-gray-700 mb-2">
                    Precio Base <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 text-lg font-semibold">$</span>
                    </div>
                    <input type="number" 
                           id="precio_base" 
                           name="precio_base" 
                           value="{{ old('precio_base') }}"
                           step="0.01" 
                           min="0"
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors @error('precio_base') border-red-500 @enderror" 
                           placeholder="0.00"
                           required>
                </div>
                @error('precio_base')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">Precio base del producto en pesos</p>
            </div>

            <!-- Estado Activo -->
            <div class="flex items-start">
                <div class="flex items-center h-5">
                    <input id="activo" 
                           name="activo" 
                           type="checkbox" 
                           {{ old('activo', true) ? 'checked' : '' }}
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                </div>
                <div class="ml-3">
                    <label for="activo" class="text-sm font-medium text-gray-700">
                        Producto activo
                    </label>
                    <p class="text-xs text-gray-500">
                        Los productos activos están disponibles para nuevos repartos
                    </p>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-200">
                <button type="submit" 
                        class="flex-1 sm:flex-none inline-flex justify-center items-center px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Guardar Producto
                </button>
                <a href="{{ route('productos.index') }}" 
                   class="flex-1 sm:flex-none inline-flex justify-center items-center px-6 py-2.5 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-lg shadow-sm transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
