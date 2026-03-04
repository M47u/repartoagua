# 🔧 Guía de Implementación - Mejoras Prioritarias

## 🎯 Quick Wins: Código Listo para Implementar

---

## 1️⃣ EXPORTACIÓN A PDF/EXCEL

### 📦 Instalación de Dependencias

```bash
# Instalar Laravel Excel
composer require maatwebsite/excel

# Instalar DomPDF
composer require barryvdh/laravel-dompdf

# Publicar configuraciones
php artisan vendor:publish --provider="Maatwebsite\Excel\ExcelServiceProvider" --tag=config
php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider"
```

### 📄 Crear Export Class (Excel)

**Archivo:** `app/Exports/BidonesCobradosExport.php`

```php
<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BidonesCobradosExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    protected $bidonesDetalle;
    protected $fechaInicio;
    protected $fechaFin;

    public function __construct($bidonesDetalle, $fechaInicio, $fechaFin)
    {
        $this->bidonesDetalle = collect($bidonesDetalle);
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin = $fechaFin;
    }

    public function collection()
    {
        return $this->bidonesDetalle->map(function($item, $index) {
            return [
                'N°' => $index + 1,
                'Fecha' => \Carbon\Carbon::parse($item['fecha'])->format('d/m/Y H:i'),
                'Cliente' => $item['cliente'],
                'Producto' => $item['producto'],
                'Cantidad' => $item['cantidad'],
                'Monto' => '$' . number_format($item['monto'], 2),
                'Método Pago' => ucfirst($item['metodo_pago']),
                'Repartidor' => $item['repartidor'],
            ];
        });
    }

    public function headings(): array
    {
        return [
            'N°',
            'Fecha',
            'Cliente',
            'Producto',
            'Cantidad',
            'Monto',
            'Método Pago',
            'Repartidor',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '10B981']
                ],
                'font' => ['color' => ['rgb' => 'FFFFFF']],
            ],
        ];
    }
}
```

### 📊 Agregar Métodos al Controlador

**Archivo:** `app/Http/Controllers/ReporteController.php`

```php
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BidonesCobradosExport;
use Barryvdh\DomPDF\Facade\Pdf;

// Agregar al final de la clase ReporteController:

/**
 * Exportar Bidones Cobrados a Excel
 */
public function bidonesCobradosExcel(Request $request)
{
    // Reutilizar la misma lógica del reporte
    $fechaInicio = $request->get('fecha_inicio', now()->startOfMonth()->format('Y-m-d'));
    $fechaFin = $request->get('fecha_fin', now()->format('Y-m-d'));
    
    // ... [misma lógica de filtrado] ...
    
    $fileName = 'bidones_cobrados_' . $fechaInicio . '_' . $fechaFin . '.xlsx';
    
    return Excel::download(
        new BidonesCobradosExport($bidonesDetalle, $fechaInicio, $fechaFin), 
        $fileName
    );
}

/**
 * Exportar Bidones Cobrados a PDF
 */
public function bidonesCobradosPdf(Request $request)
{
    $fechaInicio = $request->get('fecha_inicio', now()->startOfMonth()->format('Y-m-d'));
    $fechaFin = $request->get('fecha_fin', now()->format('Y-m-d'));
    
    // ... [misma lógica de filtrado] ...
    
    $pdf = Pdf::loadView('reportes.pdf.bidones-cobrados', compact(
        'fechaInicio',
        'fechaFin',
        'totalBidones',
        'totalMonto',
        'bidonesDetalle',
        'agrupadoPorProducto',
        'agrupadoPorCliente',
        'agrupadoPorMetodo'
    ));
    
    $pdf->setPaper('letter', 'landscape');
    
    return $pdf->download('bidones_cobrados_' . $fechaFin . '.pdf');
}
```

### 🛣️ Agregar Rutas

**Archivo:** `routes/web.php`

```php
Route::prefix('reportes')->name('reportes.')->group(function () {
    // Rutas existentes...
    
    // Exportaciones
    Route::get('/bidones-cobrados/excel', [ReporteController::class, 'bidonesCobradosExcel'])->name('bidones-cobrados.excel');
    Route::get('/bidones-cobrados/pdf', [ReporteController::class, 'bidonesCobradosPdf'])->name('bidones-cobrados.pdf');
    
    Route::get('/ingresos-por-periodo/excel', [ReporteController::class, 'ingresosPorPeriodoExcel'])->name('ingresos-por-periodo.excel');
    Route::get('/ingresos-por-periodo/pdf', [ReporteController::class, 'ingresosPorPeriodoPdf'])->name('ingresos-por-periodo.pdf');
    
    Route::get('/cuentas-por-cobrar/excel', [ReporteController::class, 'cuentasPorCobrarExcel'])->name('cuentas-por-cobrar.excel');
    Route::get('/cuentas-por-cobrar/pdf', [ReporteController::class, 'cuentasPorCobrarPdf'])->name('cuentas-por-cobrar.pdf');
});
```

