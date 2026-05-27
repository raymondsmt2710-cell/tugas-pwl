<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Riwayat Penarikan</h2>
            <a href="{{ route('withdrawals.create') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700 transition">
                + Tarik Dana
            </a>
        </div>
    </x-slot>

    <div class="py-8 sm:py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-6 rounded-xl border border-green-200 bg-green-50 p-4">
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            @endif

            @if ($withdrawals->isEmpty())
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z"/>
                    </svg>
                    <h3 class="mt-3 text-base font-medium text-gray-900">Belum ada penarikan</h3>
                    <p class="mt-1 text-sm text-gray-500">Permintaan penarikan dana akan muncul di sini.</p>
                </div>
            @else
                <div class="space-y-3">
                    @foreach ($withdrawals as $withdrawal)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <h3 class="text-sm font-semibold text-gray-900 truncate">{{ $withdrawal->campaign->title ?? '-' }}</h3>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold
                                            @switch($withdrawal->status)
                                                @case('pending') bg-yellow-50 text-yellow-700 @break
                                                @case('under_review') bg-blue-50 text-blue-700 @break
                                                @case('approved') bg-green-50 text-green-700 @break
                                                @case('rejected') bg-red-50 text-red-700 @break
                                                @case('paid') bg-indigo-50 text-indigo-700 @break
                                            @endswitch
                                        ">{{ $withdrawal->status_label }}</span>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ $withdrawal->created_at->format('d M Y, H:i') }} •
                                        {{ $withdrawal->bank_name }} - {{ $withdrawal->account_number }} ({{ $withdrawal->account_holder }})
                                    </p>
                                    @if ($withdrawal->admin_notes)
                                        <p class="text-xs text-gray-600 mt-1.5 bg-gray-50 rounded px-2 py-1 inline-block">
                                            Admin: {{ $withdrawal->admin_notes }}
                                        </p>
                                    @endif
                                </div>
                                <div class="flex items-center gap-3 shrink-0">
                                    <span class="text-lg font-bold text-gray-900">{{ $withdrawal->formatted_amount }}</span>
                                    @if ($withdrawal->canBeCancelled())
                                        <form action="{{ route('withdrawals.cancel', $withdrawal) }}" method="POST"
                                              onsubmit="return confirm('Batalkan penarikan ini?')">
                                            @csrf
                                            <button type="submit" class="text-xs font-medium text-red-600 hover:text-red-800">Batalkan</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">{{ $withdrawals->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
