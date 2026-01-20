@props(['title', 'value', 'icon', 'trend' => null, 'trendUp' => null, 'subtitle' => null, 'color' => 'sky'])

@php
    $colorClasses = [
        'sky' => 'bg-sky-100 text-sky-600',
        'emerald' => 'bg-emerald-100 text-emerald-600',
        'amber' => 'bg-amber-100 text-amber-600',
        'red' => 'bg-red-100 text-red-600',
        'purple' => 'bg-purple-100 text-purple-600',
    ];
    
    $iconColor = $colorClasses[$color] ?? $colorClasses['sky'];
@endphp

<div {{ $attributes->merge(['class' => 'bg-white rounded-xl shadow-sm p-6 hover:shadow-lg transition-all duration-200']) }}>
    <div class="flex items-center justify-between">
        <div class="flex-1">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-full {{ $iconColor }} flex items-center justify-center flex-shrink-0">
                    {!! $icon !!}
                </div>
                
                <div class="flex-1">
                    <p class="text-sm font-medium text-slate-500 mb-1">{{ $title }}</p>
                    <p class="text-3xl font-bold text-slate-900">{{ $value }}</p>
                    
                    @if($subtitle)
                        <p class="text-sm text-slate-500 mt-1">{{ $subtitle }}</p>
                    @endif
                </div>
            </div>
        </div>
        
        @if($trend)
            <div class="flex items-center gap-1 {{ $trendUp ? 'text-emerald-600' : 'text-red-600' }}">
                @if($trendUp)
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                @else
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                    </svg>
                @endif
                <span class="text-sm font-semibold">{{ $trend }}</span>
            </div>
        @endif
    </div>
</div>
