<x-guest-layout>

    <div class="min-h-screen bg-[#0f172a] flex items-center justify-center px-4 py-10">

        <div class="w-full max-w-md bg-white rounded-3xl shadow-2xl p-10">

            <!-- Logo -->

            <div class="text-center mb-10">

                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-black text-white text-2xl font-bold shadow-lg">
                    A
                </div>

                <h1 class="mt-6 text-3xl font-bold text-gray-900">
                    Admin Panel
                </h1>

                <p class="mt-2 text-sm text-gray-500">
                    Login untuk mengakses dashboard admin Autopahala
                </p>

            </div>

            <!-- Validation Error -->

            <x-validation-errors class="mb-4" />

            <!-- Form -->

            <form method="POST" action="{{ route('login') }}" class="space-y-6">

                @csrf

                <!-- Email -->

                <div>

                    <x-label
                        for="email"
                        value="Email"
                        class="text-sm font-semibold text-gray-700"
                    />

                    <x-input
                        id="email"
                        class="mt-2 block w-full rounded-xl border-gray-300 px-4 py-3 focus:border-black focus:ring-black"
                        type="email"
                        name="email"
                        :value="old('email')"
                        required
                        autofocus
                        placeholder="admin@email.com"
                    />

                </div>

                <!-- Password -->

                <div>

                    <x-label
                        for="password"
                        value="Password"
                        class="text-sm font-semibold text-gray-700"
                    />

                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        placeholder="Masukkan password"
                        class="mt-2 block w-full rounded-xl border-gray-300 px-4 py-3 focus:border-black focus:ring-black"
                    >

                </div>

                <!-- Remember -->

                <div class="flex items-center justify-between">

                    <label class="flex items-center">

                        <x-checkbox
                            name="remember"
                            class="rounded border-gray-300 text-black focus:ring-black"
                        />

                        <span class="ml-2 text-sm text-gray-600">
                            Remember me
                        </span>

                    </label>

                    @if (Route::has('password.request'))

                        <a
                            href="{{ route('password.request') }}"
                            class="text-sm font-semibold text-black hover:text-gray-700"
                        >
                            Forgot Password?
                        </a>

                    @endif

                </div>

                <!-- Button -->

                <button
                    type="submit"
                    class="w-full rounded-xl bg-black py-3 text-sm font-bold text-white transition hover:bg-gray-800"
                >
                    Login Admin
                </button>

            </form>

        </div>

    </div>

</x-guest-layout>