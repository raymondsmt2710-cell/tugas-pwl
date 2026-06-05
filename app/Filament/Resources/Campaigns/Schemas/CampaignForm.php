<?php

namespace App\Filament\Resources\Campaigns\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Illuminate\Support\HtmlString;

class CampaignForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Penolong Lightbox Gambar Instan di Halaman yang Sama (Simpel & Pendek)
                Placeholder::make('lightbox_helper')
                    ->columnSpanFull()
                    ->content(fn () => new HtmlString(<<<HTML
                        <style>[style*="background-image"],.fi-fo-file-upload img{cursor:zoom-in!important}</style>
                        <div x-data="{ isOpen: false, imageUrl: '', init() {
                            document.addEventListener('click', (e) => {
                                let img = e.target.closest('img, [style*=\'background-image\']');
                                let btn = e.target.closest('a[target=\"_blank\"], a[href*=\"storage\"]');
                                let container = e.target.closest('.fi-fo-file-upload, .fi-fo-file-upload-preview, .fi-fo-file-upload-item, [class*=\"file-upload\"]');
                                if (!container) return;
                                
                                let url = null;
                                if (img && img.tagName === 'IMG') {
                                    url = img.getAttribute('src');
                                } else if (img) {
                                    let match = (img.style.backgroundImage || img.getAttribute('style')).match(/url\((['\"]?)(.*?)\1\)/);
                                    if (match && match[2]) url = match[2];
                                } else if (btn) {
                                    url = btn.getAttribute('href');
                                }
                                
                                if (url && !url.endsWith('.svg') && !url.includes('data:image/svg+xml')) {
                                    e.preventDefault(); e.stopPropagation();
                                    this.imageUrl = url; this.isOpen = true;
                                }
                            }, true);
                        }}" class="w-full">
                            <div x-show="isOpen" class="fixed inset-0 z-[99999] flex items-center justify-center bg-black/90 p-4" @click="isOpen = false" @keydown.escape.window="isOpen = false" style="display:none">
                                <button type="button" class="absolute top-6 right-6 text-white text-4xl hover:text-gray-300 font-bold">&times;</button>
                                <img :src="imageUrl" class="max-w-full max-h-[85vh] object-contain rounded-lg border border-gray-800" @click.stop>
                            </div>
                        </div>
HTML
                    )),

                // Kartu 1: Informasi & Konten Kampanye (Lebar Penuh)
                Section::make('Informasi & Konten Kampanye')
                    ->description('Detail judul, kategori, pemilik, serta deskripsi cerita kampanye.')
                    ->columnSpanFull()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('id_user')
                                    ->label('Pemilik Kampanye (User)')
                                    ->relationship('user', 'full_name')
                                    ->required()
                                    ->searchable()
                                    ->preload(),
                                Select::make('id_category')
                                    ->label('Kategori Kampanye')
                                    ->relationship('category', 'name')
                                    ->required()
                                    ->preload(),
                                TextInput::make('title')
                                    ->label('Judul Kampanye')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (string $operation, $state, callable $set) => 
                                        $operation === 'create' ? $set('slug', Str::slug($state)) : null
                                    ),
                                TextInput::make('slug')
                                    ->label('Slug URL')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255),
                            ]),
                        Textarea::make('short_description')
                            ->label('Deskripsi Singkat')
                            ->required()
                            ->maxLength(500)
                            ->rows(3)
                            ->columnSpanFull(),
                        RichEditor::make('description')
                            ->label('Deskripsi Lengkap Kampanye')
                            ->required()
                            ->columnSpanFull()
                            ->toolbarButtons([
                                'blockquote',
                                'bold',
                                'bulletList',
                                'h2',
                                'h3',
                                'italic',
                                'link',
                                'orderedList',
                                'redo',
                                'strike',
                                'undo',
                            ]),
                    ]),

                // Kartu 2: Pengaturan, Keuangan & Media (Lebar Penuh)
                Section::make('Pengaturan, Keuangan & Media')
                    ->description('Target donasi, batas minimal, tenggat waktu, berkas gambar/video, dan status kepatuhan admin.')
                    ->columnSpanFull()
                    ->schema([
                        // Baris Keuangan & Waktu (Diubah ke 2 kolom agar tidak terpotong)
                        Grid::make(2)
                            ->schema([
                                TextInput::make('target_amount')
                                    ->label('Target Donasi')
                                    ->required()
                                    ->prefix('Rp')
                                    ->formatStateUsing(fn ($state) => $state !== null ? number_format((float) $state, 0, ',', '.') : null)
                                    ->dehydrateStateUsing(fn ($state) => $state !== null ? (int) str_replace('.', '', str_replace(',', '', $state)) : null),
                                TextInput::make('minimum_donation')
                                    ->label('Minimum Donasi')
                                    ->prefix('Rp')
                                    ->formatStateUsing(fn ($state) => $state !== null ? number_format((float) $state, 0, ',', '.') : null)
                                    ->dehydrateStateUsing(fn ($state) => $state !== null ? (int) str_replace('.', '', str_replace(',', '', $state)) : null)
                                    ->default('0'),
                                TextInput::make('collected_amount')
                                    ->label('Terkumpul')
                                    ->prefix('Rp')
                                    ->disabled()
                                    ->dehydrated(false)
                                    ->formatStateUsing(fn ($state) => $state !== null ? number_format((float) $state, 0, ',', '.') : null),
                                DatePicker::make('end_date')
                                    ->label('Tanggal Berakhir')
                                    ->required(),
                            ]),

                        // Baris Media Gambar & Video (2 kolom)
                        Grid::make(2)
                            ->schema([
                                FileUpload::make('banner_image')
                                    ->label('Gambar Sampul Kampanye')
                                    ->image()
                                    ->directory('campaign-banners')
                                    ->disk('public')
                                    ->openable()
                                    ->downloadable()
                                    ->required(),
                                TextInput::make('video_url')
                                    ->label('URL Video Pendukung')
                                    ->url()
                                    ->maxLength(500)
                                    ->placeholder('https://www.youtube.com/watch?v=...'),
                            ]),

                        // Repeater Terformat Slide-Grid untuk Galeri Tambahan
                        Repeater::make('galleries')
                            ->relationship('galleries')
                            ->label('Galeri Tambahan')
                            ->schema([
                                FileUpload::make('image_path')
                                    ->label('Gambar')
                                    ->image()
                                    ->directory('campaign-galleries')
                                    ->disk('public')
                                    ->openable()
                                    ->downloadable()
                                    ->required(),
                            ])
                            ->grid(5)
                            ->maxItems(5)
                            ->columnSpanFull()
                            ->helperText('Maksimal 5 gambar pendukung. Format: JPG, PNG, WebP. Masing-masing maks 2MB.'),

                        // Repeater untuk Dokumen Pendukung
                        Repeater::make('documents')
                            ->relationship('documents')
                            ->label('Dokumen Pendukung')
                            ->schema([
                                FileUpload::make('file_path')
                                    ->label('Berkas Dokumen')
                                    ->directory('campaigns/documents')
                                    ->disk('public')
                                    ->openable()
                                    ->downloadable()
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        if (is_object($state) && method_exists($state, 'getClientOriginalName')) {
                                            $set('original_name', $state->getClientOriginalName());
                                            $set('mime_type', $state->getMimeType());
                                            $set('file_size', $state->getSize());
                                        }
                                    }),
                                TextInput::make('original_name')
                                    ->label('Nama Berkas')
                                    ->disabled()
                                    ->dehydrated(),
                                TextInput::make('mime_type')
                                    ->label('Tipe MIME')
                                    ->disabled()
                                    ->dehydrated(),
                                TextInput::make('file_size')
                                    ->label('Ukuran Berkas')
                                    ->disabled()
                                    ->dehydrated()
                                    ->formatStateUsing(fn ($state) => $state !== null ? number_format($state / 1024, 2) . ' KB' : null),
                            ])
                            ->columns(2)
                            ->maxItems(5)
                            ->columnSpanFull()
                            ->helperText('Maksimal 5 dokumen pendukung (KTP, Surat Rekomendasi, Proposal, dll). Format: PDF, Word, Excel, PPT. Masing-masing maks 5MB.'),

                        // Baris Status Administrasi (Diubah ke 2 kolom agar seimbang dan rapi)
                        Grid::make(2)
                            ->schema([
                                Select::make('status')
                                    ->label('Persetujuan (Status)')
                                    ->options([
                                        'draft' => 'Draft',
                                        'pending' => 'Menunggu Review',
                                        'approved' => 'Disetujui',
                                        'rejected' => 'Ditolak',
                                        'completed' => 'Selesai',
                                    ])
                                    ->required()
                                    ->default('draft')
                                    ->disabled(fn () => !auth()->user()->isAdmin())
                                    ->dehydrated(fn ($state) => auth()->user()->isAdmin() || filled($state)),
                                Select::make('campaign_status')
                                    ->label('Status Kampanye')
                                    ->options([
                                        'draft' => 'Draft',
                                        'active' => 'Aktif',
                                        'finished' => 'Selesai',
                                        'closed' => 'Ditutup',
                                        'suspended' => 'Ditangguhkan',
                                    ])
                                    ->required()
                                    ->default('draft')
                                    ->disabled(fn () => !auth()->user()->isAdmin())
                                    ->dehydrated(fn ($state) => auth()->user()->isAdmin() || filled($state)),
                                Select::make('verification_status')
                                    ->label('Verifikasi')
                                    ->options([
                                        'draft' => 'Draft',
                                        'pending' => 'Menunggu Verifikasi',
                                        'active' => 'Terverifikasi',
                                        'rejected' => 'Ditolak',
                                        'expired' => 'Kedaluwarsa',
                                    ])
                                    ->required()
                                    ->default('draft')
                                    ->disabled(fn () => !auth()->user()->isAdmin())
                                    ->dehydrated(fn ($state) => auth()->user()->isAdmin() || filled($state))
                                    ->columnSpanFull(),
                            ]),
                    ]),
            ]);
    }
}
