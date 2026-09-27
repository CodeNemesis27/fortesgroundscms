<?php

namespace App\Filament\Employee\Resources\Materials\Pages;

use App\Filament\Employee\Resources\Materials\MaterialResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMaterials extends ListRecords
{
    protected static string $resource = MaterialResource::class;

    protected ?string $subheading = 'Browse materials, pricing, and vendor sources';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
