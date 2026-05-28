<div>
    <form wire:submit="donate">
        {{-- Amount --}}
        <div class="mb-5">
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Jumlah Donasi <span class="text-red-500">*</span></label>
            <div class="relative">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 text-sm font-medium">Rp</span>
                <input wire:model.live="donation_amount" type="number" min="{{ $campaign->minimum_donation ?: 1000 }}" step="1000"
                       class="w-full rounded-lg border-gray-300 pl-9 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="50000">
            </div>
            @error('donation_amount') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror

            <div class="mt-3 flex flex-wrap gap-2">
                @foreach([25000, 50000, 100000, 250000, 500000] as $amt)
                    <button type="button" wire:click="setAmount({{ $amt }})"
                            class="px-3 py-1.5 text-xs font-medium rounded-lg border transition
                            {{ (int)$donation_amount === $amt ? 'border-indigo-500 bg-indigo-50 text-indigo-700' : 'border-gray-200 text-gray-600 hover:bg-indigo-50 hover:border-indigo-200 hover:text-indigo-700' }}">
                        Rp {{ number_format($amt, 0, ',', '.') }}
                    </button>
                @endforeach
            </div>
        </div>

        {{-- Name --}}
        <div class="mb-5">
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama <span class="text-red-500">*</span></label>
            <input wire:model="donor_name" type="text" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="Nama Anda">
            @error('donor_name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        {{-- Email --}}
        <div class="mb-5">
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Email <span class="text-red-500">*</span></label>
            <input wire:model="donor_email" type="email" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="email@contoh.com">
            @error('donor_email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        {{-- Message --}}
        <div class="mb-5">
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Pesan / Doa</label>
            <textarea wire:model="donor_message" rows="3" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="Semoga cepat tercapai..."></textarea>
        </div>

        {{-- Anonymous --}}
        <div class="mb-5 flex items-center gap-2">
            <input wire:model="is_anonymous" type="checkbox" id="is_anonymous" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
            <label for="is_anonymous" class="text-sm text-gray-700">Sembunyikan nama saya (donasi anonim)</label>
        </div>

        {{-- Submit --}}
        <div class="pt-4 border-t border-gray-100">
            <button type="submit" wire:loading.attr="disabled" wire:loading.class="opacity-50"
                    class="w-full py-3 px-4 rounded-xl bg-indigo-600 text-white font-semibold text-sm hover:bg-indigo-700 shadow-sm transition flex items-center justify-center gap-2">
                <span wire:loading.remove wire:target="donate">Lanjutkan Pembayaran</span>
                <span wire:loading wire:target="donate">Memproses...</span>
            </button>
        </div>
    </form>
</div>
