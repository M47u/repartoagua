@extends('layouts.app')

@section('title', 'Ingresos por Período')

@section('breadcrumbs')
    <a href="{{ route('reportes.index') }}" class="text-slate-400 hover:text-slate-600">Reportes</a>
    <span class="text-slate-400 mx-2">/</span>
    <span class="text-slate-600">Ingresos por Período</span>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">💰 Ingresos por Período</h1>
            <p class="text-slate-500 mt-1">Análisis detallado de ingresos y cobranza</p>
        </div>
        <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-300 rounded-lg text-slate-700 hover:bg-slate-50">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
            </svg>
            Imprimir
        </button>
    </div>

    <!-- Filtros -->
    <x-card title="Filtros">
        <form method="GET" action="{{ route('reportes.ingresos-por-periodo') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Fecha Inicio</label>
                <input type="date" name="fecha_inicio" value="{{ $fechaInicio }}" class="w-full rounded-lg border-slate-300 focus:border-sky-500 focus:ring focus:ring-sky-200">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Fecha Fin</label>
                <input type="date" name="fecha_fin" value="{{ $fechaFin }}" class="w-full rounded-lg border-slate-300 focus:border-sky-500 focus:ring focus:ring-sky-200">
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2 bg-sky-600 text-white rounded-lg hover:bg-sky-700 transition-colors">
                    Aplicar Filtros
                </button>
            </div>
        </form>
    </x-card>

    <!-- KPI Principal -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-2">
            <x-stat-card 
                title="Total de Ingresos" 
                :value="'$' . number_format($ingresosTotales, 2, ',', '.')" 
                color="sky"
                subtitle="del {{ \Carbon\Carbon::parse($fechaInicio)->format('d/m/Y') }} al {{ \Carbon\Carbon::parse($fechaFin)->format('d/m/Y') }}">
                <x-slot:icon>
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </x-slot:icon>
            </x-stat-card>
        </div>
        <x-stat-card 
            title="Pagos Registrados" 
            :value="$ingresosPorMetodo->sum('cantidad')" 
            color="emerald"
            subtitle="transacciones">
            <x-slot:icon>
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
            </x-slot:icon>
        </x-stat-card>
    </div>

    <!-- Serie Temporal (Gráfico) -->
    @if(count($serieTemporal) > 0)
    <x-card title="Ingresos Diarios">
        <div class="overflow-x-auto pb-4 pt-4">
            <div class="min-w-max flex items-end justify-start gap-3 h-64 px-4 relative">
                @php
                    $maxValor = max(array_column($serieTemporal, 'total'));
                @endphp
                @foreach($serieTemporal as $index => $data)
                    <div class="flex flex-col items-center group relative min-w-[70px]">
                        <!-- Barra clickeable -->
                        <div class="relative w-full flex items-end justify-center" style="height: 210px;">
                            <div 
                                onclick="mostrarDetalleIngreso({{ $index }})"
                                class="w-14 bg-gradient-to-t from-sky-500 to-sky-400 rounded-t hover:from-sky-600 hover:to-sky-500 transition-all duration-200 cursor-pointer shadow-lg hover:shadow-2xl hover:scale-105 transform origin-bottom ring-2 ring-transparent hover:ring-sky-300 hover:ring-offset-2"
                                style="height: {{ $maxValor > 0 ? ($data['total'] / $maxValor * 190) : 0 }}px; min-height: 6px;">
                            </div>
                        </div>
                        
                        <!-- Etiqueta fecha -->
                        <span class="text-sm text-slate-700 mt-3 font-semibold">{{ $data['label'] }}</span>
                    </div>
                @endforeach
            </div>
            <p class="text-center text-xs text-slate-500 mt-4">
                <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"></path>
                </svg>
                Haz clic en cualquier barra para ver más detalles
            </p>
        </div>
        @if(count($serieTemporal) === 0)
        <p class="text-center text-slate-500 py-8">No hay datos de ingresos en este período</p>
        @endif
    </x-card>
    @endif

    <!-- Modal de Detalle de Ingreso -->
    <div id="modalDetalleIngreso" class="hidden fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4" onclick="cerrarModalIngreso(event)">
        <div class="bg-gradient-to-br from-slate-800 to-slate-900 text-white rounded-2xl shadow-2xl border border-slate-700/50 overflow-hidden max-w-md w-full transform transition-all duration-300 scale-95 opacity-0" id="modalContenido" onclick="event.stopPropagation()">
            <!-- Header con fecha -->
            <div class="bg-gradient-to-r from-sky-500/20 to-emerald-500/20 px-6 py-4 border-b border-slate-700/50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span class="text-sm font-medium text-slate-300" id="modalFecha"></span>
                    </div>
                    <button onclick="cerrarModalIngreso()" class="text-slate-400 hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
            <!-- Monto principal -->
            <div class="px-6 py-6 text-center bg-gradient-to-b from-transparent to-slate-800/30">
                <div class="text-sm font-medium text-sky-400 mb-2 uppercase tracking-wide">Total Ingresado</div>
                <div class="text-4xl font-bold bg-gradient-to-r from-emerald-400 to-sky-400 bg-clip-text text-transparent" id="modalMonto">
                </div>
            </div>
            
            <!-- Detalles con íconos -->
            <div class="px-6 py-4 space-y-3 bg-slate-800/50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2 text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <span>Transacciones</span>
                    </div>
                    <span class="font-bold text-white text-lg" id="modalCantidad"></span>
                </div>
                
                <div class="h-px bg-gradient-to-r from-transparent via-slate-700 to-transparent"></div>
                
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2 text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <span>Promedio por transacción</span>
                    </div>
                    <span class="font-bold text-sky-400 text-lg" id="modalPromedio"></span>
                </div>
                
                <div class="h-px bg-gradient-to-r from-transparent via-slate-700 to-transparent"></div>
                
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2 text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
                        </svg>
                        <span>Porcentaje del período</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-emerald-400 text-lg" id="modalPorcentaje"></span>
                        <div class="w-16 h-2 bg-slate-700 rounded-full overflow-hidden">
                            <div id="modalBarraPorcentaje" class="h-full bg-gradient-to-r from-emerald-500 to-sky-500 rounded-full transition-all duration-500"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 bg-slate-800/30 border-t border-slate-700/50">
                <button onclick="cerrarModalIngreso()" class="w-full px-4 py-2.5 bg-gradient-to-r from-sky-600 to-sky-500 hover:from-sky-700 hover:to-sky-600 text-white font-semibold rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl">
                    Cerrar
                </button>
            </div>
        </div>
    </div>

    <!-- Ingresos por Método de Pago -->
    <x-card title="Ingresos por Método de Pago" :padding="false">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Método</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-slate-600 uppercase tracking-wider">Cantidad</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-slate-600 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-slate-600 uppercase tracking-wider">% del Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($ingresosPorMetodo as $metodo)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    @if($metodo->metodo_pago === 'efectivo')
                                        <span class="text-2xl">💵</span>
                                    @elseif($metodo->metodo_pago === 'transferencia')
                                        <span class="text-2xl">🏦</span>
                                    @elseif($metodo->metodo_pago === 'tarjeta')
                                        <span class="text-2xl">💳</span>
                                    @else
                                        <span class="text-2xl">💰</span>
                                    @endif
                                    <span class="text-sm font-medium text-slate-900">{{ ucfirst($metodo->metodo_pago ?? 'Sin especificar') }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-sky-100 text-sky-800">
                                    {{ $metodo->cantidad }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right font-semibold text-sky-600">${{ number_format($metodo->total, 2) }}</td>
                            <td class="px-6 py-4 text-right text-sm text-slate-600">
                                {{ $ingresosTotales > 0 ? number_format($metodo->total / $ingresosTotales * 100, 1) : 0 }}%
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-slate-500">No hay datos para mostrar</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-slate-50 border-t-2 border-slate-300">
                    <tr>
                        <td class="px-6 py-4 font-bold text-slate-900">TOTAL</td>
                        <td class="px-6 py-4 text-center font-bold text-slate-900">{{ $ingresosPorMetodo->sum('cantidad') }}</td>
                        <td class="px-6 py-4 text-right font-bold text-sky-600">${{ number_format($ingresosTotales, 2) }}</td>
                        <td class="px-6 py-4 text-right font-bold text-slate-900">100%</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </x-card>

    <!-- Top 10 Clientes -->
    <x-card title="Top 10 Clientes por Ingresos" :padding="false">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">#</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Cliente</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-slate-600 uppercase tracking-wider">Pagos</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-slate-600 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-slate-600 uppercase tracking-wider">% del Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($ingresosPorCliente as $index => $item)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4 text-sm font-bold text-slate-500">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-slate-900">
                                {{ $item->cliente->nombre }} {{ $item->cliente->apellido ?? '' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                    {{ $item->cantidad }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right font-semibold text-emerald-600">${{ number_format($item->total, 2) }}</td>
                            <td class="px-6 py-4 text-right text-sm text-slate-600">
                                {{ $ingresosTotales > 0 ? number_format($item->total / $ingresosTotales * 100, 1) : 0 }}%
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-slate-500">No hay datos para mostrar</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>

</div>

@push('styles')
<style>
    @media print {
        nav, aside, .no-print, button {
            display: none !important;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Datos de la serie temporal para el modal
    const datosIngresos = @json($serieTemporal);

    function mostrarDetalleIngreso(index) {
        const data = datosIngresos[index];
        if (!data) return;

        // Actualizar contenido del modal
        document.getElementById('modalFecha').textContent = data.fecha_completa.charAt(0).toUpperCase() + data.fecha_completa.slice(1);
        document.getElementById('modalMonto').textContent = `$${parseFloat(data.total).toLocaleString('es-MX', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
        document.getElementById('modalCantidad').textContent = data.cantidad;
        document.getElementById('modalPromedio').textContent = `$${parseFloat(data.promedio).toLocaleString('es-MX', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
        document.getElementById('modalPorcentaje').textContent = `${parseFloat(data.porcentaje).toFixed(1)}%`;
        document.getElementById('modalBarraPorcentaje').style.width = `${Math.min(data.porcentaje, 100)}%`;

        // Mostrar modal
        const modal = document.getElementById('modalDetalleIngreso');
        const contenido = document.getElementById('modalContenido');
        
        modal.classList.remove('hidden');
        
        // Animación de entrada
        setTimeout(() => {
            contenido.classList.remove('scale-95', 'opacity-0');
            contenido.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function cerrarModalIngreso(event) {
        // Si se pasa un event y el click fue en el modal (no en el backdrop), no hacer nada
        if (event && event.target.id !== 'modalDetalleIngreso') {
            return;
        }
        
        const modal = document.getElementById('modalDetalleIngreso');
        const contenido = document.getElementById('modalContenido');
        
        // Animación de salida
        contenido.classList.add('scale-95', 'opacity-0');
        contenido.classList.remove('scale-100', 'opacity-100');
        
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    // Cerrar con tecla ESC
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            cerrarModalIngreso();
        }
    });
</script>
@endpush

@endsection
