<?php

namespace App\Filament\Resources\CampaignReports\Pages;

use App\Filament\Resources\CampaignReports\CampaignReportResource;
use Filament\Resources\Pages\ListRecords;

class ListCampaignReports extends ListRecords
{
    protected static string $resource = CampaignReportResource::class;

    protected function getHeaderActions(): array
    {
        return []; // Admin tidak perlu buat laporan manual
    }
}
