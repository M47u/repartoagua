@extends('layouts.app')

@section('title', 'Reportes')

@section('breadcrumbs')
    <span class="text-slate-400">Reportes</span>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-3xl font-bold text-slate-900">📊 Centro de Reportes</h1>
        <p class="text-slate-500 mt-1">Análisis y reportes del negocio</p>
    </div>

    <!-- Reportes Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        
        <!-- Bidones Cobrados -->
        <a href="{{ route('reportes.bidones-cobrados') }}" class="group">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 hover:shadow-lg hover:border-emerald-300 transition-all duration-200">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <span class="text-emerald-600 text-xs font-semibold uppercase tracking-wide">Fase 1</span>
                </div>
                <h3 class="text-lg font-semibold text-slate-900 mb-2 group-hover:text-emerald-600 transition-colors">Bidones Cobrados</h3>
                <p class="text-sm text-slate-500">Análisis de bidones efectivamente cobrados por período, producto y cliente.</p>
                <div class="mt-4 flex items-center text-emerald-600 text-sm font-medium">
                    Ver reporte
                    <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </div>
        </a>

        <!-- Ingresos por Período -->
        <a href="{{ route('reportes.ingresos-por-periodo') }}" class="group">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 hover:shadow-lg hover:border-sky-300 transition-all duration-200">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-sky-400 to-sky-600 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <span class="text-sky-600 text-xs font-semibold uppercase tracking-wide">Fase 1</span>
                </div>
                <h3 class="text-lg font-semibold text-slate-900 mb-2 group-hover:text-sky-600 transition-colors">Ingresos por Periodo</h3>
                <p class="text-sm text-slate-500">Análisis detallado de ingresos con gráficos y comparativas.</p>
                <div class="mt-4 flex items-center text-sky-600 text-sm font-medium">
                    Ver reporte
                    <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </div>
        </a>

        <!-- Estado de Cuenta por Cliente -->
        <a href="{{ route('reportes.estado-cuenta-cliente') }}" class="group">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 hover:shadow-lg hover:border-purple-300 transition-all duration-200">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-400 to-purple-600 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <span class="text-purple-600 text-xs font-semibold uppercase tracking-wide">Fase 1</span>
                </div>
                <h3 class="text-lg font-semibold text-slate-900 mb-2 group-hover:text-purple-600 transition-colors">Estado de Cuenta</h3>
                <p class="text-sm text-slate-500">Detalle completo de movimientos y saldo de cada cliente.</p>
                <div class="mt-4 flex items-center text-purple-600 text-sm font-medium">
                    Ver reporte
                    <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </div>
        </a>

        <!-- Cuentas por Cobrar -->
        <a href="{{ route('reportes.cuentas-por-cobrar') }}" class="group">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 hover:shadow-lg hover:border-amber-300 transition-all duration-200">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-amber-400 to-amber-600 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <span class="text-amber-600 text-xs font-semibold uppercase tracking-wide">Fase 1</span>
                </div>
                <h3 class="text-lg font-semibold text-slate-900 mb-2 group-hover:text-amber-600 transition-colors">Cuentas por Cobrar</h3>
                <p class="text-sm text-slate-500">Listado de clientes con deuda pendiente y análisis de morosidad.</p>
                <div class="mt-4 flex items-center text-amber-600 text-sm font-medium">
                    Ver reporte
                    <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </div>
        </a>

        <!-- Repartos por Período -->
        <a href="{{ route('reportes.repartos-por-periodo') }}" class="group">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 hover:shadow-lg hover:border-indigo-300 transition-all duration-200">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-indigo-400 to-indigo-600 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path>
                        </svg>
                    </div>
                    <span class="text-indigo-600 text-xs font-semibold uppercase tracking-wide">Fase 1</span>
                </div>
                <h3 class="text-lg font-semibold text-slate-900 mb-2 group-hover:text-indigo-600 transition-colors">Repartos por Período</h3>
                <p class="text-sm text-slate-500">Análisis de repartos realizados, pendientes y estadísticas operativas.</p>
                <div class="mt-4 flex items-center text-indigo-600 text-sm font-medium">
                    Ver reporte
                    <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </div>
        </a>

        <!-- Próximamente (Placeholder para Fase 2) -->
        <div class="bg-gradient-to-br from-slate-50 to-slate-100 rounded-xl shadow-sm border-2 border-dashed border-slate-300 p-6 opacity-60">
            <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 bg-slate-300 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </div>
                <span class="text-slate-500 text-xs font-semibold uppercase tracking-wide">Fase 2</span>
            </div>
            <h3 class="text-lg font-semibold text-slate-600 mb-2">Más reportes próximamente</h3>
            <p class="text-sm text-slate-400">Eficiencia de repartidores, análisis de zonas y más...</p>
        </div>

    </div>

    <!-- Info Box -->
    <x-card title="💡 Acerca de los Reportes">
        <div class="space-y-3 text-slate-600">
            <p><strong>Fase 1:</strong> Reportes financieros y operativos esenciales para el control diario del negocio.</p>
            <p><strong>Próximamente:</strong> Reportes analíticos avanzados, segmentación de clientes y optimización de rutas.</p>
            <p class="text-sm text-slate-500 mt-4">💾 Todos los reportes incluyen opciones de filtrado y exportación a PDF/Excel.</p>
        </div>
    </x-card>
</div>
@endsection
