<x-app-layout>
    <div class="py-8 sm:py-12">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Search Header --}}
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-900">Hasil Pencarian</h1>
                @if($query)
                    <p class="mt-1 text-sm text-gray-500">Menampilkan hasil untuk "<strong>{{ $query }}</strong>"</p>
                @endif
            </div>

            @if(strlen($query) < 2)
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                    </svg>
                    <h3 class="mt-3 text-base font-medium text-gray-900">Ketik minimal 2 karakter</h3>
                    <p class="mt-1 text-sm text-gray-500">Gunakan kolom pencarian di atas untuk mencari kampanye atau pengguna.</p>
                </div>
            @else
                {{-- Users Section --}}
                @if($users->isNotEmpty())
                    <div class="mb-10">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Pengguna ({{ $users->count() }})</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            @foreach($users as $user)
                                <a href="{{ url('/@' . $user->username) }}" class="flex items-center gap-3 p-4 bg-white rounded-xl border border-gray-200 hover:border-indigo-200 hover:shadow-sm transition">
                                    <img src="{{ $user->profile_photo_url }}" alt="{{ $user->full_name }}" class="w-10 h-10 rounded-full object-cover border border-gray-200">
                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold text-gray-900 truncate">{{ $user->full_name }}</p>
                                        <p class="text-xs text-gray-500 truncate">{{ '@' . $user->username }}</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Campaigns Section --}}
                @if($campaigns->isNotEmpty())
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Kampanye ({{ $campaigns->count() }})</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($campaigns as $campaign)
                                <a href="{{ route('campaigns.show', $campaign->slug) }}" class="group block bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-md hover:border-gray-300 transition">
                                    <div class="aspect-[16/10] overflow-hidden bg-gray-100">
                                        @if($campaign->banner_image)
                                            <img src="{{ asset('storage/' . $campaign->banner_image) }}" alt="{{ $campaign->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-300">
                                                <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909"/></svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="p-4">
                                        <span class="text-xs font-medium text-indigo-600">{{ $campaign->category->name ?? 'Umum' }}</span>
                                        <h3 class="mt-1 text-sm font-semibold text-gray-900 line-clamp-2 group-hover:text-indigo-600 transition-colors">{{ $campaign->title }}</h3>
                                        <div class="mt-3">
                                            <div class="w-full bg-gray-100 rounded-full h-1.5">
                                                <div class="bg-green-500 h-1.5 rounded-full" style="width: {{ min(100, $campaign->progress_percentage) }}%"></div>
                                            </div>
                                            <div class="flex justify-between mt-1.5 text-xs text-gray-500">
                                                <span class="font-semibold text-gray-900">Rp {{ number_format($campaign->collected_amount, 0, ',', '.') }}</span>
                                                <span>{{ $campaign->donor_count }} donatur</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- No Results --}}
                @if($campaigns->isEmpty() && $users->isEmpty())
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                        </svg>
                        <h3 class="mt-3 text-base font-medium text-gray-900">Tidak ditemukan</h3>
                        <p class="mt-1 text-sm text-gray-500">Tidak ada kampanye atau pengguna yang cocok dengan "{{ $query }}".</p>
                    </div>
                @endif
            @endif
        </div>
    </div>
</x-app-layout>
