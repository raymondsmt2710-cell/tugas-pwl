<div>
    @if($campaigns->isEmpty())
        <p class="text-center text-gray-500 py-8">Belum ada kampanye aktif saat ini.</p>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($campaigns as $campaign)
                <a href="{{ route('campaigns.show', $campaign->slug) }}" class="group block bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow duration-200">
                    <div class="aspect-[16/10] overflow-hidden bg-gray-100">
                        @if($campaign->banner_image)
                            <img src="{{ asset('storage/' . $campaign->banner_image) }}" alt="{{ $campaign->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-300">
                                <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909"/></svg>
                            </div>
                        @endif
                    </div>
                    <div class="p-5">
                        <span class="text-xs font-medium text-indigo-600">{{ $campaign->category->name ?? 'Umum' }}</span>
                        <h3 class="mt-1 text-base font-semibold text-gray-900 line-clamp-2 group-hover:text-indigo-600 transition-colors">{{ $campaign->title }}</h3>
                        <p class="mt-1.5 text-sm text-gray-500 line-clamp-2">{{ $campaign->short_description }}</p>
                        <div class="mt-4">
                            <div class="w-full bg-gray-100 rounded-full h-1.5">
                                <div class="bg-green-500 h-1.5 rounded-full" style="width: {{ min(100, $campaign->progress_percentage) }}%"></div>
                            </div>
                            <div class="flex justify-between mt-2 text-xs text-gray-500">
                                <span class="font-semibold text-gray-900">Rp {{ number_format($campaign->collected_amount, 0, ',', '.') }}</span>
                                <span>{{ $campaign->donor_count }} donatur</span>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
