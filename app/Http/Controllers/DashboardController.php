<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Reparto;
use App\Models\Producto;
use App\Models\Pago;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        if ($user->role === 'repartidor') {
            return $this->dashboardRepartidor($user);
        }
        
        return $this->dashboardAdministrativo();
    }
    
    private function dashboardAdministrativo()
    {
        // Estadísticas básicas
        $stats = [
            'total_clientes' => Cliente::where('activo', true)->count(),
            'total_productos' => Producto::where('activo', true)->count(),
            'total_repartidores' => User::where('role', 'repartidor')->count(),
            'repartos_pendientes' => Reparto::where('estado', 'pendiente')->count(),
            'repartos_hoy' => Reparto::whereDate('fecha_programada', today())->count(),
        ];
        
        // Contadores adicionales
        $repartosHoy = Reparto::whereDate('fecha_programada', today())->count();
        $repartosPendientes = Reparto::where('estado', 'pendiente')->count();
        
        // Clientes con deuda - cuenta clientes donde suma de débitos > créditos
        $clientesDeuda = DB::table('clientes')
            ->join('movimientos_cuenta', 'clientes.id', '=', 'movimientos_cuenta.cliente_id')
            ->select('clientes.id')
            ->groupBy('clientes.id')
            ->havingRaw('SUM(CASE WHEN movimientos_cuenta.tipo = "debito" THEN movimientos_cuenta.monto ELSE -movimientos_cuenta.monto END) > 0')
            ->count();
        
        // Ingresos del mes
        $ingresosMes = Pago::whereMonth('fecha_pago', now()->month)
            ->whereYear('fecha_pago', now()->year)
            ->sum('monto');
        
        // Repartos recientes de hoy
        $repartosRecientes = Reparto::with(['cliente', 'producto', 'repartidor'])
            ->whereDate('fecha_programada', today())
            ->latest()
            ->take(5)
            ->get();
        
        // Top 5 clientes del mes
        $topClientes = Cliente::select('clientes.*', DB::raw('COUNT(repartos.id) as total_bidones'))
            ->join('repartos', 'clientes.id', '=', 'repartos.cliente_id')
            ->whereMonth('repartos.created_at', now()->month)
            ->whereYear('repartos.created_at', now()->year)
            ->groupBy('clientes.id')
            ->orderByDesc('total_bidones')
            ->take(5)
            ->get();
        
        // Repartidores activos con sus stats
        $repartidoresActivos = User::where('role', 'repartidor')
            ->with(['repartos' => function($query) {
                $query->whereDate('fecha_programada', today());
            }])
            ->get()
            ->map(function($repartidor) {
                $repartosHoy = $repartidor->repartos;
                return (object)[
                    'name' => $repartidor->name,
                    'repartos_asignados' => $repartosHoy->count(),
                    'repartos_completados' => $repartosHoy->where('estado', 'entregado')->count(),
                ];
            })
            ->filter(function($repartidor) {
                return $repartidor->repartos_asignados > 0;
            });
        
        // Actividad reciente
        $actividadReciente = [];
        
        // Últimos pagos
        $ultimosPagos = Pago::with('cliente')->latest()->take(2)->get();
        foreach($ultimosPagos as $pago) {
            $actividadReciente[] = [
                'title' => 'Pago registrado',
                'description' => $pago->cliente->nombre . ' pagó $' . number_format($pago->monto, 2),
                'time' => $pago->created_at->diffForHumans(),
                'color' => 'bg-emerald-100',
                'icon' => '<svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
            ];
        }
        
        // Últimos repartos entregados
        $ultimosRepartos = Reparto::with('cliente')->where('estado', 'entregado')->latest('entregado_at')->take(2)->get();
        foreach($ultimosRepartos as $reparto) {
            $actividadReciente[] = [
                'title' => 'Reparto entregado',
                'description' => 'Entrega a ' . $reparto->cliente->nombre,
                'time' => optional($reparto->entregado_at)->diffForHumans() ?? $reparto->updated_at->diffForHumans(),
                'color' => 'bg-sky-100',
                'icon' => '<svg class="w-5 h-5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>',
            ];
        }
        
        // Mezclar y ordenar por más reciente
        usort($actividadReciente, function($a, $b) {
            return strcmp($b['time'], $a['time']);
        });
        $actividadReciente = array_slice($actividadReciente, 0, 5);
        
        return view('dashboard', compact(
            'stats',
            'repartosHoy',
            'repartosPendientes',
            'clientesDeuda',
            'ingresosMes',
            'repartosRecientes',
            'topClientes',
            'repartidoresActivos',
            'actividadReciente'
        ));
    }
    
    private function dashboardRepartidor($user)
    {
        $stats = [
            'repartos_pendientes' => Reparto::where('repartidor_id', $user->id)
                ->where('estado', 'pendiente')
                ->count(),
            'repartos_hoy' => Reparto::where('repartidor_id', $user->id)
                ->whereDate('fecha_programada', today())
                ->count(),
            'repartos_entregados_hoy' => Reparto::where('repartidor_id', $user->id)
                ->where('estado', 'entregado')
                ->whereDate('entregado_at', today())
                ->count(),
        ];
        
        $repartos_pendientes = Reparto::with(['cliente', 'producto'])
            ->where('repartidor_id', $user->id)
            ->where('estado', 'pendiente')
            ->orderBy('fecha_programada')
            ->get();
        
        $repartos_hoy = Reparto::with(['cliente', 'producto'])
            ->where('repartidor_id', $user->id)
            ->whereDate('fecha_programada', today())
            ->get();
        
        // Variables para la barra de progreso
        $total = $stats['repartos_hoy'];
        $completados = $stats['repartos_entregados_hoy'];
        $repartos = $repartos_hoy;
        
        return view('dashboard', compact('stats', 'repartos_pendientes', 'repartos_hoy', 'total', 'completados', 'repartos'));
    }
}
