<x-guest-layout>
    <div class="min-h-screen bg-gradient-to-br from-brand-50 via-white to-brand-100/30 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="flex justify-center transition-transform duration-300 hover:scale-105">
                <x-authentication-card-logo />
            </div>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md px-4">
            <div class="bg-white py-10 px-8 shadow-xl sm:rounded-3xl border border-gray-100/50 text-center">

                {{-- Success Icon --}}
                <div class="w-20 h-20 mx-auto rounded-full bg-brand-50 flex items-center justify-center border border-brand-100">
                    <svg class="w-10 h-10 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                    </svg>
                </div>

                {{-- Message --}}
                <h2 class="mt-5 text-2xl font-bold text-gray-900 font-display">Email Terverifikasi!</h2>
                <p class="mt-2 text-sm text-gray-500 leading-relaxed">
                    Selamat! Akun Anda telah berhasil diverifikasi.<br>
                    Anda sekarang dapat menggunakan semua fitur AutoPahala.
                </p>

                {{-- Features unlocked --}}
                <div class="mt-6 bg-brand-50/50 border border-brand-100/50 rounded-2xl p-5 text-left">
                    <p class="text-xs font-bold text-brand-700 uppercase tracking-wider mb-3 font-display">Fitur yang sekarang terbuka:</p>
                    <div class="space-y-2.5">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-brand-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            <span class="text-sm text-brand-900 font-medium">Buat kampanye penggalangan dana</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-brand-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            <span class="text-sm text-brand-900 font-medium">Berikan donasi ke kampanye</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-brand-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            <span class="text-sm text-brand-900 font-medium">Tarik dana ke rekening bank</span>
                        </div>
                    </div>
                </div>

                {{-- CTA --}}
                <div class="mt-6 space-y-3">
                    <a href="{{ url('/') }}" class="block w-full py-3 px-4 rounded-xl bg-brand-500 text-white font-bold text-sm hover:bg-brand-600 shadow-lg shadow-brand-500/10 transition-all hover:scale-[1.01] active:scale-[0.99] duration-150">
                        Mulai Jelajahi Kampanye
                    </a>
                    <a href="{{ url('/campaigns/create') }}" class="block w-full py-3 px-4 rounded-xl border border-gray-200 text-gray-700 font-semibold text-sm hover:bg-gray-50 transition-all hover:scale-[1.01] active:scale-[0.99] duration-150">
                        Buat Kampanye Pertama
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
