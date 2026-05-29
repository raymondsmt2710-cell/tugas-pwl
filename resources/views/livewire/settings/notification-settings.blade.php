<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-100">
        <h3 class="text-lg font-semibold text-gray-900">Notifikasi Email</h3>
        <p class="text-sm text-gray-500 mt-0.5">Pilih notifikasi yang ingin Anda terima via email.</p>
    </div>
    <div class="px-6 py-5 space-y-4">
        <label class="flex items-center justify-between py-2">
            <div>
                <p class="text-sm font-medium text-gray-900">Donasi Diterima</p>
                <p class="text-xs text-gray-500">Notifikasi saat kampanye Anda menerima donasi.</p>
            </div>
            <input wire:model.live="notify_donation_received" type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 w-5 h-5">
        </label>
        <label class="flex items-center justify-between py-2 border-t border-gray-50">
            <div>
                <p class="text-sm font-medium text-gray-900">Kampanye Disetujui</p>
                <p class="text-xs text-gray-500">Notifikasi saat kampanye Anda disetujui admin.</p>
            </div>
            <input wire:model.live="notify_campaign_approved" type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 w-5 h-5">
        </label>
        <label class="flex items-center justify-between py-2 border-t border-gray-50">
            <div>
                <p class="text-sm font-medium text-gray-900">Penarikan Disetujui</p>
                <p class="text-xs text-gray-500">Notifikasi saat penarikan dana Anda diproses.</p>
            </div>
            <input wire:model.live="notify_withdrawal_approved" type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 w-5 h-5">
        </label>
        <p class="text-xs text-gray-400 pt-2">Perubahan disimpan otomatis.</p>
    </div>
</div>
