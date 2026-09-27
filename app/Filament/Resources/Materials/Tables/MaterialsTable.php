<?php

namespace App\Filament\Resources\Materials\Tables;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\ColumnManagerLayout;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class MaterialsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->placeholder('-'),
                TextColumn::make('name')
                    ->searchable()
                    ->weight(FontWeight::SemiBold)
                    ->sortable(),
                TextColumn::make('category')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('price')
                    ->money('PHP')
                    ->sortable(),
                TextColumn::make('unit')
                    ->searchable(),
                TextColumn::make('vendor.name'),
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
                SelectFilter::make('category')
                    ->options([
                        'Concrete and Cement Products' => 'Concrete and Cement Products',
                        'Doors, Windows and Glazing' => 'Doors, Windows and Glazing',
                        'Electrical Materials' => 'Electrical Materials',
                        'Finishes' => 'Finishes',
                        'Lumber and Carpentry' => 'Lumber and Carpentry',
                        'Masonry and Aggregates' => 'Masonry and Aggregates',
                        'Plumbing Materials' => 'Plumbing Materials',
                        'Roofing and Waterproofing' => 'Roofing and Waterproofing',
                        'Site and Earthworks'
                    ]),
                TrashedFilter::make()
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make(),
                    DeleteAction::make(),
                ])
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
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
            ->defaultSort('created_at', 'asc');
    }
}
