<x-guest-layout>
    <div class="min-h-screen bg-gradient-to-br from-brand-50 via-white to-brand-100/30 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="flex justify-center transition-transform duration-300 hover:scale-105">
                <x-authentication-card-logo />
            </div>
            <h2 class="mt-6 text-center text-3xl font-bold text-gray-900 font-display">Lupa Password</h2>
            <p class="mt-2 text-center text-sm text-gray-600 leading-relaxed">
                Masukkan email Anda dan kami akan mengirim link untuk reset password.
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md px-4">
            <div class="bg-white py-8 px-6 shadow-xl sm:rounded-3xl border border-gray-100/50">

                @if (session('status'))
                    <div class="mb-5 rounded-xl bg-brand-50 border border-brand-200 p-4"
                         x-data="{ show: true }"
                         x-show="show"
                         x-init="setTimeout(() => show = false, 5000)"
                         x-transition:leave="transition ease-in duration-300"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-brand-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <p class="text-sm text-brand-700 font-medium">{{ session('status') }}</p>
                        </div>
                    </div>
                @endif

                <x-validation-errors class="mb-4" />

                <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Alamat Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                               class="w-full rounded-xl border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm py-2.5 px-3.5"
                               placeholder="email@contoh.com">
                    </div>

                    <button type="submit" class="w-full py-3 px-4 rounded-xl bg-brand-500 text-white font-bold text-sm hover:bg-brand-600 shadow-lg shadow-brand-500/10 transition-all hover:scale-[1.01] active:scale-[0.99] duration-150">
                        Kirim Link Reset Password
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <a href="{{ route('login') }}" class="text-sm font-semibold text-brand-500 hover:text-brand-700 transition-colors">
                        ← Kembali ke Login
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
