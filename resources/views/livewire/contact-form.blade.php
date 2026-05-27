<div>
    @if($sent)
        <div class="text-center py-8">
            <svg class="mx-auto w-12 h-12 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
            </svg>
            <h3 class="mt-3 text-lg font-semibold text-gray-900">Pesan Terkirim</h3>
            <p class="mt-1 text-sm text-gray-500">Terima kasih! Kami akan segera menghubungi Anda.</p>
        </div>
    @else
        <form wire:submit="send" class="space-y-4">
            <div>
                <label for="contact-name" class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                <input wire:model="name" type="text" id="contact-name" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="Nama lengkap">
                @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="contact-email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input wire:model="email" type="email" id="contact-email" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="email@contoh.com">
                @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="contact-message" class="block text-sm font-medium text-gray-700 mb-1">Pesan</label>
                <textarea wire:model="message" id="contact-message" rows="4" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="Tulis pesan Anda..."></textarea>
                @error('message') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <button type="submit" class="w-full py-2.5 px-4 rounded-lg bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700 transition">
                Kirim Pesan
            </button>
        </form>
    @endif
</div>
