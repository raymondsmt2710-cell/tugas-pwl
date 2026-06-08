<?php

namespace App\Filament\Resources\HowItWorks\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class HowItWorkForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Langkah Cara Kerja')
                    ->columnSpanFull()
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('type')
                                ->label('Tipe')
                                ->options([
                                    'galang_dana' => 'Galang Dana',
                                    'donasi' => 'Berdonasi',
                                ])
                                ->required(),
                            TextInput::make('step_number')
                                ->label('Nomor Langkah')
                                ->numeric()
                                ->required(),
                            TextInput::make('title')
                                ->label('Judul Langkah')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('icon')
                                ->label('Icon FontAwesome')
                                ->required()
                                ->maxLength(100)
                                ->placeholder('Contoh: fa-solid fa-hand-holding-heart'),
                            TextInput::make('color')
                                ->label('Warna Background & Border (Class Tailwind)')
                                ->maxLength(100)
                                ->placeholder('Contoh: bg-blue-50 border-blue-100')
                                ->nullable(),
                            TextInput::make('icon_color')
                                ->label('Warna Icon (Class Tailwind)')
                                ->maxLength(100)
                                ->placeholder('Contoh: text-blue-500')
                                ->nullable(),
                            Textarea::make('description')
                                ->label('Deskripsi Langkah')
                                ->required()
                                ->rows(3)
                                ->columnSpanFull(),
                        ]),
                    ]),
            ]);
    }
}
