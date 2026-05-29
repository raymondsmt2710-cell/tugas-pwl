<x-guest-layout>
    <div class="min-h-screen bg-gradient-to-br from-green-50 via-white to-emerald-50 flex items-center justify-center px-4 py-10 sm:px-6 lg:px-8">
        <div class="w-full max-w-6xl grid overflow-hidden rounded-3xl bg-white shadow-2xl lg:grid-cols-2">

            <div class="hidden lg:flex relative bg-green-700 p-10 text-white">
                <div class="absolute inset-0 bg-gradient-to-br from-green-700 via-emerald-700 to-green-900"></div>

                <div class="relative z-10 flex flex-col justify-between">
                    <div>
                        <div class="mb-8 inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-white/15 backdrop-blur">
                            <span class="text-2xl font-bold">A</span>
                        </div>

                        <h1 class="text-4xl font-bold leading-tight">
                            Selamat datang kembali di Autopahala.
                        </h1>

                        <p class="mt-5 text-base leading-7 text-green-50">
                            Masuk untuk melanjutkan donasi, mengelola campaign, dan mengikuti perkembangan kebaikan yang kamu dukung.
                        </p>
                    </div>

                    <div class="mt-12 grid grid-cols-2 gap-4">
                        <div class="rounded-2xl bg-white/10 p-5 backdrop-blur">
                            <p class="text-3xl font-bold">100+</p>
                            <p class="mt-1 text-sm text-green-50">Campaign aktif</p>
                        </div>

                        <div class="rounded-2xl bg-white/10 p-5 backdrop-blur">
                            <p class="text-3xl font-bold">24/7</p>
                            <p class="mt-1 text-sm text-green-50">Akses donasi</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-6 py-10 sm:px-10 lg:px-12">
                <div class="mx-auto w-full max-w-md">
                    <div class="mb-8 text-center lg:text-left">
                        <div class="mb-6 flex justify-center lg:hidden">
                            <x-authentication-card-logo />
                        </div>

                        <p class="text-sm font-semibold uppercase tracking-wide text-green-600">
                            Autopahala
                        </p>

                        <h2 class="mt-3 text-3xl font-bold text-gray-900">
                            Masuk ke akun kamu
                        </h2>

                        <p class="mt-3 text-sm text-gray-600">
                            Belum punya akun?
                            <a href="{{ route('register') }}" class="font-semibold text-green-600 hover:text-green-700">
                                Daftar sekarang
                            </a>
                        </p>
                    </div>

                    <x-validation-errors class="mb-4" />

                    @session('status')
                        <div class="mb-4 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-medium text-green-700">
                            {{ $value }}
                        </div>
                    @endsession

                    <form method="POST" action="{{ route('login') }}" class="space-y-5">
                        @csrf

                        <div>
                            <x-label for="email" value="{{ __('Email Address') }}" class="text-sm font-semibold text-gray-700" />

                            <div class="mt-2">
                                <x-input
                                    id="email"
                                    class="block w-full rounded-xl border-gray-300 px-4 py-3 text-gray-900 shadow-sm focus:border-green-500 focus:ring-green-500"
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
                            <x-label for="password" value="{{ __('Password') }}" class="text-sm font-semibold text-gray-700" />

                            <div class="mt-2 relative" x-data="{ show: false }">
                                <input
                                    :type="show ? 'text' : 'password'"
                                    id="password"
                                    name="password"
                                    required
                                    autocomplete="current-password"
                                    placeholder="Masukkan password"
                                    class="block w-full rounded-xl border-gray-300 px-4 py-3 pr-12 text-gray-900 shadow-sm focus:border-green-500 focus:ring-green-500"
                                >

                                <button
                                    type="button"
                                    @click="show = !show"
                                    class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 hover:text-gray-600"
                                    aria-label="Toggle password visibility"
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

                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <label for="remember_me" class="flex items-center">
                                <x-checkbox
                                    id="remember_me"
                                    name="remember"
                                    class="rounded border-gray-300 text-green-600 focus:ring-green-500"
                                />

                                <span class="ml-2 text-sm text-gray-600">
                                    {{ __('Remember me') }}
                                </span>
                            </label>

                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-sm font-semibold text-green-600 hover:text-green-700">
                                    {{ __('Forgot your password?') }}
                                </a>
                            @endif
                        </div>

                        <button
                            type="submit"
                            class="flex w-full justify-center rounded-xl bg-green-600 px-4 py-3 text-sm font-bold text-white shadow-lg shadow-green-600/20 transition hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                        >
                            {{ __('Login') }}
                        </button>
                    </form>

                    <div class="mt-8">
                        <div class="relative">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-200"></div>
                            </div>

                            <div class="relative flex justify-center text-sm">
                                <span class="bg-white px-3 text-gray-500">
                                    Atau lanjut dengan
                                </span>
                            </div>
                        </div>

                        <div class="mt-6">
                            <a
                                href="{{ route('social.login', 'google') }}"
                                class="inline-flex w-full items-center justify-center rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm font-bold text-gray-700 shadow-sm transition hover:bg-gray-50"
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

                    <p class="mt-8 text-center text-xs text-gray-500">
                        Dengan masuk, kamu dapat melanjutkan aktivitas donasi di Autopahala.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>