<?php

namespace App\Filament\Resources\CampaignReports\Pages;

use App\Filament\Resources\CampaignReports\CampaignReportResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCampaignReport extends EditRecord
{
    protected static string $resource = CampaignReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
