<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-100">
        <h3 class="text-lg font-semibold text-gray-900">Profil</h3>
        <p class="text-sm text-gray-500 mt-0.5">Informasi publik yang ditampilkan di profil Anda.</p>
    </div>
    <form wire:submit="save" class="px-6 py-5 space-y-5">
        @if(session('profile_saved'))
            <div class="rounded-lg bg-green-50 border border-green-200 p-3 text-sm text-green-700">Profil berhasil disimpan.</div>
        @endif

        {{-- Avatar & Cover --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Foto Profil</label>
                <div class="flex items-center gap-4">
                    <img src="{{ $avatar ? $avatar->temporaryUrl() : auth()->user()->profile_photo_url }}" class="w-16 h-16 rounded-full object-cover border-2 border-gray-200">
                    <input wire:model.live="avatar" type="file" accept="image/*" class="text-sm text-gray-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 cursor-pointer">
                </div>
                <div wire:loading wire:target="avatar" class="mt-1 text-xs text-indigo-600">Mengunggah...</div>
                @error('avatar') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Foto Sampul</label>
                <input wire:model.live="cover_photo" type="file" accept="image/*" class="text-sm text-gray-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-gray-50 file:text-gray-700 cursor-pointer">
                <div wire:loading wire:target="cover_photo" class="mt-1 text-xs text-indigo-600">Mengunggah...</div>
                @error('cover_photo') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Fields --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap</label>
                <input wire:model="full_name" type="text" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                @error('full_name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Username</label>
                <input wire:model="username" type="text" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                @error('username') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                <input wire:model="email" type="email" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Nomor Telepon</label>
                <input wire:model="phone_number" type="text" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Biografi</label>
            <textarea wire:model="bio" rows="3" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm" placeholder="Ceritakan tentang diri Anda..."></textarea>
            @error('bio') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="pt-4 border-t border-gray-100">
            <button type="submit" wire:loading.attr="disabled" class="px-5 py-2.5 rounded-lg bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 transition disabled:opacity-50">
                <span wire:loading.remove wire:target="save">Simpan Perubahan</span>
                <span wire:loading wire:target="save">Menyimpan...</span>
            </button>
        </div>
    </form>
</div>
