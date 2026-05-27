<x-app-layout>
    <div class="py-8 sm:py-12">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Campaign Info Header --}}
            <div class="mb-6 flex items-center gap-4 bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                @if ($campaign->banner_image)
                    <img src="{{ asset('storage/' . $campaign->banner_image) }}" alt="{{ $campaign->title }}" class="w-16 h-16 rounded-lg object-cover">
                @endif
                <div class="flex-1 min-w-0">
                    <h2 class="text-base font-semibold text-gray-900 truncate">{{ $campaign->title }}</h2>
                    <p class="text-sm text-gray-500">oleh {{ $campaign->user->full_name }}</p>
                </div>
            </div>

            {{-- Donation Form --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="border-b border-gray-100 px-6 py-4">
                    <h1 class="text-lg font-semibold text-gray-900">Berikan Donasi</h1>
                    <p class="text-sm text-gray-500 mt-0.5">Bantu kampanye ini mencapai targetnya.</p>
                </div>

                {{-- Errors --}}
                @if ($errors->any())
                    <div class="mx-6 mt-5 rounded-lg border border-red-200 bg-red-50 p-3">
                        <ul class="text-sm text-red-700 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mx-6 mt-5 rounded-lg border border-red-200 bg-red-50 p-3">
                        <p class="text-sm text-red-700">{{ session('error') }}</p>
                    </div>
                @endif

                <form action="{{ route('donation.store', $campaign->slug) }}" method="POST" class="p-6 space-y-5">
                    @csrf

                    {{-- Amount --}}
                    <div>
                        <label for="donation_amount" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Jumlah Donasi <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 text-sm font-medium">Rp</span>
                            <input type="number" name="donation_amount" id="donation_amount" value="{{ old('donation_amount') }}"
                                   class="w-full rounded-lg border-gray-300 pl-9 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                   min="{{ $campaign->minimum_donation ?: 1000 }}" step="1000" placeholder="50000" required>
                        </div>
                        @if ($campaign->minimum_donation > 0)
                            <p class="mt-1.5 text-xs text-gray-400">Minimum donasi: Rp {{ number_format($campaign->minimum_donation, 0, ',', '.') }}</p>
                        @endif

                        {{-- Quick Amount Buttons --}}
                        <div class="mt-3 flex flex-wrap gap-2">
                            @foreach ([25000, 50000, 100000, 250000, 500000] as $amount)
                                <button type="button" onclick="document.getElementById('donation_amount').value = {{ $amount }}"
                                        class="px-3 py-1.5 text-xs font-medium rounded-lg border border-gray-200 text-gray-600 hover:bg-indigo-50 hover:border-indigo-200 hover:text-indigo-700 transition">
                                    Rp {{ number_format($amount, 0, ',', '.') }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Donor Name --}}
                    <div>
                        <label for="donor_name" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Nama <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="donor_name" id="donor_name"
                               value="{{ old('donor_name', auth()->user()?->full_name) }}"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                               placeholder="Nama Anda" required>
                    </div>

                    {{-- Donor Email --}}
                    <div>
                        <label for="donor_email" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="donor_email" id="donor_email"
                               value="{{ old('donor_email', auth()->user()?->email) }}"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                               placeholder="email@contoh.com" required>
                    </div>

                    {{-- Message --}}
                    <div>
                        <label for="donor_message" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Pesan / Doa
                        </label>
                        <textarea name="donor_message" id="donor_message" rows="3"
                                  class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                  placeholder="Semoga cepat tercapai targetnya...">{{ old('donor_message') }}</textarea>
                    </div>

                    {{-- Anonymous --}}
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="is_anonymous" id="is_anonymous" value="1"
                               {{ old('is_anonymous') ? 'checked' : '' }}
                               class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <label for="is_anonymous" class="text-sm text-gray-700">Sembunyikan nama saya (donasi anonim)</label>
                    </div>

                    {{-- Submit --}}
                    <div class="pt-4 border-t border-gray-100">
                        <button type="submit"
                                class="w-full py-3 px-4 rounded-xl bg-indigo-600 text-white font-semibold text-sm hover:bg-indigo-700 shadow-sm transition flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/>
                            </svg>
                            Lanjutkan Pembayaran
                        </button>
                    </div>
                </form>
            </div>

            {{-- Back Link --}}
            <div class="mt-4 text-center">
                <a href="{{ route('campaigns.show', $campaign->slug) }}" class="text-sm text-gray-500 hover:text-gray-700">
                    ← Kembali ke kampanye
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
