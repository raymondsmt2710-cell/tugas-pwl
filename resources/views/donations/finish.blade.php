<x-app-layout>
    <div class="py-12">
        <div class="max-w-lg mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 text-center">

                @if ($donation->isPaid())
                    {{-- Success --}}
                    <div class="w-16 h-16 mx-auto rounded-full bg-green-100 flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                        </svg>
                    </div>
                    <h1 class="text-xl font-bold text-gray-900">Donasi Berhasil!</h1>
                    <p class="mt-2 text-sm text-gray-600">Terima kasih atas donasi Anda sebesar <strong>{{ $donation->formatted_amount }}</strong> untuk kampanye "{{ $donation->campaign->title }}".</p>
                @elseif ($donation->isPending())
                    {{-- Pending --}}
                    <div class="w-16 h-16 mx-auto rounded-full bg-yellow-100 flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                        </svg>
                    </div>
                    <h1 class="text-xl font-bold text-gray-900">Menunggu Pembayaran</h1>
                    <p class="mt-2 text-sm text-gray-600">Donasi Anda sebesar <strong>{{ $donation->formatted_amount }}</strong> sedang menunggu konfirmasi pembayaran.</p>
                @else
                    {{-- Failed/Expired --}}
                    <div class="w-16 h-16 mx-auto rounded-full bg-red-100 flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                        </svg>
                    </div>
                    <h1 class="text-xl font-bold text-gray-900">Pembayaran Gagal</h1>
                    <p class="mt-2 text-sm text-gray-600">Donasi Anda tidak dapat diproses. Silakan coba lagi.</p>
                @endif

                {{-- Details --}}
                <div class="mt-6 bg-gray-50 rounded-xl p-4 text-left text-sm space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Order ID</span>
                        <span class="font-mono text-gray-700">{{ $donation->order_id }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Status</span>
                        <span class="font-medium
                            @if($donation->isPaid()) text-green-600
                            @elseif($donation->isPending()) text-yellow-600
                            @else text-red-600
                            @endif
                        ">{{ ucfirst($donation->payment_status) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Kampanye</span>
                        <span class="text-gray-700">{{ $donation->campaign->title }}</span>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="mt-6 flex flex-col gap-3">
                    <a href="{{ route('campaigns.show', $donation->campaign->slug) }}"
                       class="w-full py-2.5 px-4 rounded-xl bg-indigo-600 text-white font-semibold text-sm hover:bg-indigo-700 transition text-center">
                        Kembali ke Kampanye
                    </a>
                    <a href="{{ route('donation.track', $donation->order_id) }}"
                       class="w-full py-2.5 px-4 rounded-xl border border-gray-200 text-gray-700 font-medium text-sm hover:bg-gray-50 transition text-center">
                        Lacak Donasi
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
