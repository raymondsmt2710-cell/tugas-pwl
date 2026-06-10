<?php

namespace App\Filament\Resources\HowItWorks\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class HowItWorksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('type')
                    ->label('Kategori/Tipe')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'galang_dana' ? 'info' : 'warning')
                    ->formatStateUsing(fn (string $state): string => $state === 'galang_dana' ? 'Galang Dana' : 'Berdonasi')
                    ->sortable(),
                TextColumn::make('step_number')
                    ->label('Langkah Ke-')
                    ->sortable(),
                TextColumn::make('title')
                    ->label('Judul Langkah')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('icon')
                    ->label('Icon')
                    ->placeholder('-'),
                TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->reorderable('step_number')
            ->defaultGroup('type')
            ->reorderRecordsTriggerAction(
                fn ($action, bool $isReordering) => $action
                    ->button()
                    ->label($isReordering ? 'Selesai Mengurutkan' : 'Ubah Urutan')
            )
            ->defaultSort('step_number', 'asc')
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
