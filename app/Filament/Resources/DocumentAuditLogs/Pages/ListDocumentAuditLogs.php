<?php

namespace App\Filament\Resources\DocumentAuditLogs\Pages;

use App\Filament\Resources\DocumentAuditLogs\DocumentAuditLogResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDocumentAuditLogs extends ListRecords
{
    protected static string $resource = DocumentAuditLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}
