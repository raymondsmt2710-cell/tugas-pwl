<?php

namespace App\Filament\Resources\HowItWorks;

use App\Filament\Resources\HowItWorks\Pages\CreateHowItWork;
use App\Filament\Resources\HowItWorks\Pages\EditHowItWork;
use App\Filament\Resources\HowItWorks\Pages\ListHowItWorks;
use App\Filament\Resources\HowItWorks\Schemas\HowItWorkForm;
use App\Filament\Resources\HowItWorks\Tables\HowItWorksTable;
use App\Models\HowItWork;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class HowItWorkResource extends Resource
{
    protected static ?string $model = HowItWork::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-list-bullet';

    protected static ?string $navigationLabel = 'Kelola Cara Kerja';

    protected static ?string $modelLabel = 'Cara Kerja';

    protected static ?string $pluralModelLabel = 'Kelola Cara Kerja';

    protected static UnitEnum|string|null $navigationGroup = 'Kelola Konten';

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()
            ->orderBy('type', 'asc')
            ->orderBy('step_number', 'asc');
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->isAdmin();
    }

    public static function form(Schema $schema): Schema
    {
        return HowItWorkForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HowItWorksTable::configure($table);
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
            'index' => ListHowItWorks::route('/'),
            'create' => CreateHowItWork::route('/create'),
            'edit' => EditHowItWork::route('/{record}/edit'),
        ];
    }
}
