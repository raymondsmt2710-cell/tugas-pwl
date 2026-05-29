<x-app-layout>
    <div class="py-16 sm:py-24">
        <div class="max-w-md mx-auto px-4 sm:px-6 text-center">
            <div class="w-20 h-20 mx-auto rounded-full bg-gray-100 flex items-center justify-center mb-6">
                <svg class="w-10 h-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/>
                </svg>
            </div>
            <h1 class="text-xl font-bold text-gray-900">Profil Ini Bersifat Privat</h1>
            <p class="mt-3 text-sm text-gray-500 leading-relaxed">
                <strong>{{ $user->full_name }}</strong> telah menyembunyikan profilnya. Konten profil ini tidak tersedia untuk dilihat publik.
            </p>
            <a href="{{ url('/') }}" class="mt-6 inline-flex items-center px-5 py-2.5 rounded-lg bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700 transition">
                ← Kembali ke Beranda
            </a>
        </div>
    </div>
</x-app-layout>
