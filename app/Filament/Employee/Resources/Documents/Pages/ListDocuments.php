<?php

namespace App\Filament\Employee\Resources\Documents\Pages;

use App\Filament\Employee\Resources\Documents\DocumentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDocuments extends ListRecords
{
    protected static string $resource = DocumentResource::class;

    protected ?string $subheading = 'Organize, share and track project documents by category and owner';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
