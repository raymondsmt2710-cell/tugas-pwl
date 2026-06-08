<?php

namespace App\Filament\Resources\Banners\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class BannerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Banner')
                    ->columnSpanFull()
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('title')
                                ->label('Judul Banner')
                                ->maxLength(255)
                                ->nullable(),
                            TextInput::make('subtitle')
                                ->label('Sub-judul / Deskripsi Pendek')
                                ->maxLength(500)
                                ->nullable(),
                            Placeholder::make('current_image_preview')
                                ->label('Gambar Saat Ini')
                                ->content(fn ($record) => $record && $record->image_path 
                                    ? new HtmlString('<img src="' . (str_starts_with($record->image_path, 'http') ? $record->image_path : asset('storage/' . $record->image_path)) . '" class="max-w-[200px] h-auto rounded-lg shadow-sm border border-gray-200">') 
                                    : 'Belum ada gambar.'
                                )
                                ->visible(fn ($record) => $record !== null)
                                ->columnSpanFull(),
                            FileUpload::make('image_path')
                                ->label('Gambar Banner')
                                ->image()
                                ->directory('banners')
                                ->disk('public')
                                ->required(fn ($record) => $record === null) // Only required when creating
                                ->dehydrated(fn ($state) => filled($state)) // Keep original if left empty
                                ->openable()
                                ->downloadable()
                                ->columnSpanFull(),
                            TextInput::make('order')
                                ->label('Urutan Tampilan')
                                ->numeric()
                                ->default(0)
                                ->required(),
                        ]),
                    ]),
            ]);
    }
}
