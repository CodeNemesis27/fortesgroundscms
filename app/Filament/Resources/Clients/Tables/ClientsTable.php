<?php

namespace App\Filament\Resources\Clients\Tables;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\ColumnManagerLayout;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;

class ClientsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('user.avatar')
                    ->circular()
                    ->disk('public')
                    ->grow(false)
                    ->label('Avatar')
                    ->default(
                        fn($record) => 'https://ui-avatars.com/api/?size=128'
                            . '&name=' . urlencode($record->user->name) . '&background=000000&color=ffffff'
                    ),
                TextColumn::make('user.name')
                    ->label('Name')
                    ->weight(FontWeight::SemiBold)
                    ->searchable(),
                TextColumn::make('contact_no')
                    ->icon(Heroicon::Phone)
                    ->label('Contact no.')
                    ->searchable(),
                TextColumn::make('user.email')
                    ->label('Email address')
                    ->icon(Heroicon::Envelope)
                    ->searchable(),
                TextColumn::make('address'),
                TextColumn::make('created_at')
                    ->label('Registered at')
                    ->sortable()
                    ->since()
                    ->dateTimeTooltip('M d, Y h:i A'),
                TextColumn::make('updated_at')
                    ->since()
                    ->sortable()
                    ->dateTimeTooltip('M d, Y h:i A')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make()
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
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
