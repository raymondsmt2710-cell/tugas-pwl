@php
    $user = auth()->user();

    // Total Donasi (Successful/Paid)
    $totalDonation = $user->donations()->where('payment_status', 'paid')->sum('donation_amount');
    $formattedTotalDonation = 'Rp ' . number_format($totalDonation, 0, ',', '.');

    // Bergabung Sejak
    $joinDate = $user->created_at ? $user->created_at->translatedFormat('F Y') : 'Mei 2026';

    // Campaigns (actual from DB)
    $myCampaigns = $user->campaigns()->with('category')->latest()->get();
    $campaignCount = $myCampaigns->count();
    $activeCampaignsCount = $user->campaigns()->active()->count();

    // Donations (actual from DB)
    $donationHistory = $user->donations()->with('campaign')->latest()->get();

    // Withdrawals (actual from DB)
    $withdrawals = $user->withdrawals()->with('campaign')->latest()->get();

    // Followers & Following
    $followersCount = $user->followers()->count();
    $followingCount = $user->following()->count();
    $followers = $user->followers()->take(24)->get();
    $following = $user->following()->take(24)->get();

    // Notifications
    $notifications = $user->notifications()->latest()->take(20)->get();
    $unreadNotificationsCount = $notifications->whereNull('read_at')->count();
@endphp

<x-app-layout>
    <!-- Font dari Google (ringan) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        [x-cloak] { display: none !important; }
        
        /* Scrollbar minimal */
        .simple-scrollbar::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }
        .simple-scrollbar::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 9999px;
        }
    </style>

    <div class="min-h-screen bg-gray-50 flex font-sans" x-data="{ activeTab: 'overview', sidebarOpen: false }">

        {{-- MOBILE HEADER --}}
        <div class="lg:hidden fixed top-0 left-0 right-0 h-16 bg-white border-b border-gray-200 flex items-center justify-between px-4 z-40">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-emerald-600 flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                    </svg>
                </div>
                <span class="text-lg font-bold text-gray-800">Autopahala</span>
            </div>
            <button @click="sidebarOpen = !sidebarOpen" class="p-2 text-gray-500 hover:bg-gray-100 rounded-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </button>
        </div>

      {{-- SIDEBAR --}}
