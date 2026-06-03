<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autopahala - Platform Penggalangan Dana & Donasi Online</title>

    <link rel="icon" type="image/jpeg" href="{{ asset('images/favicon.jpeg') }}">
    <link rel="shortcut icon" type="image/jpeg" href="{{ asset('images/favicon.jpeg') }}">

    <!-- Memanggil Tailwind CSS bawaan project -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- FontAwesome untuk Ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-50/50 font-sans antialiased text-gray-900">

    <!-- 1. NAVBAR (GoFundMe Style dengan warna tema #20BDC4) -->
    <nav class="bg-white shadow-sm border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <img src="{{ asset('images/logo-icon.jpeg') }}" alt="Logo AutoPahala" class="w-8 h-8 rounded-lg object-cover">
                <span class="font-black text-2xl tracking-tight">Auto<span style="color: #20BDC4;">pahala</span></span>
            </div>
            
            <!-- Menu Navigasi Tengah & Kanan -->
            <div class="flex items-center gap-6">
                <a href="/campaigns" class="text-sm font-bold text-gray-600 hover:text-gray-900 transition">Jelajahi</a>
                <a href="/login" class="text-sm font-bold transition hover:opacity-80" style="color: #20BDC4;">Masuk</a>
                <a href="/register" class="hidden sm:inline-block px-5 py-2.5 text-white font-extrabold text-sm rounded-full transition duration-200 hover:opacity-90 shadow-sm" style="background-color: #20BDC4;">
                    Daftar Sekarang
                </a>
            </div>
        </div>
    </nav>

    <!-- 2. HERO SECTION -->
    <main class="max-w-7xl mx-auto px-6 py-16 md:py-24">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-12 items-center">
            
            <!-- SISI KIRI: Teks Intro -->
            <div class="md:col-span-7 space-y-6 text-center md:text-left">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold border" style="background-color: rgba(32, 189, 196, 0.1); color: #20BDC4; border-color: rgba(32, 189, 196, 0.2);">
                    ✨ Tugas Besar Pemrograman Web Lanjutan
                </div>
                
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-gray-950 tracking-tight leading-none">
                    Ubah Niat Baik Menjadi <span style="color: #20BDC4;">Aksi Nyata</span>
                </h1>
                
                <p class="text-lg md:text-xl text-gray-600 max-w-xl mx-auto md:mx-0 leading-relaxed font-normal font-sans">
                    Selamat datang di platform galang dana kelompok kami. Bantu sesama, dukung ide kreatif, atau mulai campaign medismu dengan transparan, aman, dan mudah.
                </p>
                
                <!-- Tombol Aksi -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center md:justify-start pt-2">
                    <a href="/campaigns" class="px-8 py-4 text-white font-extrabold rounded-full shadow-lg transform hover:-translate-y-0.5 transition duration-200 text-center" style="background-color: #20BDC4; shadow-color: rgba(32, 189, 196, 0.3);">
                        Jelajahi Campaign 🚀
                    </a>
                    <a href="/register" class="px-8 py-4 bg-white hover:bg-gray-50 text-gray-700 font-extrabold rounded-full border border-gray-200 text-center shadow-sm transition duration-200">
                        Mulai Galang Dana
                    </a>
                </div>
            </div>

            <!-- SISI KANAN: Kotak Ilustrasi Kampanye Populer -->
            <div class="md:col-span-5 flex justify-center items-center">
                <div class="relative w-full max-w-md">
                    <!-- Ornamen Lingkaran Belakang -->
                    <div class="absolute top-0 -left-4 w-72 h-72 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-pulse" style="background-color: #20BDC4;"></div>
                    
                    <!-- Kotak Ilustrasi Kampanye Unggulan -->
                    <div class="relative bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden transition duration-300 hover:scale-[1.02]">
                        <img src="https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?auto=format&fit=crop&q=80&w=600" alt="Featured Campaign Preview" class="w-full h-48 object-cover">
                        <div class="p-6 space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold px-2.5 py-0.5 rounded-full" style="background-color: rgba(32, 189, 196, 0.1); color: #20BDC4;">Medis & Kesehatan</span>
                                <span class="text-xs font-bold text-gray-400"><i class="fa-solid fa-location-dot"></i> Bandung, Jabar</span>
                            </div>
                            <h3 class="text-lg font-black text-gray-950 leading-snug">
                                Bantu Renovasi Rumah Belajar Anak Pesisir
                            </h3>
                            <div class="space-y-2">
                                <div class="w-full bg-gray-100 rounded-full h-2">
                                    <div class="h-2 rounded-full" style="width: 75%; background-color: #20BDC4;"></div>
                                </div>
                                <div class="flex justify-between text-xs font-bold text-gray-500">
                                    <span>Terkumpul: <strong class="text-gray-900">75%</strong></span>
                                    <span style="color: #20BDC4;">Rp 45.000.000</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <!-- 3. HOW IT WORKS -->
    <section class="bg-white py-16 border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <h2 class="text-3xl font-black text-gray-950 text-center mb-16">Galang dana di Autopahala itu mudah</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <div class="space-y-4 group">
                    <div class="font-black text-6xl text-gray-200 transition duration-300 group-hover:text-[#20BDC4]">1</div>
                    <h3 class="text-xl font-extrabold text-gray-950">Mulai dengan dasar-dasar</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">Atur judul kampanye Anda, tentukan target dana, dan ceritakan kisah haru atau rencana baik Anda kepada dunia.</p>
                </div>
                <div class="space-y-4 group">
                    <div class="font-black text-6xl text-gray-200 transition duration-300 group-hover:text-[#20BDC4]">2</div>
                    <h3 class="text-xl font-extrabold text-gray-950">Bagikan ke kerabat</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">Kirimkan link kampanye Anda lewat pesan singkat, email, atau bagikan langsung di feed media sosial Anda untuk menjangkau donatur.</p>
                </div>
                <div class="space-y-4 group">
                    <div class="font-black text-6xl text-gray-200 transition duration-300 group-hover:text-[#20BDC4]">3</div>
                    <h3 class="text-xl font-extrabold text-gray-950">Terima penarikan dana</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">Setiap rupiah yang masuk bisa Anda pantau secara transparan dan dicairkan berkala langsung ke rekening bank yang dituju.</p>
                </div>
            </div>
        </div>
    </section>

</body>
</html>