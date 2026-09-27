<?php

namespace App\Filament\Resources\DocumentAuditLogs\Pages;

use App\Filament\Resources\DocumentAuditLogs\DocumentAuditLogResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDocumentAuditLog extends EditRecord
{
    protected static string $resource = DocumentAuditLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
