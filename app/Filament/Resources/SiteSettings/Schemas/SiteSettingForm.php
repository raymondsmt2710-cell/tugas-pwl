<?php

namespace App\Filament\Resources\SiteSettings\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SiteSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Kontak')
                    ->columnSpanFull()
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('email')
                                ->label('Email Kontak')
                                ->email()
                                ->required()
                                ->maxLength(100),
                            TextInput::make('phone')
                                ->label('Telepon / WhatsApp')
                                ->required()
                                ->maxLength(50),
                            Textarea::make('address')
                                ->label('Alamat Kantor')
                                ->required()
                                ->rows(3)
                                ->columnSpanFull(),
                        ]),
                    ]),
                Section::make('Media Sosial')
                    ->columnSpanFull()
                    ->schema([
                        Repeater::make('social_media')
                            ->label('Daftar Akun Media Sosial')
                            ->schema([
                                Select::make('platform')
                                    ->label('Platform')
                                    ->options([
                                        'instagram' => 'Instagram',
                                        'facebook' => 'Facebook',
                                        'twitter' => 'Twitter / X',
                                        'tiktok' => 'TikTok',
                                        'youtube' => 'YouTube',
                                        'linkedin' => 'LinkedIn',
                                    ])
                                    ->required(),
                                TextInput::make('label')
                                    ->label('Keterangan / Nama Akun')
                                    ->placeholder('Contoh: @autopahala')
                                    ->required()
                                    ->maxLength(100),
                                TextInput::make('url')
                                    ->label('URL Tautan')
                                    ->url()
                                    ->required()
                                    ->maxLength(255),
                            ])
                            ->columns(3)
                            ->default([])
                            ->createItemButtonLabel('Tambah Media Sosial'),
                    ]),
            ]);
    }
}
