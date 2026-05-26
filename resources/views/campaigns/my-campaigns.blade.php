<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Kampanye Saya
            </h2>
            <a href="{{ route('campaign.create') }}"
               class="inline-flex items-center px-4 py-2 border border-transparent rounded-xl text-sm font-semibold text-white bg-gray-900 hover:bg-gray-800 shadow-sm transition-colors">
                <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Buat Kampanye
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Flash Messages --}}
            @if (session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 rounded-xl p-4">
                    <div class="flex">
                        <svg class="w-5 h-5 text-green-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <p class="text-sm text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if ($campaigns->isEmpty())
                {{-- Empty State --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">
                    <div class="p-12 text-center">
                        <svg class="mx-auto h-16 w-16 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">Belum ada kampanye</h3>
                        <p class="mt-2 text-sm text-gray-500">Mulai buat kampanye penggalangan dana pertama Anda.</p>
                        <a href="{{ route('campaign.create') }}"
                           class="mt-6 inline-flex items-center px-5 py-2.5 border border-transparent rounded-xl text-sm font-semibold text-white bg-gray-900 hover:bg-gray-800 shadow-sm transition-colors">
                            Buat Kampanye Pertama
                        </a>
                    </div>
                </div>
            @else
                {{-- Campaign List --}}
                <div class="space-y-4">
                    @foreach ($campaigns as $campaign)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 hover:border-gray-200 transition-colors">
                            <div class="p-5 flex flex-col sm:flex-row gap-4">
                                {{-- Image --}}
                                <div class="flex-shrink-0">
                                    @if ($campaign->banner_image)
                                        <img src="{{ asset('storage/' . $campaign->banner_image) }}" alt="{{ $campaign->title }}"
                                             class="w-full sm:w-32 h-24 object-cover rounded-lg">
                                    @else
                                        <div class="w-full sm:w-32 h-24 bg-gray-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-8 h-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                {{-- Content --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-2">
                                        <div>
                                            <h3 class="text-base font-semibold text-gray-900 truncate">{{ $campaign->title }}</h3>
                                            <p class="text-sm text-gray-500 mt-0.5">{{ $campaign->category->name ?? '-' }}</p>
                                        </div>
                                        {{-- Status Badge --}}
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium flex-shrink-0
                                            @switch($campaign->status)
                                                @case('draft') bg-gray-100 text-gray-700 @break
                                                @case('pending') bg-yellow-100 text-yellow-800 @break
                                                @case('approved') bg-green-100 text-green-800 @break
                                                @case('rejected') bg-red-100 text-red-800 @break
                                                @case('completed') bg-blue-100 text-blue-800 @break
                                            @endswitch
                                        ">
                                            @switch($campaign->status)
                                                @case('draft') Draft @break
                                                @case('pending') Menunggu Review @break
                                                @case('approved') Aktif @break
                                                @case('rejected') Ditolak @break
                                                @case('completed') Selesai @break
                                            @endswitch
                                        </span>
                                    </div>

                                    {{-- Progress --}}
                                    <div class="mt-3">
                                        <div class="flex justify-between text-xs text-gray-500 mb-1">
                                            <span>Rp {{ number_format($campaign->collected_amount, 0, ',', '.') }}</span>
                                            <span>Rp {{ number_format($campaign->target_amount, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-1.5">
                                            <div class="bg-green-500 h-1.5 rounded-full" style="width: {{ min(100, $campaign->progress_percentage) }}%"></div>
                                        </div>
                                    </div>

                                    {{-- Actions --}}
                                    <div class="mt-3 flex flex-wrap gap-2">
                                        @if ($campaign->isApproved() || $campaign->isCompleted())
                                            <a href="{{ route('campaigns.show', $campaign->slug) }}" class="text-xs font-medium text-blue-600 hover:text-blue-800">Lihat</a>
                                        @endif

                                        @if ($campaign->isEditable())
                                            <a href="{{ route('campaign.edit', $campaign) }}" class="text-xs font-medium text-gray-600 hover:text-gray-800">Edit</a>
                                        @endif

                                        @if ($campaign->isDraft() || $campaign->isRejected())
                                            <form action="{{ route('campaign.submit', $campaign) }}" method="POST" class="inline"
                                                  onsubmit="return confirm('Ajukan kampanye ini untuk review?')">
                                                @csrf
                                                <button type="submit" class="text-xs font-medium text-green-600 hover:text-green-800">Ajukan Review</button>
                                            </form>
                                        @endif

                                        @if ($campaign->isDeletable())
                                            <form action="{{ route('campaign.destroy', $campaign) }}" method="POST" class="inline"
                                                  onsubmit="return confirm('Yakin ingin menghapus kampanye ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-xs font-medium text-red-600 hover:text-red-800">Hapus</button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-6">
                    {{ $campaigns->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
