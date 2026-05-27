<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Autopahala' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-white text-gray-900 antialiased">

    {{-- Navbar --}}
    <x-navbar />

    {{-- Hero --}}
    <section class="py-20 sm:py-28">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 text-center">
            <h1 class="text-4xl sm:text-5xl font-bold text-gray-900 leading-tight">
                Berbagi Kebaikan,<br>Satu Donasi Sekaligus
            </h1>
            <p class="mt-5 text-lg text-gray-600 max-w-2xl mx-auto">
                Platform crowdfunding terpercaya untuk membantu sesama. Buat kampanye atau berikan donasi untuk mereka yang membutuhkan.
            </p>
            <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="{{ url('/campaigns') }}" class="w-full sm:w-auto px-6 py-3 rounded-lg bg-indigo-600 text-white font-semibold text-sm hover:bg-indigo-700 transition">
                    Jelajahi Kampanye
                </a>
                @auth
                    <a href="{{ url('/campaigns/create') }}" class="w-full sm:w-auto px-6 py-3 rounded-lg border border-gray-300 text-gray-700 font-semibold text-sm hover:bg-gray-50 transition">
                        Buat Kampanye
                    </a>
                @else
                    <a href="{{ route('register') }}" class="w-full sm:w-auto px-6 py-3 rounded-lg border border-gray-300 text-gray-700 font-semibold text-sm hover:bg-gray-50 transition">
                        Mulai Galang Dana
                    </a>
                @endauth
            </div>
        </div>
    </section>

    {{-- Statistics --}}
    <section class="py-12 bg-gray-50 border-y border-gray-100">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="grid grid-cols-3 gap-8 text-center">
                <div>
                    <p class="text-3xl font-bold text-gray-900">{{ $totalCampaigns }}</p>
                    <p class="mt-1 text-sm text-gray-500">Kampanye Aktif</p>
                </div>
                <div>
                    <p class="text-3xl font-bold text-gray-900">{{ $totalDonors }}</p>
                    <p class="mt-1 text-sm text-gray-500">Donatur</p>
                </div>
                <div>
                    <p class="text-3xl font-bold text-gray-900">Rp {{ number_format($totalRaised / 1000000, 0) }}jt</p>
                    <p class="mt-1 text-sm text-gray-500">Dana Terkumpul</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Featured Campaigns --}}
    <section id="campaigns" class="py-16 sm:py-20">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Kampanye Terbaru</h2>
                    <p class="mt-1 text-sm text-gray-500">Bantu mereka yang membutuhkan</p>
                </div>
                <a href="{{ url('/campaigns') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">Lihat Semua →</a>
            </div>
            @livewire('featured-campaigns')
        </div>
    </section>

    {{-- Categories --}}
    <section id="categories" class="py-16 bg-gray-50 border-y border-gray-100">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <h2 class="text-2xl font-bold text-gray-900 text-center">Kategori</h2>
            <p class="mt-2 text-sm text-gray-500 text-center">Pilih kategori yang ingin Anda dukung</p>
            <div class="mt-8 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
                @foreach($categories as $category)
                    <a href="{{ url('/campaigns?category=' . $category->id_category) }}" class="block p-4 bg-white rounded-xl border border-gray-200 text-center hover:border-indigo-300 hover:shadow-sm transition">
                        <p class="text-sm font-semibold text-gray-900">{{ $category->name }}</p>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- How It Works --}}
    <section id="how-it-works" class="py-16 sm:py-20">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <h2 class="text-2xl font-bold text-gray-900 text-center">Cara Kerja</h2>
            <p class="mt-2 text-sm text-gray-500 text-center">Tiga langkah mudah untuk mulai berbagi</p>
            <div class="mt-10 grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="w-12 h-12 mx-auto rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-lg">1</div>
                    <h3 class="mt-4 text-base font-semibold text-gray-900">Buat Kampanye</h3>
                    <p class="mt-2 text-sm text-gray-500">Daftarkan akun dan buat kampanye penggalangan dana dengan detail yang jelas.</p>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 mx-auto rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-lg">2</div>
                    <h3 class="mt-4 text-base font-semibold text-gray-900">Bagikan</h3>
                    <p class="mt-2 text-sm text-gray-500">Setelah diverifikasi, bagikan kampanye Anda ke teman, keluarga, dan media sosial.</p>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 mx-auto rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-lg">3</div>
                    <h3 class="mt-4 text-base font-semibold text-gray-900">Terima Dana</h3>
                    <p class="mt-2 text-sm text-gray-500">Donasi masuk langsung ke kampanye. Tarik dana kapan saja ke rekening bank Anda.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- About --}}
    <section class="py-16 bg-gray-50 border-y border-gray-100">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 text-center">
            <h2 class="text-2xl font-bold text-gray-900">Tentang Autopahala</h2>
            <p class="mt-4 text-gray-600 leading-relaxed">
                Autopahala adalah platform crowdfunding yang menghubungkan orang-orang baik dengan mereka yang membutuhkan bantuan. Kami percaya setiap kebaikan, sekecil apapun, bisa membawa perubahan besar. Dengan sistem yang transparan dan terverifikasi, kami memastikan setiap donasi sampai ke tangan yang tepat.
            </p>
        </div>
    </section>

    {{-- Success Stories --}}
    <section class="py-16 sm:py-20">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <h2 class="text-2xl font-bold text-gray-900 text-center">Cerita Sukses</h2>
            <p class="mt-2 text-sm text-gray-500 text-center">Kampanye yang berhasil mencapai target</p>
            <div class="mt-10 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="p-6 bg-white rounded-xl border border-gray-200">
                    <p class="text-sm text-gray-600 italic">"Berkat Autopahala, kami berhasil mengumpulkan dana untuk operasi anak kami dalam waktu 2 minggu. Terima kasih untuk semua donatur."</p>
                    <p class="mt-4 text-sm font-semibold text-gray-900">— Ibu Sari, Jakarta</p>
                </div>
                <div class="p-6 bg-white rounded-xl border border-gray-200">
                    <p class="text-sm text-gray-600 italic">"Platform yang sangat mudah digunakan. Proses verifikasi cepat dan penarikan dana lancar tanpa kendala."</p>
                    <p class="mt-4 text-sm font-semibold text-gray-900">— Pak Ahmad, Surabaya</p>
                </div>
                <div class="p-6 bg-white rounded-xl border border-gray-200">
                    <p class="text-sm text-gray-600 italic">"Saya rutin berdonasi di sini karena yakin kampanyenya sudah diverifikasi. Transparansi yang baik."</p>
                    <p class="mt-4 text-sm font-semibold text-gray-900">— Rina, Bandung</p>
                </div>
            </div>
        </div>
    </section>

    {{-- FAQ --}}
    <section id="faq" class="py-16 bg-gray-50 border-y border-gray-100">
        <div class="max-w-2xl mx-auto px-4 sm:px-6">
            <h2 class="text-2xl font-bold text-gray-900 text-center">Pertanyaan Umum</h2>
            <p class="mt-2 text-sm text-gray-500 text-center mb-8">Jawaban untuk pertanyaan yang sering ditanyakan</p>
            @livewire('faq-section')
        </div>
    </section>

    {{-- Contact --}}
    <section id="contact" class="py-16 sm:py-20">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Hubungi Kami</h2>
                    <p class="mt-2 text-sm text-gray-500">Ada pertanyaan atau masukan? Kami siap membantu.</p>
                    <div class="mt-6 space-y-4">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/></svg>
                            <span class="text-sm text-gray-600">support@autopahala.com</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                            <span class="text-sm text-gray-600">Medan, Sumatera Utara, Indonesia</span>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    @livewire('contact-form')
                </div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="py-16 bg-indigo-600">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 text-center">
            <h2 class="text-2xl font-bold text-white">Siap Berbagi Kebaikan?</h2>
            <p class="mt-2 text-indigo-100">Mulai buat kampanye atau berikan donasi hari ini.</p>
            <div class="mt-6 flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="{{ url('/campaigns') }}" class="w-full sm:w-auto px-6 py-3 rounded-lg bg-white text-indigo-600 font-semibold text-sm hover:bg-indigo-50 transition">
                    Donasi Sekarang
                </a>
                @auth
                    <a href="{{ url('/campaigns/create') }}" class="w-full sm:w-auto px-6 py-3 rounded-lg border border-indigo-300 text-white font-semibold text-sm hover:bg-indigo-700 transition">
                        Buat Kampanye
                    </a>
                @else
                    <a href="{{ route('register') }}" class="w-full sm:w-auto px-6 py-3 rounded-lg border border-indigo-300 text-white font-semibold text-sm hover:bg-indigo-700 transition">
                        Daftar Gratis
                    </a>
                @endauth
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="py-12 bg-gray-900">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="md:col-span-2">
                    <p class="text-lg font-bold text-white">Auto<span class="text-indigo-400">pahala</span></p>
                    <p class="mt-2 text-sm text-gray-400 max-w-sm">Platform crowdfunding terpercaya untuk membantu sesama. Setiap kebaikan, sekecil apapun, berarti.</p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-white">Navigasi</p>
                    <div class="mt-3 space-y-2">
                        <a href="{{ url('/campaigns') }}" class="block text-sm text-gray-400 hover:text-white">Kampanye</a>
                        <a href="{{ url('/about') }}" class="block text-sm text-gray-400 hover:text-white">Tentang</a>
                        <a href="#faq" class="block text-sm text-gray-400 hover:text-white">FAQ</a>
                        <a href="#contact" class="block text-sm text-gray-400 hover:text-white">Kontak</a>
                    </div>
                </div>
                <div>
                    <p class="text-sm font-semibold text-white">Akun</p>
                    <div class="mt-3 space-y-2">
                        @auth
                            <a href="{{ url('/user/profile') }}" class="block text-sm text-gray-400 hover:text-white">Profil</a>
                            <a href="{{ url('/my-campaigns') }}" class="block text-sm text-gray-400 hover:text-white">Kampanye Saya</a>
                            <a href="{{ url('/my-donations') }}" class="block text-sm text-gray-400 hover:text-white">Riwayat Donasi</a>
                        @else
                            <a href="{{ route('login') }}" class="block text-sm text-gray-400 hover:text-white">Masuk</a>
                            <a href="{{ route('register') }}" class="block text-sm text-gray-400 hover:text-white">Daftar</a>
                        @endauth
                    </div>
                </div>
            </div>
            <div class="mt-10 pt-6 border-t border-gray-800 text-center">
                <p class="text-xs text-gray-500">&copy; {{ date('Y') }} Autopahala. All rights reserved.</p>
            </div>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
