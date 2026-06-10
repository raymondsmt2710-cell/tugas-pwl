<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Kategori')
                    ->columnSpanFull()
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('name')
                                ->label('Nama Kategori')
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
                                ->maxLength(255)
                                ->helperText('Slug dibuat otomatis dari nama untuk alamat URL kategori.'),
                        ]),
                        TextInput::make('logo')
                            ->label('Icon FontAwesome')
                            ->placeholder('Contoh: fa-solid fa-graduation-cap')
                            ->maxLength(100)
                            ->nullable(),
                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(4)
                            ->nullable(),
                    ]),
            ]);
    }
}
