<x-app-layout>
    <div class="py-8 sm:py-12">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- Main Content --}}
                <div class="lg:col-span-2 space-y-6">
                    {{-- Image Slider (Banner + Gallery) --}}
                    @php
                        $slides = collect();
                        $slides->push($campaign->banner_image_url);
                        if ($campaign->galleries) {
                            foreach ($campaign->galleries as $g) {
                                $slides->push(asset('storage/' . $g->image_path));
                            }
                        }
                    @endphp

                    @if($slides->count() > 0)
                        <div class="mb-4">
                            <a href="{{ route('home') }}" onclick="event.preventDefault(); window.history.length > 1 ? window.history.back() : window.location.href='{{ route('home') }}';" class="inline-flex items-center gap-1 text-sm font-semibold text-brand-500 hover:text-brand-700 transition">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
                                </svg>
                                Kembali
                            </a>
                        </div>
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
                            <span class="text-xs font-medium text-brand-500 bg-brand-50 px-2.5 py-1 rounded-full">
                                {{ $campaign->category->name ?? 'Umum' }}
                            </span>
                            @if($campaign->isGoalReached() || $campaign->hasReachedGoal())
                                <span class="text-xs font-medium text-green-700 bg-green-100 px-2.5 py-1 rounded-full">
                                    🏆 Goal Reached
                                </span>
                            @endif
                            <span class="text-xs text-gray-400">•</span>
                            <span class="text-xs text-gray-500">{{ $campaign->created_at->format('d M Y') }}</span>
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $campaign->title }}</h1>
                        <p class="mt-2 text-gray-600">{{ $campaign->short_description }}</p>
                    </div>

                    {{-- Description --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6" x-data="{ expanded: false, isLong: false }" x-init="isLong = $refs.descContent.scrollHeight > 200">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Tentang Kampanye Ini</h2>
                        <div class="relative overflow-hidden transition-all duration-300"
                             :class="expanded ? 'max-h-[9999px]' : 'max-h-48'">
                            <div class="prose prose-sm max-w-none text-gray-700" x-ref="descContent">
                                {!! nl2br(e($campaign->description)) !!}
                            </div>
                            
                            {{-- Gradient Overlay --}}
                            <div x-show="isLong && !expanded" 
                                 class="absolute bottom-0 left-0 right-0 h-20 bg-gradient-to-t from-white to-transparent pointer-events-none"></div>
                        </div>
                        
                        {{-- Read More / Less Toggle --}}
                        <div x-show="isLong" class="mt-4 text-center">
                            <button @click="expanded = !expanded" 
                                    class="inline-flex items-center gap-1.5 text-sm font-semibold text-brand-500 hover:text-brand-700 transition">
                                <span x-text="expanded ? 'Lihat Lebih Sedikit' : 'Lihat Selengkapnya'"></span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" 
                                     class="w-4 h-4 transform transition-transform duration-200"
                                     :class="expanded ? 'rotate-180' : ''">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Financial Summary --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Keuangan</h2>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="bg-gray-50 rounded-lg p-3 text-center">
                                <p class="text-xs text-gray-500">Target</p>
                                <p class="text-sm font-bold text-gray-900">Rp {{ number_format($campaign->target_amount, 0, ',', '.') }}</p>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3 text-center">
                                <p class="text-xs text-gray-500">Terkumpul</p>
                                <p class="text-sm font-bold text-brand-500">Rp {{ number_format($campaign->collected_amount, 0, ',', '.') }}</p>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3 text-center">
                                <p class="text-xs text-gray-500">Ditarik</p>
                                <p class="text-sm font-bold text-gray-900">Rp {{ number_format($campaign->withdrawal_amount, 0, ',', '.') }}</p>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3 text-center">
                                <p class="text-xs text-gray-500">Sisa Saldo</p>
                                <p class="text-sm font-bold text-gray-900">Rp {{ number_format($campaign->available_balance, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Withdrawal Detail --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-semibold text-gray-900">Riwayat Penarikan Dana</h2>
                            @if($withdrawals->count() > 0)
                                <span class="text-xs font-medium text-gray-500 bg-gray-100 px-2.5 py-1 rounded-full">
                                    {{ $withdrawals->count() }} penarikan
                                </span>
                            @endif
                        </div>

                        @if($withdrawals->isEmpty())
                            <div class="text-center py-8">
                                <svg class="w-10 h-10 text-gray-200 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75"/>
                                </svg>
                                <p class="text-sm text-gray-400">Belum ada penarikan dana.</p>
                            </div>
                        @else
                            <div class="space-y-3 max-h-[400px] overflow-y-auto pr-1">
                                @foreach($withdrawals as $w)
                                    <div class="flex items-start justify-between p-4 bg-gray-50 rounded-xl border border-gray-100 gap-4">
                                        <div class="flex-1 min-w-0">
                                            {{-- Amount + Status --}}
                                            <div class="flex items-center gap-2 flex-wrap mb-1.5">
                                                <span class="text-sm font-bold text-gray-900">
                                                    Rp {{ number_format($w->amount, 0, ',', '.') }}
                                                </span>
                                                <span class="text-xs font-semibold px-2 py-0.5 rounded-full
                                                    @if($w->status === 'paid') bg-brand-50 text-brand-600
                                                    @elseif($w->status === 'approved') bg-blue-50 text-blue-700
                                                    @else bg-gray-100 text-gray-500 @endif">
                                                    {{ $w->status_label }}
                                                </span>
                                            </div>

                                            {{-- Purpose --}}
                                            @if($w->purpose)
                                                <p class="text-sm text-gray-700 leading-relaxed mb-1">{{ $w->purpose }}</p>
                                            @endif

                                            {{-- Notes --}}
                                            @if($w->notes)
                                                <p class="text-xs text-gray-400 italic">{{ $w->notes }}</p>
                                            @endif

                                            {{-- Date & Proof --}}
                                            <div class="flex items-center justify-between mt-1.5 flex-wrap gap-2">
                                                <p class="text-xs text-gray-400">
                                                    @if($w->paid_at)
                                                        Dibayarkan {{ $w->paid_at->translatedFormat('d M Y') }}
                                                    @else
                                                        Diajukan {{ $w->created_at->translatedFormat('d M Y') }}
                                                    @endif
                                                </p>

                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- Comments Section --}}
                    <livewire:campaign-comments :campaign-id="$campaign->id_campaign" />

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
                                <div class="bg-brand-500 h-3 rounded-full transition-all" style="width: {{ min(100, $campaign->progress_percentage) }}%"></div>
                            </div>
                            <p class="mt-2 text-xs text-gray-500 text-right">{{ number_format($campaign->progress_percentage, 1) }}%</p>
                        </div>

                        {{-- Stats --}}
                        <div class="grid grid-cols-2 gap-3 mb-5">
                            <a href="{{ route('donation.donors', $campaign->slug) }}" class="text-center p-3 bg-gray-50 rounded-xl hover:bg-brand-50 hover:border-brand-100 border border-transparent transition-colors">
                                <p class="text-lg font-bold text-gray-900">{{ $campaign->donor_count }}</p>
                                <p class="text-xs text-gray-500">Donatur <svg class="inline w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg></p>
                            </a>
                            <div class="text-center p-3 bg-gray-50 rounded-xl">
                                <p class="text-lg font-bold text-gray-900">{{ $campaign->days_remaining }}</p>
                                <p class="text-xs text-gray-500">Hari Lagi</p>
                            </div>
                        </div>

                        {{-- Donate Button --}}
                        @if ($campaign->canAcceptDonations())
                            <a href="{{ url('/campaigns/' . $campaign->slug . '/donate') }}"
                               class="block w-full text-center py-3 px-4 rounded-xl bg-brand-500 text-white font-semibold text-sm hover:bg-brand-600 shadow-sm transition">
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
                            @if($campaign->user && !$campaign->user->trashed())
                                <a href="{{ url('/@' . $campaign->user->username) }}" class="flex items-center gap-3 hover:bg-gray-50 -mx-2 px-2 py-1 rounded-lg transition">
                                    <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden">
                                        <img src="{{ $campaign->user->profile_photo_url }}" alt="{{ $campaign->user->full_name }}" class="w-full h-full object-cover">
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 hover:text-brand-500">{{ $campaign->user->full_name }}</p>
                                        <p class="text-xs text-gray-500">Penggalang Dana</p>
                                    </div>
                                </a>
                            @else
                                <div class="flex items-center gap-3 -mx-2 px-2 py-1 rounded-lg">
                                    <div class="w-8 h-8 rounded-full bg-gray-100 border border-gray-200 flex items-center justify-center text-xs text-gray-400 font-bold">?</div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-400">Akun Dihapus</p>
                                        <p class="text-xs text-gray-500">Penggalang Dana</p>
                                    </div>
                                </div>
                            @endif
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
                                       class="block w-full text-center py-2.5 px-4 rounded-xl border-2 border-brand-500 text-brand-500 font-semibold text-sm hover:bg-brand-50 transition">
                                        Tarik Dana
                                    </a>
                                </div>
                            @endif
                        @endauth

                        {{-- Interactions Bar --}}
                        <div class="mt-5 pt-5 border-t border-gray-100 flex items-center justify-between">
                            <livewire:like-campaign-button :campaign="$campaign" />

                            <livewire:report-campaign-modal :campaign="$campaign" />
                        </div>

                        {{-- Share --}}
                        <div class="mt-5 pt-5 border-t border-gray-100" x-data="{ copied: false }">
                            <p class="text-xs font-medium text-gray-500 mb-2">Bagikan Kampanye</p>
                            <div class="flex gap-2">
                                <button @click="navigator.clipboard.writeText(window.location.href); copied = true; setTimeout(() => copied = false, 3000)"
                                        class="flex-1 inline-flex items-center justify-center gap-1.5 py-2.5 px-3 rounded-lg border text-xs font-semibold transition-all duration-300 active:scale-98"
                                        :class="copied ? 'border-brand-300 bg-brand-50/30 text-brand-600' : 'border-gray-200 text-gray-600 hover:bg-gray-50 hover:text-gray-900'">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m9.86-2.06a4.5 4.5 0 0 0-1.242-7.244l-4.5-4.5a4.5 4.5 0 0 0-6.364 6.364L5.25 9.94"/>
                                    </svg>
                                    <span x-text="copied ? 'Link telah disalin' : 'Salin Link'"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
