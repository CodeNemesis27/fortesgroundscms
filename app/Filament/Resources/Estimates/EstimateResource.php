<?php

namespace App\Filament\Resources\Estimates;

use App\Filament\Resources\Estimates\Pages\CreateEstimate;
use App\Filament\Resources\Estimates\Pages\EditEstimate;
use App\Filament\Resources\Estimates\Pages\ListEstimates;
use App\Filament\Resources\Estimates\Pages\ViewEstimate;
use App\Filament\Resources\Estimates\RelationManagers\MaterialsRelationManager;
use App\Filament\Resources\Estimates\Schemas\EstimateForm;
use App\Filament\Resources\Estimates\Schemas\EstimateInfolist;
use App\Filament\Resources\Estimates\Tables\EstimatesTable;
use App\Models\Estimate;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class EstimateResource extends Resource
{
    protected static ?string $model = Estimate::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalculator;

    protected static string | UnitEnum | null $navigationGroup = 'Project Management';

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::where('status', 'Draft')->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        $count = static::getModel()::where('status', 'Draft')->count();

        return $count > 0 ? "{$count} drafts" : null;
    }

    public static function form(Schema $schema): Schema
    {
        return EstimateForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return EstimateInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EstimatesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            // MaterialsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEstimates::route('/'),
            'create' => CreateEstimate::route('/create'),
            'view' => ViewEstimate::route('/{record}'),
            'edit' => EditEstimate::route('/{record}/edit'),
        ];
    }
}
