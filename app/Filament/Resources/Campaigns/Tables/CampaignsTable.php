<?php

namespace App\Filament\Resources\Campaigns\Tables;

use App\Models\Campaign;
use App\Services\CampaignService;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class CampaignsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('banner_image')
                    ->label('Sampul')
                    ->square(),
                TextColumn::make('title')
                    ->label('Judul Kampanye')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->description(fn (Campaign $record): string => $record->short_description ?? ''),
                TextColumn::make('user.full_name')
                    ->label('Penggalang Dana')
                    ->searchable()
                    ->sortable()
                    ->placeholder('-'),
                TextColumn::make('category.name')
                    ->label('Kategori')
                    ->sortable()
                    ->placeholder('-'),
                TextColumn::make('target_amount')
                    ->label('Target Dana')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('collected_amount')
                    ->label('Terkumpul')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'approved' => 'success',
                        'pending' => 'warning',
                        'rejected' => 'danger',
                        'completed' => 'info',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'draft' => 'Draft',
                        'pending' => 'Menunggu Review',
                        'approved' => 'Aktif',
                        'rejected' => 'Ditolak',
                        'completed' => 'Selesai',
                        default => $state,
                    })
                    ->sortable(),
                TextColumn::make('end_date')
                    ->label('Berakhir')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Saring Status')
                    ->options([
                        'draft' => 'Draft',
                        'pending' => 'Menunggu Review',
                        'approved' => 'Disetujui / Aktif',
                        'rejected' => 'Ditolak',
                        'completed' => 'Selesai',
                    ]),
            ])
            ->recordActions([
                // Approve Action
                Action::make('approve')
                    ->label('Setujui')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Setujui Kampanye')
                    ->modalDescription(fn (Campaign $record): string => "Apakah Anda yakin ingin menyetujui kampanye \"{$record->title}\"? Kampanye akan ditampilkan ke publik dan dapat menerima donasi.")
                    ->modalSubmitActionLabel('Ya, Setujui')
                    ->visible(fn (Campaign $record): bool => $record->status === 'pending' && auth()->user()->isAdmin())
                    ->action(function (Campaign $record): void {
                        app(CampaignService::class)->approve($record);

                        \Filament\Notifications\Notification::make()
                            ->title('Kampanye Disetujui')
                            ->body("Kampanye \"{$record->title}\" berhasil disetujui dan sekarang aktif.")
                            ->success()
                            ->send();
                    }),

                // Reject Action
                Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Tolak Kampanye')
                    ->modalDescription(fn (Campaign $record): string => "Apakah Anda yakin ingin menolak kampanye \"{$record->title}\"? Penggalang dana akan dapat mengedit dan mengajukan ulang.")
                    ->modalSubmitActionLabel('Ya, Tolak')
                    ->visible(fn (Campaign $record): bool => $record->status === 'pending' && auth()->user()->isAdmin())
                    ->action(function (Campaign $record): void {
                        app(CampaignService::class)->reject($record);

                        \Filament\Notifications\Notification::make()
                            ->title('Kampanye Ditolak')
                            ->body("Kampanye \"{$record->title}\" telah ditolak.")
                            ->danger()
                            ->send();
                    }),

                // Complete Action
                Action::make('complete')
                    ->label('Selesaikan')
                    ->icon('heroicon-o-flag')
                    ->color('info')
                    ->requiresConfirmation()
                    ->modalHeading('Selesaikan Kampanye')
                    ->modalDescription(fn (Campaign $record): string => "Tandai kampanye \"{$record->title}\" sebagai selesai? Kampanye tidak akan menerima donasi lagi.")
                    ->modalSubmitActionLabel('Ya, Selesaikan')
                    ->visible(fn (Campaign $record): bool => $record->status === 'approved' && auth()->user()->isAdmin())
                    ->action(function (Campaign $record): void {
                        app(CampaignService::class)->complete($record);

                        \Filament\Notifications\Notification::make()
                            ->title('Kampanye Diselesaikan')
                            ->body("Kampanye \"{$record->title}\" telah ditandai selesai.")
                            ->info()
                            ->send();
                    }),

                EditAction::make(),
                ViewAction::make()
                    ->label('Review'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    // Bulk Approve
                    BulkAction::make('bulkApprove')
                        ->label('Setujui Terpilih')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Setujui Kampanye Terpilih')
                        ->modalDescription('Semua kampanye yang dipilih dengan status "Menunggu Review" akan disetujui.')
                        ->modalSubmitActionLabel('Setujui Semua')
                        ->deselectRecordsAfterCompletion()
                        ->action(function (Collection $records): void {
                            $service = app(CampaignService::class);
                            $count = 0;

                            foreach ($records as $record) {
                                if ($record->status === 'pending') {
                                    $service->approve($record);
                                    $count++;
                                }
                            }

                            \Filament\Notifications\Notification::make()
                                ->title("{$count} Kampanye Disetujui")
                                ->success()
                                ->send();
                        }),

                    // Bulk Reject
                    BulkAction::make('bulkReject')
                        ->label('Tolak Terpilih')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('Tolak Kampanye Terpilih')
                        ->modalDescription('Semua kampanye yang dipilih dengan status "Menunggu Review" akan ditolak.')
                        ->modalSubmitActionLabel('Tolak Semua')
                        ->deselectRecordsAfterCompletion()
                        ->action(function (Collection $records): void {
                            $service = app(CampaignService::class);
                            $count = 0;

                            foreach ($records as $record) {
                                if ($record->status === 'pending') {
                                    $service->reject($record);
                                    $count++;
                                }
                            }

                            \Filament\Notifications\Notification::make()
                                ->title("{$count} Kampanye Ditolak")
                                ->danger()
                                ->send();
                        }),

                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
