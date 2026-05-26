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

            {{-- Campaign Quick Actions --}}
            <div class="mb-8 bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                <div class="p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Kampanye Saya</h3>
                        <p class="text-sm text-gray-500 mt-1">Kelola kampanye penggalangan dana Anda.</p>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ url('/my-campaigns') }}" class="inline-flex items-center px-4 py-2.5 border border-gray-300 rounded-xl text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM3.75 12h.007v.008H3.75V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-.375 5.25h.007v.008H3.75v-.008Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                            </svg>
                            Lihat Semua
                        </a>
                        <a href="{{ url('/campaigns/create') }}" class="inline-flex items-center px-4 py-2.5 border border-transparent rounded-xl text-sm font-semibold text-white bg-gray-900 hover:bg-gray-800 shadow-sm transition-colors">
                            <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            Buat Kampanye
                        </a>
                    </div>
                </div>
            </div>

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
