<?php

namespace App\Filament\Resources\Campaigns\Pages;

use App\Filament\Resources\Campaigns\CampaignResource;
use App\Models\Campaign;
use App\Services\CampaignService;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewCampaign extends ViewRecord
{
    protected static string $resource = CampaignResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Approve Action
            Action::make('approve')
                ->label('Setujui Kampanye')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Setujui Kampanye')
                ->modalDescription(fn (): string => "Kampanye \"{$this->record->title}\" akan ditampilkan ke publik dan dapat menerima donasi.")
                ->modalSubmitActionLabel('Ya, Setujui')
                ->visible(fn (): bool => $this->record->status === 'pending' && auth()->user()->isAdmin())
                ->action(function (): void {
                    app(CampaignService::class)->approve($this->record);

                    Notification::make()
                        ->title('Kampanye Disetujui')
                        ->body("Kampanye \"{$this->record->title}\" berhasil disetujui.")
                        ->success()
                        ->send();

                    $this->refreshFormData(['status', 'campaign_status', 'verification_status']);
                }),

            // Reject Action
            Action::make('reject')
                ->label('Tolak Kampanye')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Tolak Kampanye')
                ->modalDescription(fn (): string => "Kampanye \"{$this->record->title}\" akan ditolak. Penggalang dana dapat mengedit dan mengajukan ulang.")
                ->modalSubmitActionLabel('Ya, Tolak')
                ->visible(fn (): bool => $this->record->status === 'pending' && auth()->user()->isAdmin())
                ->action(function (): void {
                    app(CampaignService::class)->reject($this->record);

                    Notification::make()
                        ->title('Kampanye Ditolak')
                        ->body("Kampanye \"{$this->record->title}\" telah ditolak.")
                        ->danger()
                        ->send();

                    $this->refreshFormData(['status', 'campaign_status', 'verification_status']);
                }),

            // Complete Action
            Action::make('complete')
                ->label('Selesaikan')
                ->icon('heroicon-o-flag')
                ->color('info')
                ->requiresConfirmation()
                ->modalHeading('Selesaikan Kampanye')
                ->modalDescription(fn (): string => "Tandai kampanye \"{$this->record->title}\" sebagai selesai?")
                ->modalSubmitActionLabel('Ya, Selesaikan')
                ->visible(fn (): bool => $this->record->status === 'approved' && auth()->user()->isAdmin())
                ->action(function (): void {
                    app(CampaignService::class)->complete($this->record);

                    Notification::make()
                        ->title('Kampanye Diselesaikan')
                        ->body("Kampanye \"{$this->record->title}\" telah ditandai selesai.")
                        ->info()
                        ->send();

                    $this->refreshFormData(['status', 'campaign_status', 'verification_status']);
                }),

            EditAction::make(),
        ];
    }
}
