<x-app-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">

    <div class="min-h-screen bg-white font-[Plus_Jakarta_Sans]">

        <div class="profile-cover relative w-full h-[180px] sm:h-[240px] md:h-[300px] overflow-hidden bg-gradient-to-br from-indigo-400 to-purple-500">
            <img src="{{ $user->cover_photo_url }}" alt="{{ $user->name }} Cover"
                 class="w-full h-full object-cover transition-transform duration-500 ease-out" />
            <div class="absolute inset-0 bg-gradient-to-b from-transparent via-transparent to-slate-900/20 pointer-events-none"></div>
        </div>

        <div class="max-w-5xl mx-auto px-4 sm:px-6">
            <div class="flex items-end justify-between -mt-[43px] sm:-mt-[67px] md:-mt-[70px]">
                <div class="w-[86px] h-[86px] sm:w-[134px] sm:h-[134px] md:w-[140px] md:h-[140px] rounded-full border-[4px] border-white bg-white overflow-hidden shadow-lg shrink-0 z-10">
                    <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="w-full h-full object-cover rounded-full" />
                </div>

                <div class="flex items-center gap-2 pb-0 sm:pb-2">
                    @auth
                        @if(auth()->user()->id_user !== $user->id_user)
                            @livewire('follow-button', ['user' => $user])
                            <button id="btn-share-profile"
                                    class="group flex items-center justify-center w-9 h-9 rounded-full border border-slate-200 bg-white text-slate-600 hover:text-brand-500 hover:border-brand-300 hover:bg-brand-50/20 transition-all duration-300 shadow-sm active:scale-95 cursor-pointer"
                                    title="{{ __('Share') }}">
                                <i class="fas fa-share-from-square text-[14px] transition-transform duration-300 group-hover:scale-110"></i>
                            </button>
                        @else
                            <a href="{{ url('/settings') }}"
                               class="group inline-flex items-center justify-center gap-2 px-5 h-9 rounded-full border border-slate-200 bg-white text-sm font-bold text-slate-700 hover:text-slate-900 hover:bg-slate-50 hover:border-slate-300 transition-all duration-300 active:scale-95 shadow-sm">
                                <i class="fas fa-gear text-xs transition-transform duration-500 group-hover:rotate-45"></i>
                                <span>{{ __('Pengaturan') }}</span>
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}"
                           class="btn-follow inline-flex items-center justify-center gap-2 px-5 h-9 rounded-full bg-brand-500 hover:bg-brand-600 text-white text-sm font-bold shadow-sm shadow-brand-500/10 transition-all duration-300 active:scale-95 cursor-pointer">
                            <i class="fas fa-user-plus text-xs"></i>
                            <span>Follow</span>
                        </a>
                    @endauth
                </div>
            </div>
        </div>

        <div class="max-w-5xl mx-auto px-4 sm:px-6 mt-3">
            <div class="flex items-center gap-1.5 flex-wrap">
                <h1 class="text-xl font-extrabold text-slate-900 leading-tight">{{ $user->name }}</h1>
                @if($user->is_verified)
                    <span class="text-brand-500 text-lg" title="{{ __('Verified') }}">
                        <i class="fas fa-circle-check"></i>
                    </span>
                @endif
            </div>

            <p class="text-[15px] text-slate-500 mt-0.5">{{ '@' . $user->username }}</p>

            @if($user->bio)
                <p class="mt-3 text-[15px] leading-relaxed text-slate-900 break-words max-w-2xl">{{ $user->bio }}</p>
            @endif

            <div class="flex flex-wrap gap-x-4 gap-y-1 mt-3 text-sm text-slate-500">
                @if($user->location)
                    <span class="inline-flex items-center gap-1.5">
                        <i class="fas fa-location-dot text-slate-400 text-xs w-4 text-center"></i>
                        {{ $user->location }}
                    </span>
                @endif
                <span class="inline-flex items-center gap-1.5">
                    <i class="fas fa-calendar-days text-slate-400 text-xs w-4 text-center"></i>
                    {{ __('Joined') }} {{ $user->created_at->format('F Y') }}
                </span>
            </div>

            <div class="flex gap-5 mt-3 text-sm" x-data="{ showFollowersModal: false, showFollowingModal: false }">
                {{-- Following --}}
                @if($showFollowing)
                    <button @click="showFollowingModal = true" class="text-slate-500 hover:underline cursor-pointer text-left">
                        <span class="font-bold text-slate-900">{{ $followingCount }}</span>
                        Mengikuti
                    </button>
                @else
                    <span class="text-slate-400 cursor-default text-left flex items-center gap-1">
                        <i class="fas fa-lock text-xs"></i> Mengikuti
                    </span>
                @endif

                {{-- Followers --}}
                @if($showFollowers)
                    <button @click="showFollowersModal = true" class="text-slate-500 hover:underline cursor-pointer text-left">
                        <span class="font-bold text-slate-900">{{ $followersCount }}</span>
                        Followers
                    </button>
                @else
                    <span class="text-slate-400 cursor-default flex items-center gap-1">
                        <i class="fas fa-lock text-xs"></i> Followers
                    </span>
                @endif

                {{-- Modal: Following --}}
                @if($showFollowing)
                    <div x-show="showFollowingModal" x-transition.opacity
                         class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4"
                         @click.self="showFollowingModal = false">
                        <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm max-h-[80vh] flex flex-col">
                            <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
                                <h3 class="text-base font-bold text-slate-900">Mengikuti ({{ $followingCount }})</h3>
                                <button @click="showFollowingModal = false" class="text-slate-400 hover:text-slate-700 transition">
                                    <i class="fas fa-xmark text-lg"></i>
                                </button>
                            </div>
                            <div class="overflow-y-auto flex-1 divide-y divide-slate-50">
                                @forelse($followingList as $u)
                                    <a href="{{ url('/@' . $u->username) }}" @click="showFollowingModal = false"
                                       class="flex items-center gap-3 px-5 py-3 hover:bg-slate-50 transition">
                                        <img src="{{ $u->profile_photo_url }}" class="w-10 h-10 rounded-full object-cover border border-slate-200 shrink-0">
                                        <div class="min-w-0">
                                            <p class="text-sm font-semibold text-slate-900 truncate">{{ $u->full_name ?? $u->name }}</p>
                                            <p class="text-xs text-slate-400 truncate">{{ '@' . $u->username }}</p>
                                        </div>
                                    </a>
                                @empty
                                    <div class="py-10 text-center text-sm text-slate-400">Belum mengikuti siapapun.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Modal: Followers --}}
                @if($showFollowers)
                    <div x-show="showFollowersModal" x-transition.opacity
                         class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4"
                         @click.self="showFollowersModal = false">
                        <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm max-h-[80vh] flex flex-col">
                            <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
                                <h3 class="text-base font-bold text-slate-900">Followers ({{ $followersCount }})</h3>
                                <button @click="showFollowersModal = false" class="text-slate-400 hover:text-slate-700 transition">
                                    <i class="fas fa-xmark text-lg"></i>
                                </button>
                            </div>
                            <div class="overflow-y-auto flex-1 divide-y divide-slate-50">
                                @forelse($followersList as $u)
                                    <a href="{{ url('/@' . $u->username) }}" @click="showFollowersModal = false"
                                       class="flex items-center gap-3 px-5 py-3 hover:bg-slate-50 transition">
                                        <img src="{{ $u->profile_photo_url }}" class="w-10 h-10 rounded-full object-cover border border-slate-200 shrink-0">
                                        <div class="min-w-0">
                                            <p class="text-sm font-semibold text-slate-900 truncate">{{ $u->full_name ?? $u->name }}</p>
                                            <p class="text-xs text-slate-400 truncate">{{ '@' . $u->username }}</p>
                                        </div>
                                    </a>
                                @empty
                                    <div class="py-10 text-center text-sm text-slate-400">Belum ada followers.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="border-b border-slate-200 mt-4">
            <nav class="max-w-5xl mx-auto px-4 sm:px-6 flex overflow-x-auto scrollbar-none">
                <a href="javascript:void(0)" class="profile-tab active relative mr-8 flex items-center justify-center py-4 text-sm font-semibold text-slate-500 hover:text-slate-900 transition whitespace-nowrap cursor-pointer" data-tab="campaigns">
                    {{ __('Campaigns') }}
                </a>
                @if($isOwner)
                    <a href="javascript:void(0)" class="profile-tab relative mr-8 flex items-center justify-center py-4 text-sm font-semibold text-slate-500 hover:text-slate-900 transition whitespace-nowrap cursor-pointer" data-tab="liked">
                        {{ __('Liked') }}
                    </a>
                    <a href="javascript:void(0)" class="profile-tab relative mr-8 flex items-center justify-center py-4 text-sm font-semibold text-slate-500 hover:text-slate-900 transition whitespace-nowrap cursor-pointer" data-tab="comments">
                        {{ __('Comments') }}
                    </a>
                @endif
                <a href="javascript:void(0)" class="profile-tab relative flex items-center justify-center py-4 text-sm font-semibold text-slate-500 hover:text-slate-900 transition whitespace-nowrap cursor-pointer" data-tab="about">
                    {{ __('About') }}
                </a>
            </nav>
        </div>

        <div class="max-w-5xl mx-auto px-4 sm:px-6 mt-4 pb-16">

            <div id="tab-content-campaigns" class="tab-pane">
                @if($campaigns->isEmpty())
                    <div class="py-16 text-center">
                        <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-slate-100 text-slate-400 text-2xl mb-4">
                            <i class="fas fa-hand-holding-heart"></i>
                        </div>
                        @if($isOwner)
                            <h3 class="text-base font-bold text-slate-900">Belum ada kampanye</h3>
                            <p class="text-sm text-slate-500 mt-1.5 max-w-[340px] mx-auto leading-relaxed">Buat kampanye pertama kamu untuk mulai menggalang dana.</p>
                            <a href="{{ url('/campaigns/create') }}"
                               class="inline-flex items-center gap-1.5 mt-5 px-5 py-2.5 rounded-full bg-brand-500 text-white text-sm font-bold hover:bg-brand-600 transition shadow-md shadow-brand-500/20">
                                <i class="fas fa-plus text-xs"></i>
                                Buat Kampanye
                            </a>
                        @else
                            <h3 class="text-base font-bold text-slate-900">{{ __('No active campaigns') }}</h3>
                            <p class="text-sm text-slate-500 mt-1.5 max-w-[340px] mx-auto leading-relaxed">{{ __('When this creator starts a campaign, it will appear here for you to support.') }}</p>
                        @endif
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($campaigns as $campaign)
                            <x-campaign-card
                                :campaign="$campaign"
                                :liked="in_array($campaign->id_campaign, $likedIds)"
                                :likesCount="$campaign->likes_count"
                            />
                        @endforeach
                    </div>
                @endif
            </div>

            @if($isOwner)
                <div id="tab-content-liked" class="tab-pane" style="display: none;">
                    @if($likedCampaigns->isEmpty())
                        <div class="py-16 text-center">
                            <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-slate-100 text-rose-400 text-2xl mb-4">
                                <i class="fas fa-heart"></i>
                            </div>
                            <h3 class="text-base font-bold text-slate-900">Belum ada kampanye yang disukai</h3>
                            <p class="text-sm text-slate-500 mt-1.5 max-w-[340px] mx-auto leading-relaxed">Kampanye yang kamu sukai akan muncul di sini.</p>
                            <a href="{{ url('/campaigns') }}"
                               class="inline-flex items-center gap-1.5 mt-5 px-5 py-2.5 rounded-full bg-rose-500 text-white text-sm font-bold hover:bg-rose-600 transition shadow-md shadow-rose-500/20">
                                <i class="fas fa-search text-xs"></i>
                                Jelajahi Kampanye
                            </a>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($likedCampaigns as $campaign)
                                <x-campaign-card
                                    :campaign="$campaign"
                                    :liked="true"
                                    :likesCount="$campaign->likes_count"
                                />
                            @endforeach
                        </div>
                    @endif
                </div>

                <div id="tab-content-comments" class="tab-pane" style="display: none;">
                    @if($userComments->isEmpty())
                        <div class="py-16 text-center">
                            <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-slate-100 text-blue-400 text-2xl mb-4">
                                <i class="fas fa-comments"></i>
                            </div>
                            <h3 class="text-base font-bold text-slate-900">Belum ada komentar</h3>
                            <p class="text-sm text-slate-500 mt-1.5 max-w-[340px] mx-auto leading-relaxed">Komentar yang kamu tulis pada kampanye akan muncul di sini.</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($userComments as $comment)
                                @if($comment->campaign)
                                    <div class="flex gap-4 p-4 bg-slate-50 rounded-2xl border border-slate-100 hover:border-slate-200 transition">
                                        <div class="shrink-0">
                                            @if($comment->campaign->banner_image)
                                                <img src="{{ asset('storage/' . $comment->campaign->banner_image) }}"
                                                     class="w-16 h-16 rounded-xl object-cover border border-slate-200"
                                                     alt="{{ $comment->campaign->title }}">
                                            @else
                                                <div class="w-16 h-16 rounded-xl bg-slate-200 flex items-center justify-center">
                                                    <i class="fas fa-hand-holding-heart text-slate-400 text-xl"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <a href="{{ url('/campaigns/' . $comment->campaign->slug) }}"
                                               class="text-sm font-bold text-slate-900 hover:text-brand-600 truncate block leading-tight mb-1">
                                                {{ $comment->campaign->title }}
                                            </a>
                                            <p class="text-[13px] text-slate-700 leading-relaxed line-clamp-2">{{ $comment->comment }}</p>
                                            <p class="text-xs text-slate-400 mt-1.5">{{ $comment->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif

            <div id="tab-content-about" class="tab-pane" style="display: none;">
                <div class="py-5 space-y-6">
                    <div>
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">{{ __('Bio') }}</h3>
                        <p class="text-[15px] leading-relaxed text-slate-900 max-w-2xl">{{ $user->bio ?? __('This creator hasn\'t added a bio yet.') }}</p>
                    </div>

                    <div>
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">{{ __('Details') }}</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @if($user->location)
                                <div class="flex items-center gap-3 p-3.5 rounded-2xl bg-slate-50 border border-slate-200 hover:border-slate-300 transition">
                                    <span class="flex items-center justify-center w-[38px] h-[38px] rounded-xl bg-brand-100 text-brand-500 text-[15px] shrink-0"><i class="fas fa-location-dot"></i></span>
                                    <div>
                                        <p class="text-xs text-slate-400 font-medium">{{ __('Location') }}</p>
                                        <p class="text-sm font-semibold text-slate-900 mt-0.5">{{ $user->location }}</p>
                                    </div>
                                </div>
                            @endif
                            <div class="flex items-center gap-3 p-3.5 rounded-2xl bg-slate-50 border border-slate-200 hover:border-slate-300 transition">
                                <span class="flex items-center justify-center w-[38px] h-[38px] rounded-xl bg-green-100 text-brand-500 text-[15px] shrink-0"><i class="fas fa-calendar-days"></i></span>
                                <div>
                                    <p class="text-xs text-slate-400 font-medium">{{ __('Joined') }}</p>
                                    <p class="text-sm font-semibold text-slate-900 mt-0.5">{{ $user->created_at->format('d F Y') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 p-3.5 rounded-2xl bg-slate-50 border border-slate-200 hover:border-slate-300 transition">
                                <span class="flex items-center justify-center w-[38px] h-[38px] rounded-xl bg-amber-100 text-amber-600 text-[15px] shrink-0"><i class="fas fa-bullhorn"></i></span>
                                <div>
                                    <p class="text-xs text-slate-400 font-medium">{{ __('Total Campaigns') }}</p>
                                    <p class="text-sm font-semibold text-slate-900 mt-0.5">{{ $campaignCount }} {{ __('campaigns') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 p-3.5 rounded-2xl bg-slate-50 border border-slate-200 hover:border-slate-300 transition">
                                <span class="flex items-center justify-center w-[38px] h-[38px] rounded-xl bg-pink-100 text-pink-600 text-[15px] shrink-0"><i class="fas fa-coins"></i></span>
                                <div>
                                    <p class="text-xs text-slate-400 font-medium">{{ __('Total Collected') }}</p>
                                    <p class="text-sm font-semibold text-slate-900 mt-0.5">Rp {{ number_format($totalDonationsReceived, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">{{ __('Visibility') }}</h3>
                        <div class="flex items-center gap-3 p-3.5 rounded-2xl bg-slate-50 border border-slate-200 max-w-sm">
                            <span class="flex items-center justify-center w-[38px] h-[38px] rounded-xl bg-green-100 text-brand-500 text-[15px] shrink-0">
                                <i class="fas fa-{{ $settings->show_profile_publicly ? 'globe' : 'lock' }}"></i>
                            </span>
                            <div>
                                <p class="text-xs text-slate-400 font-medium">{{ __('Profile Visibility') }}</p>
                                <p class="text-sm font-semibold text-slate-900 mt-0.5">{{ $settings->show_profile_publicly ? __('Public profile') : __('Private profile') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="custom-toast" class="fixed bottom-6 left-1/2 -translate-x-1/2 translate-y-[100px] opacity-0 pointer-events-none bg-slate-900 text-white px-6 py-3 rounded-full shadow-xl text-sm font-semibold flex items-center gap-2 z-[9999] transition-all duration-400 ease-out">
        <i class="fas fa-check-circle text-brand-400 text-[15px]"></i>
        <span class="toast-message">Link profil berhasil disalin!</span>
    </div>

    <style>
        #custom-toast.show {
            transform: translateX(-50%) translateY(0) !important;
            opacity: 1 !important;
            pointer-events: auto !important;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tabs = document.querySelectorAll('.profile-tab');
            const tabPanes = document.querySelectorAll('.tab-pane');

            tabs.forEach(tab => {
                tab.addEventListener('click', function () {
                    tabs.forEach(t => {
                        t.classList.remove('active');
                        t.classList.remove('text-slate-900');
                        t.classList.add('text-slate-500');
                    });
                    this.classList.add('active');
                    this.classList.remove('text-slate-500');
                    this.classList.add('text-slate-900');

                    tabPanes.forEach(pane => pane.style.display = 'none');

                    const targetTabId = 'tab-content-' + this.getAttribute('data-tab');
                    const targetPane = document.getElementById(targetTabId);
                    if (targetPane) {
                        targetPane.style.display = 'block';
                    }
                });
            });

            const shareBtn = document.getElementById('btn-share-profile');
            if (shareBtn) {
                shareBtn.addEventListener('click', function () {
                    const shareData = {
                        title: "{{ $user->name }} on " + "{{ config('app.name') }}",
                        text: "Bantu dukung perjuangan dan kampanye dari " + "{{ $user->name }}",
                        url: window.location.href
                    };

                    if (navigator.share) {
                        navigator.share(shareData)
                            .catch((err) => console.log('Share failed', err));
                    } else {
                        navigator.clipboard.writeText(window.location.href)
                            .then(() => {
                                showToast('Link profil berhasil disalin ke clipboard!');
                            })
                            .catch(err => {
                                console.error('Failed to copy text: ', err);
                            });
                    }
                });
            }

            function showToast(message) {
                const toast = document.getElementById('custom-toast');
                if (toast) {
                    toast.querySelector('.toast-message').textContent = message;
                    toast.classList.add('show');

                    setTimeout(() => {
                        toast.classList.remove('show');
                    }, 3000);
                }
            }
        });
    </script>
</x-app-layout>