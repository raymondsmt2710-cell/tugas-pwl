<div class="space-y-3">
    @foreach($faqs as $index => $faq)
        <div class="border border-gray-200 rounded-lg overflow-hidden">
            <button wire:click="toggle({{ $index }})" class="w-full flex items-center justify-between px-5 py-4 text-left hover:bg-gray-50 transition-colors">
                <span class="text-sm font-medium text-gray-900">{{ $faq['q'] }}</span>
                <svg class="w-4 h-4 text-gray-400 shrink-0 ml-4 transition-transform {{ $openIndex === $index ? 'rotate-180' : '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                </svg>
            </button>
            @if($openIndex === $index)
                <div class="px-5 pb-4">
                    <p class="text-sm text-gray-600 leading-relaxed">{{ $faq['a'] }}</p>
                </div>
            @endif
        </div>
    @endforeach
</div>
