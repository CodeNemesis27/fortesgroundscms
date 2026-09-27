<?php

namespace App\Filament\Resources\DocumentAuditLogs\Pages;

use App\Filament\Resources\DocumentAuditLogs\DocumentAuditLogResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDocumentAuditLog extends CreateRecord
{
    protected static string $resource = DocumentAuditLogResource::class;
}
