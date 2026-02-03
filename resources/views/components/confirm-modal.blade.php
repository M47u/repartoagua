@props([
    'id' => 'confirmModal',
    'title' => '¿Confirmar acción?',
    'message' => '¿Estás seguro de que deseas realizar esta acción?',
    'confirmText' => 'Confirmar',
    'cancelText' => 'Cancelar',
    'confirmColor' => 'sky'
])

@php
    $iconBgClass = match($confirmColor) {
        'red' => 'bg-red-100',
        'emerald' => 'bg-emerald-100',
        'sky' => 'bg-sky-100',
        default => 'bg-sky-100'
    };
    
    $iconTextClass = match($confirmColor) {
        'red' => 'text-red-600',
        'emerald' => 'text-emerald-600',
        'sky' => 'text-sky-600',
        default => 'text-sky-600'
    };
    
    $buttonBgClass = match($confirmColor) {
        'red' => 'bg-red-600 hover:bg-red-700',
        'emerald' => 'bg-emerald-600 hover:bg-emerald-700',
        'sky' => 'bg-sky-600 hover:bg-sky-700',
        default => 'bg-sky-600 hover:bg-sky-700'
    };
@endphp

<div 
    x-data="{ open: false }"
    @open-modal.window="if ($event.detail.id === '{{ $id }}') open = true"
    x-show="open"
    x-cloak
    class="fixed inset-0 z-50 overflow-y-auto"
    aria-labelledby="modal-title" 
    role="dialog" 
    aria-modal="true"
    style="display: none;"
>
    <!-- Backdrop -->
    <div 
        x-show="open"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-slate-900 bg-opacity-50 transition-opacity"
        @click="open = false"
    ></div>

    <!-- Modal -->
    <div class="flex min-h-full items-center justify-center p-4">
        <div 
            x-show="open"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="relative z-10 transform overflow-hidden rounded-lg bg-white shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg"
            style="background-color: #ffffff;"
        >
            <div class="bg-white px-6 pt-5 pb-4" style="background-color: #ffffff;">
                <div class="sm:flex sm:items-start">
                    <!-- Icon -->
                    <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full {{ $iconBgClass }} sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 {{ $iconTextClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <!-- Content -->
                    <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left flex-1">
                        <h3 class="text-lg font-semibold leading-6 text-slate-900" id="modal-title">
                            {{ $title }}
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-slate-500">
                                {{ $message }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Actions -->
            <div class="bg-slate-50 px-6 py-3 sm:flex sm:flex-row-reverse gap-3">
                <button 
                    type="button"
                    @click="$dispatch('confirm-action', { id: '{{ $id }}' }); open = false"
                    class="inline-flex w-full justify-center rounded-lg px-4 py-2 text-sm font-semibold text-white shadow-sm sm:w-auto transition-colors {{ $buttonBgClass }}"
                >
                    {{ $confirmText }}
                </button>
                <button 
                    type="button"
                    @click="open = false"
                    class="mt-3 inline-flex w-full justify-center rounded-lg bg-white px-4 py-2 text-sm font-semibold text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 sm:mt-0 sm:w-auto transition-colors"
                >
                    {{ $cancelText }}
                </button>
            </div>
        </div>
    </div>
</div>