<div>
    <!-- Backdrop mobile -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition.opacity class="fixed inset-0 bg-black/20 z-40 lg:hidden"></div>

    <div :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
         class="fixed lg:sticky top-0 left-0 h-screen w-64 bg-white border-r border-gray-200 flex flex-col justify-between py-6 z-50 transition-transform duration-300">
        
        <div class="px-4">
            <!-- Logo -->
            <div class="flex items-center gap-2 px-2 mb-6">
                <div class="w-8 h-8 rounded-lg bg-emerald-600 flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                    </svg>
                </div>
                <span class="text-lg font-bold text-gray-800">Autopahala</span>
            </div>

            {{-- Tombol Buat Kampanye (dipindah ke atas) --}}
            <div class="mb-6 px-2">
                <a href="{{ route('campaign.create') }}" 
                   class="w-full flex items-center justify-center gap-2 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-semibold transition">
                    <i class="fas fa-plus text-xs"></i>
                    Buat Kampanye
                </a>
            </div>

            <!-- Menu -->
            <nav class="space-y-1">
                <!-- Overview -->
                <button @click="activeTab = 'overview'; sidebarOpen = false"
                        :class="activeTab === 'overview' ? 'bg-emerald-50 text-emerald-700' : 'text-gray-600 hover:bg-gray-50'"
                        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition">
                    <i class="fas fa-home w-4 h-4 text-center"></i>
                    <span>Overview</span>
                </button>

                <!-- My Campaigns -->
                <button @click="activeTab = 'campaigns'; sidebarOpen = false"
                        :class="activeTab === 'campaigns' ? 'bg-emerald-50 text-emerald-700' : 'text-gray-600 hover:bg-gray-50'"
                        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition">
                    <i class="fas fa-bullhorn w-4 h-4 text-center"></i>
                    <span>Kampanye Saya</span>
                </button>

                <!-- Donations -->
                <button @click="activeTab = 'donations'; sidebarOpen = false"
                        :class="activeTab === 'donations' ? 'bg-emerald-50 text-emerald-700' : 'text-gray-600 hover:bg-gray-50'"
                        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition">
                    <i class="fas fa-heart w-4 h-4 text-center"></i>
                    <span>Donasi</span>
                </button>

                <!-- Withdrawals -->
                <button @click="activeTab = 'withdrawals'; sidebarOpen = false"
                        :class="activeTab === 'withdrawals' ? 'bg-emerald-50 text-emerald-700' : 'text-gray-600 hover:bg-gray-50'"
                        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition">
                    <i class="fas fa-wallet w-4 h-4 text-center"></i>
                    <span>Penarikan</span>
                </button>

                <!-- Notifications -->
                <button @click="activeTab = 'notifications'; sidebarOpen = false"
                        :class="activeTab === 'notifications' ? 'bg-emerald-50 text-emerald-700' : 'text-gray-600 hover:bg-gray-50'"
                        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition relative">
                    <i class="fas fa-bell w-4 h-4 text-center"></i>
                    <span>Notifikasi</span>
                    @if($unreadNotificationsCount > 0)
                        <span class="ml-auto bg-emerald-600 text-white text-xs font-bold px-1.5 py-0.5 rounded-full">{{ $unreadNotificationsCount }}</span>
                    @endif
                </button>

                <!-- Followers -->
                <button @click="activeTab = 'followers'; sidebarOpen = false"
                        :class="activeTab === 'followers' ? 'bg-emerald-50 text-emerald-700' : 'text-gray-600 hover:bg-gray-50'"
                        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition">
                    <i class="fas fa-users w-4 h-4 text-center"></i>
                    <span>Followers</span>
                    <span class="ml-auto text-xs text-gray-400">{{ $followersCount }}</span>
                </button>

                <!-- Following -->
                <button @click="activeTab = 'following'; sidebarOpen = false"
                        :class="activeTab === 'following' ? 'bg-emerald-50 text-emerald-700' : 'text-gray-600 hover:bg-gray-50'"
                        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition">
                    <i class="fas fa-user-check w-4 h-4 text-center"></i>
                    <span>Mengikuti</span>
                    <span class="ml-auto text-xs text-gray-400">{{ $followingCount }}</span>
                </button>

                <div class="border-t border-gray-100 my-4"></div>

                <!-- Profile Publik -->
                <a href="{{ url('/@' . $user->username) }}"
                   class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50 transition">
                    <i class="fas fa-user-circle w-4 h-4 text-center"></i>
                    <span>Profil Publik</span>
                </a>

                <!-- Settings -->
                <a href="{{ url('/user/profile') }}"
                   class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50 transition">
                    <i class="fas fa-cog w-4 h-4 text-center"></i>
                    <span>Pengaturan</span>
                </a>
            </nav>
        </div>
    </div>
