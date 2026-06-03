<?php

namespace App\Filament\Resources\CampaignReports;

use App\Filament\Resources\CampaignReports\Pages\ListCampaignReports;
use App\Filament\Resources\CampaignReports\Tables\CampaignReportsTable;
use App\Models\CampaignReport;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class CampaignReportResource extends Resource
{
    protected static ?string $model = CampaignReport::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-exclamation-triangle';

    protected static ?string $navigationLabel = 'Kelola Laporan';

    protected static ?string $modelLabel = 'Laporan Kampanye';

    protected static ?string $pluralModelLabel = 'Kelola Laporan';

    protected static UnitEnum|string|null $navigationGroup = 'Donasi & Kampanye';

    public static function canViewAny(): bool
    {
        return auth()->user()->isAdmin();
    }

    public static function getNavigationBadge(): ?string
    {
        $count = CampaignReport::pending()->count();
        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function table(Table $table): Table
    {
        return CampaignReportsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCampaignReports::route('/'),
        ];
    }
}
