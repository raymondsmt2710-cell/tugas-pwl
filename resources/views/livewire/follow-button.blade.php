<div class="inline-flex items-center">
    <button wire:click="toggle" wire:loading.attr="disabled"
            class="btn-follow inline-flex items-center justify-center gap-2 px-5 h-9 rounded-full text-sm font-bold transition-all duration-300 active:scale-95 cursor-pointer select-none {{ $isFollowing ? 'bg-slate-100 hover:bg-red-50 text-slate-700 hover:text-red-600 border border-slate-200/60 hover:border-red-200 group' : 'bg-brand-500 hover:bg-brand-600 text-white shadow-sm shadow-brand-500/10' }}">
        @if($isFollowing)
            <i class="fas fa-check text-xs group-hover:hidden"></i>
            <i class="fas fa-user-xmark text-xs hidden group-hover:inline"></i>
            <span class="group-hover:hidden">Following</span>
            <span class="hidden group-hover:inline">Unfollow</span>
        @else
            <i class="fas fa-user-plus text-xs"></i>
            <span>Follow</span>
        @endif
    </button>
</div>
