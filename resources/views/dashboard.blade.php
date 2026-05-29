@php
    $user = auth()->user();

    // Total Donasi (Successful/Paid)
    $totalDonation = $user->donations()->where('payment_status', 'paid')->sum('donation_amount');
    $formattedTotalDonation = 'Rp ' . number_format($totalDonation, 0, ',', '.');

    // Transaksi (Successful/Paid)
    $totalTransactions = $user->donations()->where('payment_status', 'paid')->count();

    // Bergabung Sejak
    $joinDate = $user->created_at ? $user->created_at->translatedFormat('F Y') : 'Mei 2026';

    // Impact Points Formula (Total Donasi / 1000)
    $impactPoints = (int) ($totalDonation / 1000);
    $formattedImpactPoints = number_format($impactPoints, 0, ',', '.');

    // Campaigns (actual from DB)
    $myCampaigns = $user->campaigns()->latest()->take(3)->get();

    // Donations (actual from DB)
    $donationHistory = $user->donations()->with('campaign')->latest()->take(4)->get();

    // Withdrawals (actual from DB)
    $withdrawals = $user->withdrawals()->latest()->take(4)->get();

    // Followers & Following
    $followersCount = $user->followers()->count();
    $followingCount = $user->following()->count();
    $followers = $user->followers()->take(7)->get();
    $following = $user->following()->take(7)->get();

    // Notifications
    $notifications = $user->notifications()->latest()->take(3)->get();
@endphp

