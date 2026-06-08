@extends('layouts.app')

@section('content')
<section class="bg-white py-20">
    <div class="max-w-6xl mx-auto px-8">
        <p class="text-green-600 font-semibold mb-3">Kontak</p>

        <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
            Hubungi Tim Autopahala
        </h1>

        <p class="text-lg text-gray-600 leading-relaxed mb-10">
            Untuk pertanyaan, masukan, atau kerja sama, pengguna dapat menghubungi
            tim Autopahala melalui informasi berikut.
        </p>

        <div class="grid md:grid-cols-2 gap-6">
            <div class="bg-gray-100 p-6 rounded-2xl shadow">
                <h2 class="text-xl font-bold mb-4">Informasi Kontak</h2>

                <div class="space-y-3 text-gray-700">
                    <p>Email: <a href="mailto:{{ $siteSetting->email ?? 'support@autopahala.com' }}" class="text-green-600 hover:underline">{{ $siteSetting->email ?? 'support@autopahala.com' }}</a></p>
                    @if(!empty($siteSetting->phone))
                        <p>Telepon: <a href="tel:{{ $siteSetting->phone }}" class="text-green-600 hover:underline">{{ $siteSetting->phone }}</a></p>
                    @endif
                    @if(!empty($siteSetting->social_media))
                        @foreach($siteSetting->social_media as $social)
                            @php
                                $platformLabel = ucfirst($social['platform'] === 'twitter' ? 'Twitter / X' : $social['platform']);
                            @endphp
                            <p>{{ $platformLabel }}: <a href="{{ $social['url'] }}" target="_blank" class="text-green-600 hover:underline">{{ $social['label'] ?? $social['url'] }}</a></p>
                        @endforeach
                    @endif
                    <p>Lokasi: <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($siteSetting->address ?? 'Medan, Sumatera Utara, Indonesia') }}" target="_blank" class="text-green-600 hover:underline">{{ $siteSetting->address ?? 'Indonesia' }}</a></p>
                </div>
            </div>

            <div class="bg-gray-100 p-6 rounded-2xl shadow">
                <h2 class="text-xl font-bold mb-4">Catatan Project</h2>

                <p class="text-gray-600 leading-relaxed">
                    Halaman ini masih berupa halaman statis. Ke depannya halaman kontak
                    bisa dikembangkan menjadi form yang tersimpan ke database.
                </p>
            </div>
        </div>
    </div>
</section>
@endsection