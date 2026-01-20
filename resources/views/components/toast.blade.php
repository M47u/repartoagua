<div 
    x-data="{ show: false, message: '', type: 'success' }"
    x-on:toast.window="
        message = $event.detail.message;
        type = $event.detail.type || 'success';
        show = true;
        setTimeout(() => show = false, $event.detail.duration || 3000);
    "
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform translate-x-full"
    x-transition:enter-end="opacity-100 transform translate-x-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 transform translate-x-0"
    x-transition:leave-end="opacity-0 transform translate-x-full"
    class="fixed top-4 right-4 z-50 max-w-sm w-full"
    style="display: none;"
>
    <div 
        :class="{
            'bg-emerald-50 border-emerald-500': type === 'success',
            'bg-red-50 border-red-500': type === 'error',
            'bg-amber-50 border-amber-500': type === 'warning',
            'bg-sky-50 border-sky-500': type === 'info'
        }"
        class="flex items-center gap-3 p-4 rounded-lg shadow-lg border-l-4"
    >
        <!-- Icon -->
        <div 
            :class="{
                'text-emerald-600': type === 'success',
                'text-red-600': type === 'error',
                'text-amber-600': type === 'warning',
                'text-sky-600': type === 'info'
            }"
        >
            <!-- Success Icon -->
            <svg x-show="type === 'success'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <!-- Error Icon -->
            <svg x-show="type === 'error'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <!-- Warning Icon -->
            <svg x-show="type === 'warning'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            <!-- Info Icon -->
            <svg x-show="type === 'info'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        
        <!-- Message -->
        <p 
            x-text="message"
            :class="{
                'text-emerald-900': type === 'success',
                'text-red-900': type === 'error',
                'text-amber-900': type === 'warning',
                'text-sky-900': type === 'info'
            }"
            class="flex-1 font-medium"
        ></p>
        
        <!-- Close Button -->
        <button 
            @click="show = false"
            :class="{
                'text-emerald-600 hover:text-emerald-800': type === 'success',
                'text-red-600 hover:text-red-800': type === 'error',
                'text-amber-600 hover:text-amber-800': type === 'warning',
                'text-sky-600 hover:text-sky-800': type === 'info'
            }"
            class="flex-shrink-0"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
</div>
