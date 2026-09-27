<?php

namespace App\Filament\Employee\Resources\Estimates\Pages;

use App\Filament\Employee\Resources\Estimates\EstimateResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEstimate extends CreateRecord
{
    protected static string $resource = EstimateResource::class;
}
