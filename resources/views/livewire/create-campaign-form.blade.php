<div>
    <form wire:submit="save">

        {{-- Card: Informasi Dasar --}}
        <div class="bg-white shadow-sm rounded-2xl border border-gray-100 overflow-hidden">
            <div class="border-b border-gray-100 px-6 py-4">
                <h3 class="text-base font-semibold text-gray-900">Informasi Dasar</h3>
                <p class="text-sm text-gray-500 mt-0.5">Detail utama kampanye penggalangan dana Anda.</p>
            </div>
            <div class="px-6 py-5 space-y-5">
                {{-- Title --}}
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1.5">Judul Kampanye <span class="text-red-500">*</span></label>
                    <input wire:model.live.debounce.500ms="title" type="text" id="title"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                           placeholder="Contoh: Bantu Korban Banjir Medan">
                    @error('title') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Category --}}
                <div>
                    <label for="id_category" class="block text-sm font-medium text-gray-700 mb-1.5">Kategori <span class="text-red-500">*</span></label>
                    <select wire:model="id_category" id="id_category" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <option value="">— Pilih Kategori —</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id_category }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('id_category') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Short Description --}}
                <div>
                    <label for="short_description" class="block text-sm font-medium text-gray-700 mb-1.5">Deskripsi Singkat <span class="text-red-500">*</span></label>
                    <textarea wire:model="short_description" id="short_description" rows="2"
                              class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                              placeholder="Ringkasan singkat kampanye (maks 500 karakter)"></textarea>
                    @error('short_description') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Description --}}
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1.5">Cerita Lengkap <span class="text-red-500">*</span></label>
                    <textarea wire:model="description" id="description" rows="6"
                              class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                              placeholder="Ceritakan secara detail tentang kampanye Anda..."></textarea>
                    <p class="mt-1 text-xs text-gray-400">Minimal 50 karakter</p>
                    @error('description') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Card: Target & Waktu --}}
        <div class="mt-6 bg-white shadow-sm rounded-2xl border border-gray-100 overflow-hidden">
            <div class="border-b border-gray-100 px-6 py-4">
                <h3 class="text-base font-semibold text-gray-900">Target & Waktu</h3>
            </div>
            <div class="px-6 py-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="target_amount" class="block text-sm font-medium text-gray-700 mb-1.5">Target Donasi <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 text-sm">Rp</span>
                            <input wire:model="target_amount" type="number" id="target_amount" min="100000" step="1000"
                                   class="w-full rounded-lg border-gray-300 pl-9 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="100000">
                        </div>
                        @error('target_amount') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="minimum_donation" class="block text-sm font-medium text-gray-700 mb-1.5">Minimum Donasi</label>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 text-sm">Rp</span>
                            <input wire:model="minimum_donation" type="number" id="minimum_donation" min="1000" step="1000"
                                   class="w-full rounded-lg border-gray-300 pl-9 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="10000">
                        </div>
                        @error('minimum_donation') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal Berakhir <span class="text-red-500">*</span></label>
                        <input wire:model="end_date" type="date" id="end_date" min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        @error('end_date') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="video_url" class="block text-sm font-medium text-gray-700 mb-1.5">URL Video</label>
                        <input wire:model="video_url" type="url" id="video_url"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                               placeholder="https://youtube.com/watch?v=...">
                        @error('video_url') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Card: Banner Image --}}
        <div class="mt-6 bg-white shadow-sm rounded-2xl border border-gray-100 overflow-hidden">
            <div class="border-b border-gray-100 px-6 py-4">
                <h3 class="text-base font-semibold text-gray-900">Gambar Sampul <span class="text-red-500">*</span></h3>
                <p class="text-sm text-gray-500 mt-0.5">JPG, PNG, WebP. Maksimal 2MB.</p>
            </div>
            <div class="px-6 py-5">
                @if($banner_image)
                    <div class="relative mb-4">
                        <img src="{{ $banner_image->temporaryUrl() }}" class="w-full h-48 object-cover rounded-lg border border-gray-200">
                        <button type="button" wire:click="removeBanner"
                                class="absolute top-2 right-2 w-7 h-7 bg-red-500 text-white rounded-full flex items-center justify-center text-xs hover:bg-red-600 transition">✕</button>
                    </div>
                @endif

                <div>
                    <input wire:model.live="banner_image" type="file" id="banner_image" accept="image/jpeg,image/png,image/webp"
                           class="block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer border border-gray-300 rounded-lg">
                    <div wire:loading wire:target="banner_image" class="mt-2 flex items-center gap-2 text-sm text-indigo-600">
                        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                        Mengunggah...
                    </div>
                </div>
                @error('banner_image') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Card: Gallery --}}
        <div class="mt-6 bg-white shadow-sm rounded-2xl border border-gray-100 overflow-hidden">
            <div class="border-b border-gray-100 px-6 py-4">
                <h3 class="text-base font-semibold text-gray-900">Galeri Tambahan</h3>
                <p class="text-sm text-gray-500 mt-0.5">Maks 5 gambar. JPG, PNG, WebP. Masing-masing maks 2MB.</p>
            </div>
            <div class="px-6 py-5">
                @if(count($gallery_images) > 0)
                    <div class="grid grid-cols-3 sm:grid-cols-5 gap-3 mb-4">
                        @foreach($gallery_images as $index => $image)
                            <div class="relative group">
                                <img src="{{ $image->temporaryUrl() }}" class="w-full h-20 object-cover rounded-lg border border-gray-200">
                                <button type="button" wire:click="removeGalleryImage({{ $index }})"
                                        class="absolute -top-1.5 -right-1.5 w-5 h-5 bg-red-500 text-white rounded-full flex items-center justify-center text-[10px] opacity-0 group-hover:opacity-100 transition">✕</button>
                            </div>
                        @endforeach
                    </div>
                @endif

                @if(count($gallery_images) < 5)
                    <input wire:model.live="gallery_images" type="file" accept="image/jpeg,image/png,image/webp" multiple
                           class="block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-gray-50 file:text-gray-700 hover:file:bg-gray-100 cursor-pointer border border-gray-300 rounded-lg">
                    <div wire:loading wire:target="gallery_images" class="mt-2 flex items-center gap-2 text-sm text-indigo-600">
                        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                        Mengunggah...
                    </div>
                @else
                    <p class="text-sm text-gray-500">Maksimal 5 gambar tercapai.</p>
                @endif
                @error('gallery_images.*') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
                @error('gallery_images') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Card: Documents --}}
        <div class="mt-6 bg-white shadow-sm rounded-2xl border border-gray-100 overflow-hidden">
            <div class="border-b border-gray-100 px-6 py-4">
                <h3 class="text-base font-semibold text-gray-900">Dokumen Pendukung</h3>
                <p class="text-sm text-gray-500 mt-0.5">Maks 5 file. PDF, Word, Excel, PowerPoint. Masing-masing maks 5MB.</p>
            </div>
            <div class="px-6 py-5">
                @if(count($documents) > 0)
                    <div class="space-y-2 mb-4">
                        @foreach($documents as $index => $doc)
                            <div class="flex items-center justify-between bg-gray-50 rounded-lg px-4 py-2.5">
                                <div class="flex items-center gap-2 min-w-0">
                                    <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/></svg>
                                    <span class="text-sm text-gray-700 truncate">{{ $doc->getClientOriginalName() }}</span>
                                    <span class="text-xs text-gray-400 shrink-0">({{ number_format($doc->getSize() / 1024, 0) }} KB)</span>
                                </div>
                                <button type="button" wire:click="removeDocument({{ $index }})"
                                        class="text-red-400 hover:text-red-600 p-1 transition">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        @endforeach
                    </div>
                @endif

                @if(count($documents) < 5)
                    <input wire:model.live="documents" type="file" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx" multiple
                           class="block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-green-50 file:text-green-700 hover:file:bg-green-100 cursor-pointer border border-gray-300 rounded-lg">
                    <div wire:loading wire:target="documents" class="mt-2 flex items-center gap-2 text-sm text-indigo-600">
                        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                        Mengunggah...
                    </div>
                @else
                    <p class="text-sm text-gray-500">Maksimal 5 dokumen tercapai.</p>
                @endif
                @error('documents.*') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
                @error('documents') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Actions --}}
        <div class="mt-8 flex items-center justify-between">
            <a href="{{ url('/my-campaigns') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">← Kembali</a>
            <button type="submit"
                    wire:loading.attr="disabled"
                    wire:loading.class="opacity-50 cursor-not-allowed"
                    class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 transition disabled:opacity-50">
                <span wire:loading.remove wire:target="save">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                </span>
                <svg wire:loading wire:target="save" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                <span wire:loading.remove wire:target="save">Buat Kampanye</span>
                <span wire:loading wire:target="save">Menyimpan...</span>
            </button>
        </div>

        {{-- Info --}}
        <div class="mt-6 rounded-xl border border-blue-100 bg-blue-50 p-4">
            <div class="flex gap-3">
                <svg class="h-5 w-5 text-blue-500 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <div class="text-sm text-blue-700">
                    <p class="font-medium">Kampanye akan berstatus Draft setelah dibuat.</p>
                    <p class="mt-1 text-blue-600">Ajukan untuk review agar dapat ditampilkan ke publik dan menerima donasi.</p>
                </div>
            </div>
        </div>
    </form>
</div>
