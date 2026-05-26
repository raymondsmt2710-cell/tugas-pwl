@extends('layouts.app')

@section('content')
<section class="bg-white py-20">
    <div class="max-w-6xl mx-auto px-8">
        <p class="text-green-600 font-semibold mb-3">Tentang Autopahala</p>

        <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
            Platform donasi online untuk memudahkan kebaikan.
        </h1>

        <p class="text-lg text-gray-600 leading-relaxed mb-6">
            Autopahala adalah website crowdfunding dan donasi yang membantu menghubungkan
            donatur dengan campaign sosial yang membutuhkan dukungan.
        </p>

        <p class="text-lg text-gray-600 leading-relaxed">
            Project ini dikembangkan sebagai bagian dari tugas kelompok kuliah dengan
            menggunakan Laravel, Tailwind CSS, Vite, Git, dan GitHub.
        </p>
    </div>
</section>

<section class="bg-gray-100 py-16">
    <div class="max-w-6xl mx-auto px-8 grid md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-2xl shadow">
            <h2 class="text-xl font-bold mb-3">Mudah</h2>
            <p class="text-gray-600">
                Pengguna dapat melihat campaign dan informasi donasi dengan tampilan sederhana.
            </p>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow">
            <h2 class="text-xl font-bold mb-3">Transparan</h2>
            <p class="text-gray-600">
                Setiap campaign menampilkan target, jumlah terkumpul, dan progress donasi.
            </p>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow">
            <h2 class="text-xl font-bold mb-3">Reusable</h2>
            <p class="text-gray-600">
                Struktur Blade dibuat modular agar aman untuk kerja tim.
            </p>
        </div>
    </div>
</section>
@endsection