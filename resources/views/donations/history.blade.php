<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Riwayat Donasi Saya
        </h2>
    </x-slot>

    <div class="py-8 sm:py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Summary Stats --}}
            @php
                $totalPaid = $donations->getCollection()->where('payment_status', 'paid')->sum('donation_amount');
                $totalCount = $donations->total();
            @endphp
            <div class="mb-6 grid grid-cols-2 sm:grid-cols-3 gap-4">
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 text-center">
                    <p class="text-2xl font-bold text-gray-900">{{ $totalCount }}</p>
                    <p class="text-xs text-gray-500 mt-1">Total Donasi</p>
                </div>
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 text-center">
                    <p class="text-2xl font-bold text-green-600">Rp {{ number_format($totalPaid, 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-500 mt-1">Total Berhasil</p>
                </div>
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 text-center hidden sm:block">
                    <p class="text-2xl font-bold text-gray-900">{{ $donations->getCollection()->where('payment_status', 'paid')->count() }}</p>
                    <p class="text-xs text-gray-500 mt-1">Donasi Berhasil</p>
                </div>
            </div>

            @if ($donations->isEmpty())
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
                    <svg class="mx-auto h-16 w-16 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/>
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Belum ada donasi</h3>
                    <p class="mt-2 text-sm text-gray-500">Donasi yang Anda berikan akan muncul di sini.</p>
                    <a href="{{ route('campaigns.index') }}" class="mt-6 inline-flex items-center px-5 py-2.5 rounded-xl bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700 transition">
                        Jelajahi Kampanye
                    </a>
                </div>
            @else
                <div class="space-y-3">
                    @foreach ($donations as $donation)
                        <a href="{{ route('donation.track', $donation->order_id) }}"
                           class="block bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:border-indigo-200 hover:shadow-md transition-all">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                {{-- Left: Campaign info --}}
                                <div class="flex items-start gap-3 flex-1 min-w-0">
                                    {{-- Status indicator --}}
                                    <div class="mt-0.5 shrink-0">
                                        @switch($donation->payment_status)
                                            @case('paid')
                                                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                                                    <svg class="w-4 h-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
                                                </div>
                                                @break
                                            @case('pending')
                                                <div class="w-8 h-8 rounded-full bg-yellow-100 flex items-center justify-center">
                                                    <svg class="w-4 h-4 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                                                </div>
                                                @break
                                            @case('expired')
                                                <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center">
                                                    <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                                                </div>
                                                @break
                                            @default
                                                <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center">
                                                    <svg class="w-4 h-4 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                                                </div>
                                        @endswitch
                                    </div>

                                    {{-- Info --}}
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <h3 class="text-sm font-semibold text-gray-900 truncate">
                                                {{ $donation->campaign->title ?? 'Kampanye Dihapus' }}
                                            </h3>
                                            @if ($donation->is_anonymous)
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-gray-100 text-gray-500">
                                                    Anonim
                                                </span>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-2 mt-1 text-xs text-gray-500">
                                            <span>{{ $donation->created_at->format('d M Y, H:i') }}</span>
                                            <span>•</span>
                                            <span class="font-mono">{{ $donation->order_id }}</span>
                                        </div>
                                        <div class="mt-1.5">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold
                                                @switch($donation->payment_status)
                                                    @case('paid') bg-green-50 text-green-700 @break
                                                    @case('pending') bg-yellow-50 text-yellow-700 @break
                                                    @case('expired') bg-gray-50 text-gray-600 @break
                                                    @default bg-red-50 text-red-700
                                                @endswitch
                                            ">
                                                @switch($donation->payment_status)
                                                    @case('paid') ✓ Berhasil @break
                                                    @case('pending') ⏳ Menunggu Pembayaran @break
                                                    @case('expired') ⏰ Kedaluwarsa @break
                                                    @default ✗ Gagal
                                                @endswitch
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Right: Amount --}}
                                <div class="text-right shrink-0">
                                    <p class="text-lg font-bold text-gray-900">{{ $donation->formatted_amount }}</p>
                                    @if ($donation->payment_method)
                                        <p class="text-[11px] text-gray-400 mt-0.5">{{ ucfirst(str_replace('_', ' ', $donation->payment_method)) }}</p>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $donations->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
