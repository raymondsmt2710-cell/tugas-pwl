<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Leaderboard</h2>
    </x-slot>

    <div class="py-8 sm:py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Period Filter --}}
            <div class="mb-6 flex flex-wrap gap-2">
                @foreach(['all' => 'Semua', 'weekly' => 'Minggu Ini', 'monthly' => 'Bulan Ini', 'yearly' => 'Tahun Ini'] as $key => $label)
                    <a href="{{ url('/leaderboard?period=' . $key . '&tab=' . $tab) }}"
                       class="px-4 py-2 rounded-lg text-sm font-medium transition {{ $period === $key ? 'bg-indigo-600 text-white' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>

            {{-- Tabs --}}
            <div class="mb-8 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="flex border-b border-gray-100">
                    <a href="{{ url('/leaderboard?period=' . $period . '&tab=donors') }}"
                       class="flex-1 text-center px-4 py-3 text-sm font-medium transition {{ $tab === 'donors' ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-gray-500 hover:text-gray-700' }}">
                        🏆 Top Donatur
                    </a>
                    <a href="{{ url('/leaderboard?period=' . $period . '&tab=campaigns') }}"
                       class="flex-1 text-center px-4 py-3 text-sm font-medium transition {{ $tab === 'campaigns' ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-gray-500 hover:text-gray-700' }}">
                        📈 Top Kampanye
                    </a>
                    <a href="{{ url('/leaderboard?period=' . $period . '&tab=creators') }}"
                       class="flex-1 text-center px-4 py-3 text-sm font-medium transition {{ $tab === 'creators' ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-gray-500 hover:text-gray-700' }}">
                        ⭐ Top Penggalang
                    </a>
                </div>
            </div>

            {{-- Content based on tab --}}
            @php
                $data = match($tab) {
                    'campaigns' => $topCampaigns,
                    'creators' => $topCreators,
                    default => $topDonors,
                };
                $top3 = array_slice($data, 0, 3);
                $rest = array_slice($data, 3);
            @endphp

            @if(empty($data))
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
                    <p class="text-sm text-gray-500">Belum ada data untuk periode ini.</p>
                </div>
            @else
                {{-- PODIUM SECTION --}}
                @if(count($top3) >= 1)
                    <div class="mb-8">
                        {{-- Desktop: #2 #1 #3 layout --}}
                        <div class="hidden sm:flex items-end justify-center gap-4">
                            {{-- Rank #2 --}}
                            @if(isset($top3[1]))
                                <a href="{{ $tab === 'campaigns' ? url('/campaigns/' . $top3[1]['slug']) : url('/@' . $top3[1]['username']) }}" class="flex flex-col items-center w-40 hover:opacity-80 transition">
                                    <div class="relative">
                                        <div class="w-16 h-16 rounded-full border-4 border-gray-300 overflow-hidden bg-gray-100">
                                            @if($tab === 'campaigns')
                                                <img src="{{ $top3[1]['banner_image'] ? asset('storage/' . $top3[1]['banner_image']) : '' }}" class="w-full h-full object-cover">
                                            @else
                                                <img src="{{ $top3[1]['avatar'] }}" class="w-full h-full object-cover">
                                            @endif
                                        </div>
                                        <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-gray-400 text-white rounded-full flex items-center justify-center text-xs font-bold">2</div>
                                    </div>
                                    <p class="mt-2 text-sm font-semibold text-gray-900 text-center truncate w-full">
                                        @if($tab === 'campaigns') {{ $top3[1]['title'] }} @else {{ $top3[1]['user_name'] }} @endif
                                    </p>
                                    <p class="text-xs font-bold text-green-600">Rp {{ number_format($tab === 'campaigns' ? $top3[1]['collected_amount'] : ($tab === 'creators' ? $top3[1]['total_raised'] : $top3[1]['total_amount']), 0, ',', '.') }}</p>
                                    <div class="mt-2 w-full h-20 bg-gray-200 rounded-t-lg"></div>
                                </a>
                            @endif

                            {{-- Rank #1 --}}
                            <a href="{{ $tab === 'campaigns' ? url('/campaigns/' . $top3[0]['slug']) : url('/@' . $top3[0]['username']) }}" class="flex flex-col items-center w-44 hover:opacity-80 transition">
                                <div class="relative">
                                    <div class="w-20 h-20 rounded-full border-4 border-yellow-400 overflow-hidden bg-gray-100 shadow-lg">
                                        @if($tab === 'campaigns')
                                            <img src="{{ $top3[0]['banner_image'] ? asset('storage/' . $top3[0]['banner_image']) : '' }}" class="w-full h-full object-cover">
                                        @else
                                            <img src="{{ $top3[0]['avatar'] }}" class="w-full h-full object-cover">
                                        @endif
                                    </div>
                                    <div class="absolute -bottom-1 -right-1 w-7 h-7 bg-yellow-500 text-white rounded-full flex items-center justify-center text-xs font-bold shadow">🏆</div>
                                </div>
                                <p class="mt-2 text-base font-bold text-gray-900 text-center truncate w-full">
                                    @if($tab === 'campaigns') {{ $top3[0]['title'] }} @else {{ $top3[0]['user_name'] }} @endif
                                </p>
                                <p class="text-sm font-bold text-green-600">Rp {{ number_format($tab === 'campaigns' ? $top3[0]['collected_amount'] : ($tab === 'creators' ? $top3[0]['total_raised'] : $top3[0]['total_amount']), 0, ',', '.') }}</p>
                                <div class="mt-2 w-full h-28 bg-yellow-100 border-2 border-yellow-300 rounded-t-lg"></div>
                            </a>

                            {{-- Rank #3 --}}
                            @if(isset($top3[2]))
                                <a href="{{ $tab === 'campaigns' ? url('/campaigns/' . $top3[2]['slug']) : url('/@' . $top3[2]['username']) }}" class="flex flex-col items-center w-40 hover:opacity-80 transition">
                                    <div class="relative">
                                        <div class="w-16 h-16 rounded-full border-4 border-orange-300 overflow-hidden bg-gray-100">
                                            @if($tab === 'campaigns')
                                                <img src="{{ $top3[2]['banner_image'] ? asset('storage/' . $top3[2]['banner_image']) : '' }}" class="w-full h-full object-cover">
                                            @else
                                                <img src="{{ $top3[2]['avatar'] }}" class="w-full h-full object-cover">
                                            @endif
                                        </div>
                                        <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-orange-500 text-white rounded-full flex items-center justify-center text-xs font-bold">3</div>
                                    </div>
                                    <p class="mt-2 text-sm font-semibold text-gray-900 text-center truncate w-full">
                                        @if($tab === 'campaigns') {{ $top3[2]['title'] }} @else {{ $top3[2]['user_name'] }} @endif
                                    </p>
                                    <p class="text-xs font-bold text-green-600">Rp {{ number_format($tab === 'campaigns' ? $top3[2]['collected_amount'] : ($tab === 'creators' ? $top3[2]['total_raised'] : $top3[2]['total_amount']), 0, ',', '.') }}</p>
                                    <div class="mt-2 w-full h-14 bg-orange-100 rounded-t-lg"></div>
                                </a>
                            @endif
                        </div>

                        {{-- Mobile: Stacked vertically --}}
                        <div class="sm:hidden space-y-3">
                            @foreach($top3 as $i => $item)
                                @php
                                    $colors = ['border-yellow-400 bg-yellow-50', 'border-gray-300 bg-gray-50', 'border-orange-300 bg-orange-50'];
                                    $badges = ['🏆', '🥈', '🥉'];
                                    $link = $tab === 'campaigns' ? url('/campaigns/' . $item['slug']) : url('/@' . $item['username']);
                                @endphp
                                <a href="{{ $link }}" class="flex items-center gap-4 p-4 rounded-xl border-2 {{ $colors[$i] }} hover:shadow-md transition">
                                    <span class="text-2xl">{{ $badges[$i] }}</span>
                                    <div class="w-12 h-12 rounded-full overflow-hidden bg-gray-200 shrink-0">
                                        @if($tab === 'campaigns')
                                            <img src="{{ $item['banner_image'] ? asset('storage/' . $item['banner_image']) : '' }}" class="w-full h-full object-cover">
                                        @else
                                            <img src="{{ $item['avatar'] }}" class="w-full h-full object-cover">
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-900 truncate">
                                            @if($tab === 'campaigns') {{ $item['title'] }} @else {{ $item['user_name'] }} @endif
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            @if($tab === 'donors') {{ $item['donation_count'] }} donasi
                                            @elseif($tab === 'campaigns') {{ $item['donor_count'] }} donatur
                                            @else {{ $item['campaign_count'] }} kampanye
                                            @endif
                                        </p>
                                    </div>
                                    <span class="text-sm font-bold text-green-600 shrink-0">
                                        Rp {{ number_format($tab === 'campaigns' ? $item['collected_amount'] : ($tab === 'creators' ? $item['total_raised'] : $item['total_amount']), 0, ',', '.') }}
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- MAIN LIST (Rank 4+) --}}
                @if(!empty($rest))
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        @foreach($rest as $index => $item)
                            <div class="flex items-center justify-between px-5 py-4 {{ !$loop->last ? 'border-b border-gray-50' : '' }}">
                                <div class="flex items-center gap-4 min-w-0">
                                    <div class="w-7 h-7 rounded-full bg-gray-50 flex items-center justify-center text-xs font-bold text-gray-500 shrink-0">
                                        {{ $index + 4 }}
                                    </div>
                                    <div class="w-9 h-9 rounded-full overflow-hidden bg-gray-100 shrink-0">
                                        @if($tab === 'campaigns')
                                            <img src="{{ $item['banner_image'] ? asset('storage/' . $item['banner_image']) : '' }}" class="w-full h-full object-cover">
                                        @else
                                            <img src="{{ $item['avatar'] }}" class="w-full h-full object-cover">
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold text-gray-900 truncate">
                                            @if($tab === 'campaigns')
                                                <a href="{{ url('/campaigns/' . $item['slug']) }}" class="hover:text-indigo-600">{{ $item['title'] }}</a>
                                            @else
                                                <a href="{{ url('/@' . $item['username']) }}" class="hover:text-indigo-600">{{ $item['user_name'] }}</a>
                                            @endif
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            @if($tab === 'donors') {{ $item['donation_count'] }} donasi
                                            @elseif($tab === 'campaigns') {{ $item['creator_name'] }} • {{ $item['donor_count'] }} donatur
                                            @else {{ $item['campaign_count'] }} kampanye
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <span class="text-sm font-bold text-green-600 shrink-0">
                                    Rp {{ number_format($tab === 'campaigns' ? $item['collected_amount'] : ($tab === 'creators' ? $item['total_raised'] : $item['total_amount']), 0, ',', '.') }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif
            @endif

        </div>
    </div>
</x-app-layout>
