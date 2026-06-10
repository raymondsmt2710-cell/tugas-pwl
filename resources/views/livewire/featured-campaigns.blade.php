<div>
    @if($campaigns->isEmpty())
        <p class="text-center text-gray-500 py-8">Belum ada kampanye aktif saat ini.</p>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
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
