<div class="flex items-center gap-4" x-data="{ showReportModal: @entangle('showModal') }">
    {{-- Report Button --}}
    @if ($alreadyReported)
        <span class="flex items-center gap-1.5 text-sm text-gray-300 font-medium cursor-not-allowed select-none" title="Anda sudah melaporkan kampanye ini">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4.5 h-4.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 3v1.5M3 21v-6m0 0 2.77-.693a9 9 0 0 1 6.208.682l.108.054a9 9 0 0 0 6.086.71l3.114-.732a48.524 48.524 0 0 1-.005-10.485l-3.11.733a9 9 0 0 1-6.088-.71l-.109-.054a9 9 0 0 0-6.208-.682L3 7.5M3 15V7.5" />
            </svg>
            <span>Sudah Dilaporkan</span>
        </span>
    @else
        <button type="button" 
                wire:click="openModal" 
                class="flex items-center gap-1.5 text-sm text-gray-400 hover:text-red-600 transition font-medium focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4.5 h-4.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 3v1.5M3 21v-6m0 0 2.77-.693a9 9 0 0 1 6.208.682l.108.054a9 9 0 0 0 6.086.71l3.114-.732a48.524 48.524 0 0 1-.005-10.485l-3.11.733a9 9 0 0 1-6.088-.71l-.109-.054a9 9 0 0 0-6.208-.682L3 7.5M3 15V7.5" />
            </svg>
            <span>Laporkan</span>
        </button>
    @endif

    {{-- Report Modal --}}
    <div x-show="showReportModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4"
         @click.self="showReportModal = false"
         style="display: none;">
        
        <!-- Modal Content -->
        <div x-show="showReportModal"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl border border-gray-100 relative text-left font-normal">
            
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-6 h-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                </svg>
                Laporkan Kampanye
            </h3>
            
            <form wire:submit.prevent="submitReport">
                <!-- Reason -->
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Alasan Pelaporan <span class="text-red-500">*</span></label>
                    <div class="space-y-2">
                        @foreach(['Penipuan / Kecurangan', 'Konten Tidak Layak / SARA', 'Spam / Iklan', 'Lainnya'] as $opt)
                            <label class="flex items-center gap-3 p-2.5 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer transition">
                                <input type="radio" wire:model="reason" value="{{ $opt }}" class="text-brand-500 focus:ring-brand-500">
                                <span class="text-sm text-gray-700">{{ $opt }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('reason')
                        <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Description -->
                <div class="mb-5">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi Tambahan (Opsional)</label>
                    <textarea wire:model="description" rows="3" 
                              class="w-full rounded-xl border-gray-200 text-sm focus:border-brand-500 focus:ring-brand-500 placeholder-gray-400"
                              placeholder="Berikan detail lebih lanjut mengapa Anda melaporkan kampanye ini..."></textarea>
                    @error('description')
                        <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Action Buttons -->
                <div class="flex gap-3 justify-end">
                    <button type="button" @click="showReportModal = false"
                            class="px-4 py-2 text-sm font-semibold text-gray-500 hover:text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl transition">
                        Batal
                    </button>
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 rounded-xl shadow-sm transition">
                        <span>Kirim Laporan</span>
                        <div wire:loading wire:target="submitReport" class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
