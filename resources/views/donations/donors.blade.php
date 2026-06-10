<x-app-layout>
    <div class="py-8 sm:py-12">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="mb-6">
                <a href="{{ route('campaigns.show', $campaign->slug) }}" class="inline-flex items-center gap-1 text-sm font-semibold text-brand-500 hover:text-brand-700 transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
                    </svg>
                    Kembali ke Kampanye
                </a>
                <h1 class="mt-3 text-2xl font-bold text-gray-900">Daftar Donatur</h1>
                <p class="mt-1 text-sm text-gray-500">{{ $campaign->title }}</p>
            </div>

            <livewire:campaign-donors-list :campaign="$campaign" />
        </div>
    </div>
</x-app-layout>
