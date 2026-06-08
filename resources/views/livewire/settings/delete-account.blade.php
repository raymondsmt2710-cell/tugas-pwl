<div class="bg-white rounded-2xl border border-red-100 shadow-sm overflow-hidden" x-data="{ showModal: false }">
    <div class="px-6 py-5 border-b border-red-100 bg-red-50/50">
        <h3 class="text-lg font-semibold text-red-900">Hapus Akun</h3>
        <p class="text-sm text-red-600 mt-0.5">Tindakan ini tidak dapat dibatalkan.</p>
    </div>
    <div class="px-6 py-5">
        <p class="text-sm text-gray-600 mb-4">
            Setelah akun dihapus, semua data termasuk kampanye, donasi, dan riwayat akan dihapus permanen.
        </p>

        @if(!$confirming)
            @if(!auth()->user()->hasVerifiedEmail())
                <div class="rounded-lg bg-amber-50 border border-amber-200 p-3 text-sm text-amber-700">
                    Anda harus memverifikasi email terlebih dahulu sebelum dapat menghapus akun.
                    <a href="{{ route('verification.notice') }}" class="underline font-medium inline-flex items-center gap-1">
                        Verifikasi sekarang
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
                        </svg>
                    </a>
                </div>
            @else
                <button wire:click="confirmDeletion" class="px-5 py-2.5 rounded-lg bg-red-600 text-white text-sm font-semibold hover:bg-red-700 transition">
                    Hapus Akun Saya
                </button>
            @endif
        @else
            <div class="bg-red-50 rounded-xl p-5 border border-red-200">
                <p class="text-sm font-medium text-red-800 mb-3">Masukkan password untuk konfirmasi:</p>
                <input wire:model="password" type="password" placeholder="Password" class="w-full sm:w-64 rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-sm mb-3">
                @error('password') <p class="text-xs text-red-600 mb-3">{{ $message }}</p> @enderror
                <div class="flex gap-3">
                    <button @click="showModal = true" type="button" class="px-5 py-2.5 rounded-lg bg-red-600 text-white text-sm font-semibold hover:bg-red-700 transition">
                        Konfirmasi Hapus
                    </button>
                    <button wire:click="cancel" class="px-5 py-2.5 rounded-lg border border-gray-300 text-gray-700 text-sm font-medium hover:bg-gray-50 transition">
                        Batal
                    </button>
                </div>
            </div>
        @endif
    </div>

    {{-- Modal Pop-up Konfirmasi Final --}}
    <div x-show="showModal" x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-[9999] flex items-center justify-center p-4"
         @click.self="showModal = false"
         @keydown.escape.window="showModal = false">

        <div x-show="showModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="bg-white rounded-2xl max-w-sm w-full shadow-2xl border border-gray-100 overflow-hidden">

            {{-- Icon & Warning --}}
            <div class="px-6 pt-6 pb-4 text-center">
                <div class="mx-auto w-14 h-14 rounded-full bg-red-100 flex items-center justify-center mb-4">
                    <svg class="w-7 h-7 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900">Yakin Hapus Akun?</h3>
                <p class="text-sm text-gray-500 mt-2 leading-relaxed">
                    Ini adalah tindakan <span class="font-semibold text-red-600">permanen</span>. Semua data Anda termasuk kampanye, riwayat donasi, dan informasi profil akan dihapus dan <span class="font-semibold text-red-600">tidak dapat dikembalikan</span>.
                </p>
            </div>

            {{-- Tombol Aksi --}}
            <div class="px-6 pb-6 flex gap-3">
                <button @click="showModal = false"
                        class="flex-1 px-4 py-2.5 rounded-xl border border-gray-300 text-gray-700 text-sm font-semibold hover:bg-gray-50 transition">
                    Tidak, Batalkan
                </button>
                <button @click="showModal = false; $wire.deleteAccount()"
                        class="flex-1 px-4 py-2.5 rounded-xl bg-red-600 text-white text-sm font-semibold hover:bg-red-700 transition">
                    Ya, Hapus Akun
                </button>
            </div>
        </div>
    </div>
</div>
