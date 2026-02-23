@extends('layouts.app')

@section('title', 'Editar Vehículo')

@section('breadcrumbs')
    <a href="{{ route('vehiculos.index') }}" class="text-slate-400 hover:text-slate-600">Vehículos</a>
    <span class="text-slate-400">/</span>
    <span class="text-slate-600">{{ $vehiculo->placa }}</span>
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
    <x-card>
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-slate-900">Editar Vehículo: {{ $vehiculo->placa }}</h2>
            <p class="text-slate-600 mt-1">Actualiza los datos del vehículo</p>
        </div>

        <form action="{{ route('vehiculos.update', $vehiculo) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <!-- Información Básica -->
                <div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Información Básica
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="placa" value="Placa *" />
                            <x-text-input type="text" id="placa" name="placa" :value="old('placa', $vehiculo->placa)" required placeholder="Ej: ABC-123" />
                            <x-input-error :messages="$errors->get('placa')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="tipo" value="Tipo de Vehículo *" />
                            <select name="tipo" id="tipo" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="">Seleccionar tipo</option>
                                <option value="camion" {{ old('tipo', $vehiculo->tipo) === 'camion' ? 'selected' : '' }}>🚛 Camión</option>
                                <option value="camioneta" {{ old('tipo', $vehiculo->tipo) === 'camioneta' ? 'selected' : '' }}>🚐 Camioneta</option>
                                <option value="furgoneta" {{ old('tipo', $vehiculo->tipo) === 'furgoneta' ? 'selected' : '' }}>🚙 Furgoneta</option>
                                <option value="auto" {{ old('tipo', $vehiculo->tipo) === 'auto' ? 'selected' : '' }}>🚗 Auto</option>
                                <option value="moto" {{ old('tipo', $vehiculo->tipo) === 'moto' ? 'selected' : '' }}>🏍️ Moto</option>
                            </select>
                            <x-input-error :messages="$errors->get('tipo')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="marca" value="Marca *" />
                            <x-text-input type="text" id="marca" name="marca" :value="old('marca', $vehiculo->marca)" required placeholder="Ej: Toyota" />
                            <x-input-error :messages="$errors->get('marca')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="modelo" value="Modelo *" />
                            <x-text-input type="text" id="modelo" name="modelo" :value="old('modelo', $vehiculo->modelo)" required placeholder="Ej: Hilux" />
                            <x-input-error :messages="$errors->get('modelo')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="año" value="Año *" />
                            <x-text-input type="number" id="año" name="año" :value="old('año', $vehiculo->año)" required min="1900" :max="date('Y') + 1" />
                            <x-input-error :messages="$errors->get('año')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="color" value="Color" />
                            <x-text-input type="text" id="color" name="color" :value="old('color', $vehiculo->color)" placeholder="Ej: Blanco" />
                            <x-input-error :messages="$errors->get('color')" class="mt-2" />
                        </div>
                    </div>
                </div>

                <!-- Especificaciones Técnicas -->
                <div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Especificaciones Técnicas
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="numero_motor" value="Número de Motor" />
                            <x-text-input type="text" id="numero_motor" name="numero_motor" :value="old('numero_motor', $vehiculo->numero_motor)" />
                            <x-input-error :messages="$errors->get('numero_motor')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="numero_chasis" value="Número de Chasis" />
                            <x-text-input type="text" id="numero_chasis" name="numero_chasis" :value="old('numero_chasis', $vehiculo->numero_chasis)" />
                            <x-input-error :messages="$errors->get('numero_chasis')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="capacidad_carga" value="Capacidad de Carga (kg)" />
                            <x-text-input type="number" id="capacidad_carga" name="capacidad_carga" :value="old('capacidad_carga', $vehiculo->capacidad_carga)" step="0.01" />
                            <x-input-error :messages="$errors->get('capacidad_carga')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="capacidad_bidones" value="Capacidad en Bidones" />
                            <x-text-input type="number" id="capacidad_bidones" name="capacidad_bidones" :value="old('capacidad_bidones', $vehiculo->capacidad_bidones)" />
                            <x-input-error :messages="$errors->get('capacidad_bidones')" class="mt-2" />
                            <p class="text-xs text-slate-500 mt-1">Cantidad de bidones de agua que puede transportar</p>
                        </div>

                        <div>
                            <x-input-label for="kilometraje" value="Kilometraje Actual *" />
                            <x-text-input type="number" id="kilometraje" name="kilometraje" :value="old('kilometraje', $vehiculo->kilometraje)" required />
                            <x-input-error :messages="$errors->get('kilometraje')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="combustible" value="Tipo de Combustible" />
                            <select name="combustible" id="combustible" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Seleccionar combustible</option>
                                <option value="gasolina" {{ old('combustible', $vehiculo->combustible) === 'gasolina' ? 'selected' : '' }}>Gasolina</option>
                                <option value="diesel" {{ old('combustible', $vehiculo->combustible) === 'diesel' ? 'selected' : '' }}>Diesel</option>
                                <option value="gnc" {{ old('combustible', $vehiculo->combustible) === 'gnc' ? 'selected' : '' }}>GNC</option>
                                <option value="electrico" {{ old('combustible', $vehiculo->combustible) === 'electrico' ? 'selected' : '' }}>Eléctrico</option>
                                <option value="hibrido" {{ old('combustible', $vehiculo->combustible) === 'hibrido' ? 'selected' : '' }}>Híbrido</option>
                            </select>
                            <x-input-error :messages="$errors->get('combustible')" class="mt-2" />
                        </div>
                    </div>
                </div>

                <!-- Mantenimiento -->
                <div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Control de Mantenimiento
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="ultimo_mantenimiento" value="Último Mantenimiento" />
                            <x-text-input type="date" id="ultimo_mantenimiento" name="ultimo_mantenimiento" :value="old('ultimo_mantenimiento', $vehiculo->ultimo_mantenimiento?->format('Y-m-d'))" />
                            <x-input-error :messages="$errors->get('ultimo_mantenimiento')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="proximo_mantenimiento" value="Próximo Mantenimiento" />
                            <x-text-input type="date" id="proximo_mantenimiento" name="proximo_mantenimiento" :value="old('proximo_mantenimiento', $vehiculo->proximo_mantenimiento?->format('Y-m-d'))" />
                            <x-input-error :messages="$errors->get('proximo_mantenimiento')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="seguro_vencimiento" value="Vencimiento del Seguro" />
                            <x-text-input type="date" id="seguro_vencimiento" name="seguro_vencimiento" :value="old('seguro_vencimiento', $vehiculo->seguro_vencimiento?->format('Y-m-d'))" />
                            <x-input-error :messages="$errors->get('seguro_vencimiento')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="revision_tecnica" value="Revisión Técnica" />
                            <x-text-input type="date" id="revision_tecnica" name="revision_tecnica" :value="old('revision_tecnica', $vehiculo->revision_tecnica?->format('Y-m-d'))" />
                            <x-input-error :messages="$errors->get('revision_tecnica')" class="mt-2" />
                        </div>
                    </div>
                </div>

                <!-- Estado y observaciones -->
                <div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Estado y Observaciones
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="estado" value="Estado *" />
                            <select name="estado" id="estado" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="disponible" {{ old('estado', $vehiculo->estado) === 'disponible' ? 'selected' : '' }}>Disponible</option>
                                <option value="en_uso" {{ old('estado', $vehiculo->estado) === 'en_uso' ? 'selected' : '' }}>En Uso</option>
                                <option value="mantenimiento" {{ old('estado', $vehiculo->estado) === 'mantenimiento' ? 'selected' : '' }}>Mantenimiento</option>
                                <option value="fuera_servicio" {{ old('estado', $vehiculo->estado) === 'fuera_servicio' ? 'selected' : '' }}>Fuera de Servicio</option>
                            </select>
                            <x-input-error :messages="$errors->get('estado')" class="mt-2" />
                        </div>

                        <div class="md:col-span-2">
                            <x-input-label for="observaciones" value="Observaciones" />
                            <textarea 
                                id="observaciones" 
                                name="observaciones" 
                                rows="4" 
                                class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Detalles adicionales, historial de reparaciones, etc."
                            >{{ old('observaciones', $vehiculo->observaciones) }}</textarea>
                            <x-input-error :messages="$errors->get('observaciones')" class="mt-2" />
                        </div>
                    </div>
                </div>

                <!-- Asignación de Chofer -->
                <div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Asignación de Chofer
                    </h3>
                    <div class="bg-slate-50 p-4 rounded-lg">
                        <p class="text-sm text-slate-600 mb-3">Selecciona uno o más choferes para asignar a este vehículo</p>
                        @if($choferes->isEmpty())
                        <div class="text-center py-8 text-slate-500">
                            <svg class="w-12 h-12 mx-auto mb-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            <p class="text-sm">No hay choferes disponibles</p>
                            <a href="{{ route('usuarios.create', ['role' => 'chofer']) }}" class="text-sm text-indigo-600 hover:text-indigo-700 mt-2 inline-block">Crear chofer →</a>
                        </div>
                        @else
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @php
                                $choferesActuales = old('choferes', $vehiculo->choferesActivos->pluck('id')->toArray());
                            @endphp
                            @foreach($choferes as $chofer)
                            <label class="flex items-start gap-3 p-3 bg-white rounded-lg border border-slate-200 hover:border-indigo-300 cursor-pointer transition-colors {{ in_array($chofer->id, $choferesActuales) ? 'border-indigo-500 bg-indigo-50' : '' }}">
                                <input 
                                    type="checkbox" 
                                    name="choferes[]" 
                                    value="{{ $chofer->id }}"
                                    {{ in_array($chofer->id, $choferesActuales) ? 'checked' : '' }}
                                    class="mt-1 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                                />
                                <div class="flex-1">
                                    <div class="font-medium text-slate-900">{{ $chofer->nombre_completo }}</div>
                                    <div class="text-sm text-slate-500">{{ $chofer->email }}</div>
                                    @if($chofer->vehiculosActivos->isNotEmpty())
                                    <div class="text-xs text-amber-600 mt-1">
                                        Ya tiene {{ $chofer->vehiculosActivos->count() }} vehículo(s) asignado(s)
                                        @if(!$chofer->vehiculosActivos->contains($vehiculo->id))
                                        - Adicional a este
                                        @endif
                                    </div>
                                    @endif
                                </div>
                            </label>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Botones de acción -->
            <div class="flex items-center justify-end gap-4 mt-8 pt-6 border-t border-slate-200">
                <a href="{{ route('vehiculos.show', $vehiculo) }}">
                    <x-button type="button" variant="outline">
                        Cancelar
                    </x-button>
                </a>
                <x-button type="submit" variant="primary">
                    <x-slot:icon>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </x-slot:icon>
                    Actualizar Vehículo
                </x-button>
            </div>
        </form>
    </x-card>
</div>
@endsection
