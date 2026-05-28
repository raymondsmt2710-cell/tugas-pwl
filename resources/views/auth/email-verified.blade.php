<x-guest-layout>
    <div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="text-center">
                <a href="/" class="text-2xl font-bold text-gray-900">Auto<span class="text-indigo-600">pahala</span></a>
            </div>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-10 px-6 shadow-sm sm:rounded-2xl border border-gray-100 text-center">

                {{-- Success Icon --}}
                <div class="w-20 h-20 mx-auto rounded-full bg-green-100 flex items-center justify-center">
                    <svg class="w-10 h-10 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                    </svg>
                </div>

                {{-- Message --}}
                <h2 class="mt-5 text-xl font-bold text-gray-900">Email Terverifikasi!</h2>
                <p class="mt-2 text-sm text-gray-500 leading-relaxed">
                    Selamat! Akun Anda telah berhasil diverifikasi.<br>
                    Anda sekarang dapat menggunakan semua fitur AutoPahala.
                </p>

                {{-- Features unlocked --}}
                <div class="mt-6 bg-green-50 rounded-xl p-4 text-left">
                    <p class="text-xs font-semibold text-green-700 uppercase tracking-wide mb-2">Fitur yang tersedia:</p>
                    <div class="space-y-2">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            <span class="text-sm text-green-700">Buat kampanye penggalangan dana</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            <span class="text-sm text-green-700">Berikan donasi ke kampanye</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            <span class="text-sm text-green-700">Tarik dana ke rekening bank</span>
                        </div>
                    </div>
                </div>

                {{-- CTA --}}
                <div class="mt-6 space-y-3">
                    <a href="{{ url('/') }}" class="block w-full py-3 px-4 rounded-xl bg-indigo-600 text-white font-semibold text-sm hover:bg-indigo-700 shadow-sm transition">
                        Mulai Jelajahi Kampanye
                    </a>
                    <a href="{{ url('/campaigns/create') }}" class="block w-full py-3 px-4 rounded-xl border border-gray-200 text-gray-700 font-medium text-sm hover:bg-gray-50 transition">
                        Buat Kampanye Pertama
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
