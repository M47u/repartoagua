<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Reparto;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Support\Facades\DB;

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
        $stats = [
            'total_clientes' => Cliente::where('activo', true)->count(),
            'total_productos' => Producto::where('activo', true)->count(),
            'total_repartidores' => User::where('role', 'repartidor')->count(),
            'repartos_pendientes' => Reparto::where('estado', 'pendiente')->count(),
            'repartos_hoy' => Reparto::whereDate('fecha', today())->count(),
            'total_deuda' => 0,
        ];
        
        $repartos_hoy = Reparto::with(['cliente', 'producto', 'repartidor'])
            ->whereDate('fecha', today())
            ->latest()
            ->get();
        
        $clientes_con_deuda = [];
        
        return view('dashboard', compact('stats', 'repartos_hoy', 'clientes_con_deuda'));
    }
    
    private function dashboardRepartidor($user)
    {
        $stats = [
            'repartos_pendientes' => Reparto::where('repartidor_id', $user->id)
                ->where('estado', 'pendiente')
                ->count(),
            'repartos_hoy' => Reparto::where('repartidor_id', $user->id)
                ->whereDate('fecha', today())
                ->count(),
            'repartos_entregados_hoy' => Reparto::where('repartidor_id', $user->id)
                ->where('estado', 'entregado')
                ->whereDate('entregado_at', today())
                ->count(),
        ];
        
        $repartos_pendientes = Reparto::with(['cliente', 'producto'])
            ->where('repartidor_id', $user->id)
            ->where('estado', 'pendiente')
            ->orderBy('fecha')
            ->get();
        
        $repartos_hoy = Reparto::with(['cliente', 'producto'])
            ->where('repartidor_id', $user->id)
            ->whereDate('fecha', today())
            ->get();
        
        // Variables para la barra de progreso
        $total = $stats['repartos_hoy'];
        $completados = $stats['repartos_entregados_hoy'];
        $repartos = $repartos_hoy;
        
        return view('dashboard', compact('stats', 'repartos_pendientes', 'repartos_hoy', 'total', 'completados', 'repartos'));
    }
}
