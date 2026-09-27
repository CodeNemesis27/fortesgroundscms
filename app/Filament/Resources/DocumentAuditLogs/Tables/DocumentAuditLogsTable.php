<?php

namespace App\Filament\Resources\DocumentAuditLogs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DocumentAuditLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('document.title')
                    ->searchable()
                    ->weight(FontWeight::SemiBold),
                TextColumn::make('user.name')
                    ->searchable(),
                TextColumn::make('event')
                    ->badge()
                    ->searchable(),
                TextColumn::make('description')
                    ->searchable(),
                TextColumn::make('ip_address')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordAction(null)
            ->recordUrl(null)
            ->recordActions([
                //
            ])
            ->toolbarActions([
                //
            ]);
    }
}
