<?php

namespace App\Filament\Resources\Employees\Tables;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\ColumnManagerLayout;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class EmployeesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('employee_code')
                    ->searchable(),
                TextColumn::make('user.name')
                    ->label('Name')
                    ->weight(FontWeight::SemiBold),
                TextColumn::make('position')
                    ->searchable(),
                TextColumn::make('employee_type')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Regular' => 'info',
                        'Contractual' => 'warning',
                        'Project based' => 'danger',
                    })
                    ->icon(fn(string $state): string => match ($state) {
                        'Regular' => 'heroicon-s-sparkles',
                        'Contractual' => 'heroicon-s-document-text',
                        'Project based' => 'heroicon-s-briefcase',
                    }),
                TextColumn::make('date_hired')
                    ->date()
                    ->sortable(),
                TextColumn::make('date_terminated')
                    ->date()
                    ->placeholder('-')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('basic_rate')
                    ->numeric()
                    ->money('PHP'),
                TextColumn::make('rate_type')
                    ->badge(),
                TextColumn::make('contact_no')
                    ->label('Contact no.')
                    ->icon(Heroicon::Phone)
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Active' => 'info',
                        'On-leave' => 'warning',
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
                SelectFilter::make('employee_type')
                    ->options([
                        'Regular' => 'Regular',
                        'Contractual' => 'Contractual',
                        'Project based' => 'Project based',
                    ]),
                SelectFilter::make('rate_type')
                    ->options([
                        'Hourly' => 'Hourly',
                        'Daily' => 'Daily',
                        'Monthly' => 'Monthly',
                    ]),
                SelectFilter::make('status')
                    ->options([
                        'Active' => 'Active',
                        'On-leave' => 'On-leave',
                        'Terminated' => 'Terminated',
                    ]),
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
