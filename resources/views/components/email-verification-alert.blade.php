@if (auth()->check() && !auth()->user()->hasVerifiedEmail())
    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-6">
        <div class="flex items-start gap-4">
            <div class="flex-shrink-0">
                <svg class="w-5 h-5 text-amber-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="flex-1">
                <h3 class="text-sm font-semibold text-amber-900">Email Anda Belum Diverifikasi</h3>
                <p class="text-sm text-amber-700 mt-1">Silakan verifikasi email Anda untuk akses penuh ke fitur platform.</p>
                <div class="mt-3 flex items-center gap-3">
                    <a href="{{ route('verification.notice') }}" class="text-sm font-medium text-amber-700 hover:text-amber-900 underline">
                        Pergi ke halaman verifikasi
                    </a>
                    <span class="text-amber-300">•</span>
                    <form method="POST" action="{{ route('verification.send') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-sm font-medium text-amber-700 hover:text-amber-900 underline">
                            Kirim ulang email
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif
