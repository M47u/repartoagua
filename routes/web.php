<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\RepartoController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\VehiculoController;
use App\Http\Controllers\ReporteController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Clientes - Solo admin y administrativo
    Route::resource('clientes', ClienteController::class);
    Route::patch('clientes/{cliente}/toggle-estado', [ClienteController::class, 'toggleEstado'])->name('clientes.toggle-estado');
    
    // Productos - Solo admin y administrativo
    Route::resource('productos', ProductoController::class);
    
    // Repartos - Todos los roles con restricciones en policies
    Route::resource('repartos', RepartoController::class);
    Route::post('repartos/{id}/entregar', [RepartoController::class, 'marcarEntregado'])
        ->name('repartos.entregar');
    
    // Pagos - Solo admin y administrativo
    // IMPORTANTE: Rutas específicas ANTES del resource
    Route::post('pagos/cobro-rapido', [PagoController::class, 'cobroRapido'])
        ->name('pagos.cobro-rapido');
    Route::get('api/repartos/{reparto}/cobro-info', [PagoController::class, 'getCobroInfo'])
        ->name('api.repartos.cobro-info');
    Route::resource('pagos', PagoController::class);
    
    // Usuarios - Admin y gerente
    Route::resource('usuarios', UsuarioController::class);
    Route::patch('usuarios/{usuario}/toggle-estado', [UsuarioController::class, 'toggleEstado'])
        ->name('usuarios.toggle-estado');
    
    // Reportes - Admin, gerente, administrativo
    Route::prefix('reportes')->name('reportes.')->group(function () {
        Route::get('/', [ReporteController::class, 'index'])->name('index');
        Route::get('/bidones-cobrados', [ReporteController::class, 'bidonesCobrados'])->name('bidones-cobrados');
        Route::get('/ingresos-por-periodo', [ReporteController::class, 'ingresosPorPeriodo'])->name('ingresos-por-periodo');
        Route::get('/estado-cuenta-cliente/{cliente?}', [ReporteController::class, 'estadoCuentaCliente'])->name('estado-cuenta-cliente');
        Route::get('/cuentas-por-cobrar', [ReporteController::class, 'cuentasPorCobrar'])->name('cuentas-por-cobrar');
        Route::get('/repartos-por-periodo', [ReporteController::class, 'repartosPorPeriodo'])->name('repartos-por-periodo');
        Route::get('/analisis-geografico', [ReporteController::class, 'analisisGeografico'])->name('analisis-geografico');
    });
    
    // Vehículos - Admin, gerente, administrativo y chofer (limitado)
    Route::resource('vehiculos', VehiculoController::class);
    Route::patch('vehiculos/{vehiculo}/toggle-estado', [VehiculoController::class, 'toggleEstado'])
        ->name('vehiculos.toggle-estado');
    Route::post('vehiculos/{vehiculo}/mantenimiento', [VehiculoController::class, 'registrarMantenimiento'])
        ->name('vehiculos.mantenimiento');
});

require __DIR__.'/auth.php';
