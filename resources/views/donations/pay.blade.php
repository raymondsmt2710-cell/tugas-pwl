<x-app-layout>
    <div class="py-12">
        <div class="max-w-lg mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 text-center">

                {{-- Loading State --}}
                <div id="payment-loading">
                    <div class="w-16 h-16 mx-auto rounded-full bg-indigo-100 flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-indigo-600 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                    <h1 class="text-xl font-bold text-gray-900">Memproses Pembayaran</h1>
                    <p class="mt-2 text-sm text-gray-600">Halaman pembayaran Midtrans akan segera muncul...</p>
                </div>

                {{-- Payment Info --}}
                <div id="payment-info" class="hidden">
                    <div class="bg-gray-50 rounded-xl p-4 text-left text-sm space-y-2 mb-6">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Kampanye</span>
                            <span class="text-gray-800 font-medium">{{ $campaign->title }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Jumlah</span>
                            <span class="text-gray-900 font-bold">{{ $donation->formatted_amount }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Order ID</span>
                            <span class="font-mono text-gray-700 text-xs">{{ $donation->order_id }}</span>
                        </div>
                    </div>

                    <button id="pay-button"
                            class="w-full py-3 px-4 rounded-xl bg-indigo-600 text-white font-semibold text-sm hover:bg-indigo-700 shadow-sm transition">
                        Bayar Sekarang
                    </button>

                    <a href="{{ route('campaigns.show', $campaign->slug) }}" class="block mt-4 text-sm text-gray-500 hover:text-gray-700">
                        Batalkan & kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Midtrans Snap.js --}}
    <script src="{{ $snapJsUrl }}" data-client-key="{{ $clientKey }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var snapToken = @json($snapToken);

            // Auto-trigger Snap popup
            window.snap.pay(snapToken, {
                onSuccess: function(result) {
                    window.location.href = '{{ route("donation.finish", $donation->order_id) }}';
                },
                onPending: function(result) {
                    window.location.href = '{{ route("donation.finish", $donation->order_id) }}';
                },
                onError: function(result) {
                    window.location.href = '{{ route("donation.finish", $donation->order_id) }}';
                },
                onClose: function() {
                    // User closed the popup without completing payment
                    document.getElementById('payment-loading').classList.add('hidden');
                    document.getElementById('payment-info').classList.remove('hidden');
                }
            });

            // Manual pay button (shown if user closes popup)
            document.getElementById('pay-button').addEventListener('click', function() {
                window.snap.pay(snapToken, {
                    onSuccess: function(result) {
                        window.location.href = '{{ route("donation.finish", $donation->order_id) }}';
                    },
                    onPending: function(result) {
                        window.location.href = '{{ route("donation.finish", $donation->order_id) }}';
                    },
                    onError: function(result) {
                        window.location.href = '{{ route("donation.finish", $donation->order_id) }}';
                    },
                    onClose: function() {}
                });
            });
        });
    </script>
</x-app-layout>