### 🎨 Vista PDF Template

**Archivo:** `resources/views/reportes/pdf/bidones-cobrados.blade.php`

```blade
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Bidones Cobrados</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #10B981;
            padding-bottom: 10px;
        }
        .header h1 {
            color: #10B981;
            margin: 0;
            font-size: 20px;
        }
        .info-box {
            background: #f3f4f6;
            padding: 10px;
            margin: 15px 0;
            border-radius: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th {
            background: #10B981;
            color: white;
            padding: 8px;
            text-align: left;
            font-size: 10px;
        }
        td {
            padding: 6px 8px;
            border-bottom: 1px solid #e5e7eb;
        }
        tr:nth-child(even) {
            background: #f9fafb;
        }
        .totals {
            margin-top: 20px;
            text-align: right;
        }
        .totals strong {
            color: #10B981;
            font-size: 14px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 9px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📊 Reporte de Bidones Cobrados</h1>
        <p style="margin: 5px 0; color: #6b7280;">
            Período: {{ \Carbon\Carbon::parse($fechaInicio)->format('d/m/Y') }} - 
            {{ \Carbon\Carbon::parse($fechaFin)->format('d/m/Y') }}
        </p>
    </div>

    <div class="info-box">
        <table style="border: none; margin: 0;">
            <tr style="background: transparent !important;">
                <td style="border: none;"><strong>Total Bidones:</strong> {{ number_format($totalBidones) }}</td>
                <td style="border: none;"><strong>Total Monto:</strong> ${{ number_format($totalMonto, 2) }}</td>
                <td style="border: none;"><strong>Promedio:</strong> ${{ $totalBidones > 0 ? number_format($totalMonto / $totalBidones, 2) : '0.00' }}</td>
            </tr>
        </table>
    </div>

    <h3 style="color: #374151; margin-top: 25px;">Detalle de Bidones Cobrados</h3>
    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Cliente</th>
                <th>Producto</th>
                <th>Cant.</th>
                <th>Monto</th>
                <th>Método</th>
                <th>Repartidor</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bidonesDetalle as $bidon)
            <tr>
                <td>{{ \Carbon\Carbon::parse($bidon['fecha'])->format('d/m/Y H:i') }}</td>
                <td>{{ $bidon['cliente'] }}</td>
                <td>{{ $bidon['producto'] }}</td>
                <td>{{ $bidon['cantidad'] }}</td>
                <td>${{ number_format($bidon['monto'], 2) }}</td>
                <td>{{ ucfirst($bidon['metodo_pago']) }}</td>
                <td>{{ $bidon['repartidor'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <strong>TOTAL: ${{ number_format($totalMonto, 2) }}</strong>
    </div>

    <div class="footer">
        Generado el {{ now()->format('d/m/Y H:i') }} | Sistema de Reparto de Agua
    </div>
</body>
</html>
```

### 🔘 Botones en la Vista

**Archivo:** `resources/views/reportes/bidones-cobrados.blade.php`

```blade
{{-- Agregar después del título, antes del formulario de filtros --}}

<div class="flex gap-2 mb-4">
    <a href="{{ route('reportes.bidones-cobrados.excel', request()->all()) }}" 
       class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
        Exportar Excel
    </a>
    
    <a href="{{ route('reportes.bidones-cobrados.pdf', request()->all()) }}" 
       class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
        </svg>
        Exportar PDF
    </a>
</div>
```

---

## 2️⃣ GRÁFICOS CON CHART.JS

### 📦 Instalación

```bash
npm install chart.js
```

**O simplemente agregar el CDN en el layout:**

```blade
{{-- En resources/views/layouts/app.blade.php, antes de </body> --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
```

### 📊 Ejemplo: Gráfico en Ingresos por Período

**Archivo:** `resources/views/reportes/ingresos-por-periodo.blade.php`

