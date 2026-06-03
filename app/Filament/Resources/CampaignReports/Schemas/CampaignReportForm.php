<?php

namespace App\Filament\Resources\CampaignReports\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CampaignReportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Laporan')
                    ->aside()
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('id_campaign')
                                ->label('Kampanye Yang Dilaporkan')
                                ->relationship('campaign', 'title')
                                ->required(),
                            Select::make('id_user')
                                ->label('Pelapor (User)')
                                ->relationship('user', 'full_name')
                                ->required(),
                            TextInput::make('reason')
                                ->label('Alasan Laporan')
                                ->required()
                                ->maxLength(255),
                        ]),
                        Textarea::make('description')
                            ->label('Detail Deskripsi Laporan')
                            ->rows(4)
                            ->nullable(),
                    ]),
            ]);
    }
}
