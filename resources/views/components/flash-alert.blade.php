@php
    $message = session('success') ?? session('error') ?? session('warning') ?? session('status') ?? null;
    $type = session('success') ? 'success'
        : (session('error') ? 'error'
        : (session('warning') ? 'warning'
        : (session('status') ? 'info' : null)));
@endphp

{{-- Session flash (redirect-based) --}}
@if($message)
    @php
        $styles = [
            'success' => ['bg' => 'bg-green-50', 'border' => 'border-green-200', 'text' => 'text-green-700', 'icon_color' => 'text-green-500'],
            'error'   => ['bg' => 'bg-red-50',   'border' => 'border-red-200',   'text' => 'text-red-700',   'icon_color' => 'text-red-500'],
            'warning' => ['bg' => 'bg-amber-50',  'border' => 'border-amber-200', 'text' => 'text-amber-700', 'icon_color' => 'text-amber-500'],
            'info'    => ['bg' => 'bg-blue-50',   'border' => 'border-blue-200',  'text' => 'text-blue-700',  'icon_color' => 'text-blue-500'],
        ];
        $s = $styles[$type] ?? $styles['info'];
    @endphp
    <div x-data="{ show: true }"
         x-show="show"
         x-init="setTimeout(() => show = false, 3000)"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="mb-6 rounded-2xl {{ $s['bg'] }} border {{ $s['border'] }} p-4 flex items-start gap-3 shadow-sm">
        @if($type === 'success')
            <svg class="w-5 h-5 {{ $s['icon_color'] }} mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        @elseif($type === 'error')
            <svg class="w-5 h-5 {{ $s['icon_color'] }} mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        @elseif($type === 'warning')
            <svg class="w-5 h-5 {{ $s['icon_color'] }} mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/></svg>
        @else
            <svg class="w-5 h-5 {{ $s['icon_color'] }} mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z"/></svg>
        @endif
        <p class="text-sm {{ $s['text'] }} font-medium">{{ $message }}</p>
    </div>
@endif

{{-- Validation errors --}}
@if($errors->any())
    <div x-data="{ show: true }"
         x-show="show"
         x-init="setTimeout(() => show = false, 3000)"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="mb-6 rounded-2xl bg-red-50 border border-red-200 p-4 flex items-start gap-3 shadow-sm">
        <svg class="w-5 h-5 text-red-500 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <div class="space-y-1">
            @foreach($errors->all() as $error)
                <p class="text-sm text-red-700 font-medium">{{ $error }}</p>
            @endforeach
        </div>
    </div>
@endif

{{-- Livewire dispatch-based alert (no redirect, e.g. toggle settings) --}}
<div x-data="{
        show: false, message: '', type: 'success', timer: null,
        open(msg, t) {
            this.message = msg; this.type = t; this.show = true;
            clearTimeout(this.timer);
            this.timer = setTimeout(() => this.show = false, 3000);
        }
     }"
     @flash.window="open($event.detail.message, $event.detail.type ?? 'success')"
     x-show="show"
     x-transition:leave="transition ease-in duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     style="display:none;"
     class="mb-6 rounded-2xl p-4 flex items-start gap-3 shadow-sm border"
     :class="{
         'bg-green-50 border-green-200': type==='success',
         'bg-red-50 border-red-200':     type==='error',
         'bg-amber-50 border-amber-200': type==='warning',
         'bg-blue-50 border-blue-200':   type==='info',
     }">
    <svg class="w-5 h-5 mt-0.5 flex-shrink-0"
         :class="{
             'text-green-500': type==='success',
             'text-red-500':   type==='error',
             'text-amber-500': type==='warning',
             'text-blue-500':  type==='info',
         }"
         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path x-show="type==='success'" stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        <path x-show="type!=='success'" stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z"/>
    </svg>
    <p class="text-sm font-medium"
       :class="{
           'text-green-700': type==='success',
           'text-red-700':   type==='error',
           'text-amber-700': type==='warning',
           'text-blue-700':  type==='info',
       }"
       x-text="message"></p>
</div>
