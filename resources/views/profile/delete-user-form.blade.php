<x-action-section>
    <x-slot name="title">
        {{ __('Delete Account') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Permanently delete your account.') }}
    </x-slot>

    <x-slot name="content">
        <div class="max-w-xl text-sm text-gray-600">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </div>

        @if(!auth()->user()->hasVerifiedEmail())
            <div class="mt-4 rounded-lg bg-amber-50 border border-amber-200 p-4">
                <div class="flex items-start gap-2">
                    <svg class="w-5 h-5 text-amber-500 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <p class="text-sm text-amber-700">Anda harus memverifikasi email terlebih dahulu sebelum dapat menghapus akun.</p>
                        <a href="{{ route('verification.notice') }}" class="mt-2 inline-flex items-center gap-1.5 text-sm font-medium text-amber-800 hover:text-amber-900 underline">
                            Verifikasi Email Sekarang →
                        </a>
                    </div>
                </div>
            </div>
        @else
            <div class="mt-5">
                <x-danger-button wire:click="confirmUserDeletion" wire:loading.attr="disabled">
                    {{ __('Delete Account') }}
                </x-danger-button>
            </div>

            @teleport('body')
                <x-dialog-modal wire:model.live="confirmingUserDeletion">
                    <x-slot name="title">
                        {{ __('Delete Account') }}
                    </x-slot>

                    <x-slot name="content">
                        <p class="text-sm text-gray-600">
                            Apakah Anda yakin ingin menghapus akun? Semua data termasuk kampanye, donasi, dan riwayat akan dihapus permanen. Masukkan password untuk konfirmasi.
                        </p>

                        <div class="mt-4" x-data="{}" x-on:confirming-delete-user.window="setTimeout(() => $refs.password.focus(), 250)">
                            <x-input type="password" class="mt-1 block w-3/4"
                                        autocomplete="current-password"
                                        placeholder="{{ __('Password') }}"
                                        x-ref="password"
                                        wire:model="password"
                                        wire:keydown.enter="deleteUser" />

                            <x-input-error for="password" class="mt-2" />
                        </div>
                    </x-slot>

                    <x-slot name="footer">
                        <x-secondary-button wire:click="$toggle('confirmingUserDeletion')" wire:loading.attr="disabled">
                            {{ __('Cancel') }}
                        </x-secondary-button>

                        <x-danger-button class="ms-3" wire:click="deleteUser" wire:loading.attr="disabled">
                            {{ __('Delete Account') }}
                        </x-danger-button>
                    </x-slot>
                </x-dialog-modal>
            @endteleport
        @endif
    </x-slot>
</x-action-section>
