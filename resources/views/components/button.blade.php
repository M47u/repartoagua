@props(['variant' => 'primary', 'size' => 'md', 'icon' => null, 'iconPosition' => 'left', 'type' => 'button'])

@php
    $variants = [
        'primary' => 'bg-sky-500 hover:bg-sky-600 text-white shadow-sm hover:shadow-md',
        'secondary' => 'bg-slate-200 hover:bg-slate-300 text-slate-900',
        'danger' => 'bg-red-500 hover:bg-red-600 text-white shadow-sm hover:shadow-md',
        'success' => 'bg-emerald-500 hover:bg-emerald-600 text-white shadow-sm hover:shadow-md',
        'outline' => 'border-2 border-slate-300 hover:border-slate-400 text-slate-700 bg-white',
        'ghost' => 'hover:bg-slate-100 text-slate-700',
    ];
    
    $sizes = [
        'sm' => 'px-3 py-1.5 text-sm min-h-touch',
        'md' => 'px-4 py-2 text-base min-h-touch',
        'lg' => 'px-6 py-3 text-lg',
    ];
    
    $variantClass = $variants[$variant] ?? $variants['primary'];
    $sizeClass = $sizes[$size] ?? $sizes['md'];
@endphp

<button 
    type="{{ $type }}"
    {{ $attributes->merge(['class' => "inline-flex items-center justify-center gap-2 rounded-lg font-medium transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed $variantClass $sizeClass"]) }}
>
    @if($icon && $iconPosition === 'left')
        {!! $icon !!}
    @endif
    
    {{ $slot }}
    
    @if($icon && $iconPosition === 'right')
        {!! $icon !!}
    @endif
</button>
