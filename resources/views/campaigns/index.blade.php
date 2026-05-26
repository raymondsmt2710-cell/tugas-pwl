<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Jelajahi Kampanye
        </h2>
    </x-slot>

    <div class="py-8 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Search & Filter --}}
            <div class="mb-8 bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6">
                <form method="GET" action="{{ route('campaigns.index') }}" class="flex flex-col sm:flex-row gap-3">
                    <div class="flex-1">
                        <input type="text" name="search" value="{{ request('search') }}"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                               placeholder="Cari kampanye...">
                    </div>
                    <div class="sm:w-48">
                        <select name="category"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <option value="">Semua Kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id_category }}" {{ request('category') == $category->id_category ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="inline-flex items-center justify-center px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700 transition">
                        <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                        </svg>
                        Cari
                    </button>
                </form>
            </div>

            {{-- Campaign Grid --}}
            @if ($campaigns->isEmpty())
                <div class="text-center py-16">
                    <svg class="mx-auto h-16 w-16 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Tidak ada kampanye ditemukan</h3>
                    <p class="mt-2 text-sm text-gray-500">Coba ubah filter atau kata kunci pencarian Anda.</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($campaigns as $campaign)
                        <a href="{{ route('campaigns.show', $campaign->slug) }}" class="group bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md hover:border-gray-200 transition-all">
                            {{-- Image --}}
                            <div class="aspect-[16/9] overflow-hidden bg-gray-100">
                                @if ($campaign->banner_image)
                                    <img src="{{ asset('storage/' . $campaign->banner_image) }}" alt="{{ $campaign->title }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            {{-- Content --}}
                            <div class="p-5">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="text-xs font-medium text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full">
                                        {{ $campaign->category->name ?? 'Umum' }}
                                    </span>
                                    @if ($campaign->days_remaining <= 7 && $campaign->days_remaining > 0)
                                        <span class="text-xs font-medium text-red-600 bg-red-50 px-2 py-0.5 rounded-full">
                                            {{ $campaign->days_remaining }} hari lagi
                                        </span>
                                    @endif
                                </div>

                                <h3 class="text-base font-semibold text-gray-900 line-clamp-2 group-hover:text-indigo-600 transition-colors">
                                    {{ $campaign->title }}
                                </h3>

                                <p class="mt-1.5 text-sm text-gray-500 line-clamp-2">{{ $campaign->short_description }}</p>

                                {{-- Progress --}}
                                <div class="mt-4">
                                    <div class="w-full bg-gray-100 rounded-full h-2">
                                        <div class="bg-green-500 h-2 rounded-full transition-all" style="width: {{ min(100, $campaign->progress_percentage) }}%"></div>
                                    </div>
                                    <div class="flex justify-between mt-2 text-xs">
                                        <span class="font-semibold text-gray-900">Rp {{ number_format($campaign->collected_amount, 0, ',', '.') }}</span>
                                        <span class="text-gray-500">{{ number_format($campaign->progress_percentage, 0) }}%</span>
                                    </div>
                                </div>

                                {{-- Footer --}}
                                <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between text-xs text-gray-500">
                                    <span>{{ $campaign->user->full_name ?? 'Anonim' }}</span>
                                    <span>{{ $campaign->donor_count }} donatur</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-8">
                    {{ $campaigns->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
