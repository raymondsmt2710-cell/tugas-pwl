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
                        'goal_reached' => 'info',
                        'closed' => 'gray',
                        'archived' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'draft' => 'Draft',
                        'pending' => 'Menunggu Review',
                        'approved' => 'Aktif',
                        'rejected' => 'Ditolak',
                        'goal_reached' => '🏆 Goal Reached',
                        'closed' => 'Ditutup',
                        'archived' => 'Diarsipkan',
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
                        'approved' => 'Aktif',
                        'goal_reached' => 'Goal Reached',
                        'closed' => 'Ditutup',
                        'rejected' => 'Ditolak',
                        'archived' => 'Diarsipkan',
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

                // Close Action (admin direct close or approve close request)
                Action::make('close')
                    ->label(fn (Campaign $record): string => $record->campaign_status === 'pending_close' ? 'Setujui Tutup' : 'Tutup')
                    ->icon('heroicon-o-lock-closed')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Tutup Kampanye')
                    ->modalDescription(fn (Campaign $record): string => $record->campaign_status === 'pending_close'
                        ? "Penggalang dana mengajukan penutupan kampanye \"{$record->title}\". Setujui?"
                        : "Tutup kampanye \"{$record->title}\"? Kampanye tidak akan menerima donasi lagi.")
                    ->modalSubmitActionLabel('Ya, Tutup')
                    ->visible(fn (Campaign $record): bool => (in_array($record->status, ['approved', 'goal_reached']) || $record->campaign_status === 'pending_close') && auth()->user()->isAdmin())
                    ->action(function (Campaign $record): void {
                        app(CampaignService::class)->close($record);

                        \Filament\Notifications\Notification::make()
                            ->title('Kampanye Ditutup')
                            ->success()
                            ->send();
                    }),

                // Reopen Action
                Action::make('reopen')
                    ->label('Buka Kembali')
                    ->icon('heroicon-o-lock-open')
                    ->color('info')
                    ->requiresConfirmation()
                    ->visible(fn (Campaign $record): bool => $record->status === 'closed' && auth()->user()->isAdmin())
                    ->action(function (Campaign $record): void {
                        app(CampaignService::class)->reopen($record);

                        \Filament\Notifications\Notification::make()
                            ->title('Kampanye Dibuka Kembali')
                            ->success()
                            ->send();
                    }),

                // Archive Action
                Action::make('archive')
                    ->label('Arsipkan')
                    ->icon('heroicon-o-archive-box')
                    ->color('gray')
                    ->requiresConfirmation()
                    ->visible(fn (Campaign $record): bool => $record->status === 'closed' && auth()->user()->isAdmin())
                    ->action(function (Campaign $record): void {
                        app(CampaignService::class)->archive($record);

                        \Filament\Notifications\Notification::make()
                            ->title('Kampanye Diarsipkan')
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
