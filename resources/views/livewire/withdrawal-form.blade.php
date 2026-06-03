<div>
    @if($this->campaigns->isEmpty())
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
            <h3 class="text-base font-medium text-gray-900">Tidak ada saldo tersedia</h3>
            <p class="mt-1 text-sm text-gray-500">Anda belum memiliki kampanye dengan saldo yang bisa ditarik.</p>
        </div>
    @else
        <form wire:submit="submit">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="border-b border-gray-100 px-6 py-4">
                    <h3 class="text-base font-semibold text-gray-900">Permintaan Penarikan Dana</h3>
                </div>
                <div class="px-6 py-5 space-y-5">
                    {{-- Campaign --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Pilih Kampanye <span class="text-red-500">*</span></label>
                        <select wire:model.live="id_campaign" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm">
                            <option value="">— Pilih Kampanye —</option>
                            @foreach($this->campaigns as $c)
                                <option value="{{ $c->id_campaign }}">{{ $c->title }}</option>
                            @endforeach
                        </select>
                        @if($this->availableBalance > 0)
                            <p class="mt-2 text-sm text-green-700 bg-green-50 border border-green-100 rounded-lg px-3 py-2">
                                Saldo tersedia: <strong>Rp {{ number_format($this->availableBalance, 0, ',', '.') }}</strong>
                            </p>
                        @endif
                        @error('id_campaign') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Amount --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Jumlah Penarikan <span class="text-red-500">*</span></label>
                        <div class="relative" x-data>
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 text-sm">Rp</span>
                            <input
                                type="text" inputmode="numeric"
                                x-on:input="$el.value = $el.value.replace(/\D/g,'').replace(/\B(?=(\d{3})+(?!\d))/g,'.')"
                                x-on:change="$wire.set('amount', $el.value.replace(/\./g,''))"
                                :value="$wire.amount ? Number($wire.amount).toLocaleString('id-ID') : ''"
                                class="w-full rounded-lg border-gray-300 pl-9 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm" placeholder="50.000">
                        </div>
                        @error('amount') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Bank --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Bank <span class="text-red-500">*</span></label>
                            <input wire:model="bank_name" type="text" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm" placeholder="BCA, BNI, dll">
                            @error('bank_name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Nomor Rekening <span class="text-red-500">*</span></label>
                            <input wire:model="account_number" type="text" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm" placeholder="1234567890">
                            @error('account_number') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Pemilik Rekening <span class="text-red-500">*</span></label>
                        <input wire:model="account_holder" type="text" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm" placeholder="Sesuai buku tabungan">
                        @error('account_holder') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Tujuan Penarikan Dana <span class="text-red-500">*</span></label>
                        <textarea wire:model="purpose" rows="3"
                                  class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm"
                                  placeholder="Contoh: Biaya pengobatan pasien, pembelian alat kesehatan, renovasi sekolah, dll."></textarea>
                        @error('purpose') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Catatan <span class="text-xs text-gray-400">(opsional)</span></label>
                        <textarea wire:model="notes" rows="2" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm" placeholder="Informasi tambahan jika diperlukan"></textarea>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-between">
                <a href="{{ url('/dashboard?tab=withdrawals') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">← Kembali</a>
                <button type="submit" wire:loading.attr="disabled" wire:loading.class="opacity-50"
                        class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-brand-600 transition">
                    <span wire:loading.remove wire:target="submit">Ajukan Penarikan</span>
                    <span wire:loading wire:target="submit">Memproses...</span>
                </button>
            </div>
        </form>
    @endif
</div>
