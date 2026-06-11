<div>
    {{-- Stats --}}
    <div class="mb-6 grid grid-cols-2 gap-4">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 text-center">
            <p class="text-2xl font-bold text-gray-900">{{ $donations->total() }}</p>
            <p class="text-xs text-gray-500 mt-1">Total Donatur</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 text-center">
            <p class="text-2xl font-bold text-green-600">Rp {{ number_format($campaign->collected_amount, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-500 mt-1">Total Terkumpul</p>
        </div>
    </div>

    {{-- Search Input --}}
    <div class="mb-6">
        <div class="relative rounded-xl shadow-sm">
            <input type="text" 
                   wire:model.live.debounce.300ms="search" 
                   class="block w-full pl-4 pr-4 py-2.5 border border-gray-200 focus:border-brand-500 focus:ring-brand-500 rounded-xl text-sm placeholder-gray-400" 
                   placeholder="Cari nama donatur">
        </div>
    </div>

    {{-- Donors List --}}
    <div class="relative">


        @if ($donations->isEmpty())
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/>
                </svg>
                <h3 class="mt-3 text-base font-medium text-gray-900">Belum ada donatur</h3>
                <p class="mt-1 text-sm text-gray-500">
                    @if($search)
                        Tidak ada donatur yang cocok dengan kata kunci "{{ $search }}".
                    @else
                        Jadilah yang pertama berdonasi!
                    @endif
                </p>
            </div>
        @else
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="divide-y divide-gray-50">
                    @foreach ($donations as $index => $donation)
                        <div class="px-5 py-4 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                {{-- Numbering --}}
                                <div class="w-7 h-7 rounded-full bg-gray-100 flex items-center justify-center text-xs font-bold text-gray-500">
                                    {{ $index + 1 }}
                                </div>

                                {{-- Donor info --}}
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">
                                        {{ $donation->display_name }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        @if ($donation->created_at->diffInDays(now()) <= 7)
                                            {{ $donation->created_at->diffForHumans() }}
                                        @else
                                            {{ $donation->created_at->format('d-m-Y') }}
                                        @endif
                                    </p>
                                </div>
                            </div>

                            {{-- Amount --}}
                            <div class="text-right">
                                <p class="text-sm font-bold text-green-600">{{ $donation->formatted_amount }}</p>
                            </div>
                        </div>

                        {{-- Message (if any) --}}
                        @if ($donation->donor_message)
                            <div class="px-5 pb-3 -mt-2 ml-10">
                                <p class="text-xs text-gray-500 italic bg-gray-50 rounded-lg px-3 py-2">"{{ $donation->donor_message }}"</p>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            {{-- Load More Button --}}
            @if ($donations->hasMorePages())
                <div class="mt-6 text-center">
                    <button type="button" 
                            wire:click="loadMore" 
                            class="inline-flex items-center gap-2 px-6 py-2.5 bg-brand-500 hover:bg-brand-600 text-white font-semibold text-sm rounded-xl transition shadow-sm">
                        <span>Muat Lebih Banyak</span>
                        <div wire:loading wire:target="loadMore" class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                    </button>
                </div>
            @endif
        @endif
    </div>
</div>
