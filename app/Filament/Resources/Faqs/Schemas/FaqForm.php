<?php

namespace App\Filament\Resources\Faqs\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FaqForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi FAQ')
                    ->columnSpanFull()
                    ->schema([
                        Grid::make(1)->schema([
                            TextInput::make('question')
                                ->label('Pertanyaan')
                                ->required()
                                ->maxLength(255),
                            Textarea::make('answer')
                                ->label('Jawaban')
                                ->required()
                                ->rows(4)
                                ->maxLength(1000),
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
