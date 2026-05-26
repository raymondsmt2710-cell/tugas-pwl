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
                    <p>Email: support@autopahala.com</p>
                    <p>Instagram: @autopahala</p>
                    <p>Lokasi: Indonesia</p>
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