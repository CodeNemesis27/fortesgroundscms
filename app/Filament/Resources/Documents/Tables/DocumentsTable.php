<?php

namespace App\Filament\Resources\Documents\Tables;

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
use Filament\Actions\ViewAction;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\Layout\Grid;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\ColumnManagerLayout;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;

class DocumentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // TextColumn::make('title')
                //     ->searchable()
                //     ->sortable()
                //     ->weight('bold'),
                // TextColumn::make('category')
                //     ->badge(),
                // TextColumn::make('currentVersion.version_number')
                //     ->label('Version')
                //     ->formatStateUsing(fn($state) => $state ? "v{$state}" : '—'),
                // TextColumn::make('currentVersion.original_filename')
                //     ->label('File name'),
                // TextColumn::make('owner.name')
                //     ->label('Owner'),
                // TextColumn::make('status')
                //     ->badge()
                //     ->color(fn(string $state) => match ($state) {
                //         'active' => 'success',
                //         'archived' => 'gray',
                //         default => 'secondary',
                //     }),
                // TextColumn::make('updated_at')
                //     ->label('Last modified')
                //     ->since()
                //     ->dateTimeTooltip('M d, Y h:i A')
                //     ->sortable(),
                Grid::make()
                    ->columns(1)
                    ->schema([
                        Stack::make([
                            Split::make([
                                TextColumn::make('title')
                                    ->sortable()
                                    ->searchable()
                                    ->columnSpanFull()
                                    ->weight(FontWeight::Bold),

                            ]),

                            Stack::make([
                                TextColumn::make('currentVersion.original_filename')
                                    ->color('gray')
                                    ->sortable()
                                    ->icon(function (?string $state): string|Heroicon|null {
                                        if (!$state) {
                                            return null;
                                        }

                                        $extension = strtolower(pathinfo($state, PATHINFO_EXTENSION));

                                        return match ($extension) {
                                            'pdf' => Heroicon::OutlinedDocumentText,
                                            'jpg', 'jpeg', 'png' => Heroicon::OutlinedPhoto,
                                            'xlsx', 'xls', 'csv' => Heroicon::OutlinedTableCells,
                                            default => Heroicon::OutlinedDocument,
                                        };
                                    }),

                                Stack::make([
                                    Split::make([
                                        TextColumn::make('versions.version_number')
                                            ->sortable()
                                            ->color('gray')
                                            ->size(TextSize::ExtraSmall)
                                            ->formatStateUsing(fn($state) => "Current version: v{$state}"),
                                        TextColumn::make('category')
                                            ->sortable()
                                            ->color('gray')
                                            ->size(TextSize::ExtraSmall)
                                            ->formatStateUsing(fn($state) => "Category: {$state}"),
                                    ]),

                                    Split::make([
                                        TextColumn::make('created_at')
                                            ->size(TextSize::ExtraSmall)
                                            ->sortable()
                                            ->color('gray')
                                            ->formatStateUsing(fn($state) => $state
                                                ? 'Created at: ' . \Carbon\Carbon::parse($state)->format('M d, Y')
                                                : null),
                                        TextColumn::make('updated_at')
                                            ->size(TextSize::ExtraSmall)
                                            ->sortable()
                                            ->color('gray')
                                            ->formatStateUsing(fn($state) => $state
                                                ? 'Last modified: ' . \Carbon\Carbon::parse($state)->format('M d, Y')
                                                : null),
                                    ]),

                                ])->space(1),
                                // 1 space between category and owner.name
                                Split::make([
                                    TextColumn::make('status')
                                        ->badge()
                                        ->alignStart()
                                        ->color(fn(string $state) => match ($state) {
                                            'active' => 'success',
                                            'archived' => 'gray',
                                            default => 'secondary',
                                        }),
                                    TextColumn::make('owner.name')
                                        ->icon(Heroicon::UserCircle)
                                        ->badge()
                                        ->tooltip('Author')
                                        ->alignEnd()
                                        ->size(TextSize::Small),
                                ])

                            ])->space(2), // 2 spaces between filename and category
                        ])
                            ->grow(),
                    ])
            ])
            ->contentGrid([
                'md' => 2,
                'xl' => 3,
            ])
            ->paginated(false)
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'archived' => 'Archived',
                    ]),
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make()
                    ->tooltip('View')
                    ->hiddenLabel(),
                EditAction::make()
                    ->tooltip('Edit')
                    ->hiddenLabel(),
                Action::make('download')
                    ->hiddenLabel()
                    ->color('info')
                    ->tooltip('Download')
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
                    ->hiddenLabel()
                    ->tooltip('Archive')
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
                    ->hiddenLabel()
                    ->tooltip('Delete')
                    ->visible(fn(Document $record) => auth()->user()?->can('delete', $record) ?? false)
                    ->after(fn(Document $record) => app(DocumentAuditLogger::class)->log(AuditEvent::Deleted, $record)),

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
            ->groups([
                Group::make('status')
                    ->collapsible(),
                Group::make('category')
                    ->collapsible(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
