@if ($errors->any())
    <div x-data="{ show: true }"
         x-show="show"
         x-init="setTimeout(() => show = false, 3000)"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         {{ $attributes->merge(['class' => 'p-3.5 bg-red-50 border border-red-200 rounded-2xl text-sm text-red-700 font-medium flex items-center gap-2.5 shadow-sm shadow-red-50/5']) }}>
        <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span>{{ $errors->first() }}</span>
    </div>
@endif
