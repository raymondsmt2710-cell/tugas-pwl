<div id="comments" x-data class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
    <div class="flex items-center justify-between border-b border-gray-50 pb-4">
        <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
            <svg class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 20.25c4.97 0 9-3.694 9-8.25s-4.03-8.25-9-8.25S3 7.444 3 12c0 2.104.859 4.023 2.273 5.48.432.447.74 1.04.586 1.641a4.483 4.483 0 0 1-.923 1.785 5.969 5.969 0 0 0 2.247-.522c.627-.24 1.221-.19 1.774.129 1.124.646 2.42.987 3.793.987Z" />
            </svg>
            Komentar ({{ count($comments) }})
        </h2>
    </div>

    {{-- Comments List --}}
    <div class="space-y-4 max-h-[400px] overflow-y-auto pr-1">
        @if(count($comments) === 0)
            <div class="text-center py-8 text-gray-500">
                <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.177 48.177 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v5.01Z"/>
                </svg>
                <p class="text-sm">Belum ada komentar. Jadilah yang pertama berkomentar!</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($comments as $c)
                    <div class="flex items-start gap-3" wire:key="comment-{{ $c->id }}">
                        {{-- Profile Photo --}}
                        @if($c->user && !$c->user->trashed())
                            <a href="{{ url('/@' . $c->user->username) }}" class="w-9 h-9 rounded-full bg-gray-100 overflow-hidden flex-shrink-0 border border-gray-100 hover:opacity-95 transition">
                                <img src="{{ $c->user->profile_photo_url }}" alt="{{ $c->user->full_name }}" class="w-full h-full object-cover">
                            </a>
                        @else
                            <div class="w-9 h-9 rounded-full bg-gray-100 flex items-center justify-center flex-shrink-0 border border-gray-100">
                                <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                                </svg>
                            </div>
                        @endif
                        {{-- Comment Content --}}
                        <div class="flex-1 text-left">
                            <div class="bg-gray-50 rounded-2xl px-4 py-3 border border-gray-100/30">
                                <div class="flex items-baseline flex-wrap gap-x-1.5">
                                    @if($c->user && !$c->user->trashed())
                                        <a href="{{ url('/@' . $c->user->username) }}" class="font-bold text-gray-900 text-sm hover:text-brand-500 transition">
                                            {{ $c->user->username }}
                                        </a>
                                    @else
                                        <span class="font-bold text-gray-500 text-sm italic">
                                            Akun Dihapus
                                        </span>
                                    @endif
                                    <span class="text-gray-700 text-sm whitespace-pre-wrap leading-relaxed">{{ $c->comment }}</span>
                                </div>
                            </div>
                            {{-- Comment Date/Time --}}
                            <div class="flex items-center gap-2 mt-1.5 ml-3 text-[11px] text-gray-400">
                                <span>{{ $c->created_at->diffForHumans() }}</span>
                                @auth
                                    @if(auth()->user()->id_user === $c->id_user || auth()->user()->id_user === $campaign->id_user || auth()->user()->isAdmin())
                                        <span>•</span>
                                        <button type="button" 
                                                @click="$dispatch('confirm', {
                                                    title: 'Hapus Komentar',
                                                    message: 'Apakah Anda yakin ingin menghapus komentar ini?',
                                                    type: 'danger',
                                                    confirmText: 'Ya, Hapus',
                                                    cancelText: 'Batal',
                                                    onConfirm: () => $wire.deleteComment({{ $c->id }})
                                                })"
                                                class="inline-flex items-center text-red-500 hover:text-red-700 transition focus:outline-none"
                                                title="Hapus Komentar">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-4 h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>
                                        </button>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Comment Form --}}
    <div class="border-t border-gray-100 pt-5">
        @auth
            <form wire:submit.prevent="storeComment" class="flex items-start gap-3">
                <div class="w-9 h-9 rounded-full bg-gray-100 overflow-hidden flex-shrink-0 border border-gray-100">
                    <img src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->full_name }}" class="w-full h-full object-cover">
                </div>
                <div class="flex-1">
                    <textarea wire:model="newComment" rows="2" required placeholder="Tulis komentar utama..." 
                              class="w-full rounded-2xl border-gray-200 text-sm focus:border-brand-500 focus:ring-brand-500 placeholder-gray-400 px-4 py-2.5 resize-none transition duration-200"
                              x-on:keydown.enter.exact.prevent="$wire.storeComment()"></textarea>
                    @error('newComment') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                    <div class="flex justify-between items-center mt-2">
                        <span class="text-xs text-gray-400">Tekan Enter untuk mengirim</span>
                        <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-brand-500 hover:bg-brand-600 rounded-xl shadow-sm transition">
                            Kirim
                        </button>
                    </div>
                </div>
            </form>
        @else
            <div class="text-center py-4 bg-gray-50 rounded-2xl border border-dashed border-gray-200">
                <p class="text-sm text-gray-500">
                    Silakan <a href="{{ route('login') }}" class="font-semibold text-brand-500 hover:text-brand-700 transition">Login</a> untuk menulis komentar.
                </p>
            </div>
        @endauth
    </div>
</div>
