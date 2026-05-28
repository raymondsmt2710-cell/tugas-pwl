<div class="inline-flex items-center gap-2">
    <button wire:click="toggle" wire:loading.attr="disabled"
            class="btn-follow {{ $isFollowing ? 'following' : '' }}">
        @if($isFollowing)
            <i class="fas fa-check"></i> <span class="text">Following</span>
        @else
            <i class="fas fa-user-plus"></i> <span class="text">Follow</span>
        @endif
    </button>
</div>
