<?php

namespace App\Filament\Resources\HowItWorks\Pages;

use App\Filament\Resources\HowItWorks\HowItWorkResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditHowItWork extends EditRecord
{
    protected static string $resource = HowItWorkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
