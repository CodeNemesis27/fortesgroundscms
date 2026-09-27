<?php

namespace App\Filament\Client\Resources\Documents\RelationManagers;

use App\Enums\AuditEvent;
use App\Models\DocumentVersion;
use App\Services\DocumentAuditLogger;
use App\Services\DocumentVersionService;
use Filament\Actions\Action;
use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class VersionsRelationManager extends RelationManager
{
    protected static string $relationship = 'versions';

    protected static ?string $title = 'Version history';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('version_number')
            ->columns([
                TextColumn::make('version_number')
                    ->label('Version')
                    ->formatStateUsing(fn($state) => "v{$state}")
                    ->sortable(),
                TextColumn::make('original_filename')
                    ->label('File name'),
                TextColumn::make('size_bytes')
                    ->label('Size')
                    ->formatStateUsing(fn(DocumentVersion $record) => $record->humanSize()),
                TextColumn::make('uploader.name')
                    ->label('Uploaded by'),
                TextColumn::make('change_notes')
                    ->label('Notes')
                    ->limit(40),
                TextColumn::make('created_at')
                    ->label('Uploaded at')
                    ->since()
                    ->dateTimeTooltip('M d, Y h:i A')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                Action::make('download')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->visible(fn(DocumentVersion $record) => auth()->user()?->can('download', $record->document) ?? false)
                    ->action(function (DocumentVersion $record) {
                        $plaintext = app(DocumentVersionService::class)->decryptVersion($record);

                        app(DocumentAuditLogger::class)->log(
                            AuditEvent::Downloaded,
                            $record->document,
                            ['version_number' => $record->version_number, 'source' => 'version_history']
                        );

                        return response()->streamDownload(
                            fn() => print($plaintext),
                            $record->original_filename,
                            ['Content-Type' => $record->mime_type]
                        );
                    }),
            ])
            ->toolbarActions([
                //
            ])
            ->defaultSort('version_number', 'desc');
    }
}
