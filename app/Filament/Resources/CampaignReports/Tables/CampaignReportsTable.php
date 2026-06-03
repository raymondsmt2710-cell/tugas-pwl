<?php

namespace App\Filament\Resources\CampaignReports\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
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
                    ->limit(40)
                    ->url(fn ($record) => $record->campaign
                        ? url('/campaigns/' . $record->campaign->slug)
                        : null)
                    ->openUrlInNewTab(),

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

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending'   => 'warning',
                        'reviewed'  => 'info',
                        'resolved'  => 'success',
                        'dismissed' => 'danger',
                        default     => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending'   => 'Menunggu',
                        'reviewed'  => 'Sedang Ditinjau',
                        'resolved'  => 'Diselesaikan',
                        'dismissed' => 'Ditolak',
                        default     => ucfirst($state),
                    })
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Tanggal Laporan')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending'   => 'Menunggu',
                        'reviewed'  => 'Sedang Ditinjau',
                        'resolved'  => 'Diselesaikan',
                        'dismissed' => 'Ditolak',
                    ]),
            ])
            ->recordActions([
                Action::make('view_campaign')
                    ->label('Lihat Kampanye')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->url(fn ($record) => $record->campaign
                        ? url('/campaigns/' . $record->campaign->slug)
                        : null)
                    ->openUrlInNewTab()
                    ->visible(fn ($record) => $record->campaign !== null),

                Action::make('update_status')
                    ->label('Ubah Status')
                    ->icon('heroicon-o-pencil-square')
                    ->color('warning')
                    ->form([
                        Select::make('status')
                            ->label('Status Laporan')
                            ->options([
                                'pending'   => 'Menunggu',
                                'reviewed'  => 'Sedang Ditinjau',
                                'resolved'  => 'Diselesaikan',
                                'dismissed' => 'Ditolak',
                            ])
                            ->required(),
                        Textarea::make('admin_notes')
                            ->label('Catatan Admin')
                            ->rows(3)
                            ->placeholder('Opsional — tulis catatan penanganan laporan...'),
                    ])
                    ->fillForm(fn ($record) => [
                        'status'      => $record->status,
                        'admin_notes' => $record->admin_notes,
                    ])
                    ->action(function ($record, array $data): void {
                        $record->update([
                            'status'      => $data['status'],
                            'admin_notes' => $data['admin_notes'] ?? null,
                        ]);
                    })
                    ->successNotificationTitle('Status laporan berhasil diperbarui.'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
