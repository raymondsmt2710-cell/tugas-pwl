<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tarik Dana</h2>
    </x-slot>

    <div class="py-8 sm:py-12">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">

            @if ($errors->any())
                <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4">
                    <ul class="text-sm text-red-700 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if ($campaigns->isEmpty())
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z"/>
                    </svg>
                    <h3 class="mt-3 text-base font-medium text-gray-900">Tidak ada saldo tersedia</h3>
                    <p class="mt-1 text-sm text-gray-500">Anda belum memiliki kampanye dengan saldo yang bisa ditarik.</p>
                </div>
            @else
                <form action="{{ route('withdrawals.store') }}" method="POST">
                    @csrf

                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="border-b border-gray-100 px-6 py-4">
                            <h3 class="text-base font-semibold text-gray-900">Permintaan Penarikan Dana</h3>
                            <p class="text-sm text-gray-500 mt-0.5">Tarik dana dari kampanye Anda ke rekening bank.</p>
                        </div>

                        <div class="px-6 py-5 space-y-5">
                            {{-- Campaign Selection --}}
                            <div>
                                <label for="id_campaign" class="block text-sm font-medium text-gray-700 mb-1.5">Pilih Kampanye <span class="text-red-500">*</span></label>
                                <select name="id_campaign" id="id_campaign" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required onchange="updateBalance()">
                                    <option value="" data-balance="0">— Pilih Kampanye —</option>
                                    @foreach ($campaigns as $campaign)
                                        <option value="{{ $campaign->id_campaign }}"
                                                data-balance="{{ $campaign->available_balance }}"
                                                {{ ($selectedCampaign && $selectedCampaign->id_campaign == $campaign->id_campaign) || old('id_campaign') == $campaign->id_campaign ? 'selected' : '' }}>
                                            {{ $campaign->title }}
                                        </option>
                                    @endforeach
                                </select>
                                <div id="balance-info" class="mt-2 p-3 bg-green-50 border border-green-100 rounded-lg {{ $selectedCampaign ? '' : 'hidden' }}">
                                    <p class="text-xs text-green-700">Saldo tersedia: <strong id="balance-display">Rp {{ $selectedCampaign ? number_format($selectedCampaign->available_balance, 0, ',', '.') : '0' }}</strong></p>
                                </div>
                            </div>

                            {{-- Amount --}}
                            <div>
                                <label for="amount" class="block text-sm font-medium text-gray-700 mb-1.5">Jumlah Penarikan <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 text-sm">Rp</span>
                                    <input type="number" name="amount" id="amount" value="{{ old('amount') }}"
                                           class="w-full rounded-lg border-gray-300 pl-9 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                           min="50000" step="1000" required>
                                </div>
                                <p class="mt-1.5 text-xs text-gray-400">Minimum Rp 50.000</p>
                            </div>

                            {{-- Bank Details --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="bank_name" class="block text-sm font-medium text-gray-700 mb-1.5">Nama Bank <span class="text-red-500">*</span></label>
                                    <input type="text" name="bank_name" id="bank_name" value="{{ old('bank_name') }}"
                                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                           placeholder="BCA, BNI, Mandiri, dll" required>
                                </div>
                                <div>
                                    <label for="account_number" class="block text-sm font-medium text-gray-700 mb-1.5">Nomor Rekening <span class="text-red-500">*</span></label>
                                    <input type="text" name="account_number" id="account_number" value="{{ old('account_number') }}"
                                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                           placeholder="1234567890" required>
                                </div>
                            </div>

                            <div>
                                <label for="account_holder" class="block text-sm font-medium text-gray-700 mb-1.5">Nama Pemilik Rekening <span class="text-red-500">*</span></label>
                                <input type="text" name="account_holder" id="account_holder" value="{{ old('account_holder') }}"
                                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                       placeholder="Sesuai buku tabungan" required>
                            </div>

                            {{-- Notes --}}
                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700 mb-1.5">Catatan</label>
                                <textarea name="notes" id="notes" rows="2"
                                          class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                          placeholder="Opsional">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-between">
                        <a href="{{ route('withdrawals.history') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">← Riwayat Penarikan</a>
                        <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 transition">
                            Ajukan Penarikan
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>

    <script>
        function updateBalance() {
            var select = document.getElementById('id_campaign');
            var selected = select.options[select.selectedIndex];
            var balance = parseFloat(selected.getAttribute('data-balance')) || 0;
            var info = document.getElementById('balance-info');
            var display = document.getElementById('balance-display');

            if (balance > 0) {
                display.textContent = 'Rp ' + balance.toLocaleString('id-ID');
                info.classList.remove('hidden');
                document.getElementById('amount').max = balance;
            } else {
                info.classList.add('hidden');
            }
        }
        // Run on page load if campaign is pre-selected
        document.addEventListener('DOMContentLoaded', updateBalance);
    </script>
</x-app-layout>
