# 📝 Snippets de Código - Aguas del Litoral

Colección de fragmentos de código reutilizables para agilizar el desarrollo.

---

## 🔵 Blade Components

### Card con Datos
```blade
<x-card title="Título" :padding="false">
    <x-slot:icon>
        <svg class="w-6 h-6 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
    </x-slot:icon>
    
    <x-slot:actions>
        <a href="#" class="text-sm font-medium text-sky-600 hover:text-sky-700">Ver más →</a>
    </x-slot:actions>
    
    <div class="p-6">
        <!-- Contenido -->
    </div>
</x-card>
```

### Tabla Responsive
```blade
<x-card :padding="false">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">
                        Columna 1
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">
                        Columna 2
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($items as $item)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">{{ $item->name }}</td>
                        <td class="px-6 py-4">{{ $item->value }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="px-6 py-12">
                            <x-empty-state
                                title="No hay datos"
                                description="Los registros aparecerán aquí"
                            >
                                <x-slot:icon>
                                    <svg class="w-16 h-16 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                    </svg>
                                </x-slot:icon>
                            </x-empty-state>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($items->hasPages())
        <div class="px-6 py-4 border-t border-slate-200">
            {{ $items->links() }}
        </div>
    @endif
</x-card>
```

### Formulario con Validación
```blade
<form method="POST" action="{{ route('resource.store') }}" class="space-y-6">
    @csrf
    
    <!-- Campo de Texto -->
    <div>
        <label for="name" class="block text-sm font-medium text-slate-700 mb-2">
            Nombre <span class="text-red-500">*</span>
        </label>
        <input 
            type="text" 
            id="name" 
            name="name" 
            value="{{ old('name') }}"
            class="input-primary @error('name') input-error @enderror"
            required
        >
        @error('name')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
    
    <!-- Select -->
    <div>
        <label for="type" class="block text-sm font-medium text-slate-700 mb-2">
            Tipo <span class="text-red-500">*</span>
        </label>
        <select 
            id="type" 
            name="type"
            class="input-primary @error('type') input-error @enderror"
            required
        >
            <option value="">Seleccionar...</option>
            <option value="hogar" {{ old('type') === 'hogar' ? 'selected' : '' }}>🏠 Hogar</option>
            <option value="comercio" {{ old('type') === 'comercio' ? 'selected' : '' }}>🏢 Comercio</option>
            <option value="empresa" {{ old('type') === 'empresa' ? 'selected' : '' }}>🏭 Empresa</option>
        </select>
        @error('type')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
    
    <!-- Textarea -->
    <div>
        <label for="notes" class="block text-sm font-medium text-slate-700 mb-2">
            Observaciones
        </label>
        <textarea 
            id="notes" 
            name="notes" 
            rows="4"
            class="input-primary @error('notes') input-error @enderror"
        >{{ old('notes') }}</textarea>
        @error('notes')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
    
    <!-- Botones -->
    <div class="flex items-center gap-3">
        <x-button type="submit" variant="primary">
            <x-slot:icon>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </x-slot:icon>
            Guardar
        </x-button>
        
        <a href="{{ route('resource.index') }}">
            <x-button type="button" variant="outline">
                Cancelar
            </x-button>
        </a>
    </div>
</form>
```

### Modal con Alpine.js
```blade
<div x-data="{ open: false }">
    <!-- Trigger Button -->
    <x-button @click="open = true">
        Abrir Modal
    </x-button>
    
    <!-- Modal -->
    <div 
        x-show="open"
        @click.away="open = false"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 overflow-y-auto"
        style="display: none;"
    >
        <!-- Overlay -->
        <div class="fixed inset-0 bg-slate-900/50"></div>
        
        <!-- Modal Content -->
        <div class="flex min-h-full items-center justify-center p-4">
            <div 
                x-show="open"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="transform scale-95 opacity-0"
                x-transition:enter-end="transform scale-100 opacity-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="transform scale-100 opacity-100"
                x-transition:leave-end="transform scale-95 opacity-0"
                class="relative bg-white rounded-xl shadow-xl max-w-md w-full"
            >
                <!-- Header -->
                <div class="flex items-center justify-between p-6 border-b border-slate-200">
                    <h3 class="text-xl font-semibold text-slate-900">Título del Modal</h3>
                    <button @click="open = false" class="text-slate-400 hover:text-slate-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <!-- Body -->
                <div class="p-6">
                    <p class="text-slate-600">Contenido del modal...</p>
                </div>
                
                <!-- Footer -->
                <div class="flex items-center justify-end gap-3 p-6 border-t border-slate-200">
                    <x-button @click="open = false" variant="outline">
                        Cancelar
                    </x-button>
                    <x-button variant="primary">
                        Confirmar
                    </x-button>
                </div>
            </div>
        </div>
    </div>
</div>
```

