<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Pengaturan</h2>
    </x-slot>

    <div class="py-8 sm:py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <div x-data="{ 
                     show: {{ !auth()->user()->isProfileComplete() ? 'true' : 'false' }},
                     missingFields: '{{ implode(', ', auth()->user()->missingProfileFields()) }}'
                 }"
                 x-show="show"
                 x-on:profile-updated.window="
                     if ($event.detail.isComplete) {
                         setTimeout(() => {
                             show = false;
                         }, 3000);
                     } else {
                         missingFields = $event.detail.missingFields;
                     }
                 "
                 x-transition:leave="transition ease-in duration-500"
                 x-transition:leave-start="opacity-100 transform scale-100"
                 x-transition:leave-end="opacity-0 transform scale-95"
                 class="bg-amber-50 border border-amber-200 rounded-2xl p-4 flex gap-3 shadow-sm"
                 style="display: none;">
                <svg class="w-5 h-5 text-amber-500 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
                </svg>
                <div>
                    <h4 class="text-sm font-semibold text-amber-800">Lengkapi Profil Anda</h4>
                    <p class="text-xs text-amber-700 mt-1">
                        Anda belum melengkapi seluruh profil yang diperlukan untuk dapat membuat kampanye galang dana. 
                        Silakan lengkapi bagian berikut: 
                        <strong class="font-semibold" x-text="missingFields"></strong>.
                    </p>
                </div>
            </div>

            @livewire('settings.profile-settings')
            @livewire('settings.security-settings')
            @livewire('settings.privacy-settings')
            @livewire('settings.notification-settings')
        </div>
    </div>
</x-app-layout>
