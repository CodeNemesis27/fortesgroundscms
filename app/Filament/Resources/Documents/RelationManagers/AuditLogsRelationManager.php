<?php

namespace App\Filament\Resources\Documents\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AuditLogsRelationManager extends RelationManager
{
    protected static string $relationship = 'auditLogs';

    protected static ?string $title = 'Audit trail';

    // Read-only by design: audit rows are immutable (see DocumentAuditLog
    // model), so no create/edit/delete affordances are exposed here.
    public function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('event')
            ->columns([
                TextColumn::make('created_at')
                    ->label('Timestamp')
                    ->dateTime('M d, Y h:i A')
                    ->sortable(),
                TextColumn::make('event')
                    ->badge()
                    ->formatStateUsing(fn($state) => str($state->value)->after('document.')->headline()),
                TextColumn::make('user.name')
                    ->label('Actor')
                    ->placeholder('System / anonymous'),
                TextColumn::make('ip_address')
                    ->label('IP address'),
                TextColumn::make('description')
                    ->wrap(),
            ])
            ->defaultSort('created_at', 'desc')
            ->headerActions([])
            ->recordActions([]);
    }
}
