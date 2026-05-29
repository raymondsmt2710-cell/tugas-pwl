<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-100">
        <h3 class="text-lg font-semibold text-gray-900">Privasi</h3>
        <p class="text-sm text-gray-500 mt-0.5">Kontrol apa yang orang lain bisa lihat tentang Anda.</p>
    </div>
    <div class="px-6 py-5 space-y-4">
        <label class="flex items-center justify-between py-2">
            <div>
                <p class="text-sm font-medium text-gray-900">Tampilkan Profil Secara Publik</p>
                <p class="text-xs text-gray-500">Profil Anda dapat dilihat oleh siapa saja.</p>
            </div>
            <input wire:model.live="show_profile_publicly" type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 w-5 h-5">
        </label>
        <label class="flex items-center justify-between py-2 border-t border-gray-50">
            <div>
                <p class="text-sm font-medium text-gray-900">Tampilkan Jumlah Pengikut</p>
                <p class="text-xs text-gray-500">Jumlah followers terlihat di profil publik.</p>
            </div>
            <input wire:model.live="show_followers_count" type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 w-5 h-5">
        </label>
        <label class="flex items-center justify-between py-2 border-t border-gray-50">
            <div>
                <p class="text-sm font-medium text-gray-900">Tampilkan Jumlah Mengikuti</p>
                <p class="text-xs text-gray-500">Jumlah following terlihat di profil publik.</p>
            </div>
            <input wire:model.live="show_following_count" type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 w-5 h-5">
        </label>
        <p class="text-xs text-gray-400 pt-2">Perubahan disimpan otomatis.</p>
    </div>
</div>
