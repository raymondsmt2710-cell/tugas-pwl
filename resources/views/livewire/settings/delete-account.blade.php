<div class="bg-white rounded-2xl border border-red-100 shadow-sm overflow-hidden">
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
                    <a href="{{ route('verification.notice') }}" class="underline font-medium">Verifikasi sekarang →</a>
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
                    <button wire:click="deleteAccount" class="px-5 py-2.5 rounded-lg bg-red-600 text-white text-sm font-semibold hover:bg-red-700 transition">
                        Konfirmasi Hapus
                    </button>
                    <button wire:click="cancel" class="px-5 py-2.5 rounded-lg border border-gray-300 text-gray-700 text-sm font-medium hover:bg-gray-50 transition">
                        Batal
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>
