<?php

namespace App\Filament\Employee\Resources\Vendors\Pages;

use App\Filament\Employee\Resources\Vendors\VendorResource;
use Filament\Resources\Pages\CreateRecord;

class CreateVendor extends CreateRecord
{
    protected static string $resource = VendorResource::class;
}