### Tabs con Alpine.js
```blade
<div x-data="{ activeTab: 'tab1' }">
    <!-- Tab Headers -->
    <div class="bg-white rounded-t-xl border-b border-slate-200">
        <nav class="flex gap-1 p-4">
            <button 
                @click="activeTab = 'tab1'"
                :class="activeTab === 'tab1' ? 'bg-sky-50 text-sky-700 font-semibold' : 'text-slate-600 hover:bg-slate-50'"
                class="flex items-center gap-2 px-4 py-2 rounded-lg transition-all"
            >
                Tab 1
            </button>
            
            <button 
                @click="activeTab = 'tab2'"
                :class="activeTab === 'tab2' ? 'bg-sky-50 text-sky-700 font-semibold' : 'text-slate-600 hover:bg-slate-50'"
                class="flex items-center gap-2 px-4 py-2 rounded-lg transition-all"
            >
                Tab 2
            </button>
        </nav>
    </div>
    
    <!-- Tab Content -->
    <div class="bg-white rounded-b-xl shadow-sm">
        <div x-show="activeTab === 'tab1'" class="p-6">
            Contenido Tab 1
        </div>
        
        <div x-show="activeTab === 'tab2'" class="p-6" style="display: none;">
            Contenido Tab 2
        </div>
    </div>
</div>
```

### Dropdown con Alpine.js
```blade
<div x-data="{ open: false }" class="relative">
    <!-- Trigger -->
    <button 
        @click="open = !open"
        class="flex items-center gap-2 px-4 py-2 bg-white border border-slate-300 rounded-lg hover:bg-slate-50"
    >
        <span>Opciones</span>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>
    
    <!-- Dropdown Menu -->
    <div 
        x-show="open"
        @click.away="open = false"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-slate-200 py-1 z-10"
        style="display: none;"
    >
        <a href="#" class="flex items-center gap-2 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
            Opción 1
        </a>
        <a href="#" class="flex items-center gap-2 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
            Opción 2
        </a>
        <div class="border-t border-slate-200 my-1"></div>
        <a href="#" class="flex items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50">
            Eliminar
        </a>
    </div>
</div>
```

---

## 🟢 JavaScript

### Mostrar Toast desde Laravel
```php
// En el controlador
return redirect()->route('clientes.index')
    ->with('success', 'Cliente creado exitosamente');
```

```blade
<!-- En la vista -->
@if(session('success'))
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            showToast('{{ session('success') }}', 'success');
        });
    </script>
@endif

@if(session('error'))
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            showToast('{{ session('error') }}', 'error');
        });
    </script>
@endif
```

### Búsqueda en Tiempo Real
```blade
<input 
    type="text" 
    x-data="{ search: '' }"
    x-model="search"
    @input.debounce.300ms="
        fetch(`/api/search?q=${search}`)
            .then(r => r.json())
            .then(data => console.log(data))
    "
    placeholder="Buscar..."
    class="input-primary"
>
```

### Confirmación de Eliminación
```blade
<form action="{{ route('resource.destroy', $item) }}" method="POST" 
      onsubmit="return confirmDelete(event, '¿Eliminar {{ $item->name }}?')">
    @csrf
    @method('DELETE')
    <x-button type="submit" variant="danger">
        Eliminar
    </x-button>
</form>
```

### Copiar al Portapapeles
```blade
<button 
    onclick="copyToClipboard('{{ $item->code }}')"
    class="p-2 hover:bg-slate-100 rounded-lg"
>
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
    </svg>
</button>
```

---

## 🔴 Controladores

### CRUD Básico
```php
<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use Illuminate\Http\Request;

class ResourceController extends Controller
{
    public function index()
    {
        $resources = Resource::latest()->paginate(15);
        
        return view('resources.index', compact('resources'));
    }

    public function create()
    {
        return view('resources.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:hogar,comercio,empresa',
            'notes' => 'nullable|string',
        ]);

        Resource::create($validated);

        return redirect()
            ->route('resources.index')
            ->with('success', 'Recurso creado exitosamente');
    }

    public function show(Resource $resource)
    {
        return view('resources.show', compact('resource'));
    }

    public function edit(Resource $resource)
    {
        return view('resources.edit', compact('resource'));
    }

    public function update(Request $request, Resource $resource)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:hogar,comercio,empresa',
            'notes' => 'nullable|string',
        ]);

        $resource->update($validated);

        return redirect()
            ->route('resources.show', $resource)
            ->with('success', 'Recurso actualizado exitosamente');
    }

    public function destroy(Resource $resource)
    {
        $resource->delete();

        return redirect()
            ->route('resources.index')
            ->with('success', 'Recurso eliminado exitosamente');
    }
}
```

---

## 🟡 Iconos SVG Comunes

### Camión (Reparto)
```svg
<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path>
</svg>
```

### Gota de Agua
```svg
<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
</svg>
```

### Usuarios
```svg
<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
</svg>
```

### Dinero
```svg
<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
</svg>
```

### Check (Completado)
```svg
<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
</svg>
```

---

¡Usa estos snippets para desarrollar más rápido! 🚀
