{{--
    Global Confirm Modal — driven by Alpine.js global store.
    Usage from any page:
        $dispatch('confirm', {
            title: 'Judul',
            message: 'Pesan konfirmasi',
            confirmText: 'Ya, Lanjutkan',    // optional, default 'Ya, Lanjutkan'
            cancelText: 'Batal',             // optional
            type: 'danger|warning|info',     // optional, default 'warning'
            onConfirm: () => { /* callback or form.submit() */ }
        })
--}}
<div
    x-data="{
        show: false,
        title: '',
        message: '',
        confirmText: 'Ya, Lanjutkan',
        cancelText: 'Batal',
        type: 'warning',
        onConfirm: null,
        open(opts) {
            this.title       = opts.title       ?? 'Konfirmasi';
            this.message     = opts.message     ?? 'Apakah Anda yakin?';
            this.confirmText = opts.confirmText ?? 'Ya, Lanjutkan';
            this.cancelText  = opts.cancelText  ?? 'Batal';
            this.type        = opts.type        ?? 'warning';
            this.onConfirm   = opts.onConfirm   ?? null;
            this.show = true;
        },
        confirm() {
            this.show = false;
            if (typeof this.onConfirm === 'function') this.onConfirm();
        },
        cancel() {
            this.show = false;
        }
    }"
    @confirm.window="open($event.detail)"
    x-show="show"
    x-transition.opacity
    class="fixed inset-0 z-[999] flex items-center justify-center p-4 bg-black/40 backdrop-blur-[2px]"
    style="display: none;"
    @keydown.escape.window="cancel()"
>
    <div
        x-show="show"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        @click.stop
        class="bg-white rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden"
    >
        {{-- Icon area --}}
        <div class="px-6 pt-6 pb-4 flex flex-col items-center text-center">
            <div class="w-14 h-14 rounded-full flex items-center justify-center mb-4"
                 :class="{
                     'bg-red-100'    : type === 'danger',
                     'bg-amber-100'  : type === 'warning',
                     'bg-blue-100'   : type === 'info',
                     'bg-brand-100': type === 'success'
                 }">
                {{-- danger --}}
                <svg x-show="type === 'danger'" class="w-7 h-7 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
                </svg>
                {{-- warning --}}
                <svg x-show="type === 'warning'" class="w-7 h-7 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
                </svg>
                {{-- info --}}
                <svg x-show="type === 'info'" class="w-7 h-7 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z"/>
                </svg>
                {{-- success --}}
                <svg x-show="type === 'success'" class="w-7 h-7 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                </svg>
            </div>

            <h3 class="text-base font-bold text-gray-900" x-text="title"></h3>
            <p class="mt-1.5 text-sm text-gray-500 leading-relaxed" x-text="message"></p>
        </div>

        {{-- Actions --}}
        <div class="px-6 pb-6 flex gap-3">
            <button
                @click="cancel()"
                class="flex-1 py-2.5 px-4 rounded-xl border border-gray-200 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                <span x-text="cancelText"></span>
            </button>
            <button
                @click="confirm()"
                class="flex-1 py-2.5 px-4 rounded-xl text-sm font-semibold text-white transition"
                :class="{
                    'bg-red-500 hover:bg-red-600'       : type === 'danger',
                    'bg-amber-500 hover:bg-amber-600'   : type === 'warning',
                    'bg-blue-500 hover:bg-blue-600'     : type === 'info',
                    'bg-brand-500 hover:bg-brand-500': type === 'success'
                }">
                <span x-text="confirmText"></span>
            </button>
        </div>
    </div>
</div>