</div>
        {{-- MAIN CONTENT --}}
        <div class="flex-1 pt-16 lg:pt-0 overflow-y-auto simple-scrollbar">
            <main class="p-4 lg:p-8 max-w-5xl mx-auto space-y-6">

                {{-- Admin Banner --}}
                @if ($user->isAdmin())
                    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center text-amber-600">
                                <i class="fas fa-shield-alt text-sm"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-amber-800">Admin Panel</h4>
                                <p class="text-xs text-amber-600">Kelola platform, pengguna, dan kampanye.</p>
                            </div>
                        </div>
                        <a href="{{ url('/admin') }}" class="px-4 py-2 bg-white border border-amber-300 text-xs font-semibold text-amber-800 rounded-lg hover:bg-amber-100 transition">
                            Buka Admin →
                        </a>
                    </div>
                @endif

                {{-- TAB: OVERVIEW --}}
                <div x-show="activeTab === 'overview'" class="space-y-6">
                    <!-- Welcome -->
                    <div class="flex flex-col sm:flex-row sm:items-center gap-4 bg-white border border-gray-200 p-5 rounded-xl">
                        <img class="w-14 h-14 rounded-full object-cover border-2 border-gray-100" src="{{ $user->profile_photo_url }}" alt="">
                        <div class="flex-1">
                            <h1 class="text-xl font-bold text-gray-800">Halo, {{ $user->full_name }}!</h1>
                            <p class="text-sm text-gray-500 mt-0.5">Bergabung sejak {{ $joinDate }}</p>
                        </div>
                        <a href="{{ route('campaign.create') }}" class="px-4 py-2 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-lg text-sm font-semibold hover:bg-emerald-100 transition flex items-center gap-1.5">
                            <i class="fas fa-plus text-xs"></i> Buat Kampanye
                        </a>
                    </div>

                    <!-- Stats -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="bg-white border border-gray-200 rounded-xl p-4 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                                <i class="fas fa-heart"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Total Donasi</p>
                                <p class="text-lg font-bold text-gray-800">{{ $totalDonation > 0 ? $formattedTotalDonation : 'Rp 0' }}</p>
                            </div>
                        </div>
                        <div class="bg-white border border-gray-200 rounded-xl p-4 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                                <i class="fas fa-bullhorn"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Kampanye Aktif</p>
                                <p class="text-lg font-bold text-gray-800">{{ $activeCampaignsCount }}</p>
                            </div>
                        </div>
                        <div class="bg-white border border-gray-200 rounded-xl p-4 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Bergabung</p>
                                <p class="text-lg font-bold text-gray-800">{{ $joinDate }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Profile & Recent Campaigns -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <div class="lg:col-span-3 bg-white border border-gray-200 rounded-xl p-5">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-sm font-semibold text-gray-800">Kampanye Terbaru</h3>
                                <button @click="activeTab = 'campaigns'" class="text-xs font-semibold text-emerald-600 hover:underline">Lihat semua</button>
                            </div>
                            @if($myCampaigns->isEmpty())
                                <div class="text-center py-8 text-sm text-gray-400">Belum ada kampanye.</div>
                            @else
                                <div class="space-y-4">
                                    @foreach($myCampaigns->take(3) as $c)
                                        <div class="flex items-center justify-between border-b border-gray-100 pb-3 last:border-0 last:pb-0">
                                            <div class="flex items-center gap-3 min-w-0">
                                                <img class="w-10 h-10 rounded-lg object-cover bg-gray-100" src="{{ $c->banner_image_url ?? 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?w=100&h=100&fit=crop' }}" alt="">
                                                <div class="min-w-0">
                                                    <h4 class="text-sm font-semibold text-gray-800 truncate">{{ $c->title }}</h4>
                                                    <p class="text-xs text-gray-400">Rp {{ number_format($c->collected_amount, 0, ',', '.') }}</p>
                                                </div>
                                            </div>
                                            <span class="px-2 py-0.5 rounded text-xs font-semibold
                                                @if($c->status == 'approved') bg-emerald-50 text-emerald-600
                                                @elseif($c->status == 'pending') bg-amber-50 text-amber-600
                                                @else bg-gray-100 text-gray-500 @endif">
                                                {{ $c->status == 'approved' ? 'Aktif' : ucfirst($c->status) }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- TAB: CAMPAIGNS --}}
                <div x-show="activeTab === 'campaigns'" class="space-y-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-bold text-gray-800">Kampanye Anda ({{ $campaignCount }})</h2>
                        <a href="{{ route('campaign.create') }}" class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-semibold hover:bg-emerald-700 transition flex items-center gap-1.5">
                            <i class="fas fa-plus"></i> Baru
                        </a>
                    </div>
                    @if($myCampaigns->isEmpty())
                        <div class="text-center py-12 bg-white border border-gray-200 rounded-xl">
                            <div class="text-4xl text-gray-300 mb-3"><i class="fas fa-hand-holding-heart"></i></div>
                            <h3 class="text-sm font-semibold text-gray-700">Belum ada kampanye</h3>
                            <p class="text-xs text-gray-400 mt-1">Buat kampanye pertamamu untuk mulai menggalang dana.</p>
                            <a href="{{ route('campaign.create') }}" class="mt-4 inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-semibold">
                                <i class="fas fa-plus"></i> Buat Kampanye
                            </a>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($myCampaigns as $c)
                                <div class="bg-white border border-gray-200 rounded-xl overflow-hidden flex flex-col">
                                    <img class="w-full h-40 object-cover bg-gray-100" src="{{ $c->banner_image_url ?? 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?w=400&h=200&fit=crop' }}" alt="">
                                    <div class="p-4 flex-1 flex flex-col">
                                        <span class="text-xs font-semibold px-2 py-0.5 bg-gray-100 text-gray-500 rounded self-start mb-2">{{ $c->category->name ?? 'Umum' }}</span>
                                        <h3 class="text-sm font-semibold text-gray-800 mb-2">{{ $c->title }}</h3>
                                        <div class="w-full bg-gray-100 rounded-full h-1.5 mb-2">
                                            <div class="bg-emerald-500 h-1.5 rounded-full" style="width: {{ min(100, $c->progress_percentage) }}%"></div>
                                        </div>
                                        <div class="flex justify-between text-xs text-gray-500 mb-3">
                                            <span>Rp {{ number_format($c->collected_amount, 0, ',', '.') }}</span>
                                            <span class="font-semibold">{{ round($c->progress_percentage) }}%</span>
                                        </div>
                                        <div class="flex justify-between text-xs text-gray-400 mt-auto border-t border-gray-100 pt-3">
                                            <span>{{ $c->donor_count }} Donatur</span>
                                            <span>{{ $c->days_remaining }} Hari lagi</span>
                                        </div>
                                    </div>
                                    <div class="px-4 py-3 bg-gray-50 border-t border-gray-100 flex gap-2 text-xs">
                                        @if(in_array($c->status, ['draft', 'rejected']))
                                            <a href="{{ route('campaign.edit', $c->id_campaign) }}" class="flex-1 py-1.5 border border-gray-300 text-gray-700 rounded-lg text-center font-semibold hover:bg-white">Edit</a>
                                            <form action="{{ route('campaign.submit', $c->id_campaign) }}" method="POST" class="flex-1">
                                                @csrf
                                                <button class="w-full py-1.5 bg-emerald-600 text-white rounded-lg font-semibold hover:bg-emerald-700">Ajukan</button>
                                            </form>
                                        @else
                                            <a href="{{ route('campaigns.show', $c->slug) }}" class="w-full py-1.5 border border-gray-300 text-gray-700 rounded-lg text-center font-semibold hover:bg-white">Lihat</a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- TAB: DONATIONS --}}
                <div x-show="activeTab === 'donations'" class="space-y-6">
                    <h2 class="text-lg font-bold text-gray-800">Riwayat Donasi</h2>
                    @if($donationHistory->isEmpty())
                        <div class="text-center py-12 bg-white border border-gray-200 rounded-xl">
                            <div class="text-4xl text-gray-300 mb-3"><i class="fas fa-heart"></i></div>
                            <h3 class="text-sm font-semibold text-gray-700">Belum ada donasi</h3>
                            <p class="text-xs text-gray-400 mt-1">Donasi pertama Anda akan muncul di sini.</p>
                            <a href="{{ url('/campaigns') }}" class="mt-4 inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-semibold">
                                Jelajahi Kampanye
                            </a>
                        </div>
                    @else
                        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase">
                                    <tr>
                                        <th class="p-4 text-left">Kampanye</th>
                                        <th class="p-4 text-center">Status</th>
                                        <th class="p-4 text-right">Jumlah</th>
                                        <th class="p-4 text-center">Tanggal</th>
                                        <th class="p-4 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($donationHistory as $d)
                                        <tr class="hover:bg-gray-50">
                                            <td class="p-4">
                                                <div class="flex items-center gap-3">
                                                    <img class="w-10 h-10 rounded-lg object-cover bg-gray-100" src="{{ $d->campaign->banner_image_url ?? 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?w=100&h=100&fit=crop' }}" alt="">
                                                    <div>
                                                        <h4 class="font-semibold text-gray-800 truncate max-w-[200px]">{{ $d->campaign->title ?? 'Kampanye Dihapus' }}</h4>
                                                        <span class="text-xs text-gray-400">Order ID: {{ $d->order_id }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="p-4 text-center">
                                                <span class="px-2 py-0.5 rounded text-xs font-semibold
                                                    @if($d->payment_status == 'paid') bg-emerald-50 text-emerald-600
                                                    @elseif($d->payment_status == 'pending') bg-amber-50 text-amber-600
                                                    @else bg-gray-100 text-gray-500 @endif">
                                                    {{ $d->payment_status == 'paid' ? 'Berhasil' : ucfirst($d->payment_status) }}
                                                </span>
                                            </td>
                                            <td class="p-4 text-right font-semibold text-gray-800">Rp {{ number_format($d->donation_amount, 0, ',', '.') }}</td>
                                            <td class="p-4 text-center text-xs text-gray-500">{{ $d->created_at->translatedFormat('d M Y, H:i') }}</td>
                                            <td class="p-4 text-center">
                                                <a href="{{ url('/donations/' . $d->order_id . '/track') }}" class="text-emerald-600 hover:underline text-xs font-semibold">Lacak →</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                {{-- TAB: WITHDRAWALS --}}
                <div x-show="activeTab === 'withdrawals'" class="space-y-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-bold text-gray-800">Penarikan Dana</h2>
                        <a href="{{ route('withdrawals.create') }}" class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-semibold hover:bg-emerald-700 transition flex items-center gap-1.5">
                            <i class="fas fa-plus"></i> Tarik Dana
                        </a>
                    </div>
                    @if($withdrawals->isEmpty())
                        <div class="text-center py-12 bg-white border border-gray-200 rounded-xl">
                            <div class="text-4xl text-gray-300 mb-3"><i class="fas fa-wallet"></i></div>
                            <h3 class="text-sm font-semibold text-gray-700">Belum ada penarikan</h3>
                            <p class="text-xs text-gray-400 mt-1">Dana kampanye yang siap dicairkan akan muncul di sini.</p>
                            <a href="{{ route('withdrawals.create') }}" class="mt-4 inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-semibold">
                                Ajukan Penarikan
                            </a>
                        </div>
                    @else
                        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase">
                                    <tr>
                                        <th class="p-4 text-left">Kampanye</th>
                                        <th class="p-4">Rekening</th>
                                        <th class="p-4 text-right">Jumlah</th>
                                        <th class="p-4 text-center">Status</th>
                                        <th class="p-4 text-center">Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($withdrawals as $w)
                                        <tr class="hover:bg-gray-50">
                                            <td class="p-4 font-semibold text-gray-800 truncate max-w-[200px]">{{ $w->campaign->title ?? 'Kampanye Dihapus' }}</td>
                                            <td class="p-4">
                                                <p class="font-semibold text-gray-700">{{ $w->bank_name }}</p>
                                                <p class="text-xs text-gray-400">No. Rek: {{ maskAccountNumber($w->account_number) }}</p>
                                            </td>
                                            <td class="p-4 text-right font-semibold text-gray-800">Rp {{ number_format($w->amount, 0, ',', '.') }}</td>
                                            <td class="p-4 text-center">
                                                <span class="px-2 py-0.5 rounded text-xs font-semibold
                                                    @if($w->status == 'paid') bg-emerald-50 text-emerald-600
                                                    @elseif($w->status == 'approved') bg-blue-50 text-blue-600
                                                    @elseif(in_array($w->status, ['pending', 'under_review'])) bg-amber-50 text-amber-600
                                                    @else bg-gray-100 text-gray-500 @endif">
                                                    {{ $w->status_label }}
                                                </span>
                                            </td>
                                            <td class="p-4 text-center text-xs text-gray-500">{{ $w->created_at->translatedFormat('d M Y, H:i') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                {{-- TAB: NOTIFICATIONS --}}
                <div x-show="activeTab === 'notifications'" class="space-y-6">
                    <h2 class="text-lg font-bold text-gray-800">Notifikasi</h2>
                    @if($notifications->isEmpty())
                        <div class="text-center py-12 bg-white border border-gray-200 rounded-xl">
                            <div class="text-4xl text-gray-300 mb-3"><i class="fas fa-bell-slash"></i></div>
                            <h3 class="text-sm font-semibold text-gray-700">Tidak ada notifikasi</h3>
                            <p class="text-xs text-gray-400 mt-1">Update aktivitas akan muncul di sini.</p>
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach($notifications as $notif)
                                <div class="bg-white border border-gray-200 rounded-xl p-4 flex items-start gap-3 {{ is_null($notif->read_at) ? 'border-l-4 border-l-emerald-500' : '' }}">
                                    <div class="w-8 h-8 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                                        <i class="fas fa-bell text-xs"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm text-gray-700">{!! e($notif->data['message'] ?? 'Notifikasi Baru') !!}</p>
                                        <span class="text-xs text-gray-400 mt-1 block">{{ $notif->created_at->diffForHumans() }}</span>
                                    </div>
                                    @if(is_null($notif->read_at))
                                        <span class="w-2 h-2 rounded-full bg-emerald-500 mt-1.5 shrink-0"></span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- TAB: FOLLOWERS --}}
                <div x-show="activeTab === 'followers'" class="space-y-6">
                    <h2 class="text-lg font-bold text-gray-800">Followers ({{ $followersCount }})</h2>
                    @if($followers->isEmpty())
                        <div class="text-center py-12 bg-white border border-gray-200 rounded-xl">
                            <div class="text-4xl text-gray-300 mb-3"><i class="fas fa-users"></i></div>
                            <h3 class="text-sm font-semibold text-gray-700">Belum ada pengikut</h3>
                        </div>
                    @else
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            @foreach($followers as $f)
                                <a href="{{ url('/@' . $f->username) }}" class="bg-white border border-gray-200 rounded-xl p-3 flex items-center gap-3 hover:bg-gray-50 transition">
                                    <img class="w-10 h-10 rounded-full object-cover" src="{{ $f->profile_photo_url }}" alt="">
                                    <div>
                                        <h4 class="text-sm font-semibold text-gray-800 truncate">{{ $f->full_name }}</h4>
                                        <p class="text-xs text-gray-400">@ {{ $f->username }}</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- TAB: FOLLOWING --}}
                <div x-show="activeTab === 'following'" class="space-y-6">
                    <h2 class="text-lg font-bold text-gray-800">Mengikuti ({{ $followingCount }})</h2>
                    @if($following->isEmpty())
                        <div class="text-center py-12 bg-white border border-gray-200 rounded-xl">
                            <div class="text-4xl text-gray-300 mb-3"><i class="fas fa-user-plus"></i></div>
                            <h3 class="text-sm font-semibold text-gray-700">Belum mengikuti siapapun</h3>
                        </div>
                    @else
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            @foreach($following as $f)
                                <a href="{{ url('/@' . $f->username) }}" class="bg-white border border-gray-200 rounded-xl p-3 flex items-center gap-3 hover:bg-gray-50 transition">
                                    <img class="w-10 h-10 rounded-full object-cover" src="{{ $f->profile_photo_url }}" alt="">
                                    <div>
                                        <h4 class="text-sm font-semibold text-gray-800 truncate">{{ $f->full_name }}</h4>
                                        <p class="text-xs text-gray-400">@ {{ $f->username }}</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>

            </main>
        </div>
    </div>
</x-app-layout>