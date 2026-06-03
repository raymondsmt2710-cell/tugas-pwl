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
                        <div class="mb-4">
                            <a href="{{ route('home') }}" onclick="event.preventDefault(); window.history.length > 1 ? window.history.back() : window.location.href='{{ route('home') }}';" class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-gray-900">← Kembali</a>
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

                                            {{-- Date --}}
                                            <p class="text-xs text-gray-400 mt-1.5">
                                                @if($w->paid_at)
                                                    Dibayarkan {{ $w->paid_at->translatedFormat('d M Y') }}
                                                @else
                                                    Diajukan {{ $w->created_at->translatedFormat('d M Y') }}
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- Comments Section --}}
                    <div id="comments" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                        <div class="flex items-center justify-between border-b border-gray-50 pb-4">
                            <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 20.25c4.97 0 9-3.694 9-8.25s-4.03-8.25-9-8.25S3 7.444 3 12c0 2.104.859 4.023 2.273 5.48.432.447.74 1.04.586 1.641a4.483 4.483 0 0 1-.923 1.785 5.969 5.969 0 0 0 2.247-.522c.627-.24 1.221-.19 1.774.129 1.124.646 2.42.987 3.793.987Z" />
                                </svg>
                                Komentar ({{ count($comments) }})
                            </h2>
                        </div>

                        {{-- Comments List --}}
                        <div class="space-y-4 max-h-[400px] overflow-y-auto pr-1">
                            @if(count($comments) === 0)
                                <div class="text-center py-8 text-gray-500">
                                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.177 48.177 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v5.01Z"/>
                                    </svg>
                                    <p class="text-sm">Belum ada komentar. Jadilah yang pertama berkomentar!</p>
                                </div>
                            @else
                                <div class="space-y-4">
                                    @foreach($comments as $c)
                                        <div class="flex items-start gap-3">
                                            {{-- Profile Photo --}}
                                            <a href="{{ url('/@' . $c->user->username) }}" class="w-9 h-9 rounded-full bg-gray-100 overflow-hidden flex-shrink-0 border border-gray-100 hover:opacity-95 transition">
                                                <img src="{{ $c->user->profile_photo_url }}" alt="{{ $c->user->full_name }}" class="w-full h-full object-cover">
                                            </a>
                                            {{-- Comment Content --}}
                                            <div class="flex-1 text-left">
                                                <div class="bg-gray-50 rounded-2xl px-4 py-3 border border-gray-100/30">
                                                    <div class="flex items-baseline flex-wrap gap-x-1.5">
                                                        <a href="{{ url('/@' . $c->user->username) }}" class="font-bold text-gray-900 text-sm hover:text-brand-500 transition">
                                                            {{ $c->user->username }}
                                                        </a>
                                                        <span class="text-gray-700 text-sm whitespace-pre-wrap leading-relaxed">{{ $c->comment }}</span>
                                                    </div>
                                                </div>
                                                {{-- Comment Date/Time --}}
                                                <div class="flex items-center gap-2 mt-1.5 ml-3 text-[11px] text-gray-400">
                                                    <span>{{ $c->created_at->diffForHumans() }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        {{-- Comment Form --}}
                        <div class="border-t border-gray-100 pt-5">
                            @auth
                                <form action="{{ route('campaigns.comment', $campaign->id_campaign) }}" method="POST" class="flex items-start gap-3">
                                    @csrf
                                    <div class="w-9 h-9 rounded-full bg-gray-100 overflow-hidden flex-shrink-0 border border-gray-100">
                                        <img src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->full_name }}" class="w-full h-full object-cover">
                                    </div>
                                    <div class="flex-1">
                                        <textarea name="comment" rows="2" required placeholder="Tulis komentar utama..." 
                                                  class="w-full rounded-2xl border-gray-200 text-sm focus:border-brand-500 focus:ring-brand-500 placeholder-gray-400 px-4 py-2.5 resize-none transition duration-200"
                                                  onkeydown="if(event.keyCode === 13 && !event.shiftKey) { event.preventDefault(); this.form.submit(); }"></textarea>
                                        <div class="flex justify-between items-center mt-2">
                                            <span class="text-xs text-gray-400">Tekan Enter untuk mengirim</span>
                                            <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-brand-500 hover:bg-brand-600 rounded-xl shadow-sm transition">
                                                Kirim
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            @else
                                <div class="text-center py-4 bg-gray-50 rounded-2xl border border-dashed border-gray-200">
                                    <p class="text-sm text-gray-500">
                                        Silakan <a href="{{ route('login') }}" class="font-semibold text-brand-500 hover:text-brand-700 transition">Login</a> untuk menulis komentar.
                                    </p>
                                </div>
                            @endauth
                        </div>
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
                                <div class="bg-brand-500 h-3 rounded-full transition-all" style="width: {{ min(100, $campaign->progress_percentage) }}%"></div>
                            </div>
                            <p class="mt-2 text-xs text-gray-500 text-right">{{ number_format($campaign->progress_percentage, 1) }}%</p>
                        </div>

                        {{-- Stats --}}
                        <div class="grid grid-cols-2 gap-3 mb-5">
                            <a href="{{ route('donation.donors', $campaign->slug) }}" class="text-center p-3 bg-gray-50 rounded-xl hover:bg-brand-50 hover:border-brand-100 border border-transparent transition-colors">
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
                            <a href="{{ url('/@' . $campaign->user->username) }}" class="flex items-center gap-3 hover:bg-gray-50 -mx-2 px-2 py-1 rounded-lg transition">
                                <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden">
                                    <img src="{{ $campaign->user->profile_photo_url }}" alt="{{ $campaign->user->full_name }}" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 hover:text-brand-500">{{ $campaign->user->full_name }}</p>
                                    <p class="text-xs text-gray-500">Penggalang Dana</p>
                                </div>
                            </a>
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
                        <div class="mt-5 pt-5 border-t border-gray-100 flex items-center justify-between" x-data="{ 
                            liked: {{ $isLiked ? 'true' : 'false' }},
                            likesCount: {{ $likesCount }},
                            isSubmitting: false,
                            showReportModal: false,
                            toggleLike() {
                                if (this.isSubmitting) return;
                                @guest
                                    window.location.href = '{{ route('login') }}';
                                    return;
                                @endguest
                                this.isSubmitting = true;
                                const previousLiked = this.liked;
                                this.liked = !this.liked;
                                this.likesCount += this.liked ? 1 : -1;

                                fetch('{{ route('campaigns.like', $campaign->id_campaign) }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        this.liked = data.liked;
                                        this.likesCount = data.likes_count;
                                    } else {
                                        this.liked = previousLiked;
                                        this.likesCount = previousLiked ? this.likesCount + 1 : this.likesCount - 1;
                                    }
                                })
                                .catch(error => {
                                    this.liked = previousLiked;
                                    this.likesCount = previousLiked ? this.likesCount + 1 : this.likesCount - 1;
                                    console.error('Error:', error);
                                })
                                .finally(() => {
                                    this.isSubmitting = false;
                                });
                            }
                        }">
                            {{-- Like Button --}}
                            <button @click="toggleLike()" class="flex items-center gap-2 group focus:outline-none transition duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     :class="liked ? 'text-red-500 fill-red-500' : 'text-gray-400 fill-transparent hover:text-red-400'"
                                     class="w-6 h-6 transform transition-all active:scale-125 duration-200 stroke-2"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                                </svg>
                                <span class="text-sm font-semibold"
                                      :class="liked ? 'text-red-500' : 'text-gray-500'"
                                      x-text="likesCount + (likesCount === 1 ? ' Like' : ' Likes')"></span>
                            </button>

                            {{-- Report Button --}}
                            <div class="flex items-center gap-4">
                                <button @click="@auth showReportModal = true @else window.location.href = '{{ route('login') }}' @endauth" 
                                        class="flex items-center gap-1.5 text-sm text-gray-400 hover:text-red-600 transition font-medium">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4.5 h-4.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3v1.5M3 21v-6m0 0 2.77-.693a9 9 0 0 1 6.208.682l.108.054a9 9 0 0 0 6.086.71l3.114-.732a48.524 48.524 0 0 1-.005-10.485l-3.11.733a9 9 0 0 1-6.088-.71l-.109-.054a9 9 0 0 0-6.208-.682L3 7.5M3 15V7.5" />
                                    </svg>
                                    <span>Laporkan</span>
                                </button>
                            </div>

                            @auth
                            {{-- Report Modal --}}
                            <div x-show="showReportModal" 
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0"
                                 x-transition:enter-end="opacity-100"
                                 x-transition:leave="transition ease-in duration-200"
                                 x-transition:leave-start="opacity-100"
                                 x-transition:leave-end="opacity-0"
                                 class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4"
                                 @click.self="showReportModal = false"
                                 style="display: none;">
                                
                                <!-- Modal Content -->
                                <div x-show="showReportModal"
                                     x-transition:enter="transition ease-out duration-300 transform"
                                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                     x-transition:leave="transition ease-in duration-200 transform"
                                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                     class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl border border-gray-100 relative text-left font-normal">
                                    
                                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                                        <svg class="w-6 h-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                                        </svg>
                                        Laporkan Kampanye
                                    </h3>
                                    
                                    <form action="{{ route('campaigns.report', $campaign->id_campaign) }}" method="POST">
                                        @csrf
                                        <!-- Reason -->
                                        <div class="mb-4">
                                            <label class="block text-sm font-semibold text-gray-700 mb-2">Alasan Pelaporan <span class="text-red-500">*</span></label>
                                            <div class="space-y-2">
                                                <label class="flex items-center gap-3 p-2.5 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer transition">
                                                    <input type="radio" name="reason" value="Penipuan / Kecurangan" required class="text-brand-500 focus:ring-brand-500">
                                                    <span class="text-sm text-gray-700">Penipuan / Kecurangan</span>
                                                </label>
                                                <label class="flex items-center gap-3 p-2.5 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer transition">
                                                    <input type="radio" name="reason" value="Konten Tidak Layak / SARA" class="text-brand-500 focus:ring-brand-500">
                                                    <span class="text-sm text-gray-700">Konten Tidak Layak / SARA</span>
                                                </label>
                                                <label class="flex items-center gap-3 p-2.5 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer transition">
                                                    <input type="radio" name="reason" value="Spam / Iklan" class="text-brand-500 focus:ring-brand-500">
                                                    <span class="text-sm text-gray-700">Spam / Iklan</span>
                                                </label>
                                                <label class="flex items-center gap-3 p-2.5 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer transition">
                                                    <input type="radio" name="reason" value="Lainnya" class="text-brand-500 focus:ring-brand-500">
                                                    <span class="text-sm text-gray-700">Lainnya</span>
                                                </label>
                                            </div>
                                        </div>
                                        
                                        <!-- Description -->
                                        <div class="mb-5">
                                            <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi Tambahan (Opsional)</label>
                                            <textarea name="description" rows="3" 
                                                      class="w-full rounded-xl border-gray-200 text-sm focus:border-brand-500 focus:ring-brand-500 placeholder-gray-400"
                                                      placeholder="Berikan detail lebih lanjut mengapa Anda melaporkan kampanye ini..."></textarea>
                                        </div>
                                        
                                        <!-- Action Buttons -->
                                        <div class="flex gap-3 justify-end">
                                            <button type="button" @click="showReportModal = false"
                                                    class="px-4 py-2 text-sm font-semibold text-gray-500 hover:text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl transition">
                                                Batal
                                            </button>
                                            <button type="submit"
                                                    class="px-4 py-2 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 rounded-xl shadow-sm transition">
                                                Kirim Laporan
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            @endauth
                        </div>

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
