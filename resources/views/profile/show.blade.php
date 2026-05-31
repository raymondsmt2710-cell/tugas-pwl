<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    @php
        $myDonations = auth()->user()->donations()->with('campaign')->latest()->take(10)->get();
        $myCampaigns = auth()->user()->campaigns()->with('category')->latest()->take(5)->get();
        $myWithdrawals = \App\Models\Withdrawal::byUser(auth()->user()->id_user)->with('campaign')->latest()->take(10)->get();
    @endphp

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Tab Navigation --}}
            <div class="mb-6 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="flex border-b border-gray-100 overflow-x-auto" id="profile-tabs">
                    <button class="profile-tab-btn active px-6 py-3.5 text-sm font-semibold text-gray-900 border-b-2 border-indigo-600 whitespace-nowrap" data-tab="donations">
                        Riwayat Donasi
                    </button>
                    <button class="profile-tab-btn px-6 py-3.5 text-sm font-medium text-gray-500 hover:text-gray-700 border-b-2 border-transparent whitespace-nowrap" data-tab="campaigns">
                        Kampanye Saya
                    </button>
                    <button class="profile-tab-btn px-6 py-3.5 text-sm font-medium text-gray-500 hover:text-gray-700 border-b-2 border-transparent whitespace-nowrap" data-tab="withdrawals">
                        Penarikan Dana
                    </button>
                    <button class="profile-tab-btn px-6 py-3.5 text-sm font-medium text-gray-500 hover:text-gray-700 border-b-2 border-transparent whitespace-nowrap" data-tab="liked">
                        Liked
                    </button>
                    <button class="profile-tab-btn px-6 py-3.5 text-sm font-medium text-gray-500 hover:text-gray-700 border-b-2 border-transparent whitespace-nowrap" data-tab="comments">
                        Komentar
                    </button>
                    <button class="profile-tab-btn px-6 py-3.5 text-sm font-medium text-gray-500 hover:text-gray-700 border-b-2 border-transparent whitespace-nowrap" data-tab="settings">
                        Pengaturan
                    </button>
                </div>
            </div>

            {{-- Email Verification Alert --}}
            <x-email-verification-alert />

            {{-- Tab: Riwayat Donasi --}}
            <div id="tab-donations" class="profile-tab-content">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Riwayat Donasi</h3>
                            <p class="text-sm text-gray-500 mt-0.5">Semua donasi yang pernah Anda berikan.</p>
                        </div>
                        <a href="{{ url('/my-donations') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">Lihat Semua →</a>
                    </div>
                    <div class="divide-y divide-gray-50">
                        @forelse ($myDonations as $donation)
                            <a href="{{ url('/donations/' . $donation->order_id . '/track') }}" class="flex items-center justify-between px-6 py-4 hover:bg-gray-50 transition-colors">
                                <div class="flex items-center gap-3 min-w-0">
                                    {{-- Status dot --}}
                                    @switch($donation->payment_status)
                                        @case('paid') <div class="w-2.5 h-2.5 rounded-full bg-green-500 shrink-0"></div> @break
                                        @case('pending') <div class="w-2.5 h-2.5 rounded-full bg-yellow-500 shrink-0"></div> @break
                                        @case('expired') <div class="w-2.5 h-2.5 rounded-full bg-gray-400 shrink-0"></div> @break
                                        @default <div class="w-2.5 h-2.5 rounded-full bg-red-500 shrink-0"></div>
                                    @endswitch
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">{{ $donation->campaign->title ?? 'Kampanye Dihapus' }}</p>
                                        <div class="flex items-center gap-2 mt-0.5">
                                            <span class="text-xs text-gray-500">{{ $donation->created_at->format('d M Y, H:i') }}</span>
                                            @if ($donation->is_anonymous)
                                                <span class="text-[10px] bg-gray-100 text-gray-500 px-1.5 py-0.5 rounded">Anonim</span>
                                            @endif
                                            <span class="text-[10px] px-1.5 py-0.5 rounded font-medium
                                                @switch($donation->payment_status)
                                                    @case('paid') bg-green-50 text-green-700 @break
                                                    @case('pending') bg-yellow-50 text-yellow-700 @break
                                                    @case('expired') bg-gray-100 text-gray-600 @break
                                                    @default bg-red-50 text-red-700
                                                @endswitch
                                            ">
                                                @switch($donation->payment_status)
                                                    @case('paid') Berhasil @break
                                                    @case('pending') Pending @break
                                                    @case('expired') Kedaluwarsa @break
                                                    @default Gagal
                                                @endswitch
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right shrink-0 ml-3">
                                    <p class="text-sm font-bold text-gray-900">{{ $donation->formatted_amount }}</p>
                                    <p class="text-[11px] text-indigo-500 font-medium">Detail →</p>
                                </div>
                            </a>
                        @empty
                            <div class="p-12 text-center">
                                <svg class="mx-auto h-10 w-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/>
                                </svg>
                                <p class="mt-3 text-sm text-gray-500">Belum ada riwayat donasi.</p>
                                <a href="{{ url('/campaigns') }}" class="mt-3 inline-block text-sm font-medium text-indigo-600 hover:text-indigo-800">Jelajahi Kampanye</a>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Tab: Kampanye Saya --}}
            <div id="tab-campaigns" class="profile-tab-content hidden">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Kampanye Saya</h3>
                            <p class="text-sm text-gray-500 mt-0.5">Kampanye yang Anda buat.</p>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ url('/my-campaigns') }}" class="text-sm font-medium text-gray-600 hover:text-gray-800">Kelola →</a>
                            <a href="{{ url('/campaigns/create') }}" class="inline-flex items-center px-3 py-1.5 rounded-lg bg-indigo-600 text-white text-xs font-semibold hover:bg-indigo-700 transition">
                                + Buat Baru
                            </a>
                        </div>
                    </div>
                    <div class="divide-y divide-gray-50">
                        @forelse ($myCampaigns as $campaign)
                            <div class="px-6 py-4 flex items-center justify-between">
                                <div class="flex items-center gap-3 min-w-0">
                                    @if ($campaign->banner_image)
                                        <img src="{{ asset('storage/' . $campaign->banner_image) }}" class="w-12 h-12 rounded-lg object-cover shrink-0">
                                    @else
                                        <div class="w-12 h-12 rounded-lg bg-gray-100 shrink-0"></div>
                                    @endif
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">{{ $campaign->title }}</p>
                                        <div class="flex items-center gap-2 mt-0.5">
                                            <span class="text-xs text-gray-500">{{ $campaign->category->name ?? '' }}</span>
                                            <span class="text-[10px] px-1.5 py-0.5 rounded font-medium
                                                @switch($campaign->status)
                                                    @case('approved') bg-green-50 text-green-700 @break
                                                    @case('pending') bg-yellow-50 text-yellow-700 @break
                                                    @case('draft') bg-gray-100 text-gray-600 @break
                                                    @case('rejected') bg-red-50 text-red-700 @break
                                                    @default bg-blue-50 text-blue-700
                                                @endswitch
                                            ">{{ ucfirst($campaign->status) }}</span>
                                        </div>
                                    </div>
                                </div>
                                <span class="text-sm font-semibold text-gray-900 shrink-0">Rp {{ number_format($campaign->collected_amount, 0, ',', '.') }}</span>
                            </div>
                        @empty
                            <div class="p-12 text-center">
                                <p class="text-sm text-gray-500">Belum ada kampanye.</p>
                                <a href="{{ url('/campaigns/create') }}" class="mt-3 inline-block text-sm font-medium text-indigo-600 hover:text-indigo-800">Buat Kampanye Pertama</a>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Tab: Penarikan Dana --}}
            <div id="tab-withdrawals" class="profile-tab-content hidden">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Penarikan Dana</h3>
                            <p class="text-sm text-gray-500 mt-0.5">Riwayat permintaan penarikan dana Anda.</p>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ url('/withdrawals/history') }}" class="text-sm font-medium text-gray-600 hover:text-gray-800">Semua →</a>
                            <a href="{{ url('/withdrawals/create') }}" class="inline-flex items-center px-3 py-1.5 rounded-lg bg-indigo-600 text-white text-xs font-semibold hover:bg-indigo-700 transition">
                                + Tarik Dana
                            </a>
                        </div>
                    </div>
                    <div class="divide-y divide-gray-50">
                        @forelse ($myWithdrawals as $withdrawal)
                            <div class="px-6 py-4 flex items-center justify-between">
                                <div class="flex items-center gap-3 min-w-0">
                                    @switch($withdrawal->status)
                                        @case('paid') <div class="w-2.5 h-2.5 rounded-full bg-green-500 shrink-0"></div> @break
                                        @case('approved') <div class="w-2.5 h-2.5 rounded-full bg-blue-500 shrink-0"></div> @break
                                        @case('pending') @case('under_review') <div class="w-2.5 h-2.5 rounded-full bg-yellow-500 shrink-0"></div> @break
                                        @default <div class="w-2.5 h-2.5 rounded-full bg-red-500 shrink-0"></div>
                                    @endswitch
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">{{ $withdrawal->campaign->title ?? '-' }}</p>
                                        <div class="flex items-center gap-2 mt-0.5">
                                            <span class="text-xs text-gray-500">{{ $withdrawal->created_at->format('d M Y') }}</span>
                                            <span class="text-[10px] px-1.5 py-0.5 rounded font-medium
                                                @switch($withdrawal->status)
                                                    @case('paid') bg-green-50 text-green-700 @break
                                                    @case('approved') bg-blue-50 text-blue-700 @break
                                                    @case('pending') @case('under_review') bg-yellow-50 text-yellow-700 @break
                                                    @default bg-red-50 text-red-700
                                                @endswitch
                                            ">{{ $withdrawal->status_label }}</span>
                                        </div>
                                    </div>
                                </div>
                                <span class="text-sm font-bold text-gray-900 shrink-0">{{ $withdrawal->formatted_amount }}</span>
                            </div>
                        @empty
                            <div class="p-12 text-center">
                                <p class="text-sm text-gray-500">Belum ada penarikan dana.</p>
                                <a href="{{ url('/withdrawals/create') }}" class="mt-3 inline-block text-sm font-medium text-indigo-600 hover:text-indigo-800">Tarik Dana Sekarang</a>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Tab: Liked --}}
            <div id="tab-liked" class="profile-tab-content hidden">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
                    <svg class="mx-auto h-10 w-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/>
                    </svg>
                    <h3 class="mt-3 text-base font-medium text-gray-900">Kampanye yang Disukai</h3>
                    <p class="mt-1 text-sm text-gray-500">Kampanye yang Anda sukai akan muncul di sini.</p>
                </div>
            </div>

            {{-- Tab: Komentar --}}
            <div id="tab-comments" class="profile-tab-content hidden">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
                    <svg class="mx-auto h-10 w-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z"/>
                    </svg>
                    <h3 class="mt-3 text-base font-medium text-gray-900">Komentar Saya</h3>
                    <p class="mt-1 text-sm text-gray-500">Komentar yang Anda berikan di kampanye akan muncul di sini.</p>
                </div>
            </div>

            {{-- Tab: Pengaturan --}}
            <div id="tab-settings" class="profile-tab-content hidden">
                @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                    @livewire('update-profile-information-form')
                    <x-section-border />
                @endif

                @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                    <div class="mt-10 sm:mt-0">
                        @livewire('profile.update-password-form')
                    </div>
                    <x-section-border />
                @endif

                @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                    <div class="mt-10 sm:mt-0">
                        @livewire('profile.two-factor-authentication-form')
                    </div>
                    <x-section-border />
                @endif

                <div class="mt-10 sm:mt-0">
                    @livewire('profile.logout-other-browser-sessions-form')
                </div>

                @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                    <x-section-border />
                    <div class="mt-10 sm:mt-0">
                        @livewire('profile.delete-user-form')
                    </div>
                @endif
            </div>

        </div>
    </div>

    <style>
        .profile-tab-btn.active {
            color: #111827;
            border-bottom-color: #4f46e5;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.profile-tab-btn');
            const panes = document.querySelectorAll('.profile-tab-content');

            tabs.forEach(function(tab) {
                tab.addEventListener('click', function() {
                    // Remove active from all tabs
                    tabs.forEach(function(t) {
                        t.classList.remove('active', 'text-gray-900', 'border-indigo-600');
                        t.classList.add('text-gray-500', 'border-transparent');
                    });
                    // Add active to clicked tab
                    this.classList.add('active', 'text-gray-900', 'border-indigo-600');
                    this.classList.remove('text-gray-500', 'border-transparent');

                    // Hide all panes
                    panes.forEach(function(p) { p.classList.add('hidden'); });
                    // Show target pane
                    var target = document.getElementById('tab-' + this.getAttribute('data-tab'));
                    if (target) target.classList.remove('hidden');
                });
            });
        });
    </script>
</x-app-layout>
