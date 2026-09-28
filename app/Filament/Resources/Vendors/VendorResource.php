<?php

namespace App\Filament\Resources\Vendors;


use App\Filament\Resources\Vendors\Pages\CreateVendor;
use App\Filament\Resources\Vendors\Pages\EditVendor;
use App\Filament\Resources\Vendors\Pages\ListVendors;
use App\Filament\Resources\Vendors\Pages\ViewVendor;
use App\Filament\Resources\Vendors\RelationManagers\MaterialsRelationManager;
use App\Filament\Resources\Vendors\Schemas\VendorForm;
use App\Filament\Resources\Vendors\Schemas\VendorInfolist;
use App\Filament\Resources\Vendors\Tables\VendorsTable;
use App\Models\Vendor;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class VendorResource extends Resource
{
    protected static ?string $model = Vendor::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::BuildingStorefront;

    protected static string | UnitEnum | null $navigationGroup = 'Procurement';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getGlobalSearchResultActions(Model $record): array
    {
        return [
            Action::make('view')
                ->icon(Heroicon::OutlinedEye)
                ->color('primary')
                ->url(static::getUrl('view', ['record' => $record]), shouldOpenInNewTab: true),
            Action::make('edit')
                ->color('warning')
                ->icon(Heroicon::OutlinedPencilSquare)
                ->url(static::getUrl('edit', ['record' => $record])),
        ];
    }

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
}
