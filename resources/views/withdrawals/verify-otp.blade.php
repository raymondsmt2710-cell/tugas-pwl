<x-app-layout>
    <div class="py-12">
        <div class="max-w-md mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 text-center">
                    <div class="w-14 h-14 mx-auto rounded-full bg-indigo-100 flex items-center justify-center mb-4">
                        <svg class="w-7 h-7 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/>
                        </svg>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-900">Verifikasi OTP</h2>
                    <p class="mt-1 text-sm text-gray-500">Masukkan kode 6 digit yang dikirim ke email Anda.</p>
                </div>

                <div class="p-6">
                    {{-- Success message --}}
                    @if(session('success'))
                        <div class="mb-4 rounded-lg bg-green-50 border border-green-200 p-3">
                            <p class="text-sm text-green-700">{{ session('success') }}</p>
                        </div>
                    @endif

                    {{-- Errors --}}
                    @if($errors->any())
                        <div class="mb-4 rounded-lg bg-red-50 border border-red-200 p-3">
                            @foreach($errors->all() as $error)
                                <p class="text-sm text-red-700">{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    {{-- Withdrawal Info --}}
                    <div class="mb-5 bg-gray-50 rounded-lg p-4 text-sm space-y-1">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Jumlah</span>
                            <span class="font-semibold text-gray-900">{{ $withdrawal->formatted_amount }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Bank</span>
                            <span class="text-gray-700">{{ $withdrawal->bank_name }} - {{ $withdrawal->account_number }}</span>
                        </div>
                    </div>

                    {{-- OTP Form --}}
                    <form action="{{ route('withdrawals.verify.submit', $withdrawal) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="otp_code" class="block text-sm font-medium text-gray-700 mb-1.5">Kode OTP</label>
                            <input type="text" name="otp_code" id="otp_code" maxlength="6" pattern="[0-9]{6}"
                                   class="w-full text-center text-2xl tracking-[0.5em] font-mono rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   placeholder="000000" autofocus required>
                        </div>

                        <button type="submit" class="w-full py-2.5 px-4 rounded-lg bg-indigo-600 text-white font-semibold text-sm hover:bg-indigo-700 transition">
                            Konfirmasi Penarikan
                        </button>
                    </form>

                    {{-- Resend --}}
                    <div class="mt-4 text-center">
                        <form action="{{ route('withdrawals.resend-otp', $withdrawal) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                                Kirim Ulang Kode OTP
                            </button>
                        </form>
                    </div>

                    <p class="mt-3 text-xs text-gray-400 text-center">Kode berlaku 10 menit. Maksimal 3 pengiriman per 2 menit.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
