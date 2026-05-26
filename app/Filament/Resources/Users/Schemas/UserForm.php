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
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Utama')
                    ->description('Kredensial login, peran, dan status akun utama pengguna.')
                    ->aside()
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
                    ->aside()
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
                                    ->directory('profile-photos'),
                                FileUpload::make('cover_photo_path')
                                    ->label('Foto Sampul Profil')
                                    ->image()
                                    ->directory('cover-photos'),
                                KeyValue::make('social_links')
                                    ->label('Tautan Media Sosial')
                                    ->columnSpanFull()
                                    ->keyLabel('Media Sosial')
                                    ->valueLabel('Tautan / Username')
                                    ->placeholder('Contoh: instagram -> raymond_smt'),
                            ]),
                    ]),

                Section::make('Integrasi Single Sign-On (OAuth)')
                    ->description('ID eksternal dari provider Google dan GitHub.')
                    ->aside()
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

