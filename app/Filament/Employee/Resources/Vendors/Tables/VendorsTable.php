<?php

namespace App\Filament\Employee\Resources\Vendors\Tables;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\ColumnManagerLayout;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class VendorsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('vendor_code')
                    ->searchable(),
                TextColumn::make('name')
                    ->weight(FontWeight::SemiBold)
                    ->searchable(),
                TextColumn::make('vendor_type')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Material supplier' => 'info',
                        'Service provider' => 'warning',
                        'Equipment rental' => 'danger',
                    })
                    ->icon(fn(string $state): string => match ($state) {
                        'Material supplier' => 'heroicon-s-cube',
                        'Service provider' => 'heroicon-s-wrench-screwdriver',
                        'Equipment rental' => 'heroicon-s-truck',
                    }),
                TextColumn::make('contact_person')
                    ->searchable(),
                TextColumn::make('contact_no')
                    ->label('Contact no.')
                    ->icon(Heroicon::Phone)
                    ->searchable(),
                TextColumn::make('email')
                    ->icon(Heroicon::Envelope)
                    ->label('Email address')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->since()
                    ->dateTimeTooltip('M d, Y h:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->since()
                    ->dateTimeTooltip('M d, Y h:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->since()

                    ->dateTimeTooltip('M d, Y h:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make()
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
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
