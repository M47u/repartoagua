@props(['color' => 'info', 'icon' => null, 'size' => 'md'])

@php
    $colors = [
        'success' => 'bg-emerald-100 text-emerald-800',
        'warning' => 'bg-amber-100 text-amber-800',
        'danger' => 'bg-red-100 text-red-800',
        'info' => 'bg-sky-100 text-sky-800',
        'primary' => 'bg-sky-100 text-sky-800',
        'secondary' => 'bg-slate-100 text-slate-800',
    ];
    
    $sizes = [
        'sm' => 'px-2 py-0.5 text-xs',
        'md' => 'px-2.5 py-1 text-sm',
        'lg' => 'px-3 py-1.5 text-base',
    ];
    
    $colorClass = $colors[$color] ?? $colors['info'];
    $sizeClass = $sizes[$size] ?? $sizes['md'];
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-1 rounded-full font-medium $colorClass $sizeClass"]) }}>
    @if($icon)
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <use href="#{{ $icon }}"></use>
        </svg>
    @endif
    {{ $slot }}
</span>
