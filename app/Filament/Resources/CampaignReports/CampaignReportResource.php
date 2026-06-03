<?php

namespace App\Filament\Resources\CampaignReports;

use App\Filament\Resources\CampaignReports\Pages\CreateCampaignReport;
use App\Filament\Resources\CampaignReports\Pages\EditCampaignReport;
use App\Filament\Resources\CampaignReports\Pages\ListCampaignReports;
use App\Filament\Resources\CampaignReports\Schemas\CampaignReportForm;
use App\Filament\Resources\CampaignReports\Tables\CampaignReportsTable;
use App\Models\CampaignReport;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
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

    public static function form(Schema $schema): Schema
    {
        return CampaignReportForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CampaignReportsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCampaignReports::route('/'),
            'create' => CreateCampaignReport::route('/create'),
            'edit' => EditCampaignReport::route('/{record}/edit'),
        ];
    }
}
