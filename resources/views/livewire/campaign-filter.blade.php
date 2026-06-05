<div>
    {{-- Search & Filter --}}
    <div class="mb-8 bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6">
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
                <input wire:model.live.debounce.400ms="search" type="text" placeholder="Cari kampanye..."
                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm">
            </div>
            <div class="sm:w-48">
                <select wire:model.live="category" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id_category }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    {{-- Loading --}}
    <div wire:loading.delay class="text-center py-4">
        <svg class="w-6 h-6 animate-spin text-brand-500 mx-auto" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
    </div>

    {{-- Grid --}}
    <div wire:loading.remove>
        @if($campaigns->isEmpty())
            <div class="text-center py-16">
                <svg class="mx-auto h-16 w-16 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">Tidak ada kampanye ditemukan</h3>
                <p class="mt-2 text-sm text-gray-500">Coba ubah filter atau kata kunci pencarian.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($campaigns as $campaign)
                    <x-campaign-card
                        :campaign="$campaign"
                        :liked="in_array($campaign->id_campaign, $likedIds)"
                        :likesCount="$campaign->likes_count"
                    />
                @endforeach
            </div>
            <div class="mt-8">{{ $campaigns->links() }}</div>
        @endif
    </div>
</div>
