<?php

namespace App\Filament\Resources\DocumentAuditLogs;

use App\Filament\Resources\DocumentAuditLogs\Pages\CreateDocumentAuditLog;
use App\Filament\Resources\DocumentAuditLogs\Pages\EditDocumentAuditLog;
use App\Filament\Resources\DocumentAuditLogs\Pages\ListDocumentAuditLogs;
use App\Filament\Resources\DocumentAuditLogs\Schemas\DocumentAuditLogForm;
use App\Filament\Resources\DocumentAuditLogs\Tables\DocumentAuditLogsTable;
use App\Models\DocumentAuditLog;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class DocumentAuditLogResource extends Resource
{
    protected static ?string $model = DocumentAuditLog::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string | UnitEnum | null $navigationGroup = 'Document Management';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return DocumentAuditLogForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DocumentAuditLogsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDocumentAuditLogs::route('/'),
            // 'create' => CreateDocumentAuditLog::route('/create'),
            // 'edit' => EditDocumentAuditLog::route('/{record}/edit'),
        ];
    }
}
