<x-app-layout>
    <div class="py-8 sm:py-12">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Campaign Info Header --}}
            <div class="mb-6 flex items-center gap-4 bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                @if ($campaign->banner_image)
                    <img src="{{ asset('storage/' . $campaign->banner_image) }}" alt="{{ $campaign->title }}" class="w-16 h-16 rounded-lg object-cover">
                @endif
                <div class="flex-1 min-w-0">
                    <h2 class="text-base font-semibold text-gray-900 truncate">{{ $campaign->title }}</h2>
                    <p class="text-sm text-gray-500">oleh {{ $campaign->user->full_name }}</p>
                </div>
            </div>

            {{-- Donation Form --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="border-b border-gray-100 px-6 py-4">
                    <h1 class="text-lg font-semibold text-gray-900">Berikan Donasi</h1>
                    <p class="text-sm text-gray-500 mt-0.5">Bantu kampanye ini mencapai targetnya.</p>
                </div>
                <div class="p-6">
                    @livewire('donation-form', ['slug' => $campaign->slug])
                </div>
            </div>

            <div class="mt-4 text-center">
                <a href="{{ route('campaigns.show', $campaign->slug) }}" class="text-sm text-gray-500 hover:text-gray-700">← Kembali ke kampanye</a>
            </div>
        </div>
    </div>
</x-app-layout>
