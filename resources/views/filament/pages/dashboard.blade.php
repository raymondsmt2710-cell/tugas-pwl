<x-filament-panels::page>
    <div class="w-full flex flex-col gap-6 text-slate-800 select-none">
        {{-- Internal styling to clean up header and define custom card styling --}}
        <style>
            .fi-page-header {
                display: none !important;
            }
            
            /* Custom Dashboard Non-JIT styling classes */
            .dash-accent-text {
                color: #22C7C9 !important;
            }
            .dash-accent-bg {
                background-color: #22C7C9 !important;
            }
            .dash-badge-teal {
                background-color: rgba(34, 199, 201, 0.08) !important;
                color: #22C7C9 !important;
            }
            .dash-chart-card {
                min-height: 380px !important;
            }
        </style>

        {{-- TOP SECTION: Welcome header, Date, Profile, Notifications --}}
        <div class="flex items-center justify-between bg-white px-5 py-4 rounded-xl border border-slate-100 shadow-sm shrink-0">
            <div>
                <h1 class="text-lg font-bold text-slate-900 tracking-tight flex items-center gap-2">
                    <span>Selamat datang kembali,</span>
                    <span class="dash-accent-text">{{ auth()->user()->full_name ?? 'Admin' }}</span>
                </h1>
                <p class="text-xs text-slate-500 font-medium mt-0.5">Berikut ringkasan performa platform Autopahala hari ini.</p>
            </div>
            
            <div class="flex items-center gap-4">
                {{-- Current Date --}}
                <div class="hidden md:flex items-center gap-2 text-xs font-semibold text-slate-600 bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-100">
                    <i class="fa-regular fa-calendar-days text-slate-400"></i>
                    <span>{{ now()->translatedFormat('l, d F Y') }}</span>
                </div>

                {{-- Profile badge --}}
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full dash-accent-bg text-white flex items-center justify-center font-bold text-sm shadow-sm border border-[#1bb1b3]">
                        {{ strtoupper(substr(auth()->user()->full_name ?? 'A', 0, 2)) }}
                    </div>
                    <div class="hidden sm:block text-left">
                        <p class="text-xs font-bold text-slate-900 leading-tight">{{ auth()->user()->full_name ?? 'Admin Autopahala' }}</p>
                        <p class="text-[10px] font-semibold dash-accent-text leading-none">Super Admin</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- SECOND SECTION: 4 KPI Cards in one row --}}
        <div class="grid grid-cols-4 gap-4 shrink-0">
            {{-- Card 1: Total Donasi Terkumpul --}}
            <div class="bg-white p-5 rounded-xl border border-slate-100 shadow-sm flex items-center gap-4 relative overflow-hidden group hover:border-[#22C7C9]/40 transition-colors">
                <div class="absolute top-0 left-0 w-1 h-full dash-accent-bg"></div>
                <div class="p-3 rounded-lg dash-badge-teal">
                    <i class="fa-solid fa-wallet text-lg"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Donasi Terkumpul</p>
                    <h3 class="text-lg font-black text-slate-900 mt-1 leading-none">
                        Rp {{ number_format($totalDonations, 0, ',', '.') }}
                    </h3>
                    @if($donationGrowth > 0)
                        <p class="text-[10px] text-emerald-600 font-semibold mt-1.5 flex items-center gap-0.5">
                            <i class="fa-solid fa-arrow-trend-up"></i>
                            <span>Tumbuh {{ number_format($donationGrowth, 0) }}% dari bulan lalu</span>
                        </p>
                    @elseif($donationGrowth < 0)
                        <p class="text-[10px] text-rose-600 font-semibold mt-1.5 flex items-center gap-0.5">
                            <i class="fa-solid fa-arrow-trend-down"></i>
                            <span>Turun {{ number_format(abs($donationGrowth), 0) }}% dari bulan lalu</span>
                        </p>
                    @else
                        <p class="text-[10px] text-slate-500 font-semibold mt-1.5 flex items-center gap-0.5">
                            <i class="fa-solid fa-minus"></i>
                            <span>Sama dengan bulan lalu</span>
                        </p>
                    @endif
                </div>
            </div>

            {{-- Card 2: Kampanye Aktif --}}
            <div class="bg-white p-5 rounded-xl border border-slate-100 shadow-sm flex items-center gap-4 relative overflow-hidden group hover:border-[#22C7C9]/40 transition-colors">
                <div class="absolute top-0 left-0 w-1 h-full bg-blue-500"></div>
                <div class="p-3 rounded-lg bg-blue-50 text-blue-500">
                    <i class="fa-solid fa-fire text-lg"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Kampanye Aktif</p>
                    <h3 class="text-lg font-black text-slate-900 mt-1 leading-none">
                        {{ $activeCampaigns }}
                    </h3>
                    <p class="text-[10px] text-slate-500 font-semibold mt-1.5">
                        <span>Program penggalangan dana berjalan</span>
                    </p>
                </div>
            </div>

            {{-- Card 3: Menunggu Verifikasi --}}
            <div class="bg-white p-5 rounded-xl border border-slate-100 shadow-sm flex items-center gap-4 relative overflow-hidden group hover:border-[#22C7C9]/40 transition-colors">
                <div class="absolute top-0 left-0 w-1 h-full bg-amber-500"></div>
                <div class="p-3 rounded-lg bg-amber-50 text-amber-500 relative">
                    <i class="fa-solid fa-clock-rotate-left text-lg"></i>
                    @if($pendingVerification > 0)
                        <span class="absolute -top-1 -right-1 w-2 h-2 bg-rose-500 rounded-full animate-ping"></span>
                    @endif
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Menunggu Verifikasi</p>
                    <h3 class="text-lg font-black text-slate-900 mt-1 leading-none">
                        {{ $pendingVerification }}
                    </h3>
                    <p class="text-[10px] font-semibold mt-1.5 {{ $pendingVerification > 0 ? 'text-amber-600' : 'text-slate-500' }}">
                        <span>{{ $pendingVerification > 0 ? 'Perlu tindakan verifikasi segera' : 'Semua kampanye terverifikasi' }}</span>
                    </p>
                </div>
            </div>

            {{-- Card 4: Penarikan Dana --}}
            <div class="bg-white p-5 rounded-xl border border-slate-100 shadow-sm flex items-center gap-4 relative overflow-hidden group hover:border-[#22C7C9]/40 transition-colors">
                <div class="absolute top-0 left-0 w-1 h-full bg-indigo-500"></div>
                <div class="p-3 rounded-lg bg-indigo-50 text-indigo-500">
                    <i class="fa-solid fa-money-bill-transfer text-lg"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Pencairan Tertunda</p>
                    <h3 class="text-lg font-black text-slate-900 mt-1 leading-none">
                        Rp {{ number_format($pendingWithdrawals, 0, ',', '.') }}
                    </h3>
                    <p class="text-[10px] text-slate-500 font-semibold mt-1.5">
                        <span>{{ $pendingWithdrawalsCount }} pengajuan perlu direview</span>
                    </p>
                </div>
            </div>
        </div>

        {{-- THIRD SECTION: Full Width Donation trend chart taking 380px vertical space --}}
        <div class="dash-chart-card bg-white p-6 rounded-xl border border-slate-100 shadow-sm flex flex-col">
            <div class="flex items-center justify-between shrink-0 mb-4">
                <div>
                    <h2 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider">Tren Donasi Masuk</h2>
                    <p class="text-[10px] text-slate-500 mt-0.5">Performa donasi terkumpul selama 7 hari terakhir</p>
                </div>
                <div class="flex items-center gap-3 text-[10px] font-bold">
                    <span class="flex items-center gap-1.5 text-slate-600">
                        <span class="w-2.5 h-2.5 rounded-full dash-accent-bg"></span>
                        Donasi (Rupiah)
                    </span>
                </div>
            </div>

            {{-- Custom SVG Chart Container --}}
            <div class="flex-1 w-full relative flex items-end pt-3">
                @if(count($chartPoints) > 0)
                    <svg viewBox="0 0 500 120" preserveAspectRatio="none" class="w-full h-full">
                        <defs>
                            <linearGradient id="chartGradient" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0%" stop-color="#22C7C9" stop-opacity="0.25" />
                                <stop offset="100%" stop-color="#22C7C9" stop-opacity="0.00" />
                            </linearGradient>
                        </defs>
                        
                        {{-- Grid lines --}}
                        <line x1="0" y1="10" x2="500" y2="10" stroke="#f1f5f9" stroke-width="1" stroke-dasharray="4" />
                        <line x1="0" y1="60" x2="500" y2="60" stroke="#f1f5f9" stroke-width="1" stroke-dasharray="4" />
                        <line x1="0" y1="110" x2="500" y2="110" stroke="#e2e8f0" stroke-width="1" />

                        {{-- Area fill under the path --}}
                        <path d="{{ $areaPath }}" fill="url(#chartGradient)" />

                        {{-- Main line --}}
                        <path d="{{ $linePath }}" fill="none" stroke="#22C7C9" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />

                        {{-- Data points circles and label tooltips on hover --}}
                        @foreach($chartPoints as $point)
                            <circle cx="{{ $point['x'] }}" cy="{{ $point['y'] }}" r="3.5" fill="white" stroke="#22C7C9" stroke-width="2" class="cursor-pointer hover:r-5 transition-all duration-150" />
                            <text x="{{ $point['x'] }}" y="{{ $point['y'] - 8 }}" font-size="6" font-weight="bold" fill="#0f172a" text-anchor="middle" class="opacity-0 hover:opacity-100 transition-opacity bg-white">
                                Rp{{ number_format($point['amount'] / 1000, 0) }}k
                            </text>
                        @endforeach
                    </svg>
                @else
                    <div class="absolute inset-0 flex items-center justify-center text-xs text-slate-400">
                        Belum ada data transaksi donasi
                    </div>
                @endif
            </div>

            {{-- Chart X-Axis Labels --}}
            <div class="flex justify-between border-t border-slate-100 pt-3 px-2 shrink-0 mt-2">
                @foreach($chartPoints as $point)
                    <div class="text-[9px] font-bold text-slate-400 text-center w-12">{{ $point['label'] }}</div>
                @endforeach
            </div>
        </div>
    </div>
</x-filament-panels::page>
