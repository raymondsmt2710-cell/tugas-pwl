<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Autopahala' }}</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/favicon.jpeg') }}">
    <link rel="shortcut icon" type="image/jpeg" href="{{ asset('images/favicon.jpeg') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @livewireStyles
</head>
<body class="bg-white text-gray-900 antialiased font-sans">

    {{-- NAVBAR --}}
    <x-navbar />

    {{-- ============================================================
         1. HERO — background slider otomatis, teks di tengah
    ============================================================ --}}
    <section class="relative overflow-hidden bg-gray-950 h-[480px] sm:h-[560px] flex items-center justify-center"
             x-data="{
                 current: 0,
                 total: {{ $banners->count() ?: 1 }},
                 timer: null,
                 start() {
                     this.timer = setInterval(() => {
                         this.current = (this.current + 1) % this.total;
                     }, 5000);
                 },
                 prev() { clearInterval(this.timer); this.current = (this.current - 1 + this.total) % this.total; this.start(); },
                 next() { clearInterval(this.timer); this.current = (this.current + 1) % this.total; this.start(); }
             }"
             x-init="start()">

        @if($banners->count() > 0)
            {{-- Background Images --}}
            @foreach($banners as $i => $banner)
                <div x-show="current === {{ $i }}"
                     x-transition:enter="transition-opacity duration-1000 ease-in-out"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition-opacity duration-1000 ease-in-out"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-100"
                     :class="current === {{ $i }} ? 'z-10' : 'z-0'"
                     class="absolute inset-0">
                    <img src="{{ Str::startsWith($banner->image_path, 'http') ? $banner->image_path : asset('storage/' . $banner->image_path) }}" class="w-full h-full object-cover" alt="Hero {{ $i + 1 }}">
                    <div class="absolute inset-0 bg-gray-950/60"></div>
                </div>
            @endforeach

            {{-- Content --}}
            <div class="relative z-20 w-full py-4">
                <div class="max-w-2xl mx-auto px-4 sm:px-6 text-center">
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold bg-white/10 border border-white/20 text-white mb-5">
                        <i class="fa-solid fa-shield-halved"></i> 100% Aman &amp; Terverifikasi
                    </div>
                    
                    <div class="grid grid-cols-1 grid-rows-1">
                        @foreach($banners as $i => $banner)
                            <div x-show="current === {{ $i }}" 
                                 x-transition:enter="transition-opacity duration-700 delay-200"
                                 x-transition:enter-start="opacity-0"
                                 x-transition:enter-end="opacity-100"
                                 x-transition:leave="transition-opacity duration-200"
                                 x-transition:leave-start="opacity-100"
                                 x-transition:leave-end="opacity-0"
                                 class="col-start-1 row-start-1 transition-all"
                                 @if($i > 0) style="display: none;" @endif>
                                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-white leading-tight tracking-tight">
                                    {{ $banner->title }}
                                </h1>
                                @if($banner->subtitle)
                                    <p class="mt-4 text-base sm:text-lg text-white/75 leading-relaxed max-w-xl mx-auto">
                                        {{ $banner->subtitle }}
                                    </p>
                                @endif
                                <div class="mt-7 flex flex-col sm:flex-row items-center justify-center gap-3">
                                    <a href="{{ url('/campaigns') }}"
                                       class="w-full sm:w-auto px-7 py-3 rounded-full bg-brand-500 text-white font-bold text-sm hover:bg-brand-600 shadow-lg transition">
                                        Jelajahi Kampanye
                                    </a>
                                    @auth
                                        <a href="{{ url('/campaigns/create') }}"
                                           class="w-full sm:w-auto px-7 py-3 rounded-full bg-white/10 border border-white/30 text-white font-bold text-sm hover:bg-white/20 transition">
                                            Buat Kampanye
                                        </a>
                                    @else
                                        <a href="{{ route('register') }}"
                                           class="w-full sm:w-auto px-7 py-3 rounded-full bg-white/10 border border-white/30 text-white font-bold text-sm hover:bg-white/20 transition">
                                            Buat Kampanye
                                        </a>
                                    @endauth
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Navigasi Panah --}}
            @if($banners->count() > 1)
                <button @click="prev()"
                        class="absolute left-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 rounded-full bg-white/10 border border-white/20 text-white flex items-center justify-center hover:bg-white/25 transition backdrop-blur-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
                    </svg>
                </button>
                <button @click="next()"
                        class="absolute right-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 rounded-full bg-white/10 border border-white/20 text-white flex items-center justify-center hover:bg-white/25 transition backdrop-blur-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
                    </svg>
                </button>

                {{-- Dots --}}
                <div class="absolute bottom-5 left-1/2 -translate-x-1/2 z-20 flex items-center gap-2">
                    @for($i = 0; $i < $banners->count(); $i++)
                        <button @click="current = {{ $i }}; clearInterval(timer); start()"
                                :class="current === {{ $i }} ? 'bg-white w-5' : 'bg-white/40 w-2'"
                                class="h-2 rounded-full transition-all duration-300"></button>
                    @endfor
                </div>
            @endif
        @else
            {{-- Fallback Default --}}
            <div class="absolute inset-0 bg-gray-950/80 flex items-center justify-center">
                <div class="max-w-2xl mx-auto px-4 sm:px-6 text-center py-24 sm:py-32">
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-white leading-tight tracking-tight">
                        Harapan baru dimulai dari kepedulian Anda.
                    </h1>
                    <p class="mt-4 text-base sm:text-lg text-white/75 leading-relaxed max-w-xl mx-auto">
                        Bantu sesama melewati masa sulit bersama Autopahala.
                    </p>
                </div>
            </div>
        @endif
    </section>

    {{-- ============================================================
         2. STATS BANNER
    ============================================================ --}}
    <section class="border-y border-gray-100 bg-gray-50 py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6">
            <div class="grid grid-cols-3 gap-4 text-center">
                <div>
                    <p class="text-2xl sm:text-3xl font-black text-gray-900">{{ $totalCampaigns ?? 0 }}</p>
                    <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Kampanye Aktif</p>
                </div>
                <div class="border-x border-gray-200">
                    <p class="text-2xl sm:text-3xl font-black text-gray-900">{{ $totalDonors ?? 0 }}</p>
                    <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Donatur</p>
                </div>
                <div>
                    <p class="text-2xl sm:text-3xl font-black text-brand-500">
                        Rp {{ format_rupiah_short($totalRaised ?? 0) }}
                    </p>
                    <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Dana Tersalurkan</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================
         3. KATEGORI — tengah, tiap kategori ada icon
    ============================================================ --}}
    @php
        $categoryIcons = [
            'medis'        => 'fa-solid fa-heart-pulse',
            'kesehatan'    => 'fa-solid fa-heart-pulse',
            'pendidikan'   => 'fa-solid fa-graduation-cap',
            'bencana'      => 'fa-solid fa-house-flood-water',
            'sosial'       => 'fa-solid fa-hands-holding-child',
            'kemanusiaan'  => 'fa-solid fa-hand-holding-heart',
            'lingkungan'   => 'fa-solid fa-leaf',
            'agama'        => 'fa-solid fa-mosque',
            'hewan'        => 'fa-solid fa-paw',
            'seni'         => 'fa-solid fa-palette',
            'olahraga'     => 'fa-solid fa-futbol',
            'difabel'      => 'fa-solid fa-wheelchair',
            'panti'        => 'fa-solid fa-people-roof',
            'balita'       => 'fa-solid fa-baby',
            'anak sakit'   => 'fa-solid fa-baby',
            'anak-anak'    => 'fa-solid fa-child',
            'umum'         => 'fa-solid fa-circle-dot',
        ];
        $defaultIcon = 'fa-solid fa-tag';
    @endphp

    <section id="categories" class="py-16 bg-white">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 text-center">
            <h2 class="text-2xl sm:text-3xl font-black text-gray-900 mb-2">Temukan Berdasarkan Kategori</h2>
            <p class="text-sm text-gray-500 mb-10">Pilih kategori yang ingin Anda dukung</p>

            <div class="flex flex-wrap justify-center gap-3">
                @foreach($categories as $category)
                    @php
                        $slug = strtolower($category->name);
                        $icon = $defaultIcon;
                        foreach ($categoryIcons as $key => $ic) {
                            if (str_contains($slug, $key)) { $icon = $ic; break; }
                        }
                    @endphp
                    <a href="{{ url('/campaigns?category=' . $category->id_category) }}"
                       class="group flex flex-col items-center gap-2 w-24 sm:w-28 px-3 py-4 bg-gray-50 rounded-2xl border border-gray-100 hover:border-brand-300 hover:bg-brand-50 hover:shadow-sm transition">
                        <div class="w-10 h-10 rounded-xl bg-white border border-gray-100 group-hover:border-brand-200 flex items-center justify-center shadow-sm">
                            <i class="{{ !empty($category->logo) ? $category->logo : $icon }} text-brand-500 text-sm"></i>
                        </div>
                        <span class="text-xs font-semibold text-gray-700 group-hover:text-brand-600 text-center leading-tight">{{ $category->name }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============================================================
         4. KAMPANYE TERBARU — pakai x-campaign-card (sama dgn profile)
    ============================================================ --}}
    <section id="campaigns" class="py-16 bg-gray-50 border-t border-gray-100">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="flex items-end justify-between mb-8">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-black text-gray-900">Kampanye Terbaru</h2>
                    <p class="text-sm text-gray-500 mt-1">Bantu mereka yang membutuhkan</p>
                </div>
                <a href="{{ url('/campaigns') }}" class="text-sm font-semibold text-brand-500 hover:text-brand-700 inline-flex items-center gap-1">
                    Lihat Semua 
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
                    </svg>
                </a>
            </div>
            @livewire('featured-campaigns')
        </div>
    </section>

    {{-- ============================================================
         5. LEADERBOARD — urutan 1 2 3, tombol lihat lengkap
    ============================================================ --}}
    <section class="py-16 bg-white border-t border-gray-100">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="text-center mb-10">
                <h2 class="text-2xl sm:text-3xl font-black text-gray-900">Leaderboard</h2>
                <p class="text-sm text-gray-500 mt-1">Mereka yang paling banyak berkontribusi</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                {{-- Top Donors --}}
                <div class="bg-gray-50 rounded-2xl border border-gray-100 p-5">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <span>🏆</span> Top Donatur
                    </h3>
                    <div class="space-y-2">
                        @forelse($topDonors as $i => $d)
                            @php $medals = ['🥇','🥈','🥉']; @endphp
                            <a href="{{ url('/@' . ($d['username'] ?? '')) }}"
                               class="flex items-center gap-3 p-3 bg-white rounded-xl border border-gray-100 hover:border-brand-200 hover:shadow-sm transition">
                                <span class="text-base w-6 text-center shrink-0">{{ $medals[$i] ?? ($i+1) }}</span>
                                <img src="{{ $d['avatar'] ?? '' }}" class="w-9 h-9 rounded-full object-cover border border-gray-200 shrink-0">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 truncate">{{ $d['user_name'] ?? '-' }}</p>
                                    <p class="text-xs text-gray-400">{{ $d['donation_count'] ?? 0 }} donasi</p>
                                </div>
                                <span class="text-xs font-bold text-brand-500 shrink-0">
                                    Rp {{ format_rupiah_short($d['total_amount'] ?? 0) }}
                                </span>
                            </a>
                        @empty
                            <p class="text-xs text-center text-gray-400 py-6">Belum ada data</p>
                        @endforelse
                    </div>
                </div>

                {{-- Top Campaigns --}}
                <div class="bg-gray-50 rounded-2xl border border-gray-100 p-5">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <span>📈</span> Top Kampanye
                    </h3>
                    <div class="space-y-2">
                        @forelse($topCampaigns as $i => $c)
                            @php $medals = ['🥇','🥈','🥉']; @endphp
                            <a href="{{ url('/campaigns/' . ($c['slug'] ?? '')) }}"
                               class="flex items-center gap-3 p-3 bg-white rounded-xl border border-gray-100 hover:border-brand-200 hover:shadow-sm transition">
                                <span class="text-base w-6 text-center shrink-0">{{ $medals[$i] ?? ($i+1) }}</span>
                                <img src="{{ $c['banner_image'] }}" class="w-9 h-9 rounded-lg object-cover shrink-0">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 truncate">{{ $c['title'] ?? '-' }}</p>
                                    <p class="text-xs text-gray-400">{{ $c['donor_count'] ?? 0 }} donatur</p>
                                </div>
                                <span class="text-xs font-bold text-brand-500 shrink-0">
                                    Rp {{ format_rupiah_short($c['collected_amount'] ?? 0) }}
                                </span>
                            </a>
                        @empty
                            <p class="text-xs text-center text-gray-400 py-6">Belum ada data</p>
                        @endforelse
                    </div>
                </div>

                {{-- Top Creators --}}
                <div class="bg-gray-50 rounded-2xl border border-gray-100 p-5">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <span>⭐</span> Top Penggalang
                    </h3>
                    <div class="space-y-2">
                        @forelse($topCreators as $i => $cr)
                            @php $medals = ['🥇','🥈','🥉']; @endphp
                            <a href="{{ url('/@' . ($cr['username'] ?? '')) }}"
                               class="flex items-center gap-3 p-3 bg-white rounded-xl border border-gray-100 hover:border-brand-200 hover:shadow-sm transition">
                                <span class="text-base w-6 text-center shrink-0">{{ $medals[$i] ?? ($i+1) }}</span>
                                <img src="{{ $cr['avatar'] ?? '' }}" class="w-9 h-9 rounded-full object-cover border border-gray-200 shrink-0">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 truncate">{{ $cr['user_name'] ?? '-' }}</p>
                                    <p class="text-xs text-gray-400">{{ $cr['campaign_count'] ?? 0 }} kampanye</p>
                                </div>
                                <span class="text-xs font-bold text-brand-500 shrink-0">
                                    Rp {{ format_rupiah_short($cr['total_raised'] ?? 0) }}
                                </span>
                            </a>
                        @empty
                            <p class="text-xs text-center text-gray-400 py-6">Belum ada data</p>
                        @endforelse
                    </div>
                </div>

            </div>

            {{-- Tombol Lihat Leaderboard Lengkap --}}
            <div class="mt-8 text-center">
                <a href="{{ url('/leaderboard') }}"
                   class="inline-flex items-center gap-2 px-6 py-3 rounded-xl border border-gray-200 bg-white text-sm font-semibold text-gray-700 hover:bg-gray-50 hover:border-gray-300 transition shadow-sm">
                    <i class="fas fa-trophy text-yellow-500"></i>
                    Lihat Leaderboard Lengkap
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    {{-- ============================================================
         6. CARA KERJA
    ============================================================ --}}
    <section id="cara-kerja" class="py-20 bg-gray-50 border-t border-gray-100">
        <div class="max-w-5xl mx-auto px-4 sm:px-6">
            <div class="text-center mb-14">
                <h2 class="text-2xl sm:text-3xl font-black text-gray-900 mb-2">Cara Kerja</h2>
                <p class="text-sm text-gray-500">Mudah, transparan, dan terpercaya</p>
            </div>

            {{-- Tabs + Content --}}
            <div x-data="{ tab: 'galang' }">

                {{-- Tab Switcher --}}
                <div class="flex justify-center mb-10">
                    <div class="inline-flex bg-white border border-gray-200 rounded-xl p-1 gap-1 shadow-sm">
                        <button @click="tab = 'galang'"
                                :class="tab === 'galang' ? 'bg-brand-500 text-white shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                                class="px-5 py-2 rounded-lg text-sm font-semibold transition-all duration-200">
                            <i class="fa-solid fa-hand-holding-heart mr-1.5"></i>Galang Dana
                        </button>
                        <button @click="tab = 'donasi'"
                                :class="tab === 'donasi' ? 'bg-brand-500 text-white shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                                class="px-5 py-2 rounded-lg text-sm font-semibold transition-all duration-200">
                            <i class="fa-solid fa-coins mr-1.5"></i>Berdonasi
                        </button>
                    </div>
                </div>

                @php
                    $galangSteps = $howItWorks->where('type', 'galang_dana');
                    $donasiSteps = $howItWorks->where('type', 'donasi');
                @endphp

                {{-- Galang Dana Steps --}}
                <div x-show="tab === 'galang'"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     style="display:none;">
                    <div class="relative grid grid-cols-1 md:grid-cols-4 gap-6">
                        @forelse($galangSteps as $step)
                            <div class="relative z-10 flex flex-col items-center text-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-brand-500 text-white flex items-center justify-center text-xs font-black shadow-sm ring-4 ring-white">
                                    {{ $step->step_number }}
                                </div>
                                <div class="w-16 h-16 rounded-2xl {{ $step->color ?: 'bg-brand-50 border-brand-100' }} border flex items-center justify-center shadow-sm">
                                    <i class="{{ $step->icon }} {{ $step->icon_color ?: 'text-brand-500' }} text-2xl"></i>
                                </div>
                                <h3 class="text-sm font-bold text-gray-900">{{ $step->title }}</h3>
                                <p class="text-xs text-gray-500 leading-relaxed max-w-[160px]">{{ $step->description }}</p>
                            </div>
                        @empty
                            <p class="text-xs text-gray-400 py-6 col-span-4 text-center">Belum ada langkah Cara Kerja.</p>
                        @endforelse
                    </div>
                </div>

                {{-- Berdonasi Steps --}}
                <div x-show="tab === 'donasi'"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     style="display:none;">
                    <div class="relative grid grid-cols-1 md:grid-cols-4 gap-6">
                        @forelse($donasiSteps as $step)
                            <div class="relative z-10 flex flex-col items-center text-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-brand-500 text-white flex items-center justify-center text-xs font-black shadow-sm ring-4 ring-white">
                                    {{ $step->step_number }}
                                </div>
                                <div class="w-16 h-16 rounded-2xl {{ $step->color ?: 'bg-brand-50 border-brand-100' }} border flex items-center justify-center shadow-sm">
                                    <i class="{{ $step->icon }} {{ $step->icon_color ?: 'text-brand-500' }} text-2xl"></i>
                                </div>
                                <h3 class="text-sm font-bold text-gray-900">{{ $step->title }}</h3>
                                <p class="text-xs text-gray-500 leading-relaxed max-w-[160px]">{{ $step->description }}</p>
                            </div>
                        @empty
                            <p class="text-xs text-gray-400 py-6 col-span-4 text-center">Belum ada langkah Cara Kerja.</p>
                        @endforelse
                    </div>
                </div>

                {{-- CTA --}}
                <div class="mt-12 text-center">
                    @auth
                        <a :href="tab === 'galang' ? '{{ url('/campaigns/create') }}' : '{{ route('campaigns.index') }}'"
                           class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-brand-500 text-white text-sm font-semibold hover:bg-brand-600 shadow-sm transition">
                            <i class="fa-solid fa-rocket"></i>
                            <span x-text="tab === 'galang' ? 'Mulai Galang Dana Sekarang' : 'Mulai Donasi Sekarang'">Mulai Galang Dana Sekarang</span>
                        </a>
                    @else
                        <a :href="tab === 'galang' ? '{{ route('register') }}' : '{{ route('campaigns.index') }}'"
                           class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-brand-500 text-white text-sm font-semibold hover:bg-brand-600 shadow-sm transition">
                            <i class="fa-solid fa-rocket"></i>
                            <span x-text="tab === 'galang' ? 'Mulai Galang Dana Sekarang' : 'Mulai Donasi Sekarang'">Mulai Galang Dana Sekarang</span>
                        </a>
                    @endauth
                </div>

            </div>{{-- end x-data --}}
        </div>
    </section>

    {{-- ============================================================
         7. FAQ
    ============================================================ --}}
    <section id="faq" class="py-16 bg-white border-t border-gray-100">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 text-center">
            <h2 class="text-2xl sm:text-3xl font-black text-gray-900 mb-2">Pertanyaan Umum</h2>
            <p class="text-sm text-gray-500 mb-10">Jawaban untuk pertanyaan yang sering ditanyakan</p>
        </div>
        <div class="max-w-2xl mx-auto px-4 sm:px-6">
            @livewire('faq-section')
        </div>
    </section>

    {{-- ============================================================
         8. KONTAK
    ============================================================ --}}
    <section id="contact" class="py-16 bg-gray-50 border-t border-gray-100">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-start">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-black text-gray-900 mb-3">Hubungi Kami</h2>
                    <p class="text-sm text-gray-500 mb-6 leading-relaxed">Ada pertanyaan atau masukan? Tim kami siap membantu Anda.</p>
                    <div class="space-y-3">
                        <div class="flex items-center gap-3 text-sm text-gray-600">
                            <div class="w-9 h-9 rounded-lg bg-brand-50 flex items-center justify-center shrink-0">
                                <i class="fas fa-envelope text-brand-500 text-xs"></i>
                            </div>
                            <a href="mailto:{{ $siteSetting->email ?? 'support@autopahala.com' }}" class="hover:text-brand-500 hover:underline transition">
                                {{ $siteSetting->email ?? 'support@autopahala.com' }}
                            </a>
                        </div>
                        @if(!empty($siteSetting->phone))
                            <div class="flex items-center gap-3 text-sm text-gray-600">
                                <div class="w-9 h-9 rounded-lg bg-brand-50 flex items-center justify-center shrink-0">
                                    <i class="fas fa-phone text-brand-500 text-xs"></i>
                                </div>
                                <a href="tel:{{ $siteSetting->phone }}" class="hover:text-brand-500 hover:underline transition">
                                    {{ $siteSetting->phone }}
                                </a>
                            </div>
                        @endif
                        <div class="flex items-center gap-3 text-sm text-gray-600">
                            <div class="w-9 h-9 rounded-lg bg-brand-50 flex items-center justify-center shrink-0">
                                <i class="fas fa-location-dot text-brand-500 text-xs"></i>
                            </div>
                            <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($siteSetting->address ?? 'Medan, Sumatera Utara, Indonesia') }}" target="_blank" class="hover:text-brand-500 hover:underline transition text-left">
                                {{ $siteSetting->address ?? 'Medan, Sumatera Utara, Indonesia' }}
                            </a>
                        </div>
                        @if(!empty($siteSetting->social_media))
                            @foreach($siteSetting->social_media as $social)
                                @php
                                    $iconMap = [
                                        'instagram' => 'fab fa-instagram',
                                        'facebook' => 'fab fa-facebook-f',
                                        'twitter' => 'fab fa-twitter',
                                        'tiktok' => 'fab fa-tiktok',
                                        'youtube' => 'fab fa-youtube',
                                        'linkedin' => 'fab fa-linkedin-in',
                                    ];
                                    $platformIcon = $iconMap[$social['platform']] ?? 'fas fa-link';
                                @endphp
                                <div class="flex items-center gap-3 text-sm text-gray-600">
                                    <div class="w-9 h-9 rounded-lg bg-brand-50 flex items-center justify-center shrink-0">
                                        <i class="{{ $platformIcon }} text-brand-500 text-xs"></i>
                                    </div>
                                    <a href="{{ $social['url'] }}" target="_blank" class="hover:text-brand-500 hover:underline transition text-left">
                                        {{ $social['label'] ?? $social['url'] }}
                                    </a>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    @livewire('contact-form')
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================
         9. CTA
    ============================================================ --}}
    <section class="py-16 bg-brand-500">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 text-center">
            <h2 class="text-2xl sm:text-3xl font-black text-white mb-2">Siap Berbagi Kebaikan?</h2>
            <p class="text-brand-100 text-sm mb-8">Mulai buat kampanye atau berikan donasi hari ini.</p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="{{ url('/campaigns') }}"
                   class="w-full sm:w-auto px-6 py-3 rounded-xl bg-white text-brand-500 font-bold text-sm hover:bg-brand-50 transition">
                    Donasi Sekarang
                </a>
                @auth
                    <a href="{{ url('/campaigns/create') }}"
                       class="w-full sm:w-auto px-6 py-3 rounded-xl border border-brand-400 text-white font-bold text-sm hover:bg-brand-600 transition">
                        Buat Kampanye
                    </a>
                @else
                    <a href="{{ route('register') }}"
                       class="w-full sm:w-auto px-6 py-3 rounded-xl border border-brand-400 text-white font-bold text-sm hover:bg-brand-600 transition">
                        Daftar Gratis
                    </a>
                @endauth
            </div>
        </div>
    </section>

    {{-- ============================================================
         10. FOOTER
    ============================================================ --}}
    <footer class="bg-gray-900 py-12">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="md:col-span-2">
                    <a href="{{ url('/') }}" class="flex items-center gap-2.5 text-lg font-black text-white">
                        <img src="{{ asset('images/logo-icon.jpeg') }}" alt="Logo AutoPahala" class="w-7 h-7 rounded-lg object-cover">
                        <span>Auto<span class="text-brand-400">pahala</span></span>
                    </a>
                    <p class="mt-3 text-sm text-gray-400 max-w-sm leading-relaxed">
                        Platform crowdfunding terpercaya untuk membantu sesama. Setiap kebaikan, sekecil apapun, berarti.
                    </p>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Navigasi</p>
                    <div class="space-y-2">
                        <a href="{{ url('/campaigns') }}" class="block text-sm text-gray-400 hover:text-white transition">Kampanye</a>
                        <a href="{{ url('/leaderboard') }}" class="block text-sm text-gray-400 hover:text-white transition">Leaderboard</a>
                        <a href="#cara-kerja" class="block text-sm text-gray-400 hover:text-white transition">Cara Kerja</a>
                        <a href="#faq" class="block text-sm text-gray-400 hover:text-white transition">FAQ</a>
                    </div>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Akun</p>
                    <div class="space-y-2">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="block text-sm text-gray-400 hover:text-white transition">Dashboard</a>
                            <a href="{{ url('/@' . auth()->user()->username) }}" class="block text-sm text-gray-400 hover:text-white transition">Profil Publik</a>
                            <a href="{{ url('/dashboard?tab=campaigns') }}" class="block text-sm text-gray-400 hover:text-white transition">Kampanye Saya</a>
                            <a href="{{ url('/dashboard?tab=donations') }}" class="block text-sm text-gray-400 hover:text-white transition">Riwayat Donasi</a>
                        @else
                            <a href="{{ route('login') }}" class="block text-sm text-gray-400 hover:text-white transition">Masuk</a>
                            <a href="{{ route('register') }}" class="block text-sm text-gray-400 hover:text-white transition">Daftar</a>
                        @endauth
                    </div>
                </div>
            </div>
            <div class="mt-10 pt-6 border-t border-gray-800 text-center">
                <p class="text-xs text-gray-500">&copy; {{ date('Y') }} Autopahala. All rights reserved.</p>
            </div>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
