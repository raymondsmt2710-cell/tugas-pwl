<x-app-layout>
    <div class="py-8 sm:py-12">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="border-b border-gray-100 px-6 py-4">
                    <h1 class="text-lg font-semibold text-gray-900">Lacak Donasi</h1>
                    <p class="text-sm text-gray-500 mt-0.5">Detail dan status pembayaran donasi Anda.</p>
                </div>

                <div class="p-6 space-y-6">
                    {{-- Status Badge --}}
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center
                            @if($donation->isPaid()) bg-green-100
                            @elseif($donation->isPending()) bg-yellow-100
                            @elseif($donation->isExpired()) bg-gray-100
                            @else bg-red-100
                            @endif
                        ">
                            @if($donation->isPaid())
                                <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
                            @elseif($donation->isPending())
                                <svg class="w-5 h-5 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                            @else
                                <svg class="w-5 h-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                            @endif
                        </div>
                        <div>
                            <p class="text-sm font-semibold
                                @if($donation->isPaid()) text-green-700
                                @elseif($donation->isPending()) text-yellow-700
                                @else text-red-700
                                @endif
                            ">
                                @switch($donation->payment_status)
                                    @case('paid') Pembayaran Berhasil @break
                                    @case('pending') Menunggu Pembayaran @break
                                    @case('expired') Pembayaran Kedaluwarsa @break
                                    @case('failed') Pembayaran Gagal @break
                                @endswitch
                            </p>
                            <p class="text-xs text-gray-500">{{ $donation->created_at->format('d M Y, H:i') }} WIB</p>
                        </div>
                    </div>

                    {{-- Details --}}
                    <div class="bg-gray-50 rounded-xl p-5 space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Order ID</span>
                            <span class="font-mono text-gray-800">{{ $donation->order_id }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Kampanye</span>
                            <a href="{{ route('campaigns.show', $donation->campaign->slug) }}" class="text-indigo-600 hover:text-indigo-800 font-medium">
                                {{ $donation->campaign->title }}
                            </a>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Jumlah Donasi</span>
                            <span class="font-semibold text-gray-900">{{ $donation->formatted_amount }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Nama Donatur</span>
                            <span class="text-gray-800">{{ $donation->display_name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Email</span>
                            <span class="text-gray-800">{{ $donation->donor_email }}</span>
                        </div>
                        @if ($donation->payment_method)
                            <div class="flex justify-between">
                                <span class="text-gray-500">Metode Pembayaran</span>
                                <span class="text-gray-800">{{ ucfirst(str_replace('_', ' ', $donation->payment_method)) }}</span>
                            </div>
                        @endif
                        @if ($donation->paid_at)
                            <div class="flex justify-between">
                                <span class="text-gray-500">Dibayar Pada</span>
                                <span class="text-gray-800">{{ $donation->paid_at->format('d M Y, H:i') }} WIB</span>
                            </div>
                        @endif
                        @if ($donation->donor_message)
                            <div class="pt-3 border-t border-gray-200">
                                <p class="text-gray-500 mb-1">Pesan:</p>
                                <p class="text-gray-800 italic">"{{ $donation->donor_message }}"</p>
                            </div>
                        @endif
                    </div>

                    {{-- Actions --}}
                    <div class="flex gap-3">
                        <a href="{{ route('campaigns.show', $donation->campaign->slug) }}"
                           class="flex-1 text-center py-2.5 px-4 rounded-xl bg-indigo-600 text-white font-semibold text-sm hover:bg-indigo-700 transition">
                            Lihat Kampanye
                        </a>
                        @if ($donation->isPending())
                            <a href="{{ route('donation.create', $donation->campaign->slug) }}"
                               class="flex-1 text-center py-2.5 px-4 rounded-xl border border-gray-200 text-gray-700 font-medium text-sm hover:bg-gray-50 transition">
                                Coba Lagi
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
