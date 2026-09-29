<?php

namespace App\Filament\Employee\Resources\Estimates;

use App\Filament\Employee\Resources\Estimates\Pages\CreateEstimate;
use App\Filament\Employee\Resources\Estimates\Pages\EditEstimate;
use App\Filament\Employee\Resources\Estimates\Pages\ListEstimates;
use App\Filament\Employee\Resources\Estimates\Pages\ViewEstimate;
use App\Filament\Employee\Resources\Estimates\Schemas\EstimateForm;
use App\Filament\Employee\Resources\Estimates\Schemas\EstimateInfolist;
use App\Filament\Employee\Resources\Estimates\Tables\EstimatesTable;
use App\Filament\Resources\Estimates\RelationManagers\MaterialsRelationManager;
use App\Models\Estimate;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class EstimateResource extends Resource
{
    protected static ?string $model = Estimate::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Calculator;

    protected static string | UnitEnum | null $navigationGroup = 'Project Management';

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
            MaterialsRelationManager::class
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

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
