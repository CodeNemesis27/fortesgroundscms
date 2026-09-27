<?php

namespace App\Filament\Employee\Resources\Materials\Pages;

use App\Filament\Employee\Resources\Materials\MaterialResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMaterial extends CreateRecord
{
    protected static string $resource = MaterialResource::class;
}
