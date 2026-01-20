@props(['title' => null, 'icon' => null, 'padding' => true])

<div {{ $attributes->merge(['class' => 'bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-200']) }}>
    @if($title || isset($actions))
        <div class="flex items-center justify-between {{ $padding ? 'p-6 pb-4' : 'p-6' }} border-b border-slate-100">
            <div class="flex items-center gap-3">
                @if($icon)
                    <div class="w-10 h-10 bg-sky-100 rounded-lg flex items-center justify-center">
                        {!! $icon !!}
                    </div>
                @endif
                @if($title)
                    <h3 class="text-lg font-semibold text-slate-900">{{ $title }}</h3>
                @endif
            </div>
            
            @isset($actions)
                <div class="flex items-center gap-2">
                    {{ $actions }}
                </div>
            @endisset
        </div>
    @endif
    
    <div class="{{ $padding ? 'p-6' : '' }}">
        {{ $slot }}
    </div>
    
    @isset($footer)
        <div class="p-6 pt-4 border-t border-slate-100">
            {{ $footer }}
        </div>
    @endisset
</div>
