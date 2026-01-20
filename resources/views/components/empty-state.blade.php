@props(['icon', 'title', 'description', 'actionUrl' => null, 'actionText' => null])

<div {{ $attributes->merge(['class' => 'flex flex-col items-center justify-center py-12 px-4']) }}>
    <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mb-6">
        {!! $icon !!}
    </div>
    
    <h3 class="text-xl font-semibold text-slate-900 mb-2">{{ $title }}</h3>
    <p class="text-slate-500 text-center mb-8 max-w-md">{{ $description }}</p>
    
    @if($actionUrl && $actionText)
        <a href="{{ $actionUrl }}" class="inline-flex items-center gap-2 px-6 py-3 bg-sky-500 hover:bg-sky-600 text-white rounded-lg font-medium transition-all duration-200 shadow-sm hover:shadow-md">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            {{ $actionText }}
        </a>
    @endif
    
    @isset($customAction)
        {{ $customAction }}
    @endisset
</div>
