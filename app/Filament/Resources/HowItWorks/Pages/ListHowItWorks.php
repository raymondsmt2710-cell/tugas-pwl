<?php

namespace App\Filament\Resources\HowItWorks\Pages;

use App\Filament\Resources\HowItWorks\HowItWorkResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListHowItWorks extends ListRecords
{
    protected static string $resource = HowItWorkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Cara Kerja')
                ->icon('heroicon-o-plus')
                ->color('primary'),
        ];
    }
}
