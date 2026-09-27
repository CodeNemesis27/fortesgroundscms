<?php

namespace App\Filament\Resources\Vendors\Pages;

use App\Filament\Resources\Vendors\VendorResource;
use App\Models\Vendor;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListVendors extends ListRecords
{
    protected static string $resource = VendorResource::class;

    protected ?string $subheading = 'Manage vendor information and contact details';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'All' => Tab::make()
                ->badge(Vendor::query()->count()),
            'Material supplier' => Tab::make()
                ->badge(Vendor::query()->where('vendor_type', 'Material supplier')->count())
                ->modifyQueryUsing(fn(Builder $query) => $query->where('vendor_type', 'Material supplier')),
            'Service provider' => Tab::make()
                ->badge(Vendor::query()->where('vendor_type', 'Service provider')->count())
                ->modifyQueryUsing(fn(Builder $query) => $query->where('vendor_type', 'Service provider')),
            'Equipment rental' => Tab::make()
                ->badge(Vendor::query()->where('vendor_type', 'Equipment rental')->count())
                ->modifyQueryUsing(fn(Builder $query) => $query->where('vendor_type', 'Equipment rental')),
        ];
    }
}
