<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <style>
        .btn-profile-submit {
            background-color: #111827 !important;
            color: #ffffff !important;
            padding: 12px 36px !important;
            border-radius: 8px !important;
            font-weight: 700 !important;
            border: none !important;
            cursor: pointer !important;
            box-shadow: 0 4px 12px rgba(17, 24, 39, 0.15) !important;
            transition: all 0.2s ease-in-out !important;
            font-size: 14px !important;
            text-transform: uppercase !important;
            letter-spacing: 0.05em !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            text-decoration: none !important;
        }
        .btn-profile-submit:hover {
            background-color: #030712 !important;
            transform: translateY(-1px) !important;
            box-shadow: 0 6px 16px rgba(17, 24, 39, 0.25) !important;
        }
        .btn-profile-submit:active {
            transform: translateY(0) !important;
        }
        .btn-profile-submit:disabled {
            opacity: 0.6 !important;
            cursor: not-allowed !important;
            transform: none !important;
        }
    </style>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                @livewire('update-profile-information-form')

                <x-section-border />
            @endif

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                <div class="mt-10 sm:mt-0">
                    @livewire('profile.update-password-form')
                </div>

                <x-section-border />
            @endif

            @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                <div class="mt-10 sm:mt-0">
                    @livewire('profile.two-factor-authentication-form')
                </div>

                <x-section-border />
            @endif

            <div class="mt-10 sm:mt-0">
                @livewire('profile.logout-other-browser-sessions-form')
            </div>

            @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                <x-section-border />

                <div class="mt-10 sm:mt-0">
                    @livewire('profile.delete-user-form')
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
