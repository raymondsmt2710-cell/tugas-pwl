<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-100">
        <h3 class="text-lg font-semibold text-gray-900">Keamanan</h3>
        <p class="text-sm text-gray-500 mt-0.5">Kelola password dan sesi aktif Anda.</p>
    </div>
    <div class="px-6 py-5 space-y-5">
        @if($passwordUpdated)
            <div class="rounded-lg bg-green-50 border border-green-200 p-3 text-sm text-green-700">Password berhasil diperbarui.</div>
        @endif

        <form wire:submit="updatePassword" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Password Saat Ini</label>
                    <input wire:model="current_password" type="password" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    @error('current_password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                    <input wire:model="password" type="password" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    @error('password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                    <input wire:model="password_confirmation" type="password" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                </div>
            </div>
            <div class="flex items-center justify-between">
                <button type="submit" wire:loading.attr="disabled" wire:target="updatePassword" class="px-5 py-2.5 rounded-lg bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 transition disabled:opacity-50 inline-flex items-center gap-2">
                    <span wire:loading.remove wire:target="updatePassword">Ubah Password</span>
                    <span wire:loading wire:target="updatePassword">Memproses...</span>
                    <div wire:loading wire:target="updatePassword" class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                </button>
                <button type="button" wire:click="sendResetLink" wire:loading.attr="disabled" wire:target="sendResetLink" class="text-sm text-blue-600 hover:text-blue-800 font-medium disabled:opacity-50 inline-flex items-center gap-2">
                    <span wire:loading.remove wire:target="sendResetLink">Lupa password? Kirim reset link</span>
                    <span wire:loading wire:target="sendResetLink">Mengirim link...</span>
                    <div wire:loading wire:target="sendResetLink" class="w-4 h-4 border-2 border-blue-600 border-t-transparent rounded-full animate-spin"></div>
                </button>
            </div>
        </form>

        @if($resetSent)
            <div class="rounded-lg bg-blue-50 border border-blue-200 p-3 text-sm text-blue-700">Link reset password telah dikirim ke email Anda.</div>
        @endif

        {{-- Active Sessions --}}
        <div class="pt-5 border-t border-gray-100">
            <h4 class="text-sm font-semibold text-gray-900 mb-3">Sesi Aktif</h4>
            <p class="text-sm text-gray-500 mb-3">Kelola dan logout dari sesi aktif Anda di perangkat lain.</p>
            @livewire('profile.logout-other-browser-sessions-form')
        </div>
    </div>
</div>
