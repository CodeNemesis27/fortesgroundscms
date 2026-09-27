<?php

namespace App\Filament\Employee\Resources\Vendors\Pages;

use App\Filament\Employee\Resources\Vendors\VendorResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewVendor extends ViewRecord
{
    protected static string $resource = VendorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }

    public function getHeading(): string
    {
        return 'View ' . $this->record->name;
    }
}
