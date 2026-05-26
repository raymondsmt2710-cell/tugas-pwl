<?php

namespace App\Filament\Resources\Campaigns\Pages;

use App\Filament\Resources\Campaigns\CampaignResource;
use App\Models\Campaign;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCampaigns extends ListRecords
{
    protected static string $resource = CampaignResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getSubheading(): ?string
    {
        $pendingCount = Campaign::where('status', 'pending')->count();

        if ($pendingCount > 0) {
            return "{$pendingCount} kampanye menunggu review";
        }

        return null;
    }
}
