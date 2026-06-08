<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <h2 class="text-2xl font-bold text-gray-900 mb-2 font-display text-center">Konfirmasi Password</h2>
        <div class="mb-6 text-sm text-gray-500 text-center leading-relaxed">
            Ini adalah area aman aplikasi. Silakan konfirmasi password Anda sebelum melanjutkan.
        </div>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
            @csrf

            <div>
                <x-label for="password" value="{{ __('Password') }}" class="font-semibold text-gray-700" />
                <x-input id="password" class="block mt-1.5 w-full rounded-xl border-gray-300" type="password" name="password" required autocomplete="current-password" autofocus placeholder="Masukkan password Anda" />
            </div>

            <div class="flex justify-end mt-6">
                <button type="submit" class="w-full sm:w-auto py-2.5 px-6 rounded-xl bg-brand-500 text-white font-bold text-sm hover:bg-brand-600 shadow-lg shadow-brand-500/10 transition-all hover:scale-[1.01] active:scale-[0.99] duration-150">
                    Konfirmasi
                </button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
