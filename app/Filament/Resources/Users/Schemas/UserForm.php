<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;

use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Placeholder;
use Illuminate\Support\HtmlString;
use Filament\Schemas\Schema;

class UserForm
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

                Section::make('Informasi Utama')
                    ->description('Kredensial login, peran, dan status akun utama pengguna.')
                    ->columnSpanFull()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('full_name')
                                    ->label('Nama Lengkap')
                                    ->required()
                                    ->maxLength(100),
                                TextInput::make('username')
                                    ->label('Username')
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255),
                                TextInput::make('email')
                                    ->label('Alamat Email')
                                    ->email()
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(100),
                                TextInput::make('phone_number')
                                    ->label('Nomor Telepon')
                                    ->tel()
                                    ->maxLength(20),
                                TextInput::make('nik')
                                    ->label('NIK')
                                    ->maxLength(16),
                                TextInput::make('password')
                                    ->label('Password Baru')
                                    ->password()
                                    ->dehydrated(fn ($state) => filled($state))
                                    ->required(fn (string $context): bool => $context === 'create')
                                    ->maxLength(255)
                                    ->helperText(fn (string $context): string => $context === 'edit' ? 'Biarkan kosong jika tidak ingin mengubah password.' : ''),
                                Select::make('role')
                                    ->label('Peran / Hak Akses')
                                    ->options([
                                        'super_admin' => 'Super Administrator',
                                        'admin' => 'Administrator',
                                        'user' => 'Pengguna Biasa',
                                    ])
                                    ->required()
                                    ->default('user'),
                                Select::make('account_status')
                                    ->label('Status Akun')
                                    ->options([
                                        'active' => 'Aktif (Active)',
                                        'suspended' => 'Ditangguhkan (Suspended)',
                                        'pending' => 'Menunggu Verifikasi (Pending)',
                                    ])
                                    ->required()
                                    ->default('active'),
                                Toggle::make('is_verified')
                                    ->label('Akun Terverifikasi (Centang Biru)')
                                    ->default(false)
                                    ->inline(false),
                            ]),
                    ]),

                Section::make('Informasi Tambahan & Profil')
                    ->description('Detail profil personal, biografi, alamat, dan sosial media.')
                    ->columnSpanFull()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Textarea::make('bio')
                                    ->label('Biografi Diri / Keterangan Singkat')
                                    ->columnSpanFull()
                                    ->rows(3),
                                Textarea::make('address')
                                    ->label('Alamat Lengkap')
                                    ->columnSpanFull()
                                    ->rows(3),
                                FileUpload::make('profile_photo')
                                    ->label('Foto Profil Avatar')
                                    ->image()
                                    ->imagePreviewHeight('160')
                                    ->directory('profile-photos')
                                    ->disk('public')
                                    ->openable()
                                    ->downloadable(),
                                FileUpload::make('cover_photo_path')
                                    ->label('Foto Sampul Profil')
                                    ->image()
                                    ->imagePreviewHeight('160')
                                    ->directory('cover-photos')
                                    ->disk('public')
                                    ->openable()
                                    ->downloadable(),
                                KeyValue::make('social_links')
                                    ->label('Tautan Media Sosial')
                                    ->columnSpanFull()
                                    ->keyLabel('Media Sosial')
                                    ->valueLabel('Tautan / Username')
                                    ->keyPlaceholder('Contoh: instagram')
                                    ->valuePlaceholder('Contoh: raymond_smt'),
                            ]),
                    ]),

                Section::make('Integrasi Single Sign-On (OAuth)')
                    ->description('ID eksternal dari provider Google dan GitHub.')
                    ->columnSpanFull()
                    ->collapsed()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('google_id')
                                    ->label('Google OAuth ID')
                                    ->disabled()
                                    ->dehydrated(false),
                                TextInput::make('github_id')
                                    ->label('GitHub OAuth ID')
                                    ->disabled()
                                    ->dehydrated(false),
                            ]),
                    ]),
            ]);
    }
}

