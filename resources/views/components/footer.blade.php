<footer class="bg-gray-900 py-12 mt-auto">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            {{-- Brand --}}
            <div class="md:col-span-2">
                <a href="{{ url('/') }}" class="flex items-center gap-2.5 text-lg font-bold text-white">
                    <img src="{{ asset('images/logo-icon.jpeg') }}" alt="Logo AutoPahala" class="w-7 h-7 rounded-lg object-cover">
                    <span>Auto<span class="text-brand-400">pahala</span></span>
                </a>
                <p class="mt-3 text-sm text-gray-400 max-w-sm leading-relaxed">
                    Platform crowdfunding terpercaya untuk membantu sesama. Setiap kebaikan, sekecil apapun, berarti.
                </p>
            </div>

            {{-- Navigasi --}}
            <div>
                <p class="text-sm font-semibold text-white">Navigasi</p>
                <div class="mt-3 space-y-2">
                    <a href="{{ url('/campaigns') }}" class="block text-sm text-gray-400 hover:text-white transition">Kampanye</a>
                    <a href="{{ url('/leaderboard') }}" class="block text-sm text-gray-400 hover:text-white transition">Leaderboard</a>
                    <a href="{{ url('/#cara-kerja') }}" class="block text-sm text-gray-400 hover:text-white transition">Cara Kerja</a>
                    <a href="{{ url('/#faq') }}" class="block text-sm text-gray-400 hover:text-white transition">FAQ</a>
                    <a href="{{ url('/#contact') }}" class="block text-sm text-gray-400 hover:text-white transition">Kontak</a>
                </div>
            </div>

            {{-- Akun --}}
            <div>
                <p class="text-sm font-semibold text-white">Akun</p>
                <div class="mt-3 space-y-2">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="block text-sm text-gray-400 hover:text-white transition">Dashboard</a>
                        <a href="{{ url('/@' . auth()->user()->username) }}" class="block text-sm text-gray-400 hover:text-white transition">Profil Publik</a>
                        <a href="{{ url('/dashboard?tab=campaigns') }}" class="block text-sm text-gray-400 hover:text-white transition">Kampanye Saya</a>
                        <a href="{{ url('/dashboard?tab=donations') }}" class="block text-sm text-gray-400 hover:text-white transition">Riwayat Donasi</a>
                    @else
                        <a href="{{ route('login') }}" class="block text-sm text-gray-400 hover:text-white transition">Masuk</a>
                        <a href="{{ route('register') }}" class="block text-sm text-gray-400 hover:text-white transition">Daftar</a>
                    @endauth
                </div>
            </div>
        </div>

        <div class="mt-10 pt-6 border-t border-gray-800 text-center">
            <p class="text-xs text-gray-500">&copy; {{ date('Y') }} Autopahala. All rights reserved.</p>
        </div>
    </div>
</footer>
