@extends('layouts.app')

@section('title', 'Estado de Cuenta - ' . $cliente->nombre)

@section('breadcrumbs')
    <a href="{{ route('reportes.index') }}" class="text-slate-400 hover:text-slate-600">Reportes</a>
    <span class="text-slate-400 mx-2">/</span>
    <a href="{{ route('reportes.estado-cuenta-cliente') }}" class="text-slate-400 hover:text-slate-600">Estado de Cuenta</a>
    <span class="text-slate-400 mx-2">/</span>
    <span class="text-slate-600">{{ $cliente->nombre }}</span>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">📄 Estado de Cuenta</h1>
            <p class="text-slate-500 mt-1">{{ $cliente->nombre }} {{ $cliente->apellido ?? '' }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('reportes.estado-cuenta-cliente') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-300 rounded-lg text-slate-700 hover:bg-slate-50">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Cambiar Cliente
            </a>
            <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                Imprimir
            </button>
        </div>
    </div>

    <!-- Información del Cliente -->
    <x-card>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <p class="text-sm text-slate-500">Cliente</p>
                <p class="text-lg font-semibold text-slate-900">{{ $cliente->nombre }} {{ $cliente->apellido ?? '' }}</p>
            </div>
            <div>
                <p class="text-sm text-slate-500">Tipo</p>
                <x-badge :color="$cliente->tipo_cliente === 'empresa' ? 'purple' : ($cliente->tipo_cliente === 'comercio' ? 'sky' : 'slate')">
                    {{ ucfirst($cliente->tipo_cliente) }}
                </x-badge>
            </div>
            <div>
                <p class="text-sm text-slate-500">Contacto</p>
                <p class="text-slate-900">{{ $cliente->telefono ?? 'N/A' }}</p>
                @if($cliente->email)
                    <p class="text-sm text-slate-600">{{ $cliente->email }}</p>
                @endif
            </div>
        </div>
    </x-card>

    <!-- Período y Filtros -->
    <x-card>
        <form method="GET" action="{{ route('reportes.estado-cuenta-cliente', $cliente->id) }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Fecha Inicio</label>
                <input type="date" name="fecha_inicio" value="{{ $fechaInicio }}" class="w-full rounded-lg border-slate-300 focus:border-purple-500 focus:ring focus:ring-purple-200">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Fecha Fin</label>
                <input type="date" name="fecha_fin" value="{{ $fechaFin }}" class="w-full rounded-lg border-slate-300 focus:border-purple-500 focus:ring focus:ring-purple-200">
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                    Aplicar
                </button>
            </div>
        </form>
    </x-card>

    <!-- Resumen -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <x-stat-card 
            title="Total Débitos" 
            :value="'$' . number_format($totalDebitos, 2)" 
            color="red"
            subtitle="repartos realizados">
            <x-slot:icon>
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                </svg>
            </x-slot:icon>
        </x-stat-card>

        <x-stat-card 
            title="Total Créditos" 
            :value="'$' . number_format($totalCreditos, 2)" 
            color="emerald"
            subtitle="pagos recibidos">
            <x-slot:icon>
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </x-slot:icon>
        </x-stat-card>

        <x-stat-card 
            title="Saldo Actual" 
            :value="'$' . number_format($saldoActual, 2)" 
            :color="$saldoActual > 0 ? 'amber' : 'emerald'"
            :subtitle="$saldoActual > 0 ? 'pendiente de pago' : 'al día'">
            <x-slot:icon>
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                </svg>
            </x-slot:icon>
        </x-stat-card>
    </div>

    <!-- Movimientos -->
    <x-card title="Movimientos de Cuenta" :padding="false">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Fecha</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Tipo</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Concepto</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-slate-600 uppercase tracking-wider">Débito</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-slate-600 uppercase tracking-wider">Crédito</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-slate-600 uppercase tracking-wider">Saldo</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @php $saldo = 0; @endphp
                    @forelse($movimientos as $mov)
                        @php
                            if ($mov->tipo === 'debito') {
                                $saldo += $mov->monto;
                            } else {
                                $saldo -= $mov->monto;
                            }
                        @endphp
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4 text-sm text-slate-600">{{ \Carbon\Carbon::parse($mov->fecha)->format('d/m/Y') }}</td>
                            <td class="px-6 py-4">
                                <x-badge :color="$mov->tipo === 'debito' ? 'red' : 'emerald'">
                                    {{ $mov->tipo === 'debito' ? 'Débito' : 'Crédito' }}
                                </x-badge>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-900">
                                @if($mov->referencia_tipo === 'App\Models\Reparto')
                                    Reparto #{{ $mov->referencia_id }}
                                    @if($mov->referencia)
                                        - {{ $mov->referencia->cantidad ?? 0 }} bidones
                                    @endif
                                @elseif($mov->referencia_tipo === 'App\Models\Pago')
                                    Pago #{{ $mov->referencia_id }}
                                    @if($mov->referencia)
                                        - {{ ucfirst($mov->referencia->metodo_pago ?? 'N/A') }}
                                    @endif
                                @else
                                    {{ $mov->origen ?? 'Movimiento' }}
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right text-sm {{ $mov->tipo === 'debito' ? 'font-semibold text-red-600' : 'text-slate-400' }}">
                                {{ $mov->tipo === 'debito' ? '$' . number_format($mov->monto, 2) : '-' }}
                            </td>
                            <td class="px-6 py-4 text-right text-sm {{ $mov->tipo === 'credito' ? 'font-semibold text-emerald-600' : 'text-slate-400' }}">
                                {{ $mov->tipo === 'credito' ? '$' . number_format($mov->monto, 2) : '-' }}
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-semibold {{ $saldo > 0 ? 'text-amber-600' : 'text-emerald-600' }}">
                                ${{ number_format($saldo, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-slate-500">
                                No hay movimientos en este período
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if($movimientos->count() > 0)
                <tfoot class="bg-slate-50 border-t-2 border-slate-300">
                    <tr>
                        <td colspan="3" class="px-6 py-4 font-bold text-slate-900">TOTALES</td>
                        <td class="px-6 py-4 text-right font-bold text-red-600">${{ number_format($totalDebitos, 2) }}</td>
                        <td class="px-6 py-4 text-right font-bold text-emerald-600">${{ number_format($totalCreditos, 2) }}</td>
                        <td class="px-6 py-4 text-right font-bold {{ $saldoActual > 0 ? 'text-amber-600' : 'text-emerald-600' }}">
                            ${{ number_format($saldoActual, 2) }}
                        </td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </x-card>

    <!-- Nota -->
    @if($saldoActual > 0)
    <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
        <div class="flex gap-3">
            <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            <div class="text-sm text-amber-800">
                <p class="font-semibold">Saldo Pendiente</p>
                <p class="mt-1">Este cliente tiene un saldo pendiente de <strong>${{ number_format($saldoActual, 2) }}</strong></p>
            </div>
        </div>
    </div>
    @else
    <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4">
        <div class="flex gap-3">
            <svg class="w-5 h-5 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div class="text-sm text-emerald-800">
                <p class="font-semibold">Cliente al Día</p>
                <p class="mt-1">Este cliente no tiene saldo pendiente. Cuenta al día.</p>
            </div>
        </div>
    </div>
    @endif

</div>

@push('styles')
<style>
    @media print {
        nav, aside, .no-print, button, form {
            display: none !important;
        }
    }
</style>
@endpush
@endsection