```blade
{{-- Agregar después de las estadísticas principales --}}

<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-6">
    <h3 class="text-lg font-semibold text-slate-900 mb-4">
        📈 Evolución Temporal de Ingresos
    </h3>
    <canvas id="ingresosDiariosChart" height="80"></canvas>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    {{-- Gráfico de Pastel: Métodos de Pago --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h3 class="text-lg font-semibold text-slate-900 mb-4">
            💳 Ingresos por Método de Pago
        </h3>
        <canvas id="metodosPagoChart"></canvas>
    </div>

    {{-- Gráfico de Barras: Top Clientes --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h3 class="text-lg font-semibold text-slate-900 mb-4">
            👥 Top 10 Clientes
        </h3>
        <canvas id="topClientesChart"></canvas>
    </div>
</div>

@push('scripts')
<script>
    // Datos desde el controlador
    const serieTemporal = @json($serieTemporal);
    const ingresosPorMetodo = @json($ingresosPorMetodo);
    const ingresosPorCliente = @json($ingresosPorCliente);

    // 1. Gráfico de Línea: Evolución Temporal
    const ctxLinea = document.getElementById('ingresosDiariosChart').getContext('2d');
    new Chart(ctxLinea, {
        type: 'line',
        data: {
            labels: serieTemporal.map(s => s.label),
            datasets: [{
                label: 'Ingresos Diarios',
                data: serieTemporal.map(s => s.total),
                borderColor: '#0ea5e9',
                backgroundColor: 'rgba(14, 165, 233, 0.1)',
                tension: 0.4,
                fill: true,
                pointRadius: 4,
                pointBackgroundColor: '#0ea5e9'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return '$' + context.parsed.y.toLocaleString('es-MX', {minimumFractionDigits: 2});
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toLocaleString('es-MX');
                        }
                    }
                }
            }
        }
    });

    // 2. Gráfico de Pastel: Métodos de Pago
    const ctxPie = document.getElementById('metodosPagoChart').getContext('2d');
    new Chart(ctxPie, {
        type: 'doughnut',
        data: {
            labels: ingresosPorMetodo.map(m => m.metodo_pago ? m.metodo_pago.charAt(0).toUpperCase() + m.metodo_pago.slice(1) : 'Sin especificar'),
            datasets: [{
                data: ingresosPorMetodo.map(m => m.total),
                backgroundColor: [
                    '#10b981', // efectivo
                    '#3b82f6', // transferencia
                    '#f59e0b', // tarjeta
                    '#8b5cf6', // otro
                    '#ec4899'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            return `${label}: $${value.toLocaleString('es-MX', {minimumFractionDigits: 2})} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });

    // 3. Gráfico de Barras Horizontales: Top Clientes
    const ctxBar = document.getElementById('topClientesChart').getContext('2d');
    new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: ingresosPorCliente.map(c => c.cliente.nombre),
            datasets: [{
                label: 'Ingresos',
                data: ingresosPorCliente.map(c => c.total),
                backgroundColor: '#8b5cf6'
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return '$' + context.parsed.x.toLocaleString('es-MX', {minimumFractionDigits: 2});
                        }
                    }
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toLocaleString('es-MX');
                        }
                    }
                }
            }
        }
    });
