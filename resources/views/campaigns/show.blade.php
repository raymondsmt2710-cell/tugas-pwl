<x-app-layout>
    <div class="py-8 sm:py-12">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- Main Content --}}
                <div class="lg:col-span-2 space-y-6">
                    {{-- Image Slider (Banner + Gallery) --}}
                    @php
                        $slides = collect();
                        if ($campaign->banner_image) {
                            $slides->push(asset('storage/' . $campaign->banner_image));
                        }
                        if ($campaign->galleries) {
                            foreach ($campaign->galleries as $g) {
                                $slides->push(asset('storage/' . $g->image_path));
                            }
                        }
                    @endphp

                    @if($slides->count() > 0)
                        <div class="rounded-2xl overflow-hidden bg-gray-100 shadow-sm relative" x-data="{ current: 0, total: {{ $slides->count() }} }">
                            {{-- Slides --}}
                            <div class="relative h-64 sm:h-80">
                                @foreach($slides as $index => $src)
                                    <img x-show="current === {{ $index }}"
                                         x-transition:enter="transition ease-out duration-300"
                                         x-transition:enter-start="opacity-0"
                                         x-transition:enter-end="opacity-100"
                                         src="{{ $src }}" alt="Slide {{ $index + 1 }}"
                                         class="absolute inset-0 w-full h-full object-cover">
                                @endforeach
                            </div>

                            {{-- Navigation Arrows --}}
                            @if($slides->count() > 1)
                                <button @click="current = current > 0 ? current - 1 : total - 1"
                                        class="absolute left-3 top-1/2 -translate-y-1/2 w-10 h-10 bg-white/90 backdrop-blur-sm rounded-full flex items-center justify-center text-gray-700 hover:bg-white shadow-md transition">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/></svg>
                                </button>
                                <button @click="current = current < total - 1 ? current + 1 : 0"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 w-10 h-10 bg-white/90 backdrop-blur-sm rounded-full flex items-center justify-center text-gray-700 hover:bg-white shadow-md transition">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
                                </button>

                                {{-- Dots --}}
                                <div class="absolute bottom-3 left-1/2 -translate-x-1/2 flex items-center gap-1.5">
                                    @foreach($slides as $index => $src)
                                        <button @click="current = {{ $index }}"
                                                :class="current === {{ $index }} ? 'bg-white w-6' : 'bg-white/50 w-2'"
                                                class="h-2 rounded-full transition-all duration-200"></button>
                                    @endforeach
                                </div>

                                {{-- Counter --}}
                                <div class="absolute top-3 right-3 bg-black/50 text-white text-xs font-medium px-2.5 py-1 rounded-full backdrop-blur">
                                    <span x-text="current + 1"></span> / {{ $slides->count() }}
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="rounded-2xl overflow-hidden bg-gray-100 shadow-sm h-64 sm:h-80 flex items-center justify-center">
                            <svg class="w-16 h-16 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909"/>
                            </svg>
                        </div>
                    @endif

                    {{-- Title & Meta --}}
                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <span class="text-xs font-medium text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-full">
                                {{ $campaign->category->name ?? 'Umum' }}
                            </span>
                            <span class="text-xs text-gray-400">•</span>
                            <span class="text-xs text-gray-500">{{ $campaign->created_at->format('d M Y') }}</span>
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $campaign->title }}</h1>
                        <p class="mt-2 text-gray-600">{{ $campaign->short_description }}</p>
                    </div>

                    {{-- Description --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Tentang Kampanye Ini</h2>
                        <div class="prose prose-sm max-w-none text-gray-700">
                            {!! nl2br(e($campaign->description)) !!}
                        </div>
                    </div>

                    {{-- Video --}}
                    @if ($campaign->video_url)
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                            <h2 class="text-lg font-semibold text-gray-900 mb-3">Video</h2>
                            <a href="{{ $campaign->video_url }}" target="_blank" class="inline-flex items-center gap-2 text-sm text-indigo-600 hover:text-indigo-800">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m15.75 10.5 4.72-4.72a.75.75 0 0 1 1.28.53v11.38a.75.75 0 0 1-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25h-9A2.25 2.25 0 0 0 2.25 7.5v9a2.25 2.25 0 0 0 2.25 2.25Z"/>
                                </svg>
                                Tonton Video Kampanye
                            </a>
                        </div>
                    @endif

                    {{-- Recent Donations --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Donasi Terbaru</h2>
                        @if ($donations->isEmpty())
                            <p class="text-sm text-gray-500">Belum ada donasi. Jadilah yang pertama!</p>
                        @else
                            <div class="space-y-3">
                                @foreach ($donations as $donation)
                                    <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $donation->display_name }}</p>
                                            <p class="text-xs text-gray-500">{{ $donation->created_at->diffForHumans() }}</p>
                                        </div>
                                        <span class="text-sm font-semibold text-green-600">
                                            Rp {{ number_format($donation->donation_amount ?? 0, 0, ',', '.') }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Sidebar --}}
                <div class="space-y-6">
                    {{-- Donation Card --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-6">
                        {{-- Progress --}}
                        <div class="mb-5">
                            <div class="flex items-baseline justify-between mb-2">
                                <span class="text-2xl font-bold text-gray-900">Rp {{ number_format($campaign->collected_amount, 0, ',', '.') }}</span>
                            </div>
                            <p class="text-sm text-gray-500 mb-3">terkumpul dari target Rp {{ number_format($campaign->target_amount, 0, ',', '.') }}</p>
                            <div class="w-full bg-gray-100 rounded-full h-3">
                                <div class="bg-green-500 h-3 rounded-full transition-all" style="width: {{ min(100, $campaign->progress_percentage) }}%"></div>
                            </div>
                            <p class="mt-2 text-xs text-gray-500 text-right">{{ number_format($campaign->progress_percentage, 1) }}%</p>
                        </div>

                        {{-- Stats --}}
                        <div class="grid grid-cols-2 gap-3 mb-5">
                            <a href="{{ route('donation.donors', $campaign->slug) }}" class="text-center p-3 bg-gray-50 rounded-xl hover:bg-indigo-50 hover:border-indigo-100 border border-transparent transition-colors">
                                <p class="text-lg font-bold text-gray-900">{{ $campaign->donor_count }}</p>
                                <p class="text-xs text-gray-500">Donatur →</p>
                            </a>
                            <div class="text-center p-3 bg-gray-50 rounded-xl">
                                <p class="text-lg font-bold text-gray-900">{{ $campaign->days_remaining }}</p>
                                <p class="text-xs text-gray-500">Hari Lagi</p>
                            </div>
                        </div>

                        {{-- Donate Button --}}
                        @if ($campaign->canAcceptDonations())
                            <a href="{{ url('/campaigns/' . $campaign->slug . '/donate') }}"
                               class="block w-full text-center py-3 px-4 rounded-xl bg-indigo-600 text-white font-semibold text-sm hover:bg-indigo-700 shadow-sm transition">
                                Donasi Sekarang
                            </a>
                        @elseif ($campaign->isCompleted())
                            <div class="text-center py-3 px-4 rounded-xl bg-gray-100 text-gray-500 font-medium text-sm">
                                Kampanye Telah Selesai
                            </div>
                        @else
                            <div class="text-center py-3 px-4 rounded-xl bg-gray-100 text-gray-500 font-medium text-sm">
                                Kampanye Tidak Aktif
                            </div>
                        @endif

                        {{-- Campaign Info --}}
                        <div class="mt-5 pt-5 border-t border-gray-100 space-y-3">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden">
                                    <img src="{{ $campaign->user->profile_photo_url }}" alt="{{ $campaign->user->full_name }}" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $campaign->user->full_name }}</p>
                                    <p class="text-xs text-gray-500">Penggalang Dana</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 text-xs text-gray-500">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/>
                                </svg>
                                <span>Berakhir {{ $campaign->end_date->format('d M Y') }}</span>
                            </div>
                        </div>

                        {{-- Owner: Withdrawal --}}
                        @auth
                            @if (auth()->user()->id_user === $campaign->id_user && $campaign->available_balance > 0)
                                <div class="mt-5 pt-5 border-t border-gray-100">
                                    <p class="text-xs font-medium text-gray-500 mb-2">Saldo Tersedia</p>
                                    <p class="text-lg font-bold text-gray-900 mb-3">Rp {{ number_format($campaign->available_balance, 0, ',', '.') }}</p>
                                    <a href="{{ route('withdrawals.create', ['campaign' => $campaign->id_campaign]) }}"
                                       class="block w-full text-center py-2.5 px-4 rounded-xl border-2 border-indigo-600 text-indigo-600 font-semibold text-sm hover:bg-indigo-50 transition">
                                        Tarik Dana
                                    </a>
                                </div>
                            @endif
                        @endauth

                        {{-- Share --}}
                        <div class="mt-5 pt-5 border-t border-gray-100">
                            <p class="text-xs font-medium text-gray-500 mb-2">Bagikan Kampanye</p>
                            <div class="flex gap-2">
                                <button onclick="navigator.clipboard.writeText(window.location.href); alert('Link disalin!')"
                                        class="flex-1 inline-flex items-center justify-center gap-1.5 py-2 px-3 rounded-lg border border-gray-200 text-xs font-medium text-gray-600 hover:bg-gray-50 transition">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m9.86-2.06a4.5 4.5 0 0 0-1.242-7.244l-4.5-4.5a4.5 4.5 0 0 0-6.364 6.364L5.25 9.94"/>
                                    </svg>
                                    Salin Link
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
