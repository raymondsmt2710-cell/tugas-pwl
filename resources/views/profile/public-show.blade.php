<x-app-layout>
    @php
        $twitterUrl = '#';
        if (!empty($user->social_links['twitter'])) {
            $twitter = $user->social_links['twitter'];
            $twitterUrl = (filter_var($twitter, FILTER_VALIDATE_URL) ? $twitter : 'https://twitter.com/' . ltrim($twitter, '@'));
        }

        $facebookUrl = '#';
        if (!empty($user->social_links['facebook'])) {
            $facebook = $user->social_links['facebook'];
            $facebookUrl = (filter_var($facebook, FILTER_VALIDATE_URL) ? $facebook : 'https://facebook.com/' . ltrim($facebook, '@'));
        }

        $instagramUrl = '#';
        if (!empty($user->social_links['instagram'])) {
            $instagram = $user->social_links['instagram'];
            $instagramUrl = (filter_var($instagram, FILTER_VALIDATE_URL) ? $instagram : 'https://instagram.com/' . ltrim($instagram, '@'));
        }
    @endphp
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">

    <style>
        /* Toast Notification Styling */
        .toast-container {
            position: fixed;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%) translateY(100px);
            background: #0f172a;
            color: #ffffff;
            padding: 14px 28px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.15);
            font-size: 14.5px;
            font-weight: 700;
            z-index: 9999;
            display: flex;
            align-items: center;
            gap: 10px;
            opacity: 0;
            pointer-events: none;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 1px solid #1e293b;
        }
        .toast-container.show {
            transform: translateX(-50%) translateY(0);
            opacity: 1;
            pointer-events: auto;
        }
        .toast-icon {
            color: #10b981;
            font-size: 16px;
        }

        /* Follow Active Status overrides */
        .btn-follow.following {
            background: #ecfdf5;
            color: #047857;
            border: 1px solid #a7f3d0;
            box-shadow: none;
        }
        .btn-follow.following:hover {
            background: #fee2e2;
            color: #b91c1c;
            border-color: #fecaca;
        }

        /* Tab switching animation */
        .tab-pane {
            animation: fadeInTab 0.35s ease-out;
        }
        @keyframes fadeInTab {
            from { opacity: 0; transform: translateY(4px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>

    <div class="profile-page">
        <!-- Cover Photo -->
        <div class="profile-cover">
            <img src="{{ $user->cover_photo_url }}" alt="Cover Photo">
            <div class="profile-cover-overlay"></div>
        </div>

        <!-- Profile Header -->
        <div style="background: #fff; border-bottom: 1px solid #f3f4f6;">
            <div class="profile-info-wrapper">
                <div class="profile-avatar-row">
                    <!-- Avatar -->
                    <div class="profile-avatar-wrapper">
                        <div class="profile-avatar">
                            <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}">
                        </div>
                    </div>

                    <!-- Details -->
                    <div class="profile-details">
                        <h1 class="profile-name">
                            {{ $user->name }}
                            @if($user->is_verified)
                                <i class="fas fa-check-circle" style="color: #02a95c; font-size: 20px;" title="Verified Creator"></i>
                            @endif
                        </h1>
                        <div class="profile-username">{{ '@' . $user->username }}</div>
                        <div class="profile-meta">
                            @if($user->is_verified)
                                <span class="profile-badge"><i class="fas fa-check-shield" style="margin-right: 4px;"></i> Verified Creator</span>
                            @endif
                            <span class="profile-meta-item"><i class="fas fa-calendar-alt"></i> Joined {{ $user->created_at->format('M Y') }}</span>
                            @if($user->location)
                                <span class="profile-meta-item"><i class="fas fa-map-marker-alt"></i> {{ $user->location }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="profile-actions">
                        @auth
                            @if(auth()->user()->id_user !== $user->id_user)
                                @livewire('follow-button', ['user' => $user])
                                <button class="btn-share" id="btn-share-profile" title="Share Profile"><i class="fas fa-share-alt"></i></button>
                            @else
                                <a href="{{ route('profile.show') }}" class="btn-edit"><i class="fas fa-cog"></i>Settings</a>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn-follow"><i class="fas fa-user-plus"></i> Follow</a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="profile-tabs">
            <div class="profile-tabs-inner">
                <a href="javascript:void(0)" class="profile-tab active" data-tab="campaigns">Campaigns</a>
                @if($isOwner)
                    <a href="javascript:void(0)" class="profile-tab" data-tab="donations">Donations</a>
                    <a href="javascript:void(0)" class="profile-tab" data-tab="withdrawals">Withdrawals</a>
                    <a href="javascript:void(0)" class="profile-tab" data-tab="liked">Liked</a>
                    <a href="javascript:void(0)" class="profile-tab" data-tab="comments">Comments</a>
                @endif
                <a href="javascript:void(0)" class="profile-tab" data-tab="updates">Updates</a>
                <a href="javascript:void(0)" class="profile-tab" data-tab="about">About</a>
            </div>
        </div>

        <!-- Content -->
        <div class="profile-content">
            <!-- Sidebar -->
            <div>
                <!-- About Card -->
                <div class="card" style="margin-bottom: 24px;">
                    <div class="card-body">
                        <h2 class="card-title">About</h2>
                        <p class="about-text">{{ $user->bio ?? 'This creator hasn\'t added a bio yet.' }}</p>

                        <div style="margin-top: 24px;">
                            <div class="info-row">
                                <div class="info-icon"><i class="fas fa-map-marker-alt"></i></div>
                                <span class="info-text">{{ $user->location ?? 'Indonesia' }}</span>
                            </div>
                            <div class="info-row">
                                <div class="info-icon"><i class="fas fa-calendar-alt"></i></div>
                                <span class="info-text">Joined since {{ $user->created_at->format('F Y') }}</span>
                            </div>
                        </div>

                        <div class="social-row">
                            @if($twitterUrl !== '#')
                                <a href="{{ $twitterUrl }}" target="_blank" class="social-btn" title="Twitter"><i class="fab fa-twitter"></i></a>
                            @endif
                            @if($facebookUrl !== '#')
                                <a href="{{ $facebookUrl }}" target="_blank" class="social-btn" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                            @endif
                            @if($instagramUrl !== '#')
                                <a href="{{ $instagramUrl }}" target="_blank" class="social-btn" title="Instagram"><i class="fab fa-instagram"></i></a>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Stats Card -->
                <div class="card">
                    <div class="stats-header">
                        <div class="stats-header-label">Donations Collected</div>
                        <div class="stats-header-value">Rp {{ number_format($totalDonationsReceived, 0, ',', '.') }}</div>
                    </div>
                    <div class="stats-grid">
                        <div class="stats-item">
                            <div class="stats-number">{{ $campaignCount }}</div>
                            <div class="stats-label">Campaign</div>
                        </div>
                        <div class="stats-item">
                            <div class="stats-number" id="followers-count">{{ $followersCount }}</div>
                            <div class="stats-label">Followers</div>
                        </div>
                        <div class="stats-item">
                            <div class="stats-number" id="following-count">{{ $followingCount }}</div>
                            <div class="stats-label">Following</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Area with Dynamic TabPanes -->
            <div class="profile-main-content">
                <!-- Tab Pane: Campaigns -->
                <div id="tab-content-campaigns" class="tab-pane">
                    @if($campaigns->isEmpty())
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="fas fa-hand-holding-heart"></i>
                            </div>
                            <h3 class="empty-title">No active campaigns</h3>
                            <p class="empty-desc">When this creator starts a campaign, it will appear here for you to support.</p>
                            @auth
                                @if(auth()->user()->id_user === $user->id_user)
                                    <a href="{{ url('/campaigns/create') }}" class="btn-create"><i class="fas fa-plus"></i> Create Campaign</a>
                                @endif
                            @endauth
                        </div>
                    @else
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px;">
                            @foreach($campaigns as $campaign)
                                <a href="{{ route('campaigns.show', $campaign->slug) }}" class="card" style="text-decoration: none; color: inherit; transition: transform 0.2s, box-shadow 0.2s;">
                                    @if($campaign->banner_image)
                                        <img src="{{ asset('storage/' . $campaign->banner_image) }}" alt="{{ $campaign->title }}" style="width: 100%; height: 160px; object-fit: cover; border-radius: 12px 12px 0 0;">
                                    @endif
                                    <div class="card-body" style="padding: 16px;">
                                        <span style="font-size: 11px; font-weight: 600; color: #6366f1; background: #eef2ff; padding: 3px 8px; border-radius: 20px;">{{ $campaign->category->name ?? 'Umum' }}</span>
                                        <h3 style="font-size: 15px; font-weight: 700; margin: 10px 0 6px; color: #111827;">{{ $campaign->title }}</h3>
                                        <div style="background: #f3f4f6; border-radius: 8px; height: 6px; margin: 12px 0 8px; overflow: hidden;">
                                            <div style="background: #10b981; height: 100%; width: {{ min(100, $campaign->progress_percentage) }}%; border-radius: 8px;"></div>
                                        </div>
                                        <div style="display: flex; justify-content: space-between; font-size: 12px; color: #6b7280;">
                                            <span><strong style="color: #111827;">Rp {{ number_format($campaign->collected_amount, 0, ',', '.') }}</strong></span>
                                            <span>{{ $campaign->donor_count }} donatur</span>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                        @auth
                            @if(auth()->user()->id_user === $user->id_user)
                                <div style="margin-top: 20px; text-align: center;">
                                    <a href="{{ url('/campaigns/create') }}" class="btn-create"><i class="fas fa-plus"></i> Create Campaign</a>
                                </div>
                            @endif
                        @endauth
                    @endif
                </div>

                <!-- Tab Pane: Donations -->
                <!-- Tab Pane: Donations (owner only) -->
                @if($isOwner)
                    <div id="tab-content-donations" class="tab-pane" style="display: none;">
                        @if($donations->isEmpty())
                            <div class="empty-state">
                                <div class="empty-icon" style="background: #ecfdf5; color: #02a95c;">
                                    <i class="fas fa-hand-holding-dollar"></i>
                                </div>
                                <h3 class="empty-title">Belum ada donasi</h3>
                                <p class="empty-desc">Donasi yang Anda berikan akan muncul di sini.</p>
                            </div>
                        @else
                            <div style="display: flex; flex-direction: column; gap: 10px;">
                                @foreach($donations as $donation)
                                    <a href="{{ url('/donations/' . $donation->order_id . '/track') }}"
                                       class="card" style="padding: 16px; text-decoration: none; color: inherit; cursor: pointer;">
                                        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                                            <div style="flex: 1; min-width: 0;">
                                                <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                                                    <p style="font-size: 14px; font-weight: 600; color: #111827; margin: 0;">
                                                        {{ $donation->campaign->title ?? 'Kampanye Dihapus' }}
                                                    </p>
                                                    <span style="font-size: 10px; font-weight: 600; padding: 2px 8px; border-radius: 20px;
                                                        @switch($donation->payment_status)
                                                            @case('paid') background: #ecfdf5; color: #059669; @break
                                                            @case('pending') background: #fef9c3; color: #a16207; @break
                                                            @case('expired') background: #f3f4f6; color: #6b7280; @break
                                                            @default background: #fef2f2; color: #dc2626;
                                                        @endswitch
                                                    ">
                                                        @switch($donation->payment_status)
                                                            @case('paid') Berhasil @break
                                                            @case('pending') Pending @break
                                                            @case('expired') Kedaluwarsa @break
                                                            @default Gagal
                                                        @endswitch
                                                    </span>
                                                    @if($donation->is_anonymous)
                                                        <span style="font-size: 10px; background: #f3f4f6; color: #6b7280; padding: 2px 6px; border-radius: 4px;">Anonim</span>
                                                    @endif
                                                </div>
                                                <p style="font-size: 12px; color: #6b7280; margin: 4px 0 0;">
                                                    {{ $donation->created_at->format('d M Y, H:i') }} • {{ $donation->order_id }}
                                                </p>
                                            </div>
                                            <div style="text-align: right; margin-left: 12px;">
                                                <span style="font-size: 14px; font-weight: 700; color: #059669;">{{ $donation->formatted_amount }}</span>
                                                <p style="font-size: 11px; color: #6366f1; margin: 2px 0 0; font-weight: 500;">Detail →</p>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                            <div style="margin-top: 16px; text-align: center;">
                                <a href="{{ url('/my-donations') }}" style="font-size: 13px; font-weight: 600; color: #6366f1; text-decoration: none;">Lihat Semua Riwayat →</a>
                            </div>
                        @endif
                    </div>

                    <!-- Tab Pane: Withdrawals (owner only) -->
                    @php
                        $myWithdrawals = \App\Models\Withdrawal::byUser($user->id_user)->with('campaign')->latest()->take(10)->get();
                    @endphp
                    <div id="tab-content-withdrawals" class="tab-pane" style="display: none;">
                        @if($myWithdrawals->isEmpty())
                            <div class="empty-state">
                                <div class="empty-icon" style="background: #eff6ff; color: #3b82f6;">
                                    <i class="fas fa-money-bill-transfer"></i>
                                </div>
                                <h3 class="empty-title">Belum ada penarikan</h3>
                                <p class="empty-desc">Riwayat penarikan dana Anda akan muncul di sini.</p>
                                <a href="{{ url('/withdrawals/create') }}" class="btn-create"><i class="fas fa-plus"></i> Tarik Dana</a>
                            </div>
                        @else
                            <div style="display: flex; flex-direction: column; gap: 10px;">
                                @foreach($myWithdrawals as $withdrawal)
                                    <div class="card" style="padding: 16px;">
                                        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                                            <div style="flex: 1; min-width: 0;">
                                                <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                                                    <p style="font-size: 14px; font-weight: 600; color: #111827; margin: 0;">
                                                        {{ $withdrawal->campaign->title ?? '-' }}
                                                    </p>
                                                    <span style="font-size: 10px; font-weight: 600; padding: 2px 8px; border-radius: 20px;
                                                        @switch($withdrawal->status)
                                                            @case('paid') background: #ecfdf5; color: #059669; @break
                                                            @case('approved') background: #eff6ff; color: #2563eb; @break
                                                            @case('pending') @case('under_review') background: #fef9c3; color: #a16207; @break
                                                            @default background: #fef2f2; color: #dc2626;
                                                        @endswitch
                                                    ">{{ $withdrawal->status_label }}</span>
                                                </div>
                                                <p style="font-size: 12px; color: #6b7280; margin: 4px 0 0;">
                                                    {{ $withdrawal->created_at->format('d M Y, H:i') }} •
                                                    {{ $withdrawal->bank_name }} - {{ $withdrawal->account_number }}
                                                </p>
                                                @if($withdrawal->admin_notes)
                                                    <p style="font-size: 11px; color: #6b7280; margin-top: 4px; background: #f9fafb; padding: 4px 8px; border-radius: 6px; display: inline-block;">
                                                        Admin: {{ $withdrawal->admin_notes }}
                                                    </p>
                                                @endif
                                            </div>
                                            <span style="font-size: 14px; font-weight: 700; color: #111827; margin-left: 12px;">
                                                {{ $withdrawal->formatted_amount }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div style="margin-top: 16px; text-align: center;">
                                <a href="{{ url('/withdrawals/history') }}" style="font-size: 13px; font-weight: 600; color: #6366f1; text-decoration: none;">Lihat Semua Riwayat →</a>
                            </div>
                        @endif
                    </div>

                    <!-- Tab Pane: Liked (owner only) -->
                    <div id="tab-content-liked" class="tab-pane" style="display: none;">
                        <div class="empty-state">
                            <div class="empty-icon" style="background: #fff1f2; color: #f43f5e;">
                                <i class="fas fa-heart"></i>
                            </div>
                            <h3 class="empty-title">Kampanye yang Disukai</h3>
                            <p class="empty-desc">Kampanye yang Anda sukai akan muncul di sini.</p>
                        </div>
                    </div>

                    <!-- Tab Pane: Comments (owner only) -->
                    <div id="tab-content-comments" class="tab-pane" style="display: none;">
                        <div class="empty-state">
                            <div class="empty-icon" style="background: #eff6ff; color: #3b82f6;">
                                <i class="fas fa-comments"></i>
                            </div>
                            <h3 class="empty-title">Komentar Saya</h3>
                            <p class="empty-desc">Komentar yang Anda berikan di kampanye akan muncul di sini.</p>
                        </div>
                    </div>
                @endif

                <!-- Tab Pane: Updates -->
                <div id="tab-content-updates" class="tab-pane" style="display: none;">
                    <div class="empty-state">
                        <div class="empty-icon" style="background: #fef3c7; color: #d97706;">
                            <i class="fas fa-bullhorn"></i>
                        </div>
                        <h3 class="empty-title">No campaign updates</h3>
                        <p class="empty-desc">This creator hasn't posted any updates about their projects yet.</p>
                    </div>
                </div>

                <!-- Tab Pane: About (Mobile Responsive Tab representation) -->
                <div id="tab-content-about" class="tab-pane" style="display: none;">
                    <div class="card">
                        <div class="card-body">
                            <h2 class="card-title">About {{ $user->name }}</h2>
                            <p class="about-text" style="font-size: 15px; margin-bottom: 24px;">{{ $user->bio ?? 'This creator hasn\'t added a bio yet.' }}</p>

                            <div style="margin-top: 24px;">
                                <div class="info-row">
                                    <div class="info-icon"><i class="fas fa-map-marker-alt"></i></div>
                                    <span class="info-text">{{ $user->location ?? 'Indonesia' }}</span>
                                </div>
                                <div class="info-row">
                                    <div class="info-icon"><i class="fas fa-calendar-alt"></i></div>
                                    <span class="info-text">Joined since {{ $user->created_at->format('F Y') }}</span>
                                </div>
                            </div>

                            <div class="social-row" style="margin-top: 24px; padding-top: 20px; border-top: 1px solid #f1f5f9;">
                                @if($twitterUrl !== '#')
                                    <a href="{{ $twitterUrl }}" target="_blank" class="social-btn" title="Twitter"><i class="fab fa-twitter"></i></a>
                                @endif
                                @if($facebookUrl !== '#')
                                    <a href="{{ $facebookUrl }}" target="_blank" class="social-btn" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                                @endif
                                @if($instagramUrl !== '#')
                                    <a href="{{ $instagramUrl }}" target="_blank" class="social-btn" title="Instagram"><i class="fab fa-instagram"></i></a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom Handcrafted Toast Container for premium copy actions -->
    <div id="custom-toast" class="toast-container">
        <i class="fas fa-check-circle toast-icon"></i>
        <span class="toast-message">Link profil berhasil disalin!</span>
    </div>

    <!-- Interactive Scripts for the Public Profile Controls -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // 1. DYNAMIC TAB PANEL SWITCHING SYSTEM
            const tabs = document.querySelectorAll('.profile-tab');
            const tabPanes = document.querySelectorAll('.tab-pane');

            tabs.forEach(tab => {
                tab.addEventListener('click', function () {
                    // Remove active status from all tabs
                    tabs.forEach(t => t.classList.remove('active'));
                    // Add active class to clicked tab
                    this.classList.add('active');

                    // Hide all tab panes
                    tabPanes.forEach(pane => pane.style.display = 'none');

                    // Show target tab pane
                    const targetTabId = 'tab-content-' + this.getAttribute('data-tab');
                    const targetPane = document.getElementById(targetTabId);
                    if (targetPane) {
                        targetPane.style.display = 'block';
                    }
                });
            });

            // 2. PREMIUM FOLLOWER TOGGLE SYSTEM
            const followBtn = document.getElementById('btn-follow-toggle');
            const followersCountEl = document.getElementById('followers-count');

            if (followBtn && followersCountEl) {
                let isFollowing = false;
                let followersCount = 0; // Default count

                followBtn.addEventListener('click', function () {
                    isFollowing = !isFollowing;

                    if (isFollowing) {
                        // Toggle follow styling
                        followBtn.classList.add('following');
                        followBtn.innerHTML = '<i class="fas fa-check"></i> Following';
                        
                        // Micro interaction increments follower count
                        followersCount++;
                        followersCountEl.textContent = followersCount;

                        showToast('Kamu sekarang mengikuti ' + "{{ $user->name }}");
                    } else {
                        // Reset styling
                        followBtn.classList.remove('following');
                        followBtn.innerHTML = '<i class="fas fa-user-plus"></i> Follow';

                        // Decrements count
                        followersCount--;
                        followersCountEl.textContent = followersCount;
                    }
                });
            }

            // 3. SECURE SHARE ACTION SYSTEM
            const shareBtn = document.getElementById('btn-share-profile');

            if (shareBtn) {
                shareBtn.addEventListener('click', function () {
                    const shareData = {
                        title: "{{ $user->name }} on " + "{{ config('app.name') }}",
                        text: "Bantu dukung perjuangan dan kampanye dari " + "{{ $user->name }}",
                        url: window.location.href
                    };

                    // Check if native navigator share API is available
                    if (navigator.share) {
                        navigator.share(shareData)
                            .catch((err) => console.log('Share failed', err));
                    } else {
                        // Copy link to clipboard fallback
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

            // Toast helper utility
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
