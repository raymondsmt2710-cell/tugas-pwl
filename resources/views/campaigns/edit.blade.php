<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Kampanye
        </h2>
    </x-slot>

    <div class="py-8 sm:py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Validation Errors --}}
            @if ($errors->any())
                <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4">
                    <div class="flex items-start gap-3">
                        <svg class="h-5 w-5 text-red-500 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p class="text-sm font-semibold text-red-800">Terdapat {{ $errors->count() }} kesalahan:</p>
                            <ul class="mt-2 space-y-1 text-sm text-red-700 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('campaign.update', $campaign) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Card: Informasi Dasar --}}
                <div class="bg-white shadow-sm rounded-2xl border border-gray-100 overflow-hidden">
                    <div class="border-b border-gray-100 px-6 py-4">
                        <h3 class="text-base font-semibold text-gray-900">Informasi Dasar</h3>
                    </div>
                    <div class="px-6 py-5 space-y-5">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1.5">Judul Kampanye <span class="text-red-500">*</span></label>
                            <input type="text" name="title" id="title" value="{{ old('title', $campaign->title) }}"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                        </div>

                        <div>
                            <label for="id_category" class="block text-sm font-medium text-gray-700 mb-1.5">Kategori <span class="text-red-500">*</span></label>
                            <select name="id_category" id="id_category" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id_category }}" {{ old('id_category', $campaign->id_category) == $category->id_category ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="short_description" class="block text-sm font-medium text-gray-700 mb-1.5">Deskripsi Singkat <span class="text-red-500">*</span></label>
                            <textarea name="short_description" id="short_description" rows="2"
                                      class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>{{ old('short_description', $campaign->short_description) }}</textarea>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1.5">Cerita Lengkap <span class="text-red-500">*</span></label>
                            <textarea name="description" id="description" rows="6"
                                      class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>{{ old('description', $campaign->description) }}</textarea>
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
                                    <input type="number" name="target_amount" id="target_amount" value="{{ old('target_amount', $campaign->target_amount) }}"
                                           class="w-full rounded-lg border-gray-300 pl-9 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                           min="100000" step="1000" required>
                                </div>
                            </div>
                            <div>
                                <label for="minimum_donation" class="block text-sm font-medium text-gray-700 mb-1.5">Minimum Donasi</label>
                                <div class="relative">
                                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 text-sm">Rp</span>
                                    <input type="number" name="minimum_donation" id="minimum_donation" value="{{ old('minimum_donation', $campaign->minimum_donation) }}"
                                           class="w-full rounded-lg border-gray-300 pl-9 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                           min="1000" step="1000">
                                </div>
                            </div>
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal Berakhir <span class="text-red-500">*</span></label>
                                <input type="date" name="end_date" id="end_date" value="{{ old('end_date', $campaign->end_date->format('Y-m-d')) }}"
                                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                       min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                            </div>
                            <div>
                                <label for="video_url" class="block text-sm font-medium text-gray-700 mb-1.5">URL Video</label>
                                <input type="url" name="video_url" id="video_url" value="{{ old('video_url', $campaign->video_url) }}"
                                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                       placeholder="https://youtube.com/watch?v=...">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card: Media --}}
                <div class="mt-6 bg-white shadow-sm rounded-2xl border border-gray-100 overflow-hidden">
                    <div class="border-b border-gray-100 px-6 py-4">
                        <h3 class="text-base font-semibold text-gray-900">Media</h3>
                    </div>
                    <div class="px-6 py-5 space-y-5">
                        {{-- Current Banner --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Gambar Sampul Saat Ini</label>
                            @if ($campaign->banner_image)
                                <img src="{{ asset('storage/' . $campaign->banner_image) }}" alt="Banner" class="w-full h-40 object-cover rounded-lg border border-gray-200 mb-3">
                            @endif
                            <input type="file" name="banner_image" accept="image/jpeg,image/png,image/jpg,image/webp"
                                   class="block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer border border-gray-300 rounded-lg">
                            <p class="mt-1.5 text-xs text-gray-400">Kosongkan jika tidak ingin mengubah.</p>
                        </div>

                        {{-- Current Gallery --}}
                        @if ($campaign->galleries->count() > 0)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Galeri Saat Ini</label>
                                <div class="grid grid-cols-3 gap-2">
                                    @foreach ($campaign->galleries as $gallery)
                                        <div class="relative group">
                                            <img src="{{ asset('storage/' . $gallery->image_path) }}" alt="Gallery" class="w-full h-24 object-cover rounded-lg border border-gray-200">
                                            <label class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs cursor-pointer opacity-0 group-hover:opacity-100 transition">
                                                <input type="checkbox" name="remove_gallery[]" value="{{ $gallery->id }}" class="hidden">
                                                ✕
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                <p class="mt-1.5 text-xs text-gray-400">Hover dan klik ✕ untuk menandai gambar yang ingin dihapus.</p>
                            </div>
                        @endif

                        {{-- Add Gallery --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tambah Galeri</label>
                            <div id="gallery-container" class="space-y-2"></div>
                            <button type="button" id="add-gallery-btn" class="mt-2 inline-flex items-center gap-1.5 text-sm font-medium text-indigo-600 hover:text-indigo-800 transition">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                                Tambah Gambar
                            </button>
                        </div>

                        {{-- Add Documents --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tambah Dokumen Pendukung</label>
                            <div id="documents-container" class="space-y-2"></div>
                            <button type="button" id="add-document-btn" class="mt-2 inline-flex items-center gap-1.5 text-sm font-medium text-green-600 hover:text-green-800 transition">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                                Tambah Dokumen
                            </button>
                            <p class="mt-1.5 text-xs text-gray-400">PDF, Word, Excel, PowerPoint. Maks 5MB per file.</p>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="mt-8 flex items-center justify-between">
                    <a href="{{ url('/my-campaigns') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">← Kembali</a>
                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 transition">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let galleryCount = 0;
        let docCount = 0;
        const max = 5;

        document.getElementById('add-gallery-btn').addEventListener('click', function() {
            if (galleryCount >= max) { alert('Maksimal 5 gambar.'); return; }
            galleryCount++;
            const container = document.getElementById('gallery-container');
            const row = document.createElement('div');
            row.className = 'flex items-center gap-2';
            row.id = 'gallery-row-' + galleryCount;
            row.innerHTML = '<input type="file" name="gallery[]" accept="image/jpeg,image/png,image/jpg,image/webp" class="flex-1 text-sm text-gray-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer border border-gray-300 rounded-lg">' +
                '<button type="button" onclick="this.parentElement.remove(); galleryCount--;" class="shrink-0 p-1.5 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>';
            container.appendChild(row);
        });

        document.getElementById('add-document-btn').addEventListener('click', function() {
            if (docCount >= max) { alert('Maksimal 5 dokumen.'); return; }
            docCount++;
            const container = document.getElementById('documents-container');
            const row = document.createElement('div');
            row.className = 'flex items-center gap-2';
            row.id = 'doc-row-' + docCount;
            row.innerHTML = '<input type="file" name="documents[]" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx" class="flex-1 text-sm text-gray-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-green-50 file:text-green-700 hover:file:bg-green-100 cursor-pointer border border-gray-300 rounded-lg">' +
                '<button type="button" onclick="this.parentElement.remove(); docCount--;" class="shrink-0 p-1.5 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>';
            container.appendChild(row);
        });
    </script>
</x-app-layout>
