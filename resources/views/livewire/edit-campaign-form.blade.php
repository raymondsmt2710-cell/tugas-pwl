<div>
    <form wire:submit="save">
        {{-- Info Dasar --}}
        <div class="bg-white shadow-sm rounded-2xl border border-gray-100 overflow-hidden">
            <div class="border-b border-gray-100 px-6 py-4">
                <h3 class="text-base font-semibold text-gray-900">Informasi Dasar</h3>
            </div>
            <div class="px-6 py-5 space-y-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Judul <span class="text-red-500">*</span></label>
                    <input wire:model="title" type="text" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    @error('title') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Kategori <span class="text-red-500">*</span></label>
                    <select wire:model="id_category" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id_category }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('id_category') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Deskripsi Singkat <span class="text-red-500">*</span></label>
                    <textarea wire:model="short_description" rows="2" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"></textarea>
                    @error('short_description') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Cerita Lengkap <span class="text-red-500">*</span></label>
                    <textarea wire:model="description" rows="6" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"></textarea>
                    @error('description') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Target --}}
        <div class="mt-6 bg-white shadow-sm rounded-2xl border border-gray-100 overflow-hidden">
            <div class="border-b border-gray-100 px-6 py-4"><h3 class="text-base font-semibold text-gray-900">Target & Waktu</h3></div>
            <div class="px-6 py-5 grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Target Donasi <span class="text-red-500">*</span></label>
                    <div class="relative"><span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 text-sm">Rp</span>
                    <input wire:model="target_amount" type="number" class="w-full rounded-lg border-gray-300 pl-9 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"></div>
                    @error('target_amount') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Minimum Donasi</label>
                    <div class="relative"><span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 text-sm">Rp</span>
                    <input wire:model="minimum_donation" type="number" class="w-full rounded-lg border-gray-300 pl-9 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"></div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal Berakhir <span class="text-red-500">*</span></label>
                    <input wire:model="end_date" type="date" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    @error('end_date') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">URL Video</label>
                    <input wire:model="video_url" type="url" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="https://...">
                </div>
            </div>
        </div>

        {{-- Banner --}}
        <div class="mt-6 bg-white shadow-sm rounded-2xl border border-gray-100 overflow-hidden">
            <div class="border-b border-gray-100 px-6 py-4"><h3 class="text-base font-semibold text-gray-900">Gambar Sampul</h3></div>
            <div class="px-6 py-5">
                @if($banner_image)
                    <img src="{{ $banner_image->temporaryUrl() }}" class="w-full h-40 object-cover rounded-lg border mb-3">
                @elseif($campaign->banner_image)
                    <img src="{{ asset('storage/' . $campaign->banner_image) }}" class="w-full h-40 object-cover rounded-lg border mb-3">
                @endif
                <input wire:model.live="banner_image" type="file" accept="image/jpeg,image/png,image/webp"
                       class="block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer border border-gray-300 rounded-lg">
                <div wire:loading wire:target="banner_image" class="mt-2 text-sm text-indigo-600">Mengunggah...</div>
                <p class="mt-1.5 text-xs text-gray-400">Kosongkan jika tidak ingin mengubah.</p>
                @error('banner_image') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Existing Gallery --}}
        <div class="mt-6 bg-white shadow-sm rounded-2xl border border-gray-100 overflow-hidden">
            <div class="border-b border-gray-100 px-6 py-4"><h3 class="text-base font-semibold text-gray-900">Galeri</h3></div>
            <div class="px-6 py-5">
                @if($campaign->galleries->count() > 0)
                    <p class="text-xs text-gray-500 mb-2">Klik gambar untuk menandai hapus:</p>
                    <div class="grid grid-cols-3 sm:grid-cols-5 gap-2 mb-4">
                        @foreach($campaign->galleries as $g)
                            <div wire:click="toggleRemoveGallery({{ $g->id }})" class="relative cursor-pointer rounded-lg overflow-hidden border-2 {{ in_array($g->id, $remove_gallery) ? 'border-red-500 opacity-50' : 'border-gray-200' }}">
                                <img src="{{ asset('storage/' . $g->image_path) }}" class="w-full h-20 object-cover">
                                @if(in_array($g->id, $remove_gallery))
                                    <div class="absolute inset-0 bg-red-500/20 flex items-center justify-center"><span class="text-red-700 font-bold text-xs">HAPUS</span></div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- New gallery uploads --}}
                @if(count($gallery_images) > 0)
                    <div class="grid grid-cols-3 sm:grid-cols-5 gap-2 mb-3">
                        @foreach($gallery_images as $i => $img)
                            <div class="relative group">
                                <img src="{{ $img->temporaryUrl() }}" class="w-full h-20 object-cover rounded-lg border">
                                <button type="button" wire:click="removeNewGallery({{ $i }})" class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white rounded-full text-[10px] flex items-center justify-center opacity-0 group-hover:opacity-100">✕</button>
                            </div>
                        @endforeach
                    </div>
                @endif
                <input wire:model.live="gallery_images" type="file" accept="image/jpeg,image/png,image/webp" multiple
                       class="block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-gray-50 file:text-gray-700 cursor-pointer border border-gray-300 rounded-lg">
                <div wire:loading wire:target="gallery_images" class="mt-2 text-sm text-indigo-600">Mengunggah...</div>
            </div>
        </div>

        {{-- Documents --}}
        <div class="mt-6 bg-white shadow-sm rounded-2xl border border-gray-100 overflow-hidden">
            <div class="border-b border-gray-100 px-6 py-4"><h3 class="text-base font-semibold text-gray-900">Dokumen</h3></div>
            <div class="px-6 py-5">
                @if($campaign->documents->count() > 0)
                    <div class="space-y-2 mb-4">
                        @foreach($campaign->documents as $doc)
                            <div wire:click="toggleRemoveDocument({{ $doc->id }})" class="flex items-center justify-between px-3 py-2 rounded-lg cursor-pointer {{ in_array($doc->id, $remove_documents) ? 'bg-red-50 border border-red-200 line-through' : 'bg-gray-50' }}">
                                <span class="text-sm text-gray-700">{{ $doc->original_name }}</span>
                                <span class="text-xs {{ in_array($doc->id, $remove_documents) ? 'text-red-500' : 'text-gray-400' }}">{{ in_array($doc->id, $remove_documents) ? 'Akan dihapus' : $doc->file_size_formatted }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif

                @if(count($documents) > 0)
                    <div class="space-y-2 mb-3">
                        @foreach($documents as $i => $doc)
                            <div class="flex items-center justify-between bg-green-50 rounded-lg px-3 py-2">
                                <span class="text-sm text-gray-700 truncate">{{ $doc->getClientOriginalName() }}</span>
                                <button type="button" wire:click="removeNewDocument({{ $i }})" class="text-red-400 hover:text-red-600"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg></button>
                            </div>
                        @endforeach
                    </div>
                @endif
                <input wire:model.live="documents" type="file" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx" multiple
                       class="block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-green-50 file:text-green-700 cursor-pointer border border-gray-300 rounded-lg">
                <div wire:loading wire:target="documents" class="mt-2 text-sm text-indigo-600">Mengunggah...</div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="mt-8 flex items-center justify-between">
            <a href="{{ url('/my-campaigns') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">← Kembali</a>
            <button type="submit" wire:loading.attr="disabled" wire:loading.class="opacity-50"
                    class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 transition">
                <span wire:loading.remove wire:target="save">Simpan Perubahan</span>
                <span wire:loading wire:target="save">Menyimpan...</span>
            </button>
        </div>
    </form>
</div>
