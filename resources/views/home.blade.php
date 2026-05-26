@extends('layouts.app')

@section('content')

    <!-- HERO -->

    <section class="relative h-screen">

    <img
        src="https://picsum.photos/1920/1080"
        class="w-full h-full object-cover"
    >

    <div class="absolute inset-0 bg-black/50"></div>

    <div class="absolute inset-0 flex flex-col justify-center items-center text-white text-center px-5">

        <h1 class="text-6xl font-bold mb-6">
            Bantu Mereka Yang Membutuhkan
        </h1>

        <p class="text-xl mb-8 max-w-2xl">
            Platform donasi online untuk membantu sesama dan
            membuat kebaikan menjadi lebih mudah.
        </p>

        <button class="bg-green-500 hover:bg-green-600 px-8 py-4 rounded-xl text-lg font-semibold">
            Mulai Donasi
        </button>

    </div>

</section>

    <!-- SEARCH -->

    <section class="bg-white py-16">

        <div class="max-w-4xl mx-auto text-center">

            <h2 class="text-4xl font-bold mb-8">
                Cari Campaign
            </h2>

            <div class="flex gap-4 justify-center">

                <input
                    type="text"
                    placeholder="Cari campaign..."
                    class="border w-2/3 p-4 rounded-xl"
                >

                <button class="bg-green-500 text-white px-8 rounded-xl">
                    Cari
                </button>

            </div>

        </div>

    </section>

    <!-- CAMPAIGN -->

    <section class="py-20 px-10">

        <h2 class="text-4xl font-bold text-center mb-14">
            Campaign Populer
        </h2>

        <div class="grid md:grid-cols-3 gap-8">

            @foreach ($campaigns as $campaign)

                @php
                    $percentage = ($campaign['raised'] / $campaign['target']) * 100;
                @endphp

                <div class="bg-white rounded-2xl overflow-hidden shadow-lg">

                    <img
                        src="{{ $campaign['image'] }}"
                        alt="{{ $campaign['title'] }}"
                        class="h-60 w-full object-cover"
                    >

                    <div class="p-6">

                        <span class="inline-block bg-green-100 text-green-700 text-sm font-semibold px-3 py-1 rounded-full mb-4">
                            {{ $campaign['category'] }}
                        </span>

                        <h3 class="text-2xl font-bold mb-3">
                            {{ $campaign['title'] }}
                        </h3>

                        <p class="text-gray-600 mb-5">
                            {{ $campaign['description'] }}
                        </p>

                        <div class="w-full bg-gray-200 rounded-full h-3 mb-3">

                            <div
                                class="bg-green-500 h-3 rounded-full"
                                style="width: {{ min($percentage, 100) }}%"
                            ></div>

                        </div>

                        <div class="flex justify-between text-sm mb-2">

                            <span class="font-semibold text-green-600">
                                Rp {{ number_format($campaign['raised'], 0, ',', '.') }}
                            </span>

                            <span class="text-gray-500">
                                {{ round($percentage) }}%
                            </span>

                        </div>

                        <p class="text-sm text-gray-500 mb-5">
                            Target Rp {{ number_format($campaign['target'], 0, ',', '.') }}
                            • {{ $campaign['donors'] }} donatur
                        </p>

                        <button class="bg-green-500 hover:bg-green-600 text-white px-5 py-3 rounded-xl w-full">
                            Donasi Sekarang
                        </button>

                    </div>

                </div>

            @endforeach

        </div>

    </section>

    <!-- FOOTER -->

    <footer class="bg-black text-white py-10 text-center">

        <h2 class="text-3xl font-bold mb-4">
            Autopahala
        </h2>

        <p class="text-gray-400">
            Platform donasi online untuk membantu sesama.
        </p>

    </footer>

@endsection 