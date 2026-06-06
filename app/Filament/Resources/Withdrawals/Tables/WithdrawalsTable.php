<?php

namespace App\Filament\Resources\Withdrawals\Tables;

use App\Models\Withdrawal;
use App\Services\WithdrawalService;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Forms\Components\FileUpload;

class WithdrawalsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.full_name')
                    ->label('Pengguna')
                    ->searchable()
                    ->sortable()
                    ->placeholder('-'),
                TextColumn::make('campaign.title')
                    ->label('Kampanye')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->limit(40)
                    ->placeholder('-'),
                TextColumn::make('amount')
                    ->label('Jumlah')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('bank_name')
                    ->label('Bank')
                    ->sortable(),
                TextColumn::make('account_number')
                    ->label('No. Rekening')
                    ->copyable(),
                TextColumn::make('account_holder')
                    ->label('Atas Nama'),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'under_review' => 'info',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'paid' => 'primary',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Menunggu',
                        'under_review' => 'Ditinjau',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                        'paid' => 'Dibayar',
                        default => $state,
                    })
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Menunggu',
                        'under_review' => 'Sedang Ditinjau',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                        'paid' => 'Dibayar',
                    ]),
            ])
            ->recordActions([
                // Review action
                Action::make('review')
                    ->label('Tinjau')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->visible(fn (Withdrawal $record): bool => $record->status === 'pending')
                    ->action(function (Withdrawal $record): void {
                        app(WithdrawalService::class)->markUnderReview($record);
                        \Filament\Notifications\Notification::make()->title('Status diubah ke Sedang Ditinjau')->info()->send();
                    }),

                // Approve action
                Action::make('approve')
                    ->label('Setujui')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Setujui Penarikan')
                    ->modalDescription(fn (Withdrawal $record): string => "Setujui penarikan Rp " . number_format($record->amount, 0, ',', '.') . " untuk kampanye \"{$record->campaign->title}\"?")
                    ->visible(fn (Withdrawal $record): bool => in_array($record->status, ['pending', 'under_review']))
                    ->action(function (Withdrawal $record): void {
                        app(WithdrawalService::class)->approve($record);
                        \Filament\Notifications\Notification::make()->title('Penarikan Disetujui')->success()->send();
                    }),

                // Reject action
                Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Tolak Penarikan')
                    ->visible(fn (Withdrawal $record): bool => in_array($record->status, ['pending', 'under_review']))
                    ->action(function (Withdrawal $record): void {
                        app(WithdrawalService::class)->reject($record, 'Ditolak oleh admin.');
                        \Filament\Notifications\Notification::make()->title('Penarikan Ditolak')->danger()->send();
                    }),

                // Mark as paid action
                Action::make('markPaid')
                    ->label('Tandai Dibayar')
                    ->icon('heroicon-o-banknotes')
                    ->color('primary')
                    ->form([
                        FileUpload::make('transfer_proof')
                            ->label('Bukti Transfer')
                            ->image()
                            ->directory('withdrawals/proofs')
                            ->disk('public')
                            ->required(),
                    ])
                    ->modalHeading('Tandai Sudah Dibayar')
                    ->modalDescription(fn (Withdrawal $record): string => "Konfirmasi bahwa Rp " . number_format($record->amount, 0, ',', '.') . " sudah ditransfer ke rekening {$record->account_holder} ({$record->bank_name}). Silakan unggah bukti transfer di bawah ini:")
                    ->visible(fn (Withdrawal $record): bool => $record->status === 'approved')
                    ->action(function (Withdrawal $record, array $data): void {
                        app(WithdrawalService::class)->markAsPaid($record, $data['transfer_proof']);
                        \Filament\Notifications\Notification::make()->title('Penarikan Ditandai Dibayar dengan Bukti Transfer')->success()->send();
                    }),

                // View transfer proof action
                Action::make('viewProof')
                    ->label('Bukti Transfer')
                    ->icon('heroicon-o-eye')
                    ->color('success')
                    ->visible(fn (Withdrawal $record): bool => $record->status === 'paid' && !empty($record->transfer_proof))
                    ->modalContent(fn (Withdrawal $record) => new \Illuminate\Support\HtmlString(
                        '<div class="flex justify-center"><img src="' . asset('storage/' . $record->transfer_proof) . '" class="max-w-full max-h-[70vh] rounded-lg shadow" /></div>'
                    ))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup'),

                ViewAction::make()->label('Review'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([]),
            ]);
    }
}
