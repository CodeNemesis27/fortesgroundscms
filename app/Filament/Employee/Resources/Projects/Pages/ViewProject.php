<?php

namespace App\Filament\Employee\Resources\Projects\Pages;

use App\Filament\Employee\Resources\Projects\ProjectResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewProject extends ViewRecord
{
    protected static string $resource = ProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}
