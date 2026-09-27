<?php

namespace App\Filament\Client\Resources\Documents\RelationManagers;

use App\Enums\AuditEvent;
use App\Enums\DocumentPermission;
use App\Models\DocumentShare;
use App\Services\DocumentAuditLogger;
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
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SharesRelationManager extends RelationManager
{
    protected static string $relationship = 'shares';

    protected static ?string $title = 'Sharing & access';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('sharedWith.name')
                    ->label('Shared with')
                    ->placeholder('External link'),
                TextColumn::make('permission')
                    ->badge()
                    ->formatStateUsing(fn(DocumentPermission $state) => $state->label()),
                TextColumn::make('sharedBy.name')->label('Shared by'),
                IconColumn::make('is_active')
                    ->label('Active')
                    ->state(fn(DocumentShare $record) => $record->isUsable())
                    ->boolean(),
                TextColumn::make('expires_at')
                    ->since()
                    ->dateTimeTooltip('M d, Y h:i A')
                    ->placeholder('Never'),
                TextColumn::make('download_count')
                    ->label('Downloads'),
                TextColumn::make('last_accessed_at')
                    ->since()
                    ->dateTimeTooltip('M d, Y h:i A')
                    ->placeholder('Never')
                    ->label('Last accessed'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                Action::make('copyLink')
                    ->label('Open link')
                    ->icon('heroicon-o-link')
                    ->visible(fn(DocumentShare $record) => $record->token !== null && $record->isUsable())
                    ->url(fn(DocumentShare $record) => url("/share/{$record->token}"))
                    ->openUrlInNewTab(),

                Action::make('revoke')
                    ->label('Revoke')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn(DocumentShare $record) => $record->isUsable())
                    ->action(function (DocumentShare $record) {
                        $record->update(['revoked_at' => now()]);

                        app(DocumentAuditLogger::class)->log(
                            AuditEvent::ShareRevoked,
                            $record->document,
                            ['share_id' => $record->id]
                        );
                    }),
            ])
            ->toolbarActions([
                //
            ]);
    }
}
