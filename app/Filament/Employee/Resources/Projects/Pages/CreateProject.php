<?php

namespace App\Filament\Employee\Resources\Projects\Pages;

use App\Filament\Employee\Resources\Projects\ProjectResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProject extends CreateRecord
{
    protected static string $resource = ProjectResource::class;
}