<x-app-layout>
    {{-- Main Container --}}
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Admin Banner (tidak diubah karena fungsional) --}}
        @if ($user->isAdmin())
            <div class="mb-6 bg-amber-50 overflow-hidden shadow-sm sm:rounded-xl border border-amber-200">
                <div class="p-5 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-amber-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                        <div>
                            <p class="text-sm font-semibold text-amber-800">Admin Panel</p>
                            <p class="text-xs text-amber-600">Kelola kampanye, pengguna, dan donasi platform.</p>
                        </div>
                    </div>
                    <a href="{{ url('/admin') }}" class="inline-flex items-center px-4 py-2 border border-amber-300 rounded-lg text-sm font-semibold text-amber-800 bg-white hover:bg-amber-100 transition-all shadow-sm">
                        Buka Admin Panel →
                    </a>
                </div>
            </div>
        @endif

        {{-- Welcome Header ala Kitabisa/GoFundMe --}}
        <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex items-center gap-4">
                <img class="w-14 h-14 rounded-full object-cover border-2 border-gray-200 shadow-sm" src="{{ $user->profile_photo_url }}" alt="{{ $user->full_name }}">
                <div>
                    <h1 class="text-xl md:text-2xl font-bold text-gray-900">Halo, {{ $user->full_name }}!</h1>
                    <p class="text-sm text-gray-500 mt-0.5">Terima kasih sudah menjadi bagian dari kebaikan. Bergabung sejak {{ $joinDate }}</p>
                </div>
            </div>
            <a href="{{ route('campaign.create') }}" class="inline-flex items-center px-5 py-2.5 border border-transparent rounded-xl text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700 transition shadow-sm">
                <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Buat Kampanye Baru
            </a>
        </div>

        {{-- Statistik Utama – 4 kartu bersih --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-10">
            {{-- Total Donasi --}}
            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="p-2 rounded-lg bg-emerald-50 text-emerald-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Total Donasi</p>
                        <p class="text-lg font-bold text-gray-900">{{ $totalDonation > 0 ? $formattedTotalDonation : 'Rp 0' }}</p>
                    </div>
                </div>
            </div>

            {{-- Transaksi --}}
            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="p-2 rounded-lg bg-emerald-50 text-emerald-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Transaksi</p>
                        <p class="text-lg font-bold text-gray-900">{{ $totalTransactions }}</p>
                    </div>
                </div>
            </div>

            {{-- Bergabung --}}
            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="p-2 rounded-lg bg-emerald-50 text-emerald-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Bergabung Sejak</p>
                        <p class="text-lg font-bold text-gray-900">{{ $joinDate }}</p>
                    </div>
                </div>
            </div>

            {{-- Impact Points --}}
            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="p-2 rounded-lg bg-emerald-50 text-emerald-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499c.15-.427.65-.427.8 0l1.96 5.895a.75.75 0 0 0 .714.53h6.19c.45 0 .638.583.275.856l-5.01 3.64a.75.75 0 0 0-.273.84l1.96 5.896c.15.426-.339.782-.698.542l-5.01-3.64a.75.75 0 0 0-.88 0l-5.01 3.64c-.359.24-.849-.116-.698-.542l1.96-5.895a.75.75 0 0 0-.273-.84l-5.01-3.64c-.363-.273-.175-.856.275-.856h6.19a.75.75 0 0 0 .714-.53l1.96-5.895Z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Impact Points</p>
                        <p class="text-lg font-bold text-gray-900">{{ $totalDonation > 0 ? $formattedImpactPoints : '0' }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Konten Utama: 3 Kolom --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- Kolom 1: Kampanye & Tersimpan --}}
            <div class="space-y-8">
                {{-- My Campaigns --}}
                <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Kampanye Saya</h2>
                        <a href="{{ route('campaigns.my') }}" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700">Lihat semua</a>
                    </div>

                    @if ($myCampaigns->count() > 0)
                        <div class="space-y-5">
                            @foreach ($myCampaigns as $camp)
                                <div class="flex gap-3">
                                    <img src="{{ $camp->banner_image_url ?? 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80' }}" alt="{{ $camp->title }}" class="w-14 h-14 rounded-lg object-cover shrink-0 bg-gray-100 border border-gray-200">
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-sm font-semibold text-gray-900 truncate">
                                            <a href="{{ route('campaigns.show', $camp->slug) }}" class="hover:text-emerald-600">{{ $camp->title }}</a>
                                        </h4>
                                        <div class="w-full bg-gray-100 rounded-full h-1.5 mt-2 overflow-hidden">
                                            <div class="bg-emerald-500 h-1.5 rounded-full" style="width: {{ $camp->progress_percentage }}%"></div>
                                        </div>
                                        <div class="flex justify-between text-xs text-gray-500 mt-1">
                                            <span>Rp {{ number_format($camp->collected_amount, 0, ',', '.') }}</span>
                                            <span class="font-semibold text-emerald-600">{{ round($camp->progress_percentage) }}%</span>
                                        </div>
                                        <div class="flex gap-2 text-xs text-gray-400 mt-0.5">
                                            <span>{{ $camp->donor_count }} Donasi</span>
                                            <span>·</span>
                                            <span>{{ $camp->days_remaining }} Hari lagi</span>
                                        </div>
                                    </div>
                                    <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 capitalize">{{ $camp->status == 'approved' ? 'Aktif' : $camp->status }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="mx-auto w-12 h-12 bg-gray-50 rounded-xl flex items-center justify-center text-gray-300 border border-gray-200 mb-3">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 0 1-2.25 2.25M16.5 7.5V18a2.25 2.25 0 0 0 2.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 0 0 2.25 2.25h13.5M6 7.5h3v3H6v-3Z" />
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-gray-700">Belum ada kampanye</p>
                            <p class="text-xs text-gray-500 mt-1">Buat kampanye pertamamu dan mulailah menggalang dana.</p>
                            <a href="{{ route('campaign.create') }}" class="mt-3 inline-block px-4 py-1.5 bg-emerald-50 text-emerald-600 rounded-lg text-sm font-semibold hover:bg-emerald-100 transition border border-emerald-200">Buat Kampanye</a>
                        </div>
                    @endif
                </div>

                {{-- Saved Campaigns --}}
                <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Disimpan</h2>
                        <a href="#" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700">Lihat semua</a>
                    </div>
                    <div class="text-center py-8">
                        <div class="mx-auto w-12 h-12 bg-gray-50 rounded-xl flex items-center justify-center text-gray-300 border border-gray-200 mb-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0 1 11.186 0Z" />
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-gray-700">Belum ada yang disimpan</p>
                        <p class="text-xs text-gray-500 mt-1">Simpan kampanye favoritmu untuk dipantau nanti.</p>
                        <a href="{{ url('/campaigns') }}" class="mt-3 inline-block px-4 py-1.5 bg-emerald-50 text-emerald-600 rounded-lg text-sm font-semibold hover:bg-emerald-100 transition border border-emerald-200">Jelajahi Kampanye</a>
                    </div>
                </div>
            </div>

            {{-- Kolom 2: Donasi & Pengikut --}}
            <div class="space-y-8">
                {{-- Donation History --}}
                <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Riwayat Donasi</h2>
                        <a href="{{ route('donations.history') }}" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700">Lihat semua</a>
                    </div>

                    @if ($donationHistory->count() > 0)
                        <div class="divide-y divide-gray-100">
                            @foreach ($donationHistory as $donation)
                                <div class="flex items-center justify-between py-3 first:pt-0 last:pb-0">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <img src="{{ $donation->campaign->banner_image_url ?? 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80' }}" alt="" class="w-10 h-10 rounded-lg object-cover border border-gray-100">
                                        <div class="min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate">{{ $donation->campaign->title }}</p>
                                            <p class="text-xs text-gray-400">{{ $donation->paid_at ? $donation->paid_at->translatedFormat('d M Y') : $donation->created_at->translatedFormat('d M Y') }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-semibold text-gray-900">Rp {{ number_format($donation->donation_amount, 0, ',', '.') }}</p>
                                        <span class="text-xs font-medium px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 capitalize">{{ $donation->isPaid() ? 'Selesai' : $donation->payment_status }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="mx-auto w-12 h-12 bg-gray-50 rounded-xl flex items-center justify-center text-gray-300 border border-gray-200 mb-3">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-gray-700">Belum ada donasi</p>
                            <p class="text-xs text-gray-500 mt-1">Mulailah berdonasi untuk mendukung kampanye yang berarti.</p>
                            <a href="{{ url('/campaigns') }}" class="mt-3 inline-block px-4 py-1.5 bg-emerald-50 text-emerald-600 rounded-lg text-sm font-semibold hover:bg-emerald-100 transition border border-emerald-200">Donasi Sekarang</a>
                        </div>
                    @endif
                </div>

                {{-- Followers & Following --}}
                <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm space-y-6">
                    <div>
                        <div class="flex justify-between items-center mb-3">
                            <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Followers</h3>
                            <a href="#" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700">Lihat semua</a>
                        </div>
                        @if($followers->count() > 0)
                            <div class="flex items-center">
                                <div class="flex -space-x-2">
                                    @foreach($followers as $f)
                                        <img class="w-8 h-8 rounded-full ring-2 ring-white object-cover border border-gray-200" src="{{ $f->profile_photo_url }}" alt="Follower">
                                    @endforeach
                                </div>
                                <span class="ml-3 text-sm font-medium text-gray-600">{{ $followersCount }} pengikut</span>
                            </div>
                        @else
                            <p class="text-sm text-gray-400">Belum ada pengikut.</p>
                        @endif
                    </div>
                    <div>
                        <div class="flex justify-between items-center mb-3">
                            <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Mengikuti</h3>
                            <a href="#" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700">Lihat semua</a>
                        </div>
                        @if($following->count() > 0)
                            <div class="flex items-center">
                                <div class="flex -space-x-2">
                                    @foreach($following as $f)
                                        <img class="w-8 h-8 rounded-full ring-2 ring-white object-cover border border-gray-200" src="{{ $f->profile_photo_url }}" alt="Following">
                                    @endforeach
                                </div>
                                <span class="ml-3 text-sm font-medium text-gray-600">{{ $followingCount }} diikuti</span>
                            </div>
                        @else
                            <p class="text-sm text-gray-400">Kamu belum mengikuti siapapun.</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Kolom 3: Penarikan & Notifikasi --}}
            <div class="space-y-8">
                {{-- Withdraw History --}}
                <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Penarikan Dana</h2>
                        <a href="{{ route('withdrawals.history') }}" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700">Lihat semua</a>
                    </div>

                    @if ($withdrawals->count() > 0)
                        <div class="divide-y divide-gray-100">
                            @foreach ($withdrawals as $withdrawal)
                                <div class="flex items-center justify-between py-3 first:pt-0 last:pb-0">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-200">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0 0 12 9.75c-2.683 0-5.302.22-7.858.647V21m16.5 0H3" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">Ke {{ $withdrawal->bank_name }}</p>
                                            <p class="text-xs text-gray-400">{{ $withdrawal->created_at->translatedFormat('d M Y') }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-semibold text-gray-900">Rp {{ number_format($withdrawal->amount, 0, ',', '.') }}</p>
                                        <span class="text-xs font-medium px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 capitalize">{{ $withdrawal->status_label }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="mx-auto w-12 h-12 bg-gray-50 rounded-xl flex items-center justify-center text-gray-300 border border-gray-200 mb-3">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-1.972-.659-1.172-.879-1.172-2.303 0-3.182 1.171-.879 3.07-.879 4.242 0L15 8.25M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-gray-700">Belum ada penarikan</p>
                            <p class="text-xs text-gray-500 mt-1">Dana yang berhasil dikumpulkan akan muncul di sini saat dicairkan.</p>
                        </div>
                    @endif
                </div>

                {{-- Notifications --}}
                <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Notifikasi</h2>
                        <a href="{{ route('notifications.index') }}" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700">Lihat semua</a>
                    </div>

                    @if ($notifications->count() > 0)
                        <div class="divide-y divide-gray-100">
                            @foreach ($notifications as $notification)
                                <div class="flex items-start gap-3 py-3 first:pt-0 last:pb-0">
                                    <div class="w-8 h-8 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-200 shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm text-gray-700">{!! e($notification->data['message'] ?? 'Notifikasi baru') !!}</p>
                                        <span class="text-xs text-gray-400">{{ $notification->created_at->diffForHumans() }}</span>
                                    </div>
                                    @if(is_null($notification->read_at))
                                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 mt-1.5 shrink-0" title="Belum dibaca"></span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="mx-auto w-12 h-12 bg-gray-50 rounded-xl flex items-center justify-center text-gray-300 border border-gray-200 mb-3">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-gray-700">Tidak ada notifikasi</p>
                            <p class="text-xs text-gray-500 mt-1">Semua aktivitas terbaru akan muncul di sini.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Footer Profil --}}
        <div class="mt-8 bg-white rounded-xl border border-gray-200 p-5 shadow-sm flex flex-col md:flex-row items-center gap-6">
            <img class="w-14 h-14 rounded-full object-cover border-2 border-gray-200" src="{{ $user->profile_photo_url }}" alt="{{ $user->full_name }}">
            <div class="flex-1">
                <h3 class="text-lg font-bold text-gray-900">{{ $user->full_name }}</h3>
                <p class="text-sm text-gray-500">{{ $user->email }}</p>
                <p class="text-xs text-gray-400 mt-0.5">Anggota sejak {{ $joinDate }}</p>
            </div>
            <a href="{{ url('/user/profile') }}" class="px-5 py-2.5 border border-emerald-500 text-emerald-600 rounded-xl text-sm font-semibold hover:bg-emerald-50 transition">Edit Profil</a>
        </div>
    </div>
</x-app-layout>