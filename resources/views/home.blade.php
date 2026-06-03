<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Autopahala' }}</title>
    
    <!-- Memanggil Tailwind CSS bawaan project -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- FontAwesome untuk Ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @livewireStyles
</head>
<body class="bg-slate-50/50 text-gray-900 antialiased font-sans">

    {{-- 1. NAVBAR --}}
    <x-navbar />

    {{-- 2. HERO SECTION (GoFundMe Style dengan warna tema #20BDC4) --}}
    <section class="py-12 md:py-20 bg-white">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 grid grid-cols-1 md:grid-cols-12 gap-12 items-center">
            
            {{-- Teks Hero (Kiri) --}}
            <div class="md:col-span-7 space-y-6">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold border" style="background-color: rgba(32, 189, 196, 0.1); color: #20BDC4; border-color: rgba(32, 189, 196, 0.2);">
                    <i class="fa-solid fa-shield-halved"></i> 100% Aman & Terverifikasi
                </div>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-gray-950 tracking-tight leading-none">
                    Harapan baru dimulai dari kepedulian Anda.
                </h1>
                <p class="text-lg sm:text-xl text-gray-600 font-normal leading-relaxed max-w-2xl">
                    Bantu sesama melewati masa sulit. Mulai penggalangan dana medis, pendidikan, bencana alam, atau bantu wujudkan impian sosial di sekitar Anda secara transparan dan mudah bersama Autopahala.
                </p>
                <div class="pt-2 flex flex-col sm:flex-row gap-4">
                    @auth
                        <a href="{{ url('/campaigns/create') }}" class="inline-block w-full sm:w-auto px-8 py-4 rounded-full text-white font-extrabold text-base text-center transition duration-200 hover:opacity-90 shadow-lg" style="background-color: #20BDC4; shadow-color: rgba(32, 189, 196, 0.3);">
                            Mulai Galang Dana
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="inline-block w-full sm:w-auto px-8 py-4 rounded-full text-white font-extrabold text-base text-center transition duration-200 hover:opacity-90 shadow-lg" style="background-color: #20BDC4; shadow-color: rgba(32, 189, 196, 0.3);">
                            Mulai Galang Dana
                        </a>
                    @endauth
                    <a href="#cara-kerja" class="inline-block w-full sm:w-auto px-8 py-4 rounded-full bg-white text-gray-700 font-extrabold text-base text-center border border-gray-200 hover:bg-gray-50 transition duration-200">
                        Cara Kerja
                    </a>
                </div>
            </div>

            {{-- Media Hero / Kisah Utama (Kanan) --}}
            <div class="md:col-span-5">
                <div class="relative rounded-3xl overflow-hidden bg-white border border-gray-100 shadow-xl aspect-[4/3] group transition-all duration-300 hover:translate-y-[-4px]">
                    <img src="https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?auto=format&fit=crop&q=80&w=800" alt="Hero Campaign" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-gray-950/90 via-gray-950/20 to-transparent flex flex-col justify-end p-6 sm:p-8">
                        <span class="px-3 py-1 text-white rounded-full text-[10px] font-bold self-start mb-3 shadow-sm" style="background-color: #20BDC4;">
                            Kisah Utama Terpopuler ⚡
                        </span>
                        <h3 class="text-xl sm:text-2xl font-black text-white leading-tight mb-2">
                            Bantu Renovasi Rumah Belajar Anak Pesisir
                        </h3>
                        <div class="w-full bg-white/30 h-2 rounded-full overflow-hidden mt-2">
                            <div class="h-2 rounded-full w-3/4 animate-pulse" style="background-color: #20BDC4;"></div>
                        </div>
                        <div class="flex justify-between text-xs text-gray-200 font-bold mt-2">
                            <span>75% Terkumpul</span>
                            <span class="font-black" style="color: #20BDC4;">Rp 45.000.000</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    {{-- 3. TRUST & STATS BANNER (Dinamis mengambil data database) --}}
    <section class="border-y border-gray-200 bg-white py-8">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-4 text-center md:text-left">
                <div class="p-3 rounded-full hidden sm:block" style="background-color: rgba(32, 189, 196, 0.1); color: #20BDC4;">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z"/>
                    </svg>
                </div>
                <div>
                    <h4 class="text-sm font-black text-gray-950">Garansi Keamanan Autopahala</h4>
                    <p class="text-xs text-gray-500">Platform terverifikasi demi memastikan donasi Anda sampai ke tangan yang tepat.</p>
                </div>
            </div>
            <div class="flex gap-8 sm:gap-16 text-center">
                <div>
                    <p class="text-2xl font-black text-gray-950">{{ $totalCampaigns ?? 0 }}</p>
                    <p class="text-xs text-gray-500 font-bold">Kampanye Aktif</p>
                </div>
                <div class="border-l border-gray-200 pl-8 sm:pl-16">
                    <p class="text-2xl font-black text-gray-950">{{ $totalDonors ?? 0 }}</p>
                    <p class="text-xs text-gray-500 font-bold">Donatur Terdaftar</p>
                </div>
                <div class="border-l border-gray-200 pl-8 sm:pl-16">
                    <p class="text-2xl font-black" style="color: #20BDC4;">Rp {{ number_format($totalRaised ?? 0, 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-500 font-bold">Dana Tersalurkan</p>
                </div>
            </div>
        </div>
    </section>

    {{-- 4. DISCOVER CATEGORIES --}}
    <section id="categories" class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <h2 class="text-xl font-extrabold text-gray-950 mb-6">Cari penggalangan dana berdasarkan kategori</h2>
            <div class="flex gap-3 overflow-x-auto pb-3 snap-x no-scrollbar">
                @if(isset($categories) && count($categories) > 0)
                    @foreach($categories as $category)
                        <a href="{{ url('/campaigns?category=' . ($category->id_category ?? $category->id)) }}" 
                           class="snap-start shrink-0 px-6 py-3 bg-gray-50 rounded-full text-sm font-bold text-gray-700 border border-gray-200 transition-all duration-200 hover:text-white"
                           onmouseover="this.style.backgroundColor='#20BDC4'; this.style.borderColor='#20BDC4'; this.style.color='#ffffff';"
                           onmouseout="this.style.backgroundColor=''; this.style.borderColor=''; this.style.color='';">
                            {{ $category->name }}
                        </a>
                    @endforeach
                @else
                    @foreach(['Medis', 'Pendidikan', 'Bencana Alam', 'Panti Asuhan', 'Kemanusiaan'] as $cat)
                        <a href="#" class="snap-start shrink-0 px-6 py-3 bg-gray-50 rounded-full text-sm font-bold text-gray-400 border border-gray-200 cursor-not-allowed">
                            {{ $cat }} (Statis)
                        </a>
                    @endforeach
                @endif
            </div>
        </div>
    </section>

    {{-- 5. KAMPANYE TERPOPULER (GoFundMe Card Style) --}}
    <section id="campaigns" class="py-16 bg-slate-50/50 border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex items-baseline justify-between mb-10">
                <div>
                    <h2 class="text-2xl md:text-3xl font-black text-gray-950 tracking-tight">Kampanye Terpopuler</h2>
                    <p class="text-sm text-gray-500 mt-1">Bantu kampanye mendesak yang membutuhkan kontribusi Anda saat ini.</p>
                </div>
                <a href="{{ url('/campaigns') }}" class="text-sm font-bold hover:opacity-80 underline underline-offset-4" style="color: #20BDC4;">Lihat semua</a>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @if(isset($topCampaigns) && count($topCampaigns) > 0)
                    @foreach($topCampaigns as $item)
                        @php 
                            $campaign = is_array($item) ? (object) $item : $item; 
                            $percentage = ($campaign->target_amount ?? 0) > 0 ? min(round((($campaign->current_amount ?? 0) / $campaign->target_amount) * 100), 100) : 0;
                        @endphp
                        
                        <div class="bg-white rounded-3xl overflow-hidden border border-gray-200 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col h-full group">
                            <div class="aspect-[16/10] overflow-hidden bg-gray-100 relative">
                                <img src="{{ !empty($campaign->banner_image) ? asset('storage/' . str_replace('public/', '', $campaign->banner_image)) : 'https://images.unsplash.com/photo-1542810634-71277d95dcbb?auto=format&fit=crop&q=80&w=600' }}" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition duration-500" 
                                     alt="{{ $campaign->title ?? 'Campaign' }}">
                            </div>
                            <div class="p-6 flex flex-col flex-1 font-sans">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="text-[10px] font-extrabold uppercase tracking-wide px-2.5 py-1 rounded-full" style="background-color: rgba(32, 189, 196, 0.1); color: #20BDC4;">
                                        {{ $campaign->location ?? 'Indonesia' }}
                                    </span>
                                </div>
                                <h3 class="text-base font-black text-gray-950 mt-1 line-clamp-2 transition-colors duration-200 cursor-pointer"
                                    onmouseover="this.style.color='#20BDC4';"
                                    onmouseout="this.style.color='';"
                                    onclick="window.location='{{ url('/campaigns/' . $campaign->id) }}'">
                                    {{ $campaign->title ?? '' }}
                                </h3>
                                <p class="text-xs text-gray-500 mt-2 line-clamp-2 leading-relaxed font-normal">{{ $campaign->description ?? 'Tidak ada deskripsi.' }}</p>
                                
                                <div class="mt-auto pt-6">
                                    <div class="w-full bg-gray-100 h-2 rounded-full overflow-hidden">
                                        <div class="h-2 rounded-full transition-all duration-500" style="width: {{ $percentage }}%; background-color: #20BDC4;"></div>
                                    </div>
                                    <div class="flex justify-between items-center mt-3 text-xs">
                                        <span class="text-gray-950 font-black">
                                            Rp {{ number_format($campaign->current_amount ?? 0, 0, ',', '.') }} 
                                            <span class="text-gray-500 font-normal">terkumpul</span>
                                        </span>
                                        <span class="font-extrabold" style="color: #20BDC4;">{{ $percentage }}%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                                @else
                    @foreach([
                        ['title' => 'Solidaritas Pangan: Berbagi Paket Makanan Lansia', 'img' => 'https://images.unsplash.com/photo-1542810634-71277d95dcbb?auto=format&fit=crop&q=80&w=600', 'pct' => '60%', 'location' => 'Jakarta, DKI', 'raised' => 'Rp 15.000.000', 'target' => 'Rp 25.000.000'],
                        ['title' => 'Beasiswa Pendidikan Anak Yatim Berprestasi', 'img' => 'https://images.unsplash.com/photo-1509062522246-3755977927d7?auto=format&fit=crop&q=80&w=600', 'pct' => '40%', 'location' => 'Sleman, DIY', 'raised' => 'Rp 12.000.000', 'target' => 'Rp 30.000.000'],
                        ['title' => 'Bantuan Darurat Korban Kebakaran Pemukiman', 'img' => 'https://images.unsplash.com/photo-1532629345422-7515f3d16bb6?auto=format&fit=crop&q=80&w=600', 'pct' => '90%', 'location' => 'Medan, Sumut', 'raised' => 'Rp 45.000.000', 'target' => 'Rp 50.000.000']
                    ] as $dummy)
                        <div class="bg-white rounded-3xl overflow-hidden border border-gray-200 shadow-sm hover:shadow-xl hover:translate-y-[-6px] transition-all duration-300 flex flex-col h-full group cursor-pointer"
                             onclick="window.location='{{ url('/campaigns') }}'">
                            <div class="aspect-[16/10] overflow-hidden bg-gray-100 relative">
                                <img src="{{ $dummy['img'] }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                <div class="absolute top-3 left-3 bg-[#20BDC4] text-white text-[10px] font-black px-2.5 py-1 rounded-full shadow-sm">
                                    Simulasi ⚡
                                </div>
                            </div>
                            <div class="p-6 flex flex-col flex-1 font-sans">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="text-[10px] font-extrabold uppercase tracking-wide px-2 py-1 rounded-full" style="background-color: rgba(32, 189, 196, 0.1); color: #20BDC4;">
                                        {{ $dummy['location'] }}
                                    </span>
                                </div>
                                <h3 class="text-base font-black text-gray-950 mt-1 line-clamp-2 group-hover:text-[#20BDC4] transition-colors duration-200">
                                    {{ $dummy['title'] }}
                                </h3>
                                <p class="text-xs text-gray-500 mt-2 line-clamp-2 leading-relaxed font-normal">Ketuk untuk menjelajahi kampanye kemanusiaan aktif ini.</p>
                                <div class="mt-auto pt-6">
                                    <div class="w-full bg-gray-100 h-2 rounded-full overflow-hidden">
                                        <div class="h-2 rounded-full transition-all duration-500" style="width: {{ $dummy['pct'] }}; background-color: #20BDC4;"></div>
                                    </div>
                                    <div class="flex justify-between items-center mt-3 text-xs">
                                        <span class="text-gray-950 font-black">
                                            {{ $dummy['raised'] }} 
                                            <span class="text-gray-500 font-normal">terkumpul</span>
                                        </span>
                                        <span class="font-extrabold" style="color: #20BDC4;">{{ $dummy['pct'] }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </section>

    {{-- 6. LEADERBOARD / INSPIRASI PENGGERAK (Dinamis dari Database) --}}
    <section class="py-16 bg-white border-y border-gray-200">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="mb-10 text-center md:text-left">
                <h2 class="text-2xl md:text-3xl font-black text-gray-950 tracking-tight">Inspirasi Penggerak Kebaikan</h2>
                <p class="text-sm text-gray-500 mt-1">Apresiasi khusus bagi komunitas, donatur, dan kreator teratas.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                {{-- TOP DONORS --}}
                <div class="bg-slate-50/50 p-6 sm:p-8 rounded-3xl border border-gray-200 shadow-sm">
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-wider mb-6 flex items-center gap-2">
                        <span>🏆</span> Top Donatur
                    </h3>
                    <div class="space-y-4">
                        @if(isset($topDonors) && count($topDonors) > 0)
                            @foreach($topDonors as $index => $donor)
                                @php $d = is_array($donor) ? (object) $donor : $donor; @endphp
                                <div class="flex items-center gap-3 p-3 bg-white rounded-2xl border border-gray-100 hover:shadow-md transition">
                                    <span class="w-6 text-sm font-bold text-center">
                                        {{ $index == 0 ? '🥇' : ($index == 1 ? '🥈' : '🥉') }}
                                    </span>
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($d->donor_name ?? 'Donatur') }}&background=20BDC4&color=fff" class="w-10 h-10 rounded-full object-cover">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-bold text-gray-900 truncate">{{ $d->donor_name ?? 'Hamba Allah' }}</p>
                                        <p class="text-[11px] text-gray-500">{{ $d->total_donations ?? 1 }} Kali Donasi</p>
                                    </div>
                                    <span class="text-sm font-black" style="color: #20BDC4;">Rp {{ number_format($d->total_amount ?? 0, 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-8">
                                <i class="fa-solid fa-users text-gray-300 text-3xl mb-2"></i>
                                <p class="text-xs text-gray-400">Belum ada data donatur di database.</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- TOP CAMPAIGNS --}}
                <div class="bg-slate-50/50 p-6 sm:p-8 rounded-3xl border border-gray-200 shadow-sm">
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-wider mb-6 flex items-center gap-2">
                        <span>📈</span> Top Kampanye
                    </h3>
                    <div class="space-y-4">
                        @if(isset($topCampaigns) && count($topCampaigns) > 0)
                            @foreach($topCampaigns as $index => $camp)
                                @php $c = is_array($camp) ? (object) $camp : $camp; @endphp
                                <div class="flex items-center gap-3 p-3 bg-white rounded-2xl border border-gray-100 hover:shadow-md transition">
                                    <span class="w-6 text-sm font-bold text-center">
                                        {{ $index == 0 ? '🥇' : ($index == 1 ? '🥈' : '🥉') }}
                                    </span>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-bold text-gray-900 truncate">{{ $c->title ?? 'Judul Kampanye' }}</p>
                                        <p class="text-[11px] text-gray-500">Target: Rp {{ number_format($c->target_amount ?? 0, 0, ',', '.') }}</p>
                                    </div>
                                    <span class="text-sm font-black" style="color: #20BDC4;">Rp {{ number_format($c->current_amount ?? 0, 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-8">
                                <i class="fa-solid fa-chart-line text-gray-300 text-3xl mb-2"></i>
                                <p class="text-xs text-gray-400">Belum ada kampanye terpopuler.</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- TOP CREATORS --}}
                <div class="bg-slate-50/50 p-6 sm:p-8 rounded-3xl border border-gray-200 shadow-sm">
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-wider mb-6 flex items-center gap-2">
                        <span>⭐</span> Top Penggalang
                    </h3>
                    <div class="space-y-4">
                        @if(isset($topCreators) && count($topCreators) > 0)
                            @foreach($topCreators as $index => $creator)
                                @php $cr = is_array($creator) ? (object) $creator : $creator; @endphp
                                <div class="flex items-center gap-3 p-3 bg-white rounded-2xl border border-gray-100 hover:shadow-md transition">
                                    <span class="w-6 text-sm font-bold text-center">
                                        {{ $index == 0 ? '🥇' : ($index == 1 ? '🥈' : '🥉') }}
                                    </span>
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($cr->name ?? 'User') }}&background=20BDC4&color=fff" class="w-10 h-10 rounded-full object-cover">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-bold text-gray-900 truncate">{{ $cr->name ?? 'Penggalang Dana' }}</p>
                                        <p class="text-[11px] text-gray-500 font-semibold text-gray-400">Aktif Berbagi Kebaikan</p>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-8">
                                <i class="fa-solid fa-hand-holding-heart text-gray-300 text-3xl mb-2"></i>
                                <p class="text-xs text-gray-400">Belum ada data pembuat kampanye.</p>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- 7. HOW IT WORKS (3 Langkah Kunci GoFundMe) --}}
    <section class="py-20 bg-white" id="cara-kerja">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <h2 class="text-3xl font-black text-gray-950 text-center mb-16">Galang dana di Autopahala itu mudah</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <div class="space-y-4 group">
                    <div class="font-black text-6xl transition duration-300 text-gray-200 group-hover:text-[#20BDC4]"
                         onmouseover="this.style.color='#20BDC4';"
                         onmouseout="this.style.color='';">1</div>
                    <h3 class="text-xl font-extrabold text-gray-950">Mulai dengan dasar-dasar</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">Atur judul kampanye Anda, tentukan target dana, dan ceritakan kisah haru atau rencana baik Anda kepada dunia.</p>
                </div>
                <div class="space-y-4 group">
                    <div class="font-black text-6xl transition duration-300 text-gray-200 group-hover:text-[#20BDC4]"
                         onmouseover="this.style.color='#20BDC4';"
                         onmouseout="this.style.color='';">2</div>
                    <h3 class="text-xl font-extrabold text-gray-950">Bagikan ke kerabat</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">Kirimkan link kampanye Anda lewat pesan singkat, email, atau bagikan langsung di feed media sosial Anda untuk menjangkau donatur.</p>
                </div>
                <div class="space-y-4 group">
                    <div class="font-black text-6xl transition duration-300 text-gray-200 group-hover:text-[#20BDC4]"
                         onmouseover="this.style.color='#20BDC4';"
                         onmouseout="this.style.color='';">3</div>
                    <h3 class="text-xl font-extrabold text-gray-950">Terima penarikan dana</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">Setiap rupiah yang masuk bisa Anda pantau secara transparan dan dicairkan berkala langsung ke rekening bank yang dituju.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- 8. FAQ SECTION (Mendukung Livewire kelompok Anda) --}}
    <section class="py-16 bg-slate-50/50 border-t border-gray-100">
        <div class="max-w-4xl mx-auto px-6">
            <h2 class="text-2xl md:text-3xl font-black text-gray-950 text-center mb-10">Pertanyaan Umum</h2>
            @livewire('faq-section')
        </div>
    </section>

    {{-- 9. CONTACT FORM (Mendukung Livewire kelompok Anda) --}}
    <section id="contact" class="py-20 bg-white border-t border-gray-200">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 grid grid-cols-1 md:grid-cols-12 gap-12 items-center">
            <div class="md:col-span-5 space-y-6">
                <h2 class="text-3xl md:text-4xl font-black text-gray-950 tracking-tight leading-none">Butuh bantuan tim kami?</h2>
                <p class="text-base text-gray-600 leading-relaxed">Jika Anda mengalami kendala saat verifikasi identitas atau proses pencairan dana kampanye, tim support kami siap melayani Anda.</p>
                <p class="text-base font-bold flex items-center gap-2" style="color: #20BDC4;">
                    <i class="fa-solid fa-envelope"></i>
                    support@autopahala.com
                </p>
            </div>
            <div class="md:col-span-7 bg-slate-50/50 rounded-3xl border border-gray-200 p-6 sm:p-10 shadow-sm">
                @livewire('contact-form')
            </div>
        </div>
    </section>

    {{-- 10. FOOTER --}}
    <footer class="bg-gray-950 text-white pt-20 pb-10 border-t border-gray-900">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 grid grid-cols-1 md:grid-cols-12 gap-12 mb-16">
            <div class="md:col-span-6 space-y-4">
                <span class="text-2xl font-black tracking-tight">Auto<span style="color: #20BDC4;">pahala</span></span>
                <p class="text-sm text-gray-400 max-w-sm leading-relaxed">Platform urunan dana modern yang aman, transparan, dan berdedikasi membantu jutaan impian sosial serta kemanusiaan.</p>
            </div>
            <div class="md:col-span-3">
                <h4 class="text-xs font-black uppercase tracking-wider text-gray-400 mb-6">Navigasi Utama</h4>
                <div class="space-y-3 text-sm font-bold">
                    <a href="{{ url('/campaigns') }}" class="block text-gray-300 hover:text-white transition">Jelajahi Kampanye</a>
                    <a href="#categories" class="block text-gray-300 hover:text-white transition">Kategori Pilihan</a>
                </div>
            </div>
            <div class="md:col-span-3">
                <h4 class="text-xs font-black uppercase tracking-wider text-gray-400 mb-6">Akses Pengguna</h4>
                <div class="space-y-3 text-sm font-bold">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="block text-gray-300 hover:text-white transition">Dashboard Saya</a>
                    @else
                        <a href="{{ route('login') }}" class="block text-gray-300 hover:text-white transition">Masuk Akun</a>
                        <a href="{{ route('register') }}" class="block text-gray-300 hover:text-white transition">Daftar Baru</a>
                    @endauth
                </div>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-6 lg:px-8 pt-8 border-t border-gray-900 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-gray-500 font-semibold">
            <p>&copy; {{ date('Y') }} Autopahala. All rights reserved.</p>
            <div class="flex gap-6">
                <a href="#" class="hover:text-gray-400 transition">Syarat & Ketentuan</a>
                <a href="#" class="hover:text-gray-400 transition">Kebijakan Privasi</a>
            </div>
        </div>
    </footer>

    @livewireScripts
</body>
</html>