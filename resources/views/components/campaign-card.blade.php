@props([
    'campaign',
    'liked' => false,
    'likesCount' => 0,
])

<div
    x-data="{
        liked: {{ $liked ? 'true' : 'false' }},
        likesCount: {{ (int) $likesCount }},
        isSubmitting: false,
        copied: false,
        toggleLike() {
            if (this.isSubmitting) return;
            @guest
                window.location.href = '{{ route('login') }}';
                return;
            @endguest
            this.isSubmitting = true;
            const prev = this.liked;
            this.liked = !this.liked;
            this.likesCount += this.liked ? 1 : -1;

            fetch('{{ route('campaigns.like', $campaign->id_campaign) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    this.liked = data.liked;
                    this.likesCount = data.likes_count;
                } else {
                    this.liked = prev;
                    this.likesCount += prev ? 1 : -1;
                }
            })
            .catch(() => {
                this.liked = prev;
                this.likesCount += prev ? 1 : -1;
            })
            .finally(() => { this.isSubmitting = false; });
        },
        share() {
            const url = '{{ url('/campaigns/' . $campaign->slug) }}';
            const title = '{{ addslashes($campaign->title) }}';
            if (navigator.share) {
                navigator.share({ title, url }).catch(() => {});
            } else {
                navigator.clipboard.writeText(url).then(() => {
                    this.copied = true;
                    setTimeout(() => this.copied = false, 2000);
                });
            }
        }
    }"
    class="bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow flex flex-col h-full"
>
    {{-- Image --}}
    <a href="{{ route('campaigns.show', $campaign->slug) }}"
       class="block overflow-hidden bg-gray-100 group shrink-0"
       style="height: 200px; min-height: 200px; max-height: 200px;">
        <img src="{{ $campaign->banner_image_url }}"
             alt="{{ $campaign->title }}"
             style="width:100%; height:100%; object-fit:cover; display:block;"
             class="group-hover:scale-105 transition-transform duration-300">
    </a>

    {{-- Content --}}
    <div class="p-4 flex flex-col flex-1">
        {{-- Category + Goal badge --}}
        <div class="flex items-center gap-2 mb-2 flex-wrap">
            <span class="text-xs font-medium text-brand-500 bg-brand-50 px-2 py-0.5 rounded-full">
                {{ $campaign->category->name ?? 'Umum' }}
            </span>
            @if($campaign->status === 'goal_reached')
                <span class="text-xs font-medium text-green-700 bg-green-100 px-2 py-0.5 rounded-full">
                    🏆 Goal Tercapai
                </span>
            @endif
        </div>

        {{-- Title --}}
        <a href="{{ route('campaigns.show', $campaign->slug) }}"
           class="text-sm font-semibold text-gray-900 line-clamp-2 hover:text-brand-500 transition-colors leading-snug">
            {{ $campaign->title }}
        </a>

        {{-- Progress --}}
        <div class="mt-auto pt-3">
            <div class="w-full bg-gray-100 rounded-full h-1.5">
                <div class="bg-brand-500 h-1.5 rounded-full transition-all"
                     style="width: {{ min(100, $campaign->progress_percentage) }}%"></div>
            </div>
            <div class="flex justify-between mt-1.5 text-xs">
                <span class="font-semibold text-gray-900">Rp {{ number_format($campaign->collected_amount, 0, ',', '.') }}</span>
                <span class="text-gray-500">{{ $campaign->donor_count }} donatur</span>
            </div>
        </div>

        {{-- Creator + days --}}
        <div class="mt-3 flex items-center justify-between text-xs text-gray-400">
            @if($campaign->user && !$campaign->user->trashed())
                <a href="{{ url('/@' . $campaign->user->username) }}"
                   class="flex items-center gap-1.5 hover:text-brand-500 transition min-w-0">
                    <img src="{{ $campaign->user->profile_photo_url }}"
                         class="w-5 h-5 rounded-full object-cover border border-gray-200 shrink-0">
                    <span class="truncate max-w-[100px]">{{ $campaign->user->full_name }}</span>
                </a>
            @else
                <span class="flex items-center gap-1.5 min-w-0 text-gray-400">
                    <div class="w-5 h-5 rounded-full bg-gray-100 border border-gray-200 shrink-0 flex items-center justify-center text-[10px] font-bold">?</div>
                    <span class="truncate max-w-[100px]">Akun Dihapus</span>
                </span>
            @endif
            <span>{{ $campaign->days_remaining }} hari lagi</span>
        </div>

        {{-- Like & Share --}}
        <div class="mt-3 pt-3 border-t border-gray-100 flex items-center gap-4">
            {{-- Like --}}
            <button
                @click.prevent="toggleLike()"
                class="flex items-center gap-1.5 group focus:outline-none transition"
                :class="liked ? 'text-red-500' : 'text-gray-400 hover:text-red-400'"
                title="Like">
                <svg class="w-4 h-4 transition-all"
                     :class="liked ? 'fill-red-500 text-red-500' : 'fill-transparent'"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/>
                </svg>
                <span class="text-xs font-semibold" x-text="likesCount"></span>
            </button>

            {{-- Share --}}
            <button
                @click.prevent="share()"
                class="flex items-center gap-1.5 text-gray-400 hover:text-brand-500 transition focus:outline-none"
                title="Bagikan">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M7.217 10.907a2.25 2.25 0 1 0 0 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186 9.566-5.314m-9.566 7.5 9.566 5.314m0 0a2.25 2.25 0 1 0 3.935 2.186 2.25 2.25 0 0 0-3.935-2.186Zm0-12.814a2.25 2.25 0 1 0 3.933-2.185 2.25 2.25 0 0 0-3.933 2.185Z"/>
                </svg>
                <span class="text-xs font-semibold" x-show="!copied">Bagikan</span>
                <span class="text-xs font-semibold text-brand-500" x-show="copied" x-cloak>Link telah disalin</span>
            </button>
        </div>
    </div>
</div>
