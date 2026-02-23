<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vehiculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);
        
        $query = User::query();
        
        // Filtros
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        
        if ($request->filled('activo')) {
            $query->where('activo', $request->activo);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('apellido', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('dni', 'like', "%{$search}%");
            });
        }
        
        $usuarios = $query->latest()->paginate(15);
        
        return view('usuarios.index', compact('usuarios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', User::class);
        
        $vehiculos = Vehiculo::activos()->orderBy('marca')->get();
        
        return view('usuarios.create', compact('vehiculos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', User::class);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:administrador,administrativo,repartidor,chofer,gerente',
            'telefono' => 'nullable|string|max:255',
            'dni' => 'nullable|string|max:255|unique:users,dni',
            'direccion' => 'nullable|string|max:500',
            'ciudad' => 'nullable|string|max:255',
            'fecha_ingreso' => 'nullable|date',
            'fecha_nacimiento' => 'nullable|date|before:today',
            'observaciones' => 'nullable|string',
            'activo' => 'boolean',
            'vehiculos' => 'nullable|array',
            'vehiculos.*' => 'exists:vehiculos,id',
        ]);
        
        // Crear el usuario
        $usuario = User::create([
            'name' => $validated['name'],
            'apellido' => $validated['apellido'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'telefono' => $validated['telefono'] ?? null,
            'dni' => $validated['dni'] ?? null,
            'direccion' => $validated['direccion'] ?? null,
            'ciudad' => $validated['ciudad'] ?? null,
            'fecha_ingreso' => $validated['fecha_ingreso'] ?? now()->toDateString(),
            'fecha_nacimiento' => $validated['fecha_nacimiento'] ?? null,
            'observaciones' => $validated['observaciones'] ?? null,
            'activo' => $validated['activo'] ?? true,
        ]);
        
        // Si es chofer y tiene vehículos asignados
        if ($validated['role'] === 'chofer' && !empty($validated['vehiculos'])) {
            foreach ($validated['vehiculos'] as $vehiculoId) {
                $usuario->vehiculos()->attach($vehiculoId, [
                    'fecha_asignacion' => now()->toDateString(),
                    'asignacion_activa' => true,
                ]);
            }
        }
        
        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $usuario)
    {
        $this->authorize('view', $usuario);
        
        $usuario->load([
            'repartos' => function($query) {
                $query->latest()->take(10);
            },
            'pagos' => function($query) {
                $query->latest()->take(10);
            },
            'vehiculosActivos'
        ]);
        
        return view('usuarios.show', compact('usuario'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $usuario)
    {
        $this->authorize('update', $usuario);
        
        $vehiculos = Vehiculo::activos()->orderBy('marca')->get();
        $vehiculosAsignados = $usuario->vehiculosActivos()->pluck('vehiculos.id')->toArray();
        
        return view('usuarios.edit', compact('usuario', 'vehiculos', 'vehiculosAsignados'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $usuario)
    {
        $this->authorize('update', $usuario);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($usuario->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:administrador,administrativo,repartidor,chofer,gerente',
            'telefono' => 'nullable|string|max:255',
            'dni' => ['nullable', 'string', 'max:255', Rule::unique('users')->ignore($usuario->id)],
            'direccion' => 'nullable|string|max:500',
            'ciudad' => 'nullable|string|max:255',
            'fecha_ingreso' => 'nullable|date',
            'fecha_nacimiento' => 'nullable|date|before:today',
            'observaciones' => 'nullable|string',
            'activo' => 'boolean',
            'vehiculos' => 'nullable|array',
            'vehiculos.*' => 'exists:vehiculos,id',
        ]);
        
        // Actualizar datos básicos
        $dataToUpdate = [
            'name' => $validated['name'],
            'apellido' => $validated['apellido'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'telefono' => $validated['telefono'] ?? null,
            'dni' => $validated['dni'] ?? null,
            'direccion' => $validated['direccion'] ?? null,
            'ciudad' => $validated['ciudad'] ?? null,
            'fecha_ingreso' => $validated['fecha_ingreso'] ?? null,
            'fecha_nacimiento' => $validated['fecha_nacimiento'] ?? null,
            'observaciones' => $validated['observaciones'] ?? null,
            'activo' => $validated['activo'] ?? true,
        ];
        
        // Si se proporcionó una nueva contraseña
        if (!empty($validated['password'])) {
            $dataToUpdate['password'] = Hash::make($validated['password']);
        }
        
        $usuario->update($dataToUpdate);
        
        // Actualizar vehículos si es chofer
        if ($validated['role'] === 'chofer') {
            // Desactivar todas las asignaciones actuales
            $usuario->vehiculos()->updateExistingPivot(
                $usuario->vehiculosActivos()->pluck('vehiculos.id'),
                [
                    'asignacion_activa' => false,
                    'fecha_desasignacion' => now()->toDateString()
                ]
            );
            
            // Asignar los nuevos vehículos
            if (!empty($validated['vehiculos'])) {
                foreach ($validated['vehiculos'] as $vehiculoId) {
                    // Verificar si ya existe la relación
                    $exists = $usuario->vehiculos()->where('vehiculo_id', $vehiculoId)->exists();
                    
                    if ($exists) {
                        // Reactivar la asignación existente
                        $usuario->vehiculos()->updateExistingPivot($vehiculoId, [
                            'asignacion_activa' => true,
                            'fecha_asignacion' => now()->toDateString(),
                            'fecha_desasignacion' => null,
                        ]);
                    } else {
                        // Crear nueva asignación
                        $usuario->vehiculos()->attach($vehiculoId, [
                            'fecha_asignacion' => now()->toDateString(),
                            'asignacion_activa' => true,
                        ]);
                    }
                }
            }
        } else {
            // Si cambió de rol y ya no es chofer, desactivar todas las asignaciones
            if ($usuario->vehiculosActivos()->exists()) {
                $usuario->vehiculos()->updateExistingPivot(
                    $usuario->vehiculosActivos()->pluck('vehiculos.id'),
                    [
                        'asignacion_activa' => false,
                        'fecha_desasignacion' => now()->toDateString()
                    ]
                );
            }
        }
        
        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $usuario)
    {
        $this->authorize('delete', $usuario);
        
        // Verificar que no tenga repartos pendientes
        if ($usuario->repartos()->where('estado', 'pendiente')->exists()) {
            return redirect()->route('usuarios.index')
                ->with('error', 'No se puede eliminar el usuario porque tiene repartos pendientes.');
        }
        
        // Desactivar asignaciones de vehículos si es chofer
        if ($usuario->isChofer() && $usuario->vehiculosActivos()->exists()) {
            $usuario->vehiculos()->updateExistingPivot(
                $usuario->vehiculosActivos()->pluck('vehiculos.id'),
                [
                    'asignacion_activa' => false,
                    'fecha_desasignacion' => now()->toDateString()
                ]
            );
        }
        
        $usuario->delete();
        
        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario eliminado exitosamente.');
    }

    /**
     * Toggle the active status of a user.
     */
    public function toggleEstado(User $usuario)
    {
        $this->authorize('update', $usuario);
        
        $usuario->update([
            'activo' => !$usuario->activo
        ]);
        
        // Si se desactiva y es chofer, desactivar asignaciones de vehículos
        if (!$usuario->activo && $usuario->isChofer()) {
            $usuario->vehiculos()->updateExistingPivot(
                $usuario->vehiculosActivos()->pluck('vehiculos.id'),
                [
                    'asignacion_activa' => false,
                    'fecha_desasignacion' => now()->toDateString()
                ]
            );
        }
        
        return redirect()->route('usuarios.index')
            ->with('success', 'Estado del usuario actualizado exitosamente.');
    }
}
