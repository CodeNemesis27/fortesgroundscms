<?php

namespace App\Filament\Employee\Resources\Vendors;

use App\Filament\Employee\Resources\Vendors\Pages\CreateVendor;
use App\Filament\Employee\Resources\Vendors\Pages\EditVendor;
use App\Filament\Employee\Resources\Vendors\Pages\ListVendors;
use App\Filament\Employee\Resources\Vendors\Pages\ViewVendor;
use App\Filament\Employee\Resources\Vendors\Schemas\VendorForm;
use App\Filament\Employee\Resources\Vendors\Schemas\VendorInfolist;
use App\Filament\Employee\Resources\Vendors\Tables\VendorsTable;
use App\Filament\Resources\Vendors\RelationManagers\MaterialsRelationManager;
use App\Models\Vendor;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class VendorResource extends Resource
{
    protected static ?string $model = Vendor::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::BuildingStorefront;

    protected static string | UnitEnum | null $navigationGroup = 'Procurement';

    public static function form(Schema $schema): Schema
    {
        return VendorForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return VendorInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VendorsTable::configure($table);
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
            'index' => ListVendors::route('/'),
            'create' => CreateVendor::route('/create'),
            'view' => ViewVendor::route('/{record}'),
            'edit' => EditVendor::route('/{record}/edit'),
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
