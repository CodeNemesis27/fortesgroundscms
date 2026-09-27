<?php

namespace App\Filament\Resources\DocumentAuditLogs\Schemas;

use App\Enums\AuditEvent;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DocumentAuditLogForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
            ]);
    }
}
