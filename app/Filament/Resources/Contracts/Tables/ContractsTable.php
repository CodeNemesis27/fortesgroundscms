<?php

namespace App\Filament\Resources\Contracts\Tables;

use App\Filament\Resources\Projects\ProjectResource;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\ColumnManagerLayout;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ContractsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('contract_no')
                    ->label('Contract no.')
                    ->searchable(),
                TextColumn::make('project.name')
                    ->label('Project reference')
                    ->sortable()
                    ->color('info')
                    ->extraAttributes(['class' => 'hover:underline'])
                    ->icon(Heroicon::OutlinedArrowTopRightOnSquare)
                    ->iconColor('info')
                    ->iconPosition(IconPosition::After)
                    ->url(fn($record) => ProjectResource::getUrl('view', ['record' => $record->project]))
                    ->openUrlInNewTab(),
                TextColumn::make('contract_type')
                    ->badge(),
                TextColumn::make('original_value')
                    ->numeric()
                    ->money('PHP')
                    ->sortable(),
                TextColumn::make('current_value')
                    ->numeric()
                    ->money('PHP')
                    ->sortable(),
                TextColumn::make('retention_percentage')
                    ->numeric(decimalPlaces: 1)
                    ->suffix('%')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('payment_terms')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('effective_date')
                    ->date()
                    ->icon(Heroicon::OutlinedCalendarDays)
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Draft' => 'primary',
                        'Active' => 'info',
                        'Completed' => 'success',
                        'Terminated' => 'danger',
                    }),
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
                SelectFilter::make('contract_type')
                    ->options([
                        'Lump sum' => 'Lump sum',
                        'Unit price' => 'Unit price',
                        'Cost plus' => 'Cost plus',
                        'Time and material' => 'Time and material',
                    ]),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
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
