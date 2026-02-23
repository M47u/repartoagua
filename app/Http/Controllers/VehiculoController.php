<?php

namespace App\Http\Controllers;

use App\Models\Vehiculo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VehiculoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Vehiculo::class);
        
        $query = Vehiculo::query();
        
        // Filtros
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }
        
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        
        if ($request->filled('activo')) {
            $query->where('activo', $request->activo);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('placa', 'like', "%{$search}%")
                  ->orWhere('marca', 'like', "%{$search}%")
                  ->orWhere('modelo', 'like', "%{$search}%")
                  ->orWhere('numero_motor', 'like', "%{$search}%")
                  ->orWhere('numero_chasis', 'like', "%{$search}%");
            });
        }
        
        $vehiculos = $query->latest()->paginate(15);
        
        return view('vehiculos.index', compact('vehiculos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Vehiculo::class);
        
        $choferes = User::where('role', 'chofer')
            ->where('activo', true)
            ->orderBy('name')
            ->get();
        
        return view('vehiculos.create', compact('choferes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Vehiculo::class);
        
        $validated = $request->validate([
            'placa' => 'required|string|max:255|unique:vehiculos,placa',
            'marca' => 'required|string|max:255',
            'modelo' => 'required|string|max:255',
            'año' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'color' => 'nullable|string|max:255',
            'tipo' => 'required|in:camion,camioneta,auto,moto',
            'capacidad_carga' => 'nullable|integer|min:0',
            'capacidad_bidones' => 'nullable|integer|min:0',
            'numero_motor' => 'nullable|string|max:255',
            'numero_chasis' => 'nullable|string|max:255',
            'fecha_compra' => 'nullable|date',
            'fecha_ultimo_mantenimiento' => 'nullable|date',
            'fecha_proximo_mantenimiento' => 'nullable|date|after:fecha_ultimo_mantenimiento',
            'kilometraje' => 'nullable|numeric|min:0',
            'estado' => 'required|in:disponible,en_uso,mantenimiento,fuera_servicio',
            'observaciones' => 'nullable|string',
            'activo' => 'boolean',
            'choferes' => 'nullable|array',
            'choferes.*' => 'exists:users,id',
        ]);
        
        // Crear el vehículo
        $vehiculo = Vehiculo::create([
            'placa' => strtoupper($validated['placa']),
            'marca' => $validated['marca'],
            'modelo' => $validated['modelo'],
            'año' => $validated['año'],
            'color' => $validated['color'] ?? null,
            'tipo' => $validated['tipo'],
            'capacidad_carga' => $validated['capacidad_carga'] ?? null,
            'capacidad_bidones' => $validated['capacidad_bidones'] ?? null,
            'numero_motor' => $validated['numero_motor'] ?? null,
            'numero_chasis' => $validated['numero_chasis'] ?? null,
            'fecha_compra' => $validated['fecha_compra'] ?? null,
            'fecha_ultimo_mantenimiento' => $validated['fecha_ultimo_mantenimiento'] ?? null,
            'fecha_proximo_mantenimiento' => $validated['fecha_proximo_mantenimiento'] ?? null,
            'kilometraje' => $validated['kilometraje'] ?? 0,
            'estado' => $validated['estado'],
            'observaciones' => $validated['observaciones'] ?? null,
            'activo' => $validated['activo'] ?? true,
        ]);
        
        // Asignar choferes si se proporcionaron
        if (!empty($validated['choferes'])) {
            foreach ($validated['choferes'] as $choferId) {
                $vehiculo->choferes()->attach($choferId, [
                    'fecha_asignacion' => now()->toDateString(),
                    'asignacion_activa' => true,
                ]);
            }
        }
        
        return redirect()->route('vehiculos.index')
            ->with('success', 'Vehículo creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Vehiculo $vehiculo)
    {
        $this->authorize('view', $vehiculo);
        
        $vehiculo->load(['choferesActivos', 'choferes']);
        
        return view('vehiculos.show', compact('vehiculo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vehiculo $vehiculo)
    {
        $this->authorize('update', $vehiculo);
        
        $choferes = User::where('role', 'chofer')
            ->where('activo', true)
            ->orderBy('name')
            ->get();
            
        $choferesAsignados = $vehiculo->choferesActivos()->pluck('users.id')->toArray();
        
        return view('vehiculos.edit', compact('vehiculo', 'choferes', 'choferesAsignados'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vehiculo $vehiculo)
    {
        $this->authorize('update', $vehiculo);
        
        $validated = $request->validate([
            'placa' => ['required', 'string', 'max:255', Rule::unique('vehiculos')->ignore($vehiculo->id)],
            'marca' => 'required|string|max:255',
            'modelo' => 'required|string|max:255',
            'año' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'color' => 'nullable|string|max:255',
            'tipo' => 'required|in:camion,camioneta,auto,moto',
            'capacidad_carga' => 'nullable|integer|min:0',
            'capacidad_bidones' => 'nullable|integer|min:0',
            'numero_motor' => 'nullable|string|max:255',
            'numero_chasis' => 'nullable|string|max:255',
            'fecha_compra' => 'nullable|date',
            'fecha_ultimo_mantenimiento' => 'nullable|date',
            'fecha_proximo_mantenimiento' => 'nullable|date|after:fecha_ultimo_mantenimiento',
            'kilometraje' => 'nullable|numeric|min:0',
            'estado' => 'required|in:disponible,en_uso,mantenimiento,fuera_servicio',
            'observaciones' => 'nullable|string',
            'activo' => 'boolean',
            'choferes' => 'nullable|array',
            'choferes.*' => 'exists:users,id',
        ]);
        
        // Actualizar el vehículo
        $vehiculo->update([
            'placa' => strtoupper($validated['placa']),
            'marca' => $validated['marca'],
            'modelo' => $validated['modelo'],
            'año' => $validated['año'],
            'color' => $validated['color'] ?? null,
            'tipo' => $validated['tipo'],
            'capacidad_carga' => $validated['capacidad_carga'] ?? null,
            'capacidad_bidones' => $validated['capacidad_bidones'] ?? null,
            'numero_motor' => $validated['numero_motor'] ?? null,
            'numero_chasis' => $validated['numero_chasis'] ?? null,
            'fecha_compra' => $validated['fecha_compra'] ?? null,
            'fecha_ultimo_mantenimiento' => $validated['fecha_ultimo_mantenimiento'] ?? null,
            'fecha_proximo_mantenimiento' => $validated['fecha_proximo_mantenimiento'] ?? null,
            'kilometraje' => $validated['kilometraje'] ?? null,
            'estado' => $validated['estado'],
            'observaciones' => $validated['observaciones'] ?? null,
            'activo' => $validated['activo'] ?? true,
        ]);
        
        // Actualizar choferes asignados
        // Desactivar todas las asignaciones actuales
        $vehiculo->choferes()->updateExistingPivot(
            $vehiculo->choferesActivos()->pluck('users.id'),
            [
                'asignacion_activa' => false,
                'fecha_desasignacion' => now()->toDateString()
            ]
        );
        
        // Asignar los nuevos choferes
        if (!empty($validated['choferes'])) {
            foreach ($validated['choferes'] as $choferId) {
                // Verificar si ya existe la relación
                $exists = $vehiculo->choferes()->where('user_id', $choferId)->exists();
                
                if ($exists) {
                    // Reactivar la asignación existente
                    $vehiculo->choferes()->updateExistingPivot($choferId, [
                        'asignacion_activa' => true,
                        'fecha_asignacion' => now()->toDateString(),
                        'fecha_desasignacion' => null,
                    ]);
                } else {
                    // Crear nueva asignación
                    $vehiculo->choferes()->attach($choferId, [
                        'fecha_asignacion' => now()->toDateString(),
                        'asignacion_activa' => true,
                    ]);
                }
            }
        }
        
        return redirect()->route('vehiculos.index')
            ->with('success', 'Vehículo actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vehiculo $vehiculo)
    {
        $this->authorize('delete', $vehiculo);
        
        // Desactivar todas las asignaciones de choferes
        if ($vehiculo->choferesActivos()->exists()) {
            $vehiculo->choferes()->updateExistingPivot(
                $vehiculo->choferesActivos()->pluck('users.id'),
                [
                    'asignacion_activa' => false,
                    'fecha_desasignacion' => now()->toDateString()
                ]
            );
        }
        
        $vehiculo->delete();
        
        return redirect()->route('vehiculos.index')
            ->with('success', 'Vehículo eliminado exitosamente.');
    }

    /**
     * Toggle the active status of a vehicle.
     */
    public function toggleEstado(Vehiculo $vehiculo)
    {
        $this->authorize('update', $vehiculo);
        
        $vehiculo->update([
            'activo' => !$vehiculo->activo
        ]);
        
        // Si se desactiva, desactivar asignaciones de choferes
        if (!$vehiculo->activo && $vehiculo->choferesActivos()->exists()) {
            $vehiculo->choferes()->updateExistingPivot(
                $vehiculo->choferesActivos()->pluck('users.id'),
                [
                    'asignacion_activa' => false,
                    'fecha_desasignacion' => now()->toDateString()
                ]
            );
        }
        
        return redirect()->route('vehiculos.index')
            ->with('success', 'Estado del vehículo actualizado exitosamente.');
    }

    /**
     * Registrar mantenimiento de un vehículo.
     */
    public function registrarMantenimiento(Request $request, Vehiculo $vehiculo)
    {
        $this->authorize('update', $vehiculo);
        
        $validated = $request->validate([
            'fecha_ultimo_mantenimiento' => 'required|date',
            'fecha_proximo_mantenimiento' => 'required|date|after:fecha_ultimo_mantenimiento',
            'kilometraje' => 'nullable|numeric|min:0',
            'observaciones' => 'nullable|string',
        ]);
        
        $vehiculo->update([
            'fecha_ultimo_mantenimiento' => $validated['fecha_ultimo_mantenimiento'],
            'fecha_proximo_mantenimiento' => $validated['fecha_proximo_mantenimiento'],
            'kilometraje' => $validated['kilometraje'] ?? $vehiculo->kilometraje,
            'observaciones' => $validated['observaciones'] ?? $vehiculo->observaciones,
            'estado' => 'disponible', // Cambiar a disponible después del mantenimiento
        ]);
        
        return redirect()->route('vehiculos.show', $vehiculo)
            ->with('success', 'Mantenimiento registrado exitosamente.');
    }
}
