{{--
    Global Toast Notification
    Triggered by:
    1. Laravel session: session('success'), session('error'), session('warning'), session('status')
    2. Livewire dispatch: $this->dispatch('toast', message: '...', type: 'success|error|warning|info')
    3. JS: window.dispatchEvent(new CustomEvent('toast', { detail: { message: '...', type: 'success' } }))
--}}

@php
    $toastMessage = session('success') ?? session('error') ?? session('warning') ?? session('status') ?? null;
    $toastType = session('success') ? 'success'
        : (session('error') ? 'error'
        : (session('warning') ? 'warning'
        : (session('status') ? 'info' : null)));
@endphp

<div
    x-data="{
        show: false,
        message: '',
        type: 'success',
        timer: null,
        open(msg, t = 'success') {
            this.message = msg;
            this.type = t;
            this.show = true;
            clearTimeout(this.timer);
            this.timer = setTimeout(() => this.close(), 3000);
        },
        close() {
            this.show = false;
        }
    }"
    @toast.window="open($event.detail.message, $event.detail.type ?? 'success')"
    x-init="
        @if($toastMessage)
            $nextTick(() => open({{ json_encode($toastMessage) }}, {{ json_encode($toastType) }}));
        @endif
    "
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-y-4"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 translate-y-4"
    class="fixed bottom-6 left-1/2 -translate-x-1/2 z-[9999] w-full max-w-sm px-4"
    style="display: none;"
>
    <div class="flex items-center gap-3 rounded-2xl px-4 py-3 shadow-xl text-sm font-medium"
         :class="{
             'bg-emerald-600 text-white': type === 'success',
             'bg-red-500 text-white':     type === 'error',
             'bg-amber-500 text-white':   type === 'warning',
             'bg-blue-600 text-white':    type === 'info',
         }">

        {{-- Icon --}}
        <span class="shrink-0">
            <svg x-show="type === 'success'" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
            </svg>
            <svg x-show="type === 'error'" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/>
            </svg>
            <svg x-show="type === 'warning'" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
            </svg>
            <svg x-show="type === 'info'" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z"/>
            </svg>
        </span>

        {{-- Message --}}
        <span class="flex-1" x-text="message"></span>

        {{-- Close --}}
        <button @click="close()" class="shrink-0 opacity-75 hover:opacity-100 transition ml-1">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    {{-- Progress bar --}}
    <div class="mt-1 h-0.5 rounded-full overflow-hidden"
         :class="{
             'bg-emerald-400': type === 'success',
             'bg-red-300':     type === 'error',
             'bg-amber-300':   type === 'warning',
             'bg-blue-400':    type === 'info',
         }">
        <div x-show="show"
             x-transition:enter="transition-all ease-linear duration-[3000ms]"
             x-transition:enter-start="w-full"
             x-transition:enter-end="w-0"
             class="h-full bg-white/40 w-full"></div>
    </div>
</div>
