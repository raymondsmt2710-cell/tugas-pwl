<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Riwayat Penarikan Dana</h2>
    </x-slot>

    <div class="py-8 sm:py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Campaign Info --}}
            <div class="mb-6 bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                <a href="{{ route('campaigns.show', $campaign->slug) }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">← Kembali ke kampanye</a>
                <h3 class="mt-2 text-lg font-semibold text-gray-900">{{ $campaign->title }}</h3>
                <div class="mt-3 grid grid-cols-3 gap-4 text-center">
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-sm font-bold text-gray-900">Rp {{ number_format($campaign->collected_amount, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-500">Total Donasi</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-sm font-bold text-gray-900">Rp {{ number_format($campaign->withdrawal_amount, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-500">Total Ditarik</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-sm font-bold text-gray-900">Rp {{ number_format($campaign->available_balance, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-500">Sisa Saldo</p>
                    </div>
                </div>
            </div>

            {{-- Withdrawal List --}}
            @if($withdrawals->isEmpty())
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-12 text-center">
                    <p class="text-sm text-gray-500">Belum ada penarikan dana untuk kampanye ini.</p>
                </div>
            @else
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="divide-y divide-gray-50">
                        @foreach($withdrawals as $w)
                            <div class="px-5 py-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">Rp {{ number_format($w->amount, 0, ',', '.') }}</p>
                                        <p class="text-xs text-gray-500 mt-0.5">{{ $w->paid_at?->format('d M Y') ?? $w->created_at->format('d M Y') }}</p>
                                    </div>
                                    <span class="text-xs font-medium px-2 py-0.5 rounded-full bg-green-50 text-green-700">Dibayar</span>
                                </div>
                                @if($w->notes)
                                    <p class="mt-2 text-sm text-gray-600 bg-gray-50 rounded-lg px-3 py-2">{{ $w->notes }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="mt-6">{{ $withdrawals->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
