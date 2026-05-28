<x-guest-layout>
    <div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="text-center">
                <a href="/" class="text-2xl font-bold text-gray-900">Auto<span class="text-indigo-600">pahala</span></a>
            </div>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-6 shadow-sm sm:rounded-2xl border border-gray-100">

                {{-- Illustration --}}
                <div class="text-center mb-6">
                    <div class="w-20 h-20 mx-auto rounded-full bg-indigo-50 flex items-center justify-center">
                        <svg class="w-10 h-10 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/>
                        </svg>
                    </div>
                    <h2 class="mt-5 text-xl font-bold text-gray-900">Cek Email Anda</h2>
                    <p class="mt-2 text-sm text-gray-500 leading-relaxed">
                        Kami telah mengirim link verifikasi ke
                    </p>
                    <p class="mt-1 text-sm font-semibold text-indigo-600">{{ auth()->user()->email }}</p>
                </div>

                {{-- Success Alert --}}
                @if (session('status') == 'verification-link-sent')
                    <div class="mb-5 rounded-xl bg-green-50 border border-green-200 p-4">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-green-500 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <p class="text-sm text-green-700">Email verifikasi baru berhasil dikirim! Periksa inbox Anda.</p>
                        </div>
                    </div>
                @endif

                {{-- Steps --}}
                <div class="bg-gray-50 rounded-xl p-5 mb-6">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Langkah selanjutnya:</p>
                    <div class="space-y-3">
                        <div class="flex items-start gap-3">
                            <div class="w-6 h-6 rounded-full bg-indigo-100 flex items-center justify-center shrink-0 mt-0.5">
                                <span class="text-xs font-bold text-indigo-600">1</span>
                            </div>
                            <p class="text-sm text-gray-600">Buka inbox email <strong>{{ auth()->user()->email }}</strong></p>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-6 h-6 rounded-full bg-indigo-100 flex items-center justify-center shrink-0 mt-0.5">
                                <span class="text-xs font-bold text-indigo-600">2</span>
                            </div>
                            <p class="text-sm text-gray-600">Cari email dari <strong>AutoPahala</strong> (cek juga folder Spam)</p>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-6 h-6 rounded-full bg-indigo-100 flex items-center justify-center shrink-0 mt-0.5">
                                <span class="text-xs font-bold text-indigo-600">3</span>
                            </div>
                            <p class="text-sm text-gray-600">Klik tombol <strong>"Verifikasi Email Saya"</strong></p>
                        </div>
                    </div>
                </div>

                {{-- Resend Button with 60s cooldown --}}
                <form method="POST" action="{{ route('verification.send') }}" id="resend-form">
                    @csrf
                    <button type="submit" id="resend-btn" class="w-full py-3 px-4 rounded-xl bg-indigo-600 text-white font-semibold text-sm hover:bg-indigo-700 shadow-sm transition flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-indigo-600">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182"/>
                        </svg>
                        <span id="resend-text">Kirim Ulang Email Verifikasi</span>
                    </button>
                </form>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        var btn = document.getElementById('resend-btn');
                        var text = document.getElementById('resend-text');
                        var form = document.getElementById('resend-form');
                        var storageKey = 'resend_cooldown_until';
                        var cooldownUntil = localStorage.getItem(storageKey);

                        function startCooldown(seconds) {
                            var until = Date.now() + (seconds * 1000);
                            localStorage.setItem(storageKey, until);
                            runTimer(until);
                        }

                        function runTimer(until) {
                            btn.disabled = true;
                            var interval = setInterval(function() {
                                var remaining = Math.ceil((until - Date.now()) / 1000);
                                if (remaining <= 0) {
                                    clearInterval(interval);
                                    btn.disabled = false;
                                    text.textContent = 'Kirim Ulang Email Verifikasi';
                                    localStorage.removeItem(storageKey);
                                } else {
                                    text.textContent = 'Tunggu ' + remaining + ' detik...';
                                }
                            }, 1000);
                        }

                        // Check existing cooldown
                        if (cooldownUntil && Date.now() < parseInt(cooldownUntil)) {
                            runTimer(parseInt(cooldownUntil));
                        }

                        // Start cooldown on submit
                        form.addEventListener('submit', function() {
                            startCooldown(60);
                        });

                        @if(session('status') == 'verification-link-sent')
                            startCooldown(60);
                        @endif
                    });
                </script>

                {{-- Divider --}}
                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-200"></div></div>
                </div>

                {{-- Secondary Actions --}}
                <div class="flex items-center justify-between">
                    <a href="{{ route('profile.show') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-gray-600 hover:text-gray-900">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125"/></svg>
                        Ubah Email
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-1.5 text-sm font-medium text-red-600 hover:text-red-800">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9"/></svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>

            {{-- Footer --}}
            <p class="mt-6 text-center text-xs text-gray-400">
                Butuh bantuan? <a href="mailto:tubespwlkel999@gmail.com" class="text-indigo-600 hover:text-indigo-800 font-medium">Hubungi kami</a>
            </p>
        </div>
    </div>
</x-guest-layout>
