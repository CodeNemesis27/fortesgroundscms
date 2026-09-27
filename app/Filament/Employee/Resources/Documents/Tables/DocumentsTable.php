<?php

namespace App\Filament\Employee\Resources\Documents\Tables;

use App\Enums\AuditEvent;
use App\Models\Document;
use App\Services\DocumentAuditLogger;
use App\Services\DocumentVersionService;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\ColumnManagerLayout;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class DocumentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('category')
                    ->badge(),
                TextColumn::make('currentVersion.version_number')
                    ->label('Version')
                    ->formatStateUsing(fn($state) => $state ? "v{$state}" : '—'),
                TextColumn::make('currentVersion.original_filename')
                    ->label('File name'),
                TextColumn::make('owner.name')
                    ->label('Owner'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state) => match ($state) {
                        'active' => 'success',
                        'archived' => 'gray',
                        default => 'secondary',
                    }),
                TextColumn::make('updated_at')
                    ->label('Last modified')
                    ->since()
                    ->dateTimeTooltip('M d, Y h:i A')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'archived' => 'Archived',
                    ]),
                TrashedFilter::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    Action::make('download')
                        ->label('Download')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->visible(fn(Document $record) => auth()->user()?->can('download', $record) ?? false)
                        ->action(function (Document $record) {
                            $version = $record->currentVersion;
                            $plaintext = app(DocumentVersionService::class)->decryptVersion($version);

                            app(DocumentAuditLogger::class)->log(
                                AuditEvent::Downloaded,
                                $record,
                                ['version_number' => $version->version_number]
                            );

                            return response()->streamDownload(
                                fn() => print($plaintext),
                                $version->original_filename,
                                ['Content-Type' => $version->mime_type]
                            );
                        }),

                    Action::make('toggleArchive')
                        ->label(fn(Document $record) => $record->status === 'archived' ? 'Unarchive' : 'Archive')
                        ->icon(fn(Document $record) => $record->status === 'archived' ? 'heroicon-o-arrow-up-tray' : 'heroicon-o-archive-box')
                        ->color('gray')
                        ->visible(fn(Document $record) => auth()->user()?->can('update', $record) ?? false)
                        ->action(function (Document $record) {
                            $newStatus = $record->status === 'archived' ? 'active' : 'archived';
                            $record->update(['status' => $newStatus]);

                            app(DocumentAuditLogger::class)->log(
                                AuditEvent::Updated,
                                $record,
                                ['field' => 'status', 'new_value' => $newStatus]
                            );
                        }),

                    DeleteAction::make()
                        ->visible(fn(Document $record) => auth()->user()?->can('delete', $record) ?? false)
                        ->after(fn(Document $record) => app(DocumentAuditLogger::class)->log(AuditEvent::Deleted, $record)),
                ])
            ])
            ->toolbarActions([
                //
            ])
            ->striped()
            ->columnManagerLayout(ColumnManagerLayout::Modal)
            ->columnManagerTriggerAction(
                fn(Action $action) => $action
                    ->slideOver()
                    ->label('Columns')
                    ->button()
            )
            ->filtersLayout(FiltersLayout::Modal)
            ->filtersTriggerAction(
                fn(Action $action) => $action
                    ->slideOver()
                    ->button()
                    ->label('Filters')
            )
            ->defaultSort('created_at', 'desc');
    }
}
