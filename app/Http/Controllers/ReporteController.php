<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Reparto;
use App\Models\Pago;
use App\Models\MovimientoCuenta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReporteController extends Controller
{
    /**
     * Muestra el índice de reportes
     */
    public function index()
    {
        return view('reportes.index');
    }

    /**
     * Reporte de Bidones Cobrados
     */
    public function bidonesCobrados(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio', now()->startOfMonth()->format('Y-m-d'));
        $fechaFin = $request->get('fecha_fin', now()->format('Y-m-d'));
        $productoId = $request->get('producto_id');
        $clienteId = $request->get('cliente_id');
        $repartidorId = $request->get('repartidor_id');
        $metodoPago = $request->get('metodo_pago');

        // Query base: Pagos que están asociados a repartos
        $query = Pago::with('cliente')
            ->where('referencia', 'like', 'Reparto #%')
            ->whereBetween('fecha_pago', [$fechaInicio . ' 00:00:00', $fechaFin . ' 23:59:59']);

        // Aplicar filtros
        if ($clienteId) {
            $query->where('cliente_id', $clienteId);
        }
        if ($metodoPago) {
            $query->where('metodo_pago', $metodoPago);
        }

        $pagos = $query->get();

        // Extraer IDs de repartos y cargarlos
        $repartoIds = [];
        foreach ($pagos as $pago) {
            if (preg_match('/Reparto #(\d+)/', $pago->referencia, $matches)) {
                $repartoIds[] = (int)$matches[1];
            }
        }

        // Cargar todos los repartos de una vez
        $repartos = Reparto::with(['producto', 'repartidor'])
            ->whereIn('id', $repartoIds)
            ->get()
            ->keyBy('id');

        // Calcular estadísticas
        $totalBidones = 0;
        $totalMonto = 0;
        $bidonesDetalle = [];
        $agrupadoPorProducto = [];
        $agrupadoPorCliente = [];
        $agrupadoPorMetodo = [];

        foreach ($pagos as $pago) {
            // Extraer ID del reparto desde la referencia
            if (!preg_match('/Reparto #(\d+)/', $pago->referencia, $matches)) {
                continue;
            }
            
            $repartoId = (int)$matches[1];
            $reparto = $repartos->get($repartoId);
            
            if (!$reparto) {
                continue;
            }

            // Aplicar filtros adicionales
            if ($productoId && $reparto->producto_id != $productoId) continue;
            if ($repartidorId && $reparto->repartidor_id != $repartidorId) continue;

            $cantidad = $reparto->cantidad ?? 0;
            $totalBidones += $cantidad;
            $totalMonto += $pago->monto;

            // Detalle
            $bidonesDetalle[] = [
                'fecha' => $pago->fecha_pago,
                'cliente' => $pago->cliente->nombre . ' ' . ($pago->cliente->apellido ?? ''),
                'producto' => $reparto->producto->nombre ?? 'N/A',
                'cantidad' => $cantidad,
                'monto' => $pago->monto,
                'metodo_pago' => $pago->metodo_pago,
                'repartidor' => $reparto->repartidor->name ?? 'N/A',
            ];

            // Agrupado por producto
            $productoNombre = $reparto->producto->nombre ?? 'Sin producto';
            if (!isset($agrupadoPorProducto[$productoNombre])) {
                $agrupadoPorProducto[$productoNombre] = ['cantidad' => 0, 'monto' => 0];
            }
            $agrupadoPorProducto[$productoNombre]['cantidad'] += $cantidad;
            $agrupadoPorProducto[$productoNombre]['monto'] += $pago->monto;

            // Agrupado por cliente
            $clienteNombre = $pago->cliente->nombre . ' ' . ($pago->cliente->apellido ?? '');
            if (!isset($agrupadoPorCliente[$clienteNombre])) {
                $agrupadoPorCliente[$clienteNombre] = ['cantidad' => 0, 'monto' => 0];
            }
            $agrupadoPorCliente[$clienteNombre]['cantidad'] += $cantidad;
            $agrupadoPorCliente[$clienteNombre]['monto'] += $pago->monto;

            // Agrupado por método de pago
            $metodo = $pago->metodo_pago ?? 'Sin especificar';
            if (!isset($agrupadoPorMetodo[$metodo])) {
                $agrupadoPorMetodo[$metodo] = ['cantidad' => 0, 'monto' => 0];
            }
            $agrupadoPorMetodo[$metodo]['cantidad'] += $cantidad;
            $agrupadoPorMetodo[$metodo]['monto'] += $pago->monto;
        }

        // Calcular bidones entregados en el mismo período
        $bidonesEntregados = Reparto::whereBetween('fecha_programada', [$fechaInicio, $fechaFin])
            ->where('estado', 'entregado')
            ->sum('cantidad');

        $tasaCobro = $bidonesEntregados > 0 ? ($totalBidones / $bidonesEntregados) * 100 : 0;

        // Ordenar agrupaciones
        arsort($agrupadoPorProducto);
        arsort($agrupadoPorCliente);

        return view('reportes.bidones-cobrados', compact(
            'fechaInicio',
            'fechaFin',
            'totalBidones',
            'totalMonto',
            'bidonesDetalle',
            'agrupadoPorProducto',
            'agrupadoPorCliente',
            'agrupadoPorMetodo',
            'bidonesEntregados',
            'tasaCobro',
            'productoId',
            'clienteId',
            'repartidorId',
            'metodoPago'
        ));
    }

    /**
     * Reporte de Ingresos por Período
     */
    public function ingresosPorPeriodo(Request $request)
    {
        Carbon::setLocale('es');
        
        $fechaInicio = $request->get('fecha_inicio', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $fechaFin = $request->get('fecha_fin', Carbon::now()->format('Y-m-d'));
        $agruparPor = $request->get('agrupar_por', 'dia'); // dia, semana, mes

        // Ingresos totales
        $ingresosTotales = Pago::whereBetween('fecha_pago', [$fechaInicio . ' 00:00:00', $fechaFin . ' 23:59:59'])->sum('monto');

        // Ingresos por método de pago
        $ingresosPorMetodo = Pago::whereBetween('fecha_pago', [$fechaInicio . ' 00:00:00', $fechaFin . ' 23:59:59'])
            ->select('metodo_pago', DB::raw('SUM(monto) as total'), DB::raw('COUNT(*) as cantidad'))
            ->groupBy('metodo_pago')
            ->get();

        // Ingresos por cliente
        $ingresosPorCliente = Pago::with('cliente')
            ->whereBetween('fecha_pago', [$fechaInicio . ' 00:00:00', $fechaFin . ' 23:59:59'])
            ->select('cliente_id', DB::raw('SUM(monto) as total'), DB::raw('COUNT(*) as cantidad'))
            ->groupBy('cliente_id')
            ->orderByDesc('total')
            ->take(10)
            ->get();

        // Serie temporal de ingresos - solo mostrar fechas con datos
        $serieTemporal = [];
        $dias = Carbon::parse($fechaInicio)->diffInDays(Carbon::parse($fechaFin)) + 1;

        if ($agruparPor === 'dia' && $dias <= 31) {
            $ingresos = Pago::whereBetween('fecha_pago', [$fechaInicio . ' 00:00:00', $fechaFin . ' 23:59:59'])
                ->select(
                    DB::raw('DATE(fecha_pago) as fecha'), 
                    DB::raw('SUM(monto) as total'),
                    DB::raw('COUNT(*) as cantidad')
                )
                ->groupBy(DB::raw('DATE(fecha_pago)'))
                ->orderBy(DB::raw('DATE(fecha_pago)'))
                ->get();

            // Solo mostrar fechas con datos (no días vacíos)
            foreach ($ingresos as $ingreso) {
                $fecha = Carbon::parse($ingreso->fecha);
                $porcentaje = $ingresosTotales > 0 ? ($ingreso->total / $ingresosTotales * 100) : 0;
                $promedio = $ingreso->cantidad > 0 ? ($ingreso->total / $ingreso->cantidad) : 0;
                
                $serieTemporal[] = [
                    'fecha' => $ingreso->fecha,
                    'fecha_completa' => $fecha->isoFormat('dddd D [de] MMMM, YYYY'),
                    'label' => $fecha->format('d/m'),
                    'total' => $ingreso->total,
                    'cantidad' => $ingreso->cantidad,
                    'porcentaje' => $porcentaje,
                    'promedio' => $promedio,
                ];
            }
        }

        return view('reportes.ingresos-por-periodo', compact(
            'fechaInicio',
            'fechaFin',
            'ingresosTotales',
            'ingresosPorMetodo',
            'ingresosPorCliente',
            'serieTemporal',
            'agruparPor'
        ));
    }

    /**
     * Estado de Cuenta por Cliente
     */
    public function estadoCuentaCliente(Request $request, $clienteId = null)
    {
        $clienteId = $clienteId ?? $request->get('cliente_id');
        
        if (!$clienteId) {
            return view('reportes.estado-cuenta-seleccionar');
        }

        $cliente = Cliente::with(['producto'])->findOrFail($clienteId);
        
        $fechaInicio = $request->get('fecha_inicio', now()->startOfMonth()->format('Y-m-d'));
        $fechaFin = $request->get('fecha_fin', now()->format('Y-m-d'));

        // Movimientos de cuenta
        $movimientos = MovimientoCuenta::where('cliente_id', $clienteId)
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->with('referencia')
            ->orderBy('fecha')
            ->orderBy('id')
            ->get();

        // Estadísticas
        $totalDebitos = $movimientos->where('tipo', 'debito')->sum('monto');
        $totalCreditos = $movimientos->where('tipo', 'credito')->sum('monto');
        $saldoActual = $cliente->saldo_actual;

        return view('reportes.estado-cuenta-cliente', compact(
            'cliente',
            'movimientos',
            'totalDebitos',
            'totalCreditos',
            'saldoActual',
            'fechaInicio',
            'fechaFin'
        ));
    }

    /**
     * Reporte de Cuentas por Cobrar
     */
    public function cuentasPorCobrar(Request $request)
    {
        $ordenar = $request->get('ordenar', 'saldo_desc'); // saldo_desc, saldo_asc, nombre
        $filtroDeuda = $request->get('filtro_deuda', 'con_deuda'); // todas, con_deuda, al_dia

        // Obtener clientes con sus saldos actuales
        $clientes = Cliente::where('activo', true)
        ->get()
        ->map(function($cliente) {
            // Obtener el saldo del último movimiento de cuenta
            $ultimoMovimiento = $cliente->movimientosCuenta()
                ->latest('created_at')
                ->first();
            
            $cliente->saldo_calculado = $ultimoMovimiento ? $ultimoMovimiento->saldo_nuevo : 0;
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
        } elseif ($ordenar === 'nombre') {
            $clientes = $clientes->sortBy('nombre');
        }

        // Estadísticas
        $totalDeuda = $clientes->where('saldo_calculado', '>', 0)->sum('saldo_calculado');
        $clientesConDeuda = $clientes->where('saldo_calculado', '>', 0)->count();
        $clientesAlDia = $clientes->where('saldo_calculado', '<=', 0)->count();

        return view('reportes.cuentas-por-cobrar', compact(
            'clientes',
            'totalDeuda',
            'clientesConDeuda',
            'clientesAlDia',
            'ordenar',
            'filtroDeuda'
        ));
    }

    /**
     * Reporte de Repartos por Período
     */
    public function repartosPorPeriodo(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio', now()->startOfMonth()->format('Y-m-d'));
        $fechaFin = $request->get('fecha_fin', now()->format('Y-m-d'));
        $estado = $request->get('estado');
        $repartidorId = $request->get('repartidor_id');
        $productoId = $request->get('producto_id');

        // Query base
        $query = Reparto::with(['cliente', 'repartidor', 'producto'])
            ->whereBetween('fecha_programada', [$fechaInicio, $fechaFin]);

        // Filtros
        if ($estado) {
            $query->where('estado', $estado);
        }
        if ($repartidorId) {
            $query->where('repartidor_id', $repartidorId);
        }
        if ($productoId) {
            $query->where('producto_id', $productoId);
        }

        $repartos = $query->orderBy('fecha_programada', 'desc')->get();

        // Estadísticas
        $totalRepartos = $repartos->count();
        $totalBidones = $repartos->sum('cantidad');
        $totalValor = $repartos->sum('total');
        
        $repartosPorEstado = $repartos->groupBy('estado')->map->count();
        $repartosPorRepartidor = $repartos->groupBy('repartidor.name')->map->count()->sortDesc();
        $repartosPorProducto = $repartos->groupBy('producto.nombre')->map->sum('cantidad')->sortDesc();

        return view('reportes.repartos-por-periodo', compact(
            'fechaInicio',
            'fechaFin',
            'repartos',
            'totalRepartos',
            'totalBidones',
            'totalValor',
            'repartosPorEstado',
            'repartosPorRepartidor',
            'repartosPorProducto',
            'estado',
            'repartidorId',
            'productoId'
        ));
    }

    /**
     * Reporte de Análisis Geográfico
     */
    public function analisisGeografico(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $fechaFin = $request->get('fecha_fin', Carbon::now()->format('Y-m-d'));

        // Obtener repartos con ubicación del cliente
        $repartos = Reparto::with(['cliente', 'producto', 'repartidor'])
            ->whereBetween('fecha', [$fechaInicio . ' 00:00:00', $fechaFin . ' 23:59:59'])
            ->whereHas('cliente', function($query) {
                $query->whereNotNull('latitude')
                      ->whereNotNull('longitude');
            })
            ->get();

        // Agrupar por zona (usando colonia como barrio/vecindario específico)
        $repartosPorZona = Reparto::with('cliente')
            ->whereBetween('fecha', [$fechaInicio . ' 00:00:00', $fechaFin . ' 23:59:59'])
            ->whereHas('cliente', function($query) {
                $query->whereNotNull('colonia');
            })
            ->get()
            ->groupBy(fn($r) => $r->cliente->colonia ?? 'Sin zona')
            ->map(function($grupo) {
                return [
                    'repartos' => $grupo->count(),
                    'bidones' => $grupo->sum('cantidad'),
                    'total' => $grupo->sum('total'),
                ];
            })
            ->sortByDesc('bidones');

        // Datos para el mapa de calor (basado en consumo de bidones)
        $heatmapData = $repartos
            ->filter(fn($r) => $r->cliente && $r->cliente->latitude && $r->cliente->longitude)
            ->groupBy('cliente_id')
            ->map(function($grupo) {
                $cliente = $grupo->first()->cliente;
                $totalBidones = $grupo->sum('cantidad');
                $totalRepartos = $grupo->count();
                
                return [
                    'lat' => (float) $cliente->latitude,
                    'lng' => (float) $cliente->longitude,
                    'bidones' => $totalBidones,
                    'repartos' => $totalRepartos,
                    'nombre' => $cliente->nombre,
                    'direccion' => $cliente->direccion,
                    'colonia' => $cliente->colonia,
                    'total' => $grupo->sum('total'),
                ];
            })
            ->values();

        // Estadísticas generales
        $totalBidones = $repartos->sum('cantidad');
        $totalRepartos = $repartos->count();
        $zonasActivas = $repartosPorZona->count();
        $clientesConUbicacion = Cliente::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->where('activo', true)
            ->count();

        return view('reportes.analisis-geografico', compact(
            'fechaInicio',
            'fechaFin',
            'repartos',
            'repartosPorZona',
            'heatmapData',
            'totalBidones',
            'totalRepartos',
            'zonasActivas',
            'clientesConUbicacion'
        ));
    }
}
