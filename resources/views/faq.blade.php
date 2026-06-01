@extends('layouts.app')

@section('content')
<section class="bg-white py-20">
    <div class="max-w-6xl mx-auto px-8">
        <p class="text-green-600 font-semibold mb-3">FAQ</p>

        <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
            Pertanyaan yang Sering Diajukan
        </h1>

        <p class="text-lg text-gray-600 leading-relaxed mb-10">
            Berikut beberapa pertanyaan umum mengenai Autopahala dan pengembangan project.
        </p>

        <div class="space-y-5">
            <div class="bg-gray-100 p-6 rounded-2xl shadow">
                <h2 class="text-xl font-bold mb-2">
                    Apa itu Autopahala?
                </h2>
                <p class="text-gray-600">
                    Autopahala adalah website crowdfunding dan donasi untuk membantu pengguna
                    menemukan dan mendukung campaign sosial.
                </p>
            </div>

            <div class="bg-gray-100 p-6 rounded-2xl shadow">
                <h2 class="text-xl font-bold mb-2">
                    Apakah homepage sudah terhubung ke database?
                </h2>
                <p class="text-gray-600">
                    Saat ini homepage belum mengambil data dari database secara langsung,
                    tetapi struktur akan disiapkan agar campaign bisa dibuat dinamis.
                </p>
            </div>

            <div class="bg-gray-100 p-6 rounded-2xl shadow">
                <h2 class="text-xl font-bold mb-2">
                    Mengapa navbar dipisahkan?
                </h2>
                <p class="text-gray-600">
                    Navbar dipisahkan agar bisa dipakai ulang di banyak halaman dan mengurangi
                    duplikasi kode.
                </p>
            </div>

            <div class="bg-gray-100 p-6 rounded-2xl shadow">
                <h2 class="text-xl font-bold mb-2">
                    Apa pengembangan berikutnya?
                </h2>
                <p class="text-gray-600">
                    Tahap berikutnya adalah membuat data campaign dari database menggunakan
                    model, migration, dan seeder Laravel.
                </p>
            </div>
        </div>
    </div>
</section>
@endsection