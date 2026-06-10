<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <h2 class="text-2xl font-bold text-gray-900 mb-2 font-display text-center">Autentikasi Dua Faktor</h2>

        <div x-data="{ recovery: false }">
            <div class="mb-6 text-sm text-gray-500 text-center leading-relaxed" x-show="! recovery">
                Silakan konfirmasi akses ke akun Anda dengan memasukkan kode autentikasi dari aplikasi authenticator Anda.
            </div>

            <div class="mb-6 text-sm text-gray-500 text-center leading-relaxed" x-cloak x-show="recovery">
                Silakan konfirmasi akses ke akun Anda dengan memasukkan salah satu kode pemulihan darurat (recovery code) Anda.
            </div>

            <x-validation-errors class="mb-4" />

            <form method="POST" action="{{ route('two-factor.login') }}" class="space-y-5">
                @csrf

                <div x-show="! recovery">
                    <x-label for="code" value="Kode Autentikasi" class="font-semibold text-gray-700" />
                    <x-input id="code" class="block mt-1.5 w-full rounded-xl border-gray-300" type="text" inputmode="numeric" name="code" autofocus x-ref="code" autocomplete="one-time-code" placeholder="000000" />
                </div>

                <div x-cloak x-show="recovery">
                    <x-label for="recovery_code" value="Kode Pemulihan (Recovery Code)" class="font-semibold text-gray-700" />
                    <x-input id="recovery_code" class="block mt-1.5 w-full rounded-xl border-gray-300" type="text" name="recovery_code" x-ref="recovery_code" autocomplete="one-time-code" placeholder="xxxx-xxxx-xxxx" />
                </div>

                <div class="flex items-center justify-between mt-6 flex-wrap gap-4">
                    <button type="button" class="text-sm font-semibold text-brand-500 hover:text-brand-700 hover:underline cursor-pointer transition-colors"
                                    x-show="! recovery"
                                    x-on:click="
                                        recovery = true;
                                        $nextTick(() => { $refs.recovery_code.focus() })
                                    ">
                        Gunakan kode pemulihan
                    </button>

                    <button type="button" class="text-sm font-semibold text-brand-500 hover:text-brand-700 hover:underline cursor-pointer transition-colors"
                                    x-cloak
                                    x-show="recovery"
                                    x-on:click="
                                        recovery = false;
                                        $nextTick(() => { $refs.code.focus() })
                                    ">
                        Gunakan kode autentikasi
                    </button>

                    <button type="submit" class="w-full sm:w-auto py-2.5 px-6 rounded-xl bg-brand-500 text-white font-bold text-sm hover:bg-brand-600 shadow-lg shadow-brand-500/10 transition-all hover:scale-[1.01] active:scale-[0.99] duration-150">
                        Masuk
                    </button>
                </div>
            </form>
        </div>
    </x-authentication-card>
</x-guest-layout>
