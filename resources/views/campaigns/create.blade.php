<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Buat Kampanye Baru
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

            <form action="{{ route('campaign.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Card: Informasi Dasar --}}
                <div class="bg-white shadow-sm rounded-2xl border border-gray-100 overflow-hidden">
                    <div class="border-b border-gray-100 px-6 py-4">
                        <h3 class="text-base font-semibold text-gray-900">Informasi Dasar</h3>
                        <p class="text-sm text-gray-500 mt-0.5">Detail utama kampanye penggalangan dana Anda.</p>
                    </div>
                    <div class="px-6 py-5 space-y-5">
                        {{-- Title --}}
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Judul Kampanye <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                   placeholder="Contoh: Bantu Korban Banjir Medan" required>
                        </div>

                        {{-- Category --}}
                        <div>
                            <label for="id_category" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Kategori <span class="text-red-500">*</span>
                            </label>
                            <select name="id_category" id="id_category"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                                <option value="">— Pilih Kategori —</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id_category }}" {{ old('id_category') == $category->id_category ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Short Description --}}
                        <div>
                            <label for="short_description" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Deskripsi Singkat <span class="text-red-500">*</span>
                            </label>
                            <textarea name="short_description" id="short_description" rows="2"
                                      class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                      placeholder="Ringkasan singkat kampanye (maks 500 karakter)" required>{{ old('short_description') }}</textarea>
                            <p class="mt-1.5 text-xs text-gray-400">Maksimal 500 karakter</p>
                        </div>

                        {{-- Description --}}
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Cerita Lengkap <span class="text-red-500">*</span>
                            </label>
                            <textarea name="description" id="description" rows="6"
                                      class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                      placeholder="Ceritakan secara detail tentang kampanye Anda..." required>{{ old('description') }}</textarea>
                            <p class="mt-1.5 text-xs text-gray-400">Minimal 50 karakter</p>
                        </div>
                    </div>
                </div>

                {{-- Card: Target & Waktu --}}
                <div class="mt-6 bg-white shadow-sm rounded-2xl border border-gray-100 overflow-hidden">
                    <div class="border-b border-gray-100 px-6 py-4">
                        <h3 class="text-base font-semibold text-gray-900">Target & Waktu</h3>
                        <p class="text-sm text-gray-500 mt-0.5">Tentukan target dana dan batas waktu kampanye.</p>
                    </div>
                    <div class="px-6 py-5">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            {{-- Target Amount --}}
                            <div>
                                <label for="target_amount" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Target Donasi <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 text-sm">Rp</span>
                                    <input type="number" name="target_amount" id="target_amount" value="{{ old('target_amount') }}"
                                           class="w-full rounded-lg border-gray-300 pl-9 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                           min="100000" step="1000" placeholder="100000" required>
                                </div>
                                <p class="mt-1.5 text-xs text-gray-400">Minimal Rp 100.000</p>
                            </div>

                            {{-- Minimum Donation --}}
                            <div>
                                <label for="minimum_donation" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Minimum Donasi
                                </label>
                                <div class="relative">
                                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 text-sm">Rp</span>
                                    <input type="number" name="minimum_donation" id="minimum_donation" value="{{ old('minimum_donation') }}"
                                           class="w-full rounded-lg border-gray-300 pl-9 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                           min="1000" step="1000" placeholder="10000">
                                </div>
                                <p class="mt-1.5 text-xs text-gray-400">Opsional</p>
                            </div>

                            {{-- End Date --}}
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Tanggal Berakhir <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}"
                                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                       min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                            </div>

                            {{-- Video URL --}}
                            <div>
                                <label for="video_url" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    URL Video
                                </label>
                                <input type="url" name="video_url" id="video_url" value="{{ old('video_url') }}"
                                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                       placeholder="https://youtube.com/watch?v=...">
                                <p class="mt-1.5 text-xs text-gray-400">Opsional</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card: Media --}}
                <div class="mt-6 bg-white shadow-sm rounded-2xl border border-gray-100 overflow-hidden">
                    <div class="border-b border-gray-100 px-6 py-4">
                        <h3 class="text-base font-semibold text-gray-900">Media</h3>
                        <p class="text-sm text-gray-500 mt-0.5">Unggah gambar untuk menarik perhatian donatur.</p>
                    </div>
                    <div class="px-6 py-5 space-y-6">
                        {{-- Banner Image --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Gambar Sampul <span class="text-red-500">*</span>
                            </label>
                            <div id="banner-preview" class="hidden mb-3">
                                <img id="banner-preview-img" src="" alt="Preview" class="w-full h-48 object-cover rounded-lg border border-gray-200">
                            </div>
                            <input type="file" name="banner_image" id="banner_image"
                                   accept="image/jpeg,image/png,image/jpg,image/webp"
                                   class="block w-full text-sm text-gray-600
                                          file:mr-4 file:py-2 file:px-4
                                          file:rounded-lg file:border-0
                                          file:text-sm file:font-medium
                                          file:bg-indigo-50 file:text-indigo-700
                                          hover:file:bg-indigo-100
                                          cursor-pointer border border-gray-300 rounded-lg"
                                   required>
                            <p class="mt-1.5 text-xs text-gray-400">Format: JPG, PNG, WebP. Maksimal 2MB.</p>
                        </div>

                        {{-- Gallery (add one by one) --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Galeri Tambahan
                            </label>
                            <div id="gallery-container" class="space-y-2">
                                {{-- Dynamic inputs will be added here --}}
                            </div>
                            <button type="button" id="add-gallery-btn"
                                    class="mt-2 inline-flex items-center gap-1.5 text-sm font-medium text-indigo-600 hover:text-indigo-800 transition">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                                Tambah Gambar
                            </button>
                            <p class="mt-1.5 text-xs text-gray-400">Opsional. Maks 5 gambar, masing-masing 2MB.</p>
                        </div>
                    </div>
                </div>

                {{-- Card: Dokumen Pendukung --}}
                <div class="mt-6 bg-white shadow-sm rounded-2xl border border-gray-100 overflow-hidden">
                    <div class="border-b border-gray-100 px-6 py-4">
                        <h3 class="text-base font-semibold text-gray-900">Dokumen Pendukung</h3>
                        <p class="text-sm text-gray-500 mt-0.5">Upload proposal, surat keterangan, atau dokumen lainnya.</p>
                    </div>
                    <div class="px-6 py-5">
                        <div id="documents-container" class="space-y-2">
                            {{-- Dynamic inputs will be added here --}}
                        </div>
                        <button type="button" id="add-document-btn"
                                class="mt-2 inline-flex items-center gap-1.5 text-sm font-medium text-green-600 hover:text-green-800 transition">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            Tambah Dokumen
                        </button>
                        <p class="mt-1.5 text-xs text-gray-400">Opsional. Format: PDF, Word, Excel, PowerPoint. Maks 5 file, masing-masing 5MB.</p>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="mt-8 flex items-center justify-between">
                    <a href="{{ url('/my-campaigns') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">
                        ← Kembali
                    </a>
                    <button type="submit"
                            class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Buat Kampanye
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
    </div>

    {{-- Script --}}
    <script>
        // Banner preview
        document.getElementById('banner_image').addEventListener('change', function(e) {
            const preview = document.getElementById('banner-preview');
            const img = document.getElementById('banner-preview-img');
            if (e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function(ev) {
                    img.src = ev.target.result;
                    preview.classList.remove('hidden');
                };
                reader.readAsDataURL(e.target.files[0]);
            } else {
                preview.classList.add('hidden');
            }
        });

        // Gallery: add one by one
        let galleryCount = 0;
        const maxGallery = 5;

        document.getElementById('add-gallery-btn').addEventListener('click', function() {
            if (galleryCount >= maxGallery) {
                alert('Maksimal ' + maxGallery + ' gambar galeri.');
                return;
            }
            galleryCount++;
            const container = document.getElementById('gallery-container');
            const row = document.createElement('div');
            row.className = 'flex items-center gap-2';
            row.id = 'gallery-row-' + galleryCount;
            row.innerHTML =
                '<input type="file" name="gallery[]" accept="image/jpeg,image/png,image/jpg,image/webp" ' +
                'class="flex-1 text-sm text-gray-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer border border-gray-300 rounded-lg">' +
                '<button type="button" onclick="removeRow(\'gallery-row-' + galleryCount + '\', \'gallery\')" ' +
                'class="shrink-0 p-1.5 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition">' +
                '<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>' +
                '</button>';
            container.appendChild(row);
            toggleBtn('add-gallery-btn', galleryCount, maxGallery);
        });

        // Documents: add one by one
        let docCount = 0;
        const maxDocs = 5;

        document.getElementById('add-document-btn').addEventListener('click', function() {
            if (docCount >= maxDocs) {
                alert('Maksimal ' + maxDocs + ' dokumen.');
                return;
            }
            docCount++;
            const container = document.getElementById('documents-container');
            const row = document.createElement('div');
            row.className = 'flex items-center gap-2';
            row.id = 'doc-row-' + docCount;
            row.innerHTML =
                '<input type="file" name="documents[]" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx" ' +
                'class="flex-1 text-sm text-gray-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-green-50 file:text-green-700 hover:file:bg-green-100 cursor-pointer border border-gray-300 rounded-lg">' +
                '<button type="button" onclick="removeRow(\'doc-row-' + docCount + '\', \'doc\')" ' +
                'class="shrink-0 p-1.5 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition">' +
                '<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>' +
                '</button>';
            container.appendChild(row);
            toggleBtn('add-document-btn', docCount, maxDocs);
        });

        function removeRow(rowId, type) {
            document.getElementById(rowId).remove();
            if (type === 'gallery') {
                galleryCount--;
                toggleBtn('add-gallery-btn', galleryCount, maxGallery);
            } else {
                docCount--;
                toggleBtn('add-document-btn', docCount, maxDocs);
            }
        }

        function toggleBtn(btnId, count, max) {
            const btn = document.getElementById(btnId);
            if (count >= max) {
                btn.classList.add('opacity-50', 'pointer-events-none');
            } else {
                btn.classList.remove('opacity-50', 'pointer-events-none');
            }
        }
    </script>
</x-app-layout>
