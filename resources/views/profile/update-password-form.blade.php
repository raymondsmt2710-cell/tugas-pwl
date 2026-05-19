<x-form-section submit="updatePassword">
    <x-slot name="title">
        <span class="text-xl font-bold text-gray-900">{{ __('Security & Password') }}</span>
    </x-slot>

    <x-slot name="description">
        <span class="text-sm text-gray-500">{{ __('Keep your account safe by using a strong password that you don\'t use elsewhere.') }}</span>
    </x-slot>

    <x-slot name="form">
        <div class="col-span-6 bg-gray-50 p-6 rounded-2xl border border-gray-100 mb-4 flex items-center gap-4">
            <div class="w-12 h-12 bg-amber-100 text-amber-600 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
            </div>
            <div>
                <h4 class="font-bold text-gray-900">Strong Password Tip</h4>
                <p class="text-xs text-gray-500">Use at least 8 characters, with a mix of letters, numbers, and symbols.</p>
            </div>
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-label for="current_password" value="{{ __('Current Password') }}" class="text-gray-700 font-bold mb-1" />
            <x-input id="current_password" type="password" class="mt-1 block w-full" wire:model="state.current_password" autocomplete="current-password" placeholder="••••••••" />
            <x-input-error for="current_password" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-label for="password" value="{{ __('New Password') }}" class="text-gray-700 font-bold mb-1" />
            <x-input id="password" type="password" class="mt-1 block w-full" wire:model="state.password" autocomplete="new-password" placeholder="••••••••" />
            <x-input-error for="password" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-label for="password_confirmation" value="{{ __('Confirm New Password') }}" class="text-gray-700 font-bold mb-1" />
            <x-input id="password_confirmation" type="password" class="mt-1 block w-full" wire:model="state.password_confirmation" autocomplete="new-password" placeholder="••••••••" />
            <x-input-error for="password_confirmation" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="actions">
        <div x-data="{ show: false }" 
             x-on:saved.window="show = true; setTimeout(() => show = false, 3000)" 
             x-show="show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-y-2"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             class="mr-4 px-4 py-2 bg-green-50 text-green-700 rounded-lg border border-green-200 flex items-center gap-2"
             style="display: none;">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
            <span class="font-bold text-sm">Password updated!</span>
        </div>

        <button type="submit" class="btn-profile-submit" wire:loading.attr="disabled">
            {{ __('Update Security') }}
        </button>
    </x-slot>
</x-form-section>
