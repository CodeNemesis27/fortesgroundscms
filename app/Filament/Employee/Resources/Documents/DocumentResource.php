<?php

namespace App\Filament\Employee\Resources\Documents;

use App\Filament\Employee\Resources\Documents\Pages\CreateDocument;
use App\Filament\Employee\Resources\Documents\Pages\EditDocument;
use App\Filament\Employee\Resources\Documents\Pages\ListDocuments;
use App\Filament\Employee\Resources\Documents\Pages\ViewDocument;
use App\Filament\Employee\Resources\Documents\RelationManagers\AuditLogsRelationManager;
use App\Filament\Employee\Resources\Documents\RelationManagers\SharesRelationManager;
use App\Filament\Employee\Resources\Documents\RelationManagers\VersionsRelationManager;
use App\Filament\Employee\Resources\Documents\Schemas\DocumentForm;
use App\Filament\Employee\Resources\Documents\Schemas\DocumentInfolist;
use App\Filament\Employee\Resources\Documents\Tables\DocumentsTable;
use App\Models\Document;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class DocumentResource extends Resource
{
    protected static ?string $model = Document::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string | UnitEnum | null $navigationGroup = 'Document Management';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return DocumentForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DocumentInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DocumentsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            VersionsRelationManager::class,
            SharesRelationManager::class,
            AuditLogsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDocuments::route('/'),
            'create' => CreateDocument::route('/create'),
            'view' => ViewDocument::route('/{record}'),
            'edit' => EditDocument::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $user = auth()->user();
        $query = parent::getEloquentQuery();

        if ($user && method_exists($user, 'isAdmin') && $user->isAdmin()) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($user) {
            $q->where('owner_id', $user?->id)
                ->orWhereHas('shares', function (Builder $shareQuery) use ($user) {
                    $shareQuery->where('shared_with_user_id', $user?->id)
                        ->whereNull('revoked_at')
                        ->where(fn($e) => $e->whereNull('expires_at')->orWhere('expires_at', '>', now()));
                });
        });
    }
}
