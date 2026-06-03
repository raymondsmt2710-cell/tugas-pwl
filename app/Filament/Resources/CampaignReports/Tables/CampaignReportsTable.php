<?php

namespace App\Filament\Resources\CampaignReports\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CampaignReportsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('campaign.title')
                    ->label('Kampanye')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->limit(40),
                TextColumn::make('user.full_name')
                    ->label('Pelapor')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('reason')
                    ->label('Alasan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('description')
                    ->label('Deskripsi')
                    ->limit(50)
                    ->placeholder('-'),
                TextColumn::make('created_at')
                    ->label('Tanggal Laporan')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