</script>
@endpush
```

---

## 3️⃣ DASHBOARD EJECUTIVO

### 🎯 Crear Nuevo Controlador

**Archivo:** `app/Http/Controllers/DashboardController.php`

```php
<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Reparto;
use App\Models\Pago;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        Carbon::setLocale('es');
        
        // Fecha hoy
        $hoy = Carbon::today();
        $inicioMes = Carbon::now()->startOfMonth();
        $finMes = Carbon::now()->endOfMonth();
        
        // ===== MÉTRICAS DEL DÍA =====
        $ingresosHoy = Pago::whereDate('fecha_pago', $hoy)->sum('monto');
        $repartosHoy = Reparto::whereDate('fecha_programada', $hoy)->count();
        $repartosEntregadosHoy = Reparto::whereDate('fecha_programada', $hoy)
            ->where('estado', 'entregado')
            ->count();
        
        // ===== MÉTRICAS DEL MES =====
        $ingresosMes = Pago::whereBetween('fecha_pago', [$inicioMes, $finMes])->sum('monto');
        $repartosMes = Reparto::whereBetween('fecha_programada', [$inicioMes, $finMes])->count();
        $bidonesMes = Reparto::whereBetween('fecha_programada', [$inicioMes, $finMes])
            ->sum('cantidad');
        
        // ===== MÉTRICAS COMPARATIVAS (MES ANTERIOR) =====
        $mesAnteriorInicio = Carbon::now()->subMonth()->startOfMonth();
        $mesAnteriorFin = Carbon::now()->subMonth()->endOfMonth();
        
        $ingresosMesAnterior = Pago::whereBetween('fecha_pago', [$mesAnteriorInicio, $mesAnteriorFin])
            ->sum('monto');
        
        $crecimientoIngresos = $ingresosMesAnterior > 0 
            ? (($ingresosMes - $ingresosMesAnterior) / $ingresosMesAnterior) * 100 
            : 0;
        
        // ===== CUENTAS POR COBRAR =====
        $totalDeudaActiva = Cliente::where('activo', true)
            ->get()
            ->sum(function($cliente) {
                $ultimoMovimiento = $cliente->movimientosCuenta()->latest('created_at')->first();
                return $ultimoMovimiento ? max(0, $ultimoMovimiento->saldo_nuevo) : 0;
            });
        
        $clientesConDeuda = Cliente::where('activo', true)
            ->get()
            ->filter(function($cliente) {
                $ultimoMovimiento = $cliente->movimientosCuenta()->latest('created_at')->first();
                return $ultimoMovimiento && $ultimoMovimiento->saldo_nuevo > 0;
            })
            ->count();
        
        // ===== REPARTOS PENDIENTES =====
        $repartosPendientes = Reparto::where('estado', 'pendiente')
            ->whereDate('fecha_programada', '<=', Carbon::now())
            ->count();
        
        // ===== ÚLTIMOS 7 DÍAS: INGRESOS =====
        $ingresos7Dias = [];
        for ($i = 6; $i >= 0; $i--) {
            $fecha = Carbon::now()->subDays($i);
            $ingresos7Dias[] = [
                'fecha' => $fecha->format('d/m'),
                'monto' => Pago::whereDate('fecha_pago', $fecha)->sum('monto')
            ];
        }
        
        // ===== ÚLTIMOS 7 DÍAS: REPARTOS =====
        $repartos7Dias = [];
        for ($i = 6; $i >= 0; $i--) {
            $fecha = Carbon::now()->subDays($i);
            $repartos7Dias[] = [
                'fecha' => $fecha->format('d/m'),
                'cantidad' => Reparto::whereDate('fecha_programada', $fecha)->count()
            ];
        }
        
        // ===== TOP REPARTIDORES DEL MES =====
        $topRepartidores = Reparto::with('repartidor')
            ->whereBetween('fecha_programada', [$inicioMes, $finMes])
            ->where('estado', 'entregado')
            ->select('repartidor_id', DB::raw('COUNT(*) as total_repartos'), DB::raw('SUM(cantidad) as total_bidones'))
            ->groupBy('repartidor_id')
            ->orderByDesc('total_repartos')
            ->take(5)
            ->get();
        
        // ===== TOP CLIENTES DEL MES =====
        $topClientes = Pago::with('cliente')
            ->whereBetween('fecha_pago', [$inicioMes, $finMes])
            ->select('cliente_id', DB::raw('SUM(monto) as total'))
            ->groupBy('cliente_id')
            ->orderByDesc('total')
            ->take(5)
            ->get();
        
        // ===== ALERTAS =====
        $alertas = [];
        
        // Deuda crítica (>30 días)
        if ($clientesConDeuda > 10) {
            $alertas[] = [
                'tipo' => 'warning',
                'icono' => '⚠️',
                'mensaje' => "$clientesConDeuda clientes con deuda pendiente",
                'link' => route('reportes.cuentas-por-cobrar')
            ];
        }
        
        // Repartos pendientes
        if ($repartosPendientes > 0) {
            $alertas[] = [
                'tipo' => 'danger',
                'icono' => '🚨',
                'mensaje' => "$repartosPendientes repartos pendientes de entregar",
                'link' => route('repartos.index', ['estado' => 'pendiente'])
            ];
        }
        
        // Descenso de ingresos
        if ($crecimientoIngresos < -10) {
            $alertas[] = [
                'tipo' => 'danger',
                'icono' => '📉',
                'mensaje' => "Ingresos " . abs(round($crecimientoIngresos, 1)) . "% menores que el mes pasado",
                'link' => route('reportes.ingresos-por-periodo')
            ];
        }
        
        return view('dashboard', compact(
            'ingresosHoy',
            'repartosHoy',
            'repartosEntregadosHoy',
            'ingresosMes',
            'repartosMes',
            'bidonesMes',
            'crecimientoIngresos',
            'totalDeudaActiva',
            'clientesConDeuda',
            'repartosPendientes',
            'ingresos7Dias',
            'repartos7Dias',
            'topRepartidores',
            'topClientes',
            'alertas'
        ));
    }
}
```

### 🎨 Vista del Dashboard

**Archivo:** `resources/views/dashboard.blade.php`

```blade
@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">📊 Dashboard Ejecutivo</h1>
            <p class="text-slate-500 mt-1">Vista general del negocio</p>
        </div>
        <div class="text-right text-sm text-slate-500">
            <div>{{ now()->isoFormat('dddd, D [de] MMMM, YYYY') }}</div>
            <div class="font-semibold">{{ now()->format('H:i') }}</div>
        </div>
    </div>

    {{-- Alertas --}}
    @if(count($alertas) > 0)
    <div class="space-y-2">
        @foreach($alertas as $alerta)
        <a href="{{ $alerta['link'] }}" 
           class="block bg-{{ $alerta['tipo'] === 'danger' ? 'red' : 'amber' }}-50 border border-{{ $alerta['tipo'] === 'danger' ? 'red' : 'amber' }}-200 rounded-lg p-4 hover:bg-{{ $alerta['tipo'] === 'danger' ? 'red' : 'amber' }}-100 transition-colors">
            <div class="flex items-center">
                <span class="text-2xl mr-3">{{ $alerta['icono'] }}</span>
                <span class="text-{{ $alerta['tipo'] === 'danger' ? 'red' : 'amber' }}-800 font-medium">
                    {{ $alerta['mensaje'] }}
                </span>
                <svg class="w-5 h-5 ml-auto text-{{ $alerta['tipo'] === 'danger' ? 'red' : 'amber' }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </div>
        </a>
        @endforeach
    </div>
    @endif

    {{-- Métricas Principales (Cards) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        
        {{-- Ingresos Hoy --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm text-slate-500">Ingresos Hoy</span>
                <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                    <span class="text-xl">💰</span>
                </div>
            </div>
            <div class="text-3xl font-bold text-slate-900">${{ number_format($ingresosHoy, 2) }}</div>
            <div class="text-xs text-slate-500 mt-1">
                {{ $repartosEntregadosHoy }} entregas cobradas
            </div>
        </div>

        {{-- Repartos Hoy --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm text-slate-500">Repartos Hoy</span>
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <span class="text-xl">🚚</span>
                </div>
            </div>
            <div class="text-3xl font-bold text-slate-900">{{ $repartosHoy }}</div>
            <div class="text-xs text-slate-500 mt-1">
                {{ $repartosEntregadosHoy }} entregados
            </div>
        </div>

        {{-- Ingresos del Mes --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm text-slate-500">Ingresos del Mes</span>
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                    <span class="text-xl">📈</span>
                </div>
            </div>
            <div class="text-3xl font-bold text-slate-900">${{ number_format($ingresosMes, 0) }}</div>
            <div class="text-xs mt-1 {{ $crecimientoIngresos >= 0 ? 'text-emerald-600' : 'text-red-600' }}">
                {{ $crecimientoIngresos >= 0 ? '↗️' : '↘️' }} 
                {{ abs(round($crecimientoIngresos, 1)) }}% vs mes anterior
            </div>
        </div>

        {{-- Cuentas por Cobrar --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm text-slate-500">Por Cobrar</span>
                <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                    <span class="text-xl">⏰</span>
                </div>
            </div>
            <div class="text-3xl font-bold text-slate-900">${{ number_format($totalDeudaActiva, 0) }}</div>
            <div class="text-xs text-slate-500 mt-1">
                {{ $clientesConDeuda }} clientes
            </div>
        </div>

    </div>

    {{-- Gráficos --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        {{-- Ingresos Últimos 7 Días --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-lg font-semibold text-slate-900 mb-4">💵 Ingresos Últimos 7 Días</h3>
            <canvas id="ingresos7DiasChart" height="150"></canvas>
        </div>

        {{-- Repartos Últimos 7 Días --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-lg font-semibold text-slate-900 mb-4">🚚 Repartos Últimos 7 Días</h3>
            <canvas id="repartos7DiasChart" height="150"></canvas>
        </div>

    </div>

    {{-- Rankings --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        {{-- Top Repartidores --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-lg font-semibold text-slate-900 mb-4">🏆 Top Repartidores del Mes</h3>
            <div class="space-y-3">
                @foreach($topRepartidores as $index => $repartidor)
                <div class="flex items-center">
                    <div class="w-8 h-8 rounded-full {{ $index === 0 ? 'bg-yellow-100 text-yellow-700' : 'bg-slate-100 text-slate-600' }} flex items-center justify-center font-bold text-sm">
                        {{ $index + 1 }}
                    </div>
                    <div class="ml-3 flex-1">
                        <div class="font-medium text-slate-900">{{ $repartidor->repartidor->name ?? 'N/A' }}</div>
                        <div class="text-xs text-slate-500">{{ $repartidor->total_repartos }} repartos • {{ $repartidor->total_bidones }} bidones</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Top Clientes --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-lg font-semibold text-slate-900 mb-4">👥 Top Clientes del Mes</h3>
            <div class="space-y-3">
                @foreach($topClientes as $index => $cliente)
                <div class="flex items-center">
                    <div class="w-8 h-8 rounded-full {{ $index === 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }} flex items-center justify-center font-bold text-sm">
                        {{ $index + 1 }}
                    </div>
                    <div class="ml-3 flex-1">
                        <div class="font-medium text-slate-900">{{ $cliente->cliente->nombre ?? 'N/A' }}</div>
                        <div class="text-xs text-emerald-600 font-semibold">${{ number_format($cliente->total, 2) }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

    </div>

    {{-- Accesos Rápidos --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h3 class="text-lg font-semibold text-slate-900 mb-4">⚡ Accesos Rápidos</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('repartos.create') }}" class="flex flex-col items-center p-4 border-2 border-dashed border-slate-200 rounded-lg hover:border-blue-400 hover:bg-blue-50 transition-all">
                <span class="text-3xl mb-2">➕</span>
                <span class="text-sm font-medium text-slate-700">Nuevo Reparto</span>
            </a>
            <a href="{{ route('pagos.create') }}" class="flex flex-col items-center p-4 border-2 border-dashed border-slate-200 rounded-lg hover:border-emerald-400 hover:bg-emerald-50 transition-all">
                <span class="text-3xl mb-2">💵</span>
                <span class="text-sm font-medium text-slate-700">Registrar Pago</span>
            </a>
            <a href="{{ route('reportes.cuentas-por-cobrar') }}" class="flex flex-col items-center p-4 border-2 border-dashed border-slate-200 rounded-lg hover:border-amber-400 hover:bg-amber-50 transition-all">
                <span class="text-3xl mb-2">📋</span>
                <span class="text-sm font-medium text-slate-700">Cuentas x Cobrar</span>
            </a>
            <a href="{{ route('reportes.index') }}" class="flex flex-col items-center p-4 border-2 border-dashed border-slate-200 rounded-lg hover:border-purple-400 hover:bg-purple-50 transition-all">
                <span class="text-3xl mb-2">📊</span>
                <span class="text-sm font-medium text-slate-700">Ver Reportes</span>
            </a>
        </div>
    </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // Datos
    const ingresos7Dias = @json($ingresos7Dias);
    const repartos7Dias = @json($repartos7Dias);

    // Gráfico de Ingresos
    const ctxIngresos = document.getElementById('ingresos7DiasChart').getContext('2d');
    new Chart(ctxIngresos, {
        type: 'line',
        data: {
            labels: ingresos7Dias.map(d => d.fecha),
            datasets: [{
                label: 'Ingresos',
                data: ingresos7Dias.map(d => d.monto),
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: value => '$' + value.toLocaleString()
                    }
                }
            }
        }
    });

    // Gráfico de Repartos
    const ctxRepartos = document.getElementById('repartos7DiasChart').getContext('2d');
    new Chart(ctxRepartos, {
        type: 'bar',
        data: {
            labels: repartos7Dias.map(d => d.fecha),
            datasets: [{
                label: 'Repartos',
                data: repartos7Dias.map(d => d.cantidad),
                backgroundColor: '#3b82f6'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
</script>
@endpush

@endsection
```

### 🛣️ Actualizar Ruta

**Archivo:** `routes/web.php`

```php
use App\Http\Controllers\DashboardController;

Route::middleware(['auth'])->group(function () {
    // Ruta del dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // ... resto de rutas
});
```

---

## 4️⃣ MEJORA: CUENTAS POR COBRAR CON ANTIGÜEDAD

### 📝 Actualizar Método en el Controlador

**Archivo:** `app/Http/Controllers/ReporteController.php`

```php
public function cuentasPorCobrar(Request $request)
{
    $ordenar = $request->get('ordenar', 'saldo_desc');
    $filtroDeuda = $request->get('filtro_deuda', 'con_deuda');

    // Obtener clientes con cálculos de antigüedad
    $clientes = Cliente::where('activo', true)
        ->with(['movimientosCuenta' => function($query) {
            $query->latest('created_at')->take(1);
        }])
        ->get()
        ->map(function($cliente) {
            // Saldo actual
            $ultimoMovimiento = $cliente->movimientosCuenta->first();
            $cliente->saldo_calculado = $ultimoMovimiento ? $ultimoMovimiento->saldo_nuevo : 0;
            
            // Calcular antigüedad de la deuda (fecha del movimiento más antiguo con saldo positivo)
            if ($cliente->saldo_calculado > 0) {
                $primerDebitoImpago = $cliente->movimientosCuenta()
                    ->where('tipo', 'debito')
                    ->where('saldo_nuevo', '>', 0)
                    ->orderBy('fecha', 'asc')
                    ->first();
                    
                if ($primerDebitoImpago) {
                    $fechaDeuda = \Carbon\Carbon::parse($primerDebitoImpago->fecha);
                    $cliente->dias_morosidad = $fechaDeuda->diffInDays(now());
                    $cliente->fecha_primera_deuda = $fechaDeuda;
                } else {
                    $cliente->dias_morosidad = 0;
                    $cliente->fecha_primera_deuda = null;
                }
                
                // Clasificar riesgo
                if ($cliente->dias_morosidad > 90) {
                    $cliente->nivel_riesgo = 'crítico';
                    $cliente->color_riesgo = 'red';
                } elseif ($cliente->dias_morosidad > 60) {
                    $cliente->nivel_riesgo = 'alto';
                    $cliente->color_riesgo = 'orange';
                } elseif ($cliente->dias_morosidad > 30) {
                    $cliente->nivel_riesgo = 'medio';
                    $cliente->color_riesgo = 'yellow';
                } else {
                    $cliente->nivel_riesgo = 'bajo';
                    $cliente->color_riesgo = 'green';
                }
            } else {
                $cliente->dias_morosidad = 0;
                $cliente->fecha_primera_deuda = null;
                $cliente->nivel_riesgo = 'sin_deuda';
                $cliente->color_riesgo = 'gray';
            }
            
            return $cliente;
        });

    // Filtrar
    if ($filtroDeuda === 'con_deuda') {
        $clientes = $clientes->filter(fn($c) => $c->saldo_calculado > 0);
    } elseif ($filtroDeuda === 'al_dia') {
        $clientes = $clientes->filter(fn($c) => $c->saldo_calculado <= 0);
    }

    // Ordenar
    if ($ordenar === 'saldo_desc') {
        $clientes = $clientes->sortByDesc('saldo_calculado');
    } elseif ($ordenar === 'saldo_asc') {
        $clientes = $clientes->sortBy('saldo_calculado');
    } elseif ($ordenar === 'morosidad_desc') {
        $clientes = $clientes->sortByDesc('dias_morosidad');
    } elseif ($ordenar === 'nombre') {
        $clientes = $clientes->sortBy('nombre');
    }

    // Estadísticas
    $totalDeuda = $clientes->where('saldo_calculado', '>', 0)->sum('saldo_calculado');
    $clientesConDeuda = $clientes->where('saldo_calculado', '>', 0)->count();
    $clientesAlDia = $clientes->where('saldo_calculado', '<=', 0)->count();
    
    // Estadísticas de antigüedad
    $deuda0_30 = $clientes->filter(fn($c) => $c->dias_morosidad > 0 && $c->dias_morosidad <= 30)->sum('saldo_calculado');
    $deuda31_60 = $clientes->filter(fn($c) => $c->dias_morosidad > 30 && $c->dias_morosidad <= 60)->sum('saldo_calculado');
    $deuda61_90 = $clientes->filter(fn($c) => $c->dias_morosidad > 60 && $c->dias_morosidad <= 90)->sum('saldo_calculado');
    $deudaMas90 = $clientes->filter(fn($c) => $c->dias_morosidad > 90)->sum('saldo_calculado');

    return view('reportes.cuentas-por-cobrar', compact(
        'clientes',
        'totalDeuda',
        'clientesConDeuda',
        'clientesAlDia',
        'ordenar',
        'filtroDeuda',
        'deuda0_30',
        'deuda31_60',
        'deuda61_90',
        'deudaMas90'
    ));
}
```

### 🎨 Actualizar Vista

**Archivo:** `resources/views/reportes/cuentas-por-cobrar.blade.php`

```blade
{{-- Agregar estadísticas de envejecimiento después de las tarjetas existentes --}}

<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-6">
    <h3 class="text-lg font-semibold text-slate-900 mb-4">📅 Envejecimiento de Cartera</h3>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="border-l-4 border-emerald-500 pl-4">
            <div class="text-sm text-slate-500">0-30 días</div>
            <div class="text-2xl font-bold text-slate-900">${{ number_format($deuda0_30, 2) }}</div>
            <div class="text-xs text-emerald-600">✅ Reciente</div>
        </div>
        <div class="border-l-4 border-yellow-500 pl-4">
            <div class="text-sm text-slate-500">31-60 días</div>
            <div class="text-2xl font-bold text-slate-900">${{ number_format($deuda31_60, 2) }}</div>
            <div class="text-xs text-yellow-600">⚠️ Atención</div>
        </div>
        <div class="border-l-4 border-orange-500 pl-4">
            <div class="text-sm text-slate-500">61-90 días</div>
            <div class="text-2xl font-bold text-slate-900">${{ number_format($deuda61_90, 2) }}</div>
            <div class="text-xs text-orange-600">🔶 Urgente</div>
        </div>
        <div class="border-l-4 border-red-500 pl-4">
            <div class="text-sm text-slate-500">+90 días</div>
            <div class="text-2xl font-bold text-slate-900">${{ number_format($deudaMas90, 2) }}</div>
            <div class="text-xs text-red-600">🚨 Crítico</div>
        </div>
    </div>
</div>

{{-- Agregar opción de ordenamiento por morosidad en el formulario --}}
<select name="ordenar" class="...">
    <option value="saldo_desc" {{ $ordenar == 'saldo_desc' ? 'selected' : '' }}>Mayor deuda</option>
    <option value="morosidad_desc" {{ $ordenar == 'morosidad_desc' ? 'selected' : '' }}>Mayor antigüedad</option>
    <option value="saldo_asc" {{ $ordenar == 'saldo_asc' ? 'selected' : '' }}>Menor deuda</option>
    <option value="nombre" {{ $ordenar == 'nombre' ? 'selected' : '' }}>Nombre A-Z</option>
</select>

{{-- En la tabla, agregar columnas --}}
<thead>
    <tr>
        <th>Cliente</th>
        <th>Saldo</th>
        <th>Días Morosidad</th> {{-- NUEVA --}}
        <th>Riesgo</th> {{-- NUEVA --}}
        <th>Acciones</th>
    </tr>
</thead>
<tbody>
    @foreach($clientes as $cliente)
    <tr>
        <td>{{ $cliente->nombre }}</td>
        <td>${{ number_format($cliente->saldo_calculado, 2) }}</td>
        <td>
            @if($cliente->dias_morosidad > 0)
                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-{{ $cliente->color_riesgo }}-100 text-{{ $cliente->color_riesgo }}-700">
                    {{ $cliente->dias_morosidad }} días
                </span>
            @else
                <span class="text-slate-400">-</span>
            @endif
        </td>
        <td>
            @if($cliente->nivel_riesgo !== 'sin_deuda')
                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-{{ $cliente->color_riesgo }}-100 text-{{ $cliente->color_riesgo }}-700">
                    {{ ucfirst($cliente->nivel_riesgo) }}
                </span>
            @else
                <span class="text-slate-400">-</span>
            @endif
        </td>
        <td>
            {{-- Acciones --}}
        </td>
    </tr>
    @endforeach
</tbody>
```

---

## 🎉 CONCLUSIÓN

Con estos 4 cambios implementados tendrás:

1. ✅ **Exportación PDF/Excel** en todos los reportes
2. ✅ **Gráficos visuales** con Chart.js
3. ✅ **Dashboard Ejecutivo** con KPIs en tiempo real
4. ✅ **Antigüedad de deuda** y clasificación de riesgo

**Tiempo estimado de implementación:** 20-30 horas

---

**Próximos pasos sugeridos:**
- Implementar exportación en los demás reportes (3-4 horas por reporte)
- Crear reporte de Rendimiento de Repartidores (15-20 horas)
- Optimizar consultas con caché (5-10 horas)

