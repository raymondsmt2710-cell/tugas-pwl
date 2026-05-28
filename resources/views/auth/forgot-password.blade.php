<x-guest-layout>
    <div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="text-center">
                <a href="/" class="text-2xl font-bold text-gray-900">Auto<span class="text-indigo-600">pahala</span></a>
            </div>
            <h2 class="mt-6 text-center text-2xl font-bold text-gray-900">Lupa Password</h2>
            <p class="mt-2 text-center text-sm text-gray-500">
                Masukkan email Anda dan kami akan mengirim link untuk reset password.
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-6 shadow-sm sm:rounded-2xl border border-gray-100">

                @if (session('status'))
                    <div class="mb-5 rounded-xl bg-green-50 border border-green-200 p-4">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-green-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <p class="text-sm text-green-700">{{ session('status') }}</p>
                        </div>
                    </div>
                @endif

                <x-validation-errors class="mb-4" />

                <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Alamat Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                               class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                               placeholder="email@contoh.com">
                    </div>

                    <button type="submit" class="w-full py-3 px-4 rounded-xl bg-indigo-600 text-white font-semibold text-sm hover:bg-indigo-700 shadow-sm transition">
                        Kirim Link Reset Password
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">
                        ← Kembali ke Login
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
