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

                <div class="flex items-center gap-2 pb-2">
                    @auth
                        @if(auth()->user()->id_user !== $user->id_user)
                            @livewire('follow-button', ['user' => $user])
                            <button id="btn-share-profile"
                                    class="flex items-center justify-center w-9 h-9 rounded-full border border-slate-200 bg-white text-slate-900 text-[15px] hover:bg-slate-50 transition cursor-pointer"
                                    title="{{ __('Share') }}">
                                <i class="fas fa-share-from-square"></i>
                            </button>
                        @else
                            <a href="{{ url('/settings') }}"
                               class="inline-flex items-center gap-1.5 px-[18px] py-2 rounded-full border border-slate-200 bg-white text-sm font-bold text-slate-900 hover:bg-slate-50 transition">
                                <i class="fas fa-gear text-xs"></i>
                                {{ __('Pengaturan') }}
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}"
                           class="btn-follow inline-flex items-center gap-1.5 px-5 py-2 rounded-full bg-slate-900 text-white text-sm font-bold hover:bg-slate-800 transition">
                            <i class="fas fa-user-plus text-xs"></i>
                            <span class="text">{{ __('Follow') }}</span>
                        </a>
                    @endauth
                </div>
            </div>
        </div>

        <div class="max-w-5xl mx-auto px-4 sm:px-6 mt-3">
            <div class="flex items-center gap-1.5 flex-wrap">
                <h1 class="text-xl font-extrabold text-slate-900 leading-tight">{{ $user->name }}</h1>
                @if($user->is_verified)
                    <span class="text-indigo-600 text-lg" title="{{ __('Verified') }}">
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

            <div class="flex gap-5 mt-3 text-sm">
                <span class="text-slate-500 hover:underline cursor-default">
                    <span class="font-bold text-slate-900" id="following-count">{{ $followingCount !== null ? $followingCount : '0' }}</span>
                    {{ __('Following') }}
                </span>
                <span class="text-slate-500 hover:underline cursor-default">
                    <span class="font-bold text-slate-900" id="followers-count">{{ $followersCount !== null ? $followersCount : '0' }}</span>
                    {{ __('Followers') }}
                </span>
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

        <div class="max-w-5xl mx-auto px-4 sm:px-6 mt-4">

            <div id="tab-content-campaigns" class="tab-pane">
                @if($campaigns->isEmpty())
                    <div class="py-16 text-center">
                        <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-slate-100 text-slate-400 text-2xl mb-4">
                            <i class="fas fa-hand-holding-heart"></i>
                        </div>
                        <h3 class="text-base font-bold text-slate-900">{{ __('No active campaigns') }}</h3>
                        <p class="text-sm text-slate-500 mt-1.5 max-w-[340px] mx-auto leading-relaxed">{{ __('When this creator starts a campaign, it will appear here for you to support.') }}</p>
                        @auth
                            @if(auth()->user()->id_user === $user->id_user)
                                <a href="{{ url('/campaigns/create') }}"
                                   class="inline-flex items-center gap-1.5 mt-5 px-5 py-2.5 rounded-full bg-indigo-600 text-white text-sm font-bold hover:bg-indigo-700 transition shadow-md shadow-indigo-600/20">
                                    <i class="fas fa-plus text-xs"></i>
                                    {{ __('Create Campaign') }}
                                </a>
                            @endif
                        @endauth
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($campaigns as $campaign)
                            <a href="{{ route('campaigns.show', $campaign->slug) }}" class="block p-4 border border-slate-100 rounded-2xl hover:bg-slate-50/50 hover:shadow-sm transition">
                                <div class="flex gap-3 flex-col h-full justify-between">
                                    <div>
                                        <div class="flex items-center gap-2 text-sm flex-wrap mb-2">
                                            <div class="shrink-0 w-7 h-7 rounded-full overflow-hidden">
                                                <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="w-full h-full object-cover" />
                                            </div>
                                            <span class="font-bold text-slate-900 text-xs">{{ $user->name }}</span>
                                            <span class="text-slate-400 text-xs">·</span>
                                            <span class="text-slate-500 text-xs">{{ $campaign->created_at->diffForHumans(null, true) }}</span>
                                        </div>

                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-indigo-50 text-indigo-600 text-xs font-semibold">
                                            <i class="fas fa-tag text-[10px]"></i>
                                            {{ $campaign->category->name ?? 'Umum' }}
                                        </span>

                                        <h3 class="mt-2 text-base font-bold text-slate-900 leading-snug line-clamp-1">{{ $campaign->title }}</h3>

                                        @if($campaign->short_description ?? ($campaign->description ?? null))
                                            <p class="mt-1 text-sm text-slate-500 leading-relaxed line-clamp-2">{{ Str::limit(strip_tags($campaign->short_description ?? $campaign->description ?? ''), 140) }}</p>
                                        @endif

                                        @if($campaign->banner_image)
                                            <div class="mt-3 rounded-xl overflow-hidden border border-slate-200 h-[180px]">
                                                <img src="{{ asset('storage/' . $campaign->banner_image) }}" alt="{{ $campaign->title }}" class="w-full h-full object-cover" />
                                            </div>
                                        @endif
                                    </div>

                                    <div class="mt-4">
                                        <div class="h-1.5 bg-slate-200 rounded-full overflow-hidden">
                                            <div class="h-full bg-gradient-to-r from-emerald-500 to-emerald-400 rounded-full transition-all duration-500" style="width: {{ min(100, $campaign->progress_percentage) }}%"></div>
                                        </div>
                                        <div class="flex items-center justify-between mt-2 text-xs text-slate-500">
                                            <span><strong class="text-slate-900 font-bold">Rp {{ number_format($campaign->collected_amount, 0, ',', '.') }}</strong> terkumpul</span>
                                            <span>{{ $campaign->donor_count }} {{ __('donatur') }}</span>
                                        </div>

                                        <div class="flex justify-between mt-3 pt-2 border-t border-slate-100">
                                            <span class="inline-flex items-center gap-1.5 text-slate-500 text-xs hover:text-indigo-600 transition cursor-pointer">
                                                <i class="far fa-heart"></i>
                                            </span>
                                            <span class="inline-flex items-center gap-1.5 text-slate-500 text-xs hover:text-indigo-600 transition cursor-pointer">
                                                <i class="far fa-comment"></i>
                                            </span>
                                            <span class="inline-flex items-center gap-1.5 text-slate-500 text-xs hover:text-indigo-600 transition cursor-pointer">
                                                <i class="fas fa-arrow-up-from-bracket"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            @if($isOwner)
                <div id="tab-content-liked" class="tab-pane" style="display: none;">
                    <div class="py-12 text-center">
                        <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-slate-100 text-rose-500 text-2xl mb-4">
                            <i class="fas fa-heart"></i>
                        </div>
                        <h3 class="text-base font-bold text-slate-900">{{ __('Kampanye yang Disukai') }}</h3>
                    </div>
                </div>

                <div id="tab-content-comments" class="tab-pane" style="display: none;">
                    <div class="py-12 text-center">
                        <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-slate-100 text-blue-500 text-2xl mb-4">
                            <i class="fas fa-comments"></i>
                        </div>
                        <h3 class="text-base font-bold text-slate-900">{{ __('Komentar Saya') }}</h3>
                    </div>
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
                                    <span class="flex items-center justify-center w-[38px] h-[38px] rounded-xl bg-indigo-100 text-indigo-600 text-[15px] shrink-0"><i class="fas fa-location-dot"></i></span>
                                    <div>
                                        <p class="text-xs text-slate-400 font-medium">{{ __('Location') }}</p>
                                        <p class="text-sm font-semibold text-slate-900 mt-0.5">{{ $user->location }}</p>
                                    </div>
                                </div>
                            @endif
                            <div class="flex items-center gap-3 p-3.5 rounded-2xl bg-slate-50 border border-slate-200 hover:border-slate-300 transition">
                                <span class="flex items-center justify-center w-[38px] h-[38px] rounded-xl bg-green-100 text-green-600 text-[15px] shrink-0"><i class="fas fa-calendar-days"></i></span>
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
                            <span class="flex items-center justify-center w-[38px] h-[38px] rounded-xl bg-green-100 text-green-600 text-[15px] shrink-0">
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
        <i class="fas fa-check-circle text-emerald-400 text-[15px]"></i>
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