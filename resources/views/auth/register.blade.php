<x-guest-layout>
    <div class="min-h-screen lg:h-screen w-screen overflow-y-auto lg:overflow-hidden bg-[#EEF7F7] flex items-center justify-center p-4 sm:p-6 lg:p-8 font-sans selection:bg-brand-100 selection:text-brand-900">
        <div class="auth-container relative w-full max-w-md lg:max-w-[1000px] min-h-auto lg:h-[600px] bg-white rounded-3xl lg:rounded-[32px] shadow-[0_25px_60px_-15px_rgba(7,54,59,0.08)] border border-gray-100/50 overflow-hidden flex flex-col lg:block p-8 sm:p-10 lg:p-0"
             x-data="{ 
                 state: 'register',
                 goTo(newState, routeUrl) {
                     this.state = newState;
                     window.history.pushState(null, '', routeUrl);
                 }
             }"
             :class="state === 'register' ? 'state-register' : 'state-login'">

            {{-- 1. Branding & Stats Panel (55% Width, Slides Left/Right on Desktop, Hidden on Mobile) --}}
            <div class="branding-panel hidden lg:flex absolute top-0 left-0 h-full w-[40%] bg-[#20BDC4] text-white p-8 flex-col justify-between z-30 transition-transform duration-700 ease-in-out overflow-hidden">
                {{-- Decorative background mesh --}}
                <div class="absolute -right-10 -top-10 w-64 h-64 bg-white/5 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute -left-10 -bottom-10 w-96 h-96 bg-brand-500/10 rounded-full blur-3xl pointer-events-none"></div>

                <div class="relative z-10">
                    <a href="/" class="inline-flex items-center gap-3 group">
                        <div class="w-10 h-10 rounded-2xl bg-white flex items-center justify-center border border-white/20 shadow-md group-hover:scale-105 transition-transform">
                            <i class="fa-solid fa-hand-holding-heart text-[#20BDC4] text-lg"></i>
                        </div>
                    </a>
                </div>

                <div class="relative z-10 my-auto w-full min-h-[380px]">
                    
                    {{-- Content for LOGIN state --}}
                    <div class="absolute inset-x-0 top-0 space-y-6"
                         x-show="state === 'login'"
                         x-transition:enter="transition ease-out duration-500 delay-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-300"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95">
                        
                        <div class="space-y-4">
                            <h1 class="text-2.5xl lg:text-3xl font-black leading-tight tracking-tight font-display">
                                Menghubungkan Kebaikan Tanpa Batas.
                            </h1>
                            <p class="text-brand-100/90 leading-relaxed text-sm">
                                Bergabunglah bersama ribuan orang baik untuk mendukung penggalangan dana, mengelola donasi, dan membagikan manfaat ke seluruh penjuru negeri.
                            </p>
                        </div>

                        {{-- Glassmorphism Statistics Card (Login) --}}
                        <div class="bg-white/10 backdrop-blur-md border border-white/10 rounded-2xl p-4 shadow-xl space-y-3 max-w-[320px] w-full transition-all duration-500 hover:bg-white/15">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center border border-white/15 shrink-0 text-white">
                                    <i class="fa-solid fa-chart-simple text-white text-base"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-brand-200">Total Donasi Terkumpul</p>
                                    <p class="text-lg font-bold font-display text-white">Rp {{ number_format(\App\Models\Donation::successful()->sum('donation_amount'), 0, ',', '.') }}</p>
                                </div>
                            </div>
                            <div class="h-px bg-white/10"></div>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center border border-white/15 shrink-0 text-white">
                                    <i class="fa-solid fa-shield-halved text-white text-base"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-brand-200">Keamanan Transaksi</p>
                                    <p class="text-lg font-bold font-display text-white">100% Terverifikasi</p>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- Content for REGISTER state --}}
                    <div class="absolute inset-x-0 top-0 space-y-6"
                         x-show="state === 'register'"
                         x-transition:enter="transition ease-out duration-500 delay-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-300"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         style="display: none;">
                        
                        <div class="space-y-4">
                            <h1 class="text-2.5xl lg:text-3xl font-black leading-tight tracking-tight font-display">
                                Mulai Langkah Kebaikan Pertama.
                            </h1>
                            <p class="text-brand-100/90 leading-relaxed text-sm">
                                Buat kampanye penggalangan dana medis, sosial, atau bencana hanya dalam beberapa menit secara aman, cepat, dan transparan.
                            </p>
                        </div>

                        {{-- Glassmorphism Statistics Card (Register) --}}
                        <div class="bg-white/10 backdrop-blur-md border border-white/10 rounded-2xl p-4 shadow-xl space-y-3 max-w-[320px] w-full transition-all duration-500 hover:bg-white/15">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center border border-white/15 shrink-0 text-white">
                                    <i class="fa-solid fa-users text-white text-base"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-brand-200">Orang Baik Bergabung</p>
                                    <p class="text-lg font-bold font-display text-white">{{ number_format(\App\Models\User::count(), 0, ',', '.') }}+ Akun</p>
                                </div>
                            </div>
                            <div class="h-px bg-white/10"></div>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center border border-white/15 shrink-0 text-white">
                                    <i class="fa-solid fa-heart text-white text-base"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-brand-200">Kampanye Kebaikan</p>
                                    <p class="text-lg font-bold font-display text-white">{{ number_format(\App\Models\Campaign::count(), 0, ',', '.') }}+ Kampanye</p>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="relative z-10 text-xs text-brand-200/60 font-medium tracking-wide">
                    &copy; {{ date('Y') }} AutoPahala. All rights reserved.
                </div>
            </div>

            {{-- 2. Form Container --}}
            <div class="form-container lg:absolute lg:top-0 lg:left-[40%] lg:h-full lg:w-[60%] w-full bg-white transition-transform duration-700 ease-in-out z-10">

                {{-- Login Form Panel --}}
                <div class="login-form-panel lg:absolute lg:inset-0 w-full h-full lg:px-16 flex flex-col justify-center transition-all duration-500 ease-in-out"
                     x-show="state === 'login'"
                     x-transition:enter="transition ease-out duration-500 delay-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-300"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95">
                
                <div class="w-full max-w-md mx-auto space-y-5">
                    <div class="space-y-4 text-center lg:text-left form-element">
                        <a href="/" class="inline-flex items-center gap-2.5 group justify-center lg:justify-start">
                            <div class="w-8 h-8 rounded-xl bg-[#20BDC4] flex items-center justify-center shadow-sm group-hover:scale-105 transition-transform">
                                <i class="fa-solid fa-hand-holding-heart text-white text-sm"></i>
                            </div>
                            <span class="text-lg font-bold tracking-wide font-display text-gray-900">Auto<span class="text-[#20BDC4]">pahala</span></span>
                        </a>
                        <div class="space-y-1.5">
                            <h2 class="text-2xl lg:text-[28px] font-bold text-gray-900 font-display">
                                Masuk ke Akun Anda
                            </h2>
                            <p class="text-sm text-gray-500">
                                Belum memiliki akun?
                                <button @click="goTo('register', '{{ route('register') }}')" class="font-semibold text-brand-500 hover:text-brand-700 transition-colors focus:outline-none">
                                    Daftar sekarang
                                </button>
                            </p>
                        </div>
                    </div>

                    <x-validation-errors class="mb-4 form-element" />

                    @session('status')
                        <div class="mb-4 rounded-xl border border-brand-200 bg-brand-50/50 px-4 py-3 text-sm font-medium text-brand-700 form-element"
                             x-data="{ show: true }"
                             x-show="show"
                             x-init="setTimeout(() => show = false, 5000)"
                             x-transition:leave="transition ease-in duration-300"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95">
                            {{ $value }}
                        </div>
                    @endsession

                    <form method="POST" action="{{ route('login') }}" class="space-y-4 form-element">
                        @csrf

                        <div>
                            <x-label for="login_email" value="{{ __('Email Address') }}" class="text-sm font-semibold text-gray-700" />
                            <div class="mt-1.5">
                                <x-input
                                    id="login_email"
                                    class="block w-full rounded-xl border-gray-300 px-4 py-3 text-gray-900 shadow-sm focus:border-brand-500 focus:ring-brand-500"
                                    type="email"
                                    name="email"
                                    :value="old('email')"
                                    required
                                    autofocus
                                    autocomplete="username"
                                    placeholder="nama@email.com"
                                />
                            </div>
                        </div>

                        <div>
                            <x-label for="login_password" value="{{ __('Password') }}" class="text-sm font-semibold text-gray-700" />
                            <div class="mt-1.5 relative" x-data="{ show: false }">
                                <input
                                    :type="show ? 'text' : 'password'"
                                    id="login_password"
                                    name="password"
                                    required
                                    autocomplete="current-password"
                                    placeholder="Masukkan password"
                                    class="block w-full border-2 border-gray-200 bg-white text-gray-900 focus:border-brand-500 focus:ring-4 focus:ring-brand-100/50 rounded-xl shadow-sm transition-all px-4 py-2.5 pr-12"
                                >
                                <button
                                    type="button"
                                    @click="show = !show"
                                    class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 hover:text-gray-600 transition-colors"
                                >
                                    <svg x-show="!show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                    <svg x-show="show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12c1.292 4.338 5.31 7.5 10.066 7.5.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <label for="remember_me" class="flex items-center">
                                <x-checkbox
                                    id="remember_me"
                                    name="remember"
                                    class="rounded border-gray-300 text-brand-500 focus:ring-brand-500"
                                />
                                <span class="ml-2 text-sm text-gray-600">
                                    {{ __('Remember me') }}
                                </span>
                            </label>

                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-sm font-semibold text-brand-500 hover:text-brand-700 transition-colors">
                                    Lupa password?
                                </a>
                            @endif
                        </div>

                        <button
                            type="submit"
                            class="flex w-full justify-center rounded-xl bg-brand-500 px-4 py-3 text-sm font-bold text-white shadow-lg shadow-brand-500/10 transition-all hover:bg-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 hover:scale-[1.01] active:scale-[0.99] duration-150"
                        >
                            {{ __('Login') }}
                        </button>
                    </form>

                    <div class="space-y-4 form-element">
                        <div class="relative">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-200"></div>
                            </div>
                            <div class="relative flex justify-center text-xs">
                                <span class="bg-white px-3 text-gray-400 font-medium font-sans">Atau masuk dengan</span>
                            </div>
                        </div>
                        <a
                            href="{{ route('social.login', 'google') }}"
                            class="inline-flex w-full items-center justify-center rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50 hover:scale-[1.01] active:scale-[0.99] duration-150"
                        >
                            <svg class="mr-2 h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4" />
                                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853" />
                                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05" />
                                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335" />
                            </svg>
                            Google
                        </a>
                    </div>
                </div>
            </div>

                {{-- Register Form Panel --}}
                <div class="register-form-panel lg:absolute lg:inset-0 w-full h-full lg:px-16 flex flex-col justify-center transition-all duration-500 ease-in-out"
                     x-show="state === 'register'"
                     x-transition:enter="transition ease-out duration-500 delay-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-300"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95">
                
                <div class="w-full max-w-md mx-auto space-y-5">
                    <div class="space-y-4 text-center lg:text-left form-element">
                        <a href="/" class="inline-flex items-center gap-2.5 group justify-center lg:justify-start">
                            <div class="w-8 h-8 rounded-xl bg-[#20BDC4] flex items-center justify-center shadow-sm group-hover:scale-105 transition-transform">
                                <i class="fa-solid fa-hand-holding-heart text-white text-sm"></i>
                            </div>
                            <span class="text-lg font-bold tracking-wide font-display text-gray-900">Auto<span class="text-[#20BDC4]">pahala</span></span>
                        </a>
                        <div class="space-y-1.5">
                            <h2 class="text-2xl lg:text-[28px] font-bold text-gray-900 font-display">
                                Daftar Akun Baru
                            </h2>
                            <p class="text-sm text-gray-500">
                                Sudah memiliki akun?
                                <button @click="goTo('login', '{{ route('login') }}')" class="font-semibold text-brand-500 hover:text-brand-700 transition-colors focus:outline-none">
                                    Masuk di sini
                                </button>
                            </p>
                        </div>
                    </div>

                    <x-validation-errors class="mb-4 form-element" />

                    <form method="POST" action="{{ route('register') }}" class="space-y-3.5 form-element">
                        @csrf

                        <div>
                            <x-label for="register_name" value="{{ __('Full Name') }}" class="text-sm font-semibold text-gray-700" />
                            <div class="mt-1">
                                <x-input id="register_name" class="block w-full rounded-xl border-gray-300" type="text" name="name" :value="old('name')" required autocomplete="name" placeholder="Nama Lengkap" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <x-label for="register_username" value="Username" class="text-sm font-semibold text-gray-700" />
                                <div class="mt-1">
                                    <x-input id="register_username" class="block w-full rounded-xl border-gray-300" type="text" name="username" :value="old('username')" required autocomplete="username" placeholder="username" />
                                </div>
                            </div>
                            <div>
                                <x-label for="register_email" value="{{ __('Email Address') }}" class="text-sm font-semibold text-gray-700" />
                                <div class="mt-1">
                                    <x-input id="register_email" class="block w-full rounded-xl border-gray-300" type="email" name="email" :value="old('email')" required placeholder="nama@email.com" />
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <x-label for="register_password" value="{{ __('Password') }}" class="text-sm font-semibold text-gray-700" />
                                <div class="mt-1 relative" x-data="{ show: false }">
                                    <input :type="show ? 'text' : 'password'" id="register_password" name="password" required autocomplete="new-password"
                                           placeholder="Min 8 karakter"
                                           class="block w-full border-2 border-gray-200 bg-white text-gray-900 focus:border-brand-500 focus:ring-4 focus:ring-brand-100/50 rounded-xl shadow-sm transition-all pr-10 py-2.5 px-3.5 text-sm">
                                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 transition-colors">
                                        <svg x-show="!show" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
                                        <svg x-show="show" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12c1.292 4.338 5.31 7.5 10.066 7.5.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88"/></svg>
                                    </button>
                                </div>
                            </div>
                            <div>
                                <x-label for="register_password_confirmation" value="Konfirmasi" class="text-sm font-semibold text-gray-700" />
                                <div class="mt-1 relative" x-data="{ show: false }">
                                    <input :type="show ? 'text' : 'password'" id="register_password_confirmation" name="password_confirmation" required autocomplete="new-password"
                                           placeholder="Ulangi"
                                           class="block w-full border-2 border-gray-200 bg-white text-gray-900 focus:border-brand-500 focus:ring-4 focus:ring-brand-100/50 rounded-xl shadow-sm transition-all pr-10 py-2.5 px-3.5 text-sm">
                                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 transition-colors">
                                        <svg x-show="!show" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
                                        <svg x-show="show" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12c1.292 4.338 5.31 7.5 10.066 7.5.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88"/></svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                            <div class="flex items-center">
                                <x-checkbox name="terms" id="register_terms" required class="rounded border-gray-300 text-brand-500 focus:ring-brand-500" />
                                <div class="ml-2">
                                    <label for="register_terms" class="text-sm text-gray-600">
                                        {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                                'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline font-semibold text-brand-500 hover:text-brand-700">'.__('Terms of Service').'</a>',
                                                'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline font-semibold text-brand-500 hover:text-brand-700">'.__('Privacy Policy').'</a>',
                                        ]) !!}
                                    </label>
                                </div>
                            </div>
                        @endif

                        <button
                            type="submit"
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-lg shadow-brand-500/10 text-sm font-bold text-white bg-brand-500 hover:bg-brand-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 transition-all hover:scale-[1.01] active:scale-[0.99] duration-150"
                        >
                            {{ __('Create Account') }}
                        </button>
                    </form>

                    <div class="space-y-4 form-element">
                        <div class="relative">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-200"></div>
                            </div>
                            <div class="relative flex justify-center text-xs">
                                <span class="bg-white px-3 text-gray-400 font-medium font-sans">Atau daftar dengan</span>
                            </div>
                        </div>
                        <a href="{{ route('social.login', 'google') }}" class="w-full inline-flex justify-center py-3 px-4 border border-gray-300 rounded-xl shadow-sm bg-white text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-all hover:scale-[1.01] active:scale-[0.99] duration-150">
                            <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/>
                                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                            </svg>
                            Google
                        </a>
                    </div>
                </div>
                </div>
            </div>

        </div>
    </div>

    <style>
        /* Desktop Sliding Animations */
        @media (min-width: 1024px) {
            /* Default: state-login */
            .auth-container.state-login .branding-panel {
                transform: translateX(0);
            }
            .auth-container.state-login .form-container {
                transform: translateX(0);
            }

            /* state-register */
            .auth-container.state-register .branding-panel {
                transform: translateX(150%); /* slides to the right (60/40 of container) */
            }
            .auth-container.state-register .form-container {
                transform: translateX(-66.666%); /* slides to the left (40/60 of container) */
            }

            /* Panel scaling animation on transition */
            .auth-container.state-register .branding-panel h1 {
                transform: translateX(12px);
            }
            .auth-container.state-login .branding-panel h1 {
                transform: translateX(0);
            }
        }
    </style>
</x-guest-layout>
