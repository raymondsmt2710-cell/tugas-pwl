<x-form-section submit="updateProfileInformation">
    <x-slot name="title">
        <span class="text-xl font-black text-gray-900">{{ __('Pengaturan Profil') }}</span>
    </x-slot>

    <x-slot name="description">
        <span class="text-sm text-gray-600">{{ __('Ubah foto dan informasi publik Anda di sini.') }}</span>
    </x-slot>

    <x-slot name="form">
        <!-- Foto Sampul -->
        <div class="col-span-6 mb-4">
            <x-label value="Foto Sampul" class="font-bold text-gray-700 mb-2" />
            <div class="relative h-40 w-full rounded-lg overflow-hidden bg-gray-100 border border-gray-200 group cursor-pointer" x-on:click="$refs.cover_input.click()">
                @if ($cover_photo)
                    <img src="{{ $cover_photo->temporaryUrl() }}" class="w-full h-full object-cover">
                @else
                    <img src="{{ $this->user->cover_photo_url }}" class="w-full h-full object-cover">
                @endif
                <div class="absolute inset-0 bg-black/30 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all">
                    <span class="text-white font-bold text-sm bg-blue-600 px-4 py-2 rounded-lg">Ganti Sampul</span>
                </div>
                <input type="file" class="hidden" wire:model.live="cover_photo" x-ref="cover_input">
            </div>
            <x-input-error for="cover_photo" class="mt-2" />
        </div>

        <div class="col-span-6 flex items-center gap-6 p-6 bg-white rounded-lg border border-gray-100 shadow-sm mb-6">
            <div class="relative group cursor-pointer" x-on:click="$refs.photo_input.click()">
                <div class="w-24 h-24 rounded-lg border border-gray-100 shadow-sm overflow-hidden">
                    @if ($photo)
                        <img src="{{ $photo->temporaryUrl() }}" class="w-full h-full object-cover">
                    @else
                        <img src="{{ $this->user->profile_photo_url }}" class="w-full h-full object-cover">
                    @endif
                </div>
                <div class="absolute inset-0 bg-black/10 rounded-lg flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </div>
                <input type="file" class="hidden" wire:model.live="photo" x-ref="photo_input">
            </div>
            <div>
                <h4 class="font-bold text-gray-900 text-lg">Foto Profil</h4>
                <p class="text-sm text-gray-500">Klik lingkaran untuk mengganti foto profil Anda.</p>
                <x-input-error for="photo" class="mt-2" />
            </div>
        </div>

        <!-- Inputs Grid -->
        <div class="col-span-6 space-y-4 pt-4 border-t border-gray-100">
            <div>
                <x-label for="name" value="Nama Lengkap" class="font-bold text-gray-700 mb-1" />
                <input id="name" type="text" class="w-full px-4 py-3 rounded-md border border-gray-300 bg-white focus:border-gray-400 focus:ring-0 transition-all text-gray-900 font-medium" wire:model="state.name" required>
                <x-input-error for="name" class="mt-2" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <x-label for="username" value="Username" class="font-bold text-gray-700 mb-1" />
                <input id="username" type="text" class="w-full px-4 py-3 rounded-md border border-gray-300 bg-white focus:border-gray-400 focus:ring-0 transition-all text-gray-900 font-medium" wire:model="state.username" required>
                    <x-input-error for="username" class="mt-2" />
                </div>
                <div>
                    <x-label for="email" value="Email" class="font-bold text-gray-700 mb-1" />
                    <input id="email" type="email" class="w-full px-4 py-3 rounded-md border border-gray-300 bg-white focus:border-gray-400 focus:ring-0 transition-all text-gray-900 font-medium" wire:model="state.email" required>
                    <x-input-error for="email" class="mt-2" />
                </div>
            </div>

            <div>
                <x-label for="bio" value="Bio Singkat" class="font-bold text-gray-700 mb-1" />
                <textarea id="bio" rows="3" class="w-full px-4 py-3 rounded-md border border-gray-300 bg-white focus:border-gray-400 focus:ring-0 transition-all text-gray-900 font-medium" wire:model="state.bio" placeholder="Tulis bio singkat Anda..."></textarea>
                <x-input-error for="bio" class="mt-2" />
            </div>

            <!-- Lokasi & Sosial Media -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 border-t border-gray-100">
                <div>
                    <x-label for="location" value="Lokasi" class="font-bold text-gray-700 mb-1" />
                    <input id="location" type="text" class="w-full px-4 py-3 rounded-md border border-gray-300 bg-white focus:border-gray-400 focus:ring-0 transition-all text-gray-900 font-medium" wire:model="state.location" placeholder="Contoh: Jakarta, Indonesia">
                    <x-input-error for="location" class="mt-2" />
                </div>
                <div>
                    <x-label for="twitter" value="Username / Link Twitter (X)" class="font-bold text-gray-700 mb-1" />
                    <input id="twitter" type="text" class="w-full px-4 py-3 rounded-md border border-gray-300 bg-white focus:border-gray-400 focus:ring-0 transition-all text-gray-900 font-medium" wire:model="state.social_links.twitter" placeholder="Contoh: https://twitter.com/username">
                    <x-input-error for="social_links.twitter" class="mt-2" />
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <x-label for="facebook" value="Username / Link Facebook" class="font-bold text-gray-700 mb-1" />
                    <input id="facebook" type="text" class="w-full px-4 py-3 rounded-md border border-gray-300 bg-white focus:border-gray-400 focus:ring-0 transition-all text-gray-900 font-medium" wire:model="state.social_links.facebook" placeholder="Contoh: https://facebook.com/username">
                    <x-input-error for="social_links.facebook" class="mt-2" />
                </div>
                <div>
                    <x-label for="instagram" value="Username / Link Instagram" class="font-bold text-gray-700 mb-1" />
                    <input id="instagram" type="text" class="w-full px-4 py-3 rounded-md border border-gray-300 bg-white focus:border-gray-400 focus:ring-0 transition-all text-gray-900 font-medium" wire:model="state.social_links.instagram" placeholder="Contoh: https://instagram.com/username">
                    <x-input-error for="social_links.instagram" class="mt-2" />
                </div>
            </div>
        </div>
    </x-slot>

    <x-slot name="actions">
        <div x-data="{ show: false }" x-on:saved.window="show = true; setTimeout(() => show = false, 3000)" x-show="show" class="mr-3 text-green-600 font-bold" style="display: none;">
            Tersimpan!
        </div>

        <button type="submit" class="btn-profile-submit" wire:loading.attr="disabled" wire:target="photo, cover_photo">
            {{ __('SIMPAN PERUBAHAN') }}
        </button>
    </x-slot>
</x-form-section>
