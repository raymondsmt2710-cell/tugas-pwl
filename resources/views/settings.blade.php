<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Pengaturan</h2>
    </x-slot>

    <div class="py-8 sm:py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <x-email-verification-alert />
            @livewire('settings.profile-settings')
            @livewire('settings.security-settings')
            @livewire('settings.privacy-settings')
            @livewire('settings.notification-settings')
            @livewire('settings.delete-account')
        </div>
    </div>
</x-app-layout>
