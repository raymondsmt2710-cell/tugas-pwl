<x-guest-layout>
    <div class="min-h-screen w-screen bg-gradient-to-br from-[#EEF7F7] via-white to-[#E4F3F3] flex items-center justify-center p-4 sm:p-6 lg:p-8 font-sans select-none relative overflow-hidden">
        
        <!-- Background decorative blobs -->
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-[#20BDC4]/5 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-rose-500/5 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative w-full max-w-md bg-white/85 backdrop-blur-xl rounded-[32px] shadow-[0_25px_60px_-15px_rgba(7,54,59,0.06)] border border-white/50 overflow-hidden p-8 sm:p-10 flex flex-col items-center text-center space-y-6">
            
            <!-- Warning / Alert Icon with Premium Pulse Animation -->
            <div class="relative flex items-center justify-center">
                <div class="absolute inset-0 rounded-full bg-rose-500/10 animate-ping-slow"></div>
                <div class="relative w-20 h-20 rounded-3xl bg-rose-50 flex items-center justify-center text-rose-500 border border-rose-100 shadow-inner">
                    <i class="fa-solid fa-user-slash text-3xl"></i>
                </div>
            </div>

            <!-- Page Titles -->
            <div class="space-y-2">
                <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-gray-900 font-display">
                    Akun Ditangguhkan
                </h1>
                <p class="text-sm text-gray-500 leading-relaxed max-w-sm">
                    Akun Anda telah ditangguhkan sementara karena melanggar ketentuan dan kebijakan layanan kami.
                </p>
            </div>

            <!-- Account Details Box -->
            <div class="w-full bg-gray-50/50 border border-gray-100 rounded-2xl p-4 sm:p-5 text-left space-y-3.5">
                <div class="flex justify-between items-center text-xs">
                    <span class="text-gray-400 font-medium">STATUS AKUN</span>
                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-rose-100 text-rose-700 tracking-wide uppercase">
                        Suspended
                    </span>
                </div>
                
                <div class="h-px bg-gray-200/50"></div>
                
                <div class="space-y-1">
                    <span class="text-xs text-gray-400 font-medium block">NAMA LENGKAP</span>
                    <span class="text-sm font-semibold text-gray-800 block truncate">
                        {{ auth()->user()->full_name }}
                    </span>
                </div>

                <div class="space-y-1">
                    <span class="text-xs text-gray-400 font-medium block">ALAMAT EMAIL</span>
                    <span class="text-sm font-semibold text-gray-800 block truncate">
                        {{ auth()->user()->email }}
                    </span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="w-full flex flex-col gap-3 pt-2">
                <!-- Log out form -->
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-gray-900 text-white font-semibold hover:bg-gray-850 transition-all hover:scale-[1.01] active:scale-[0.99] duration-150 shadow-md shadow-gray-900/10">
                        <i class="fa-solid fa-right-from-bracket"></i> Keluar dari Akun
                    </button>
                </form>

                <!-- Contact Support -->
                <a href="mailto:tubespwlkel999@gmail.com?subject=Banding Penangguhan Akun - {{ auth()->user()->email }}" 
                   class="w-full flex items-center justify-center gap-2 px-5 py-3 rounded-xl border border-gray-200 bg-white text-gray-700 font-semibold hover:bg-gray-50 hover:border-gray-300 transition-all hover:scale-[1.01] active:scale-[0.99] duration-150">
                    <i class="fa-solid fa-envelope"></i> Hubungi Dukungan
                </a>
            </div>

            <!-- Footer Logo -->
            <div class="pt-4 flex items-center gap-2 text-xs text-gray-400 font-medium justify-center">
                <div class="w-5 h-5 rounded bg-[#20BDC4] flex items-center justify-center shadow-sm">
                    <i class="fa-solid fa-hand-holding-heart text-white text-[10px]"></i>
                </div>
                <span>Auto<span class="text-[#20BDC4] font-bold">pahala</span></span>
            </div>

        </div>
    </div>

    <!-- Custom Animation Keyframes for Slow Ping -->
    <style>
        @keyframes ping-slow {
            0% {
                transform: scale(1);
                opacity: 0.8;
            }
            70%, 100% {
                transform: scale(1.4);
                opacity: 0;
            }
        }
        .animate-ping-slow {
            animation: ping-slow 2.2s cubic-bezier(0.16, 1, 0.3, 1) infinite;
        }
    </style>
</x-guest-layout>
