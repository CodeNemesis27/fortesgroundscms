<?php

namespace App\Filament\Resources\Documents\RelationManagers;

use App\Enums\AuditEvent;
use App\Models\DocumentVersion;
use App\Services\DocumentAuditLogger;
use App\Services\DocumentVersionService;
use Filament\Actions\Action;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Http\UploadedFile;

class VersionsRelationManager extends RelationManager
{
    protected static string $relationship = 'versions';

    protected static ?string $title = 'Version history';

    // Versions are only ever created through the upload/edit form or the
    // restore action below — never ad hoc from this table — so there is
    // no create form here.
    public function form(Schema $schema): Schema
    {
        return $schema->components([
            //
        ]);
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
            ->defaultSort('version_number', 'desc')
            ->headerActions([])
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

                Action::make('restore')
                    ->label('Restore as new version')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->color('gray')
                    ->requiresConfirmation()
                    ->modalDescription('This creates a new version containing the contents of this older version. The current version is never deleted — full history is always preserved.')
                    ->visible(fn(DocumentVersion $record) => auth()->user()?->can('update', $record->document) ?? false)
                    ->action(function (DocumentVersion $record) {
                        $service = app(DocumentVersionService::class);
                        $plaintext = $service->decryptVersion($record);

                        // Re-run the same encrypt/store/version pipeline on the
                        // older content, exactly as if it were freshly uploaded.
                        $tempPath = tempnam(sys_get_temp_dir(), 'dms_restore_');
                        file_put_contents($tempPath, $plaintext);

                        $uploadedFile = new UploadedFile(
                            $tempPath,
                            $record->original_filename,
                            $record->mime_type,
                            null,
                            true
                        );

                        $newVersion = $service->addVersion(
                            document: $record->document,
                            file: $uploadedFile,
                            uploader: auth()->user(),
                            notes: "Restored from version {$record->version_number}",
                        );

                        app(DocumentAuditLogger::class)->log(
                            AuditEvent::VersionRestored,
                            $record->document,
                            [
                                'restored_from_version' => $record->version_number,
                                'new_version' => $newVersion->version_number,
                            ]
                        );

                        @unlink($tempPath);
                    }),
            ]);
    }
}
