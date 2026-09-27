<?php

namespace App\Filament\Employee\Resources\Projects\Tables;

use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\Summarizers\Average;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\ColumnManagerLayout;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;

class ProjectsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('project_code')
                    ->searchable(),
                TextColumn::make('name')
                    ->sortable()
                    ->weight(FontWeight::SemiBold)
                    ->searchable(),
                TextColumn::make('client.user.name')
                    ->label('Client')
                    ->searchable(),
                TextColumn::make('project_type')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'New build' => 'info',
                        'Renovation' => 'success',
                        'Infrastructure' => 'warning',
                        'Maintenance' => 'danger',
                    })
                    ->icon(fn(string $state): string => match ($state) {
                        'New build' => 'heroicon-s-building-office',
                        'Renovation' => 'heroicon-s-wrench-screwdriver',
                        'Infrastructure' => 'heroicon-s-globe-alt',
                        'Maintenance' => 'heroicon-s-cog-6-tooth',
                    }),
                TextColumn::make('contract_value')
                    ->numeric()
                    ->money('PHP')
                    ->alignEnd()
                    ->sortable()
                    ->summarize(Sum::make()
                        ->money('PHP'))
                    ->summarize(
                        Average::make()
                            ->money('PHP')
                    ),
                TextColumn::make('start_date')
                    ->date()
                    ->sortable()
                    ->icon(Heroicon::OutlinedCalendarDays),
                TextColumn::make('expected_end_date')
                    ->date()
                    ->icon(Heroicon::OutlinedCalendarDays)
                    ->sortable(),
                TextColumn::make('actual_end_date')
                    ->date()
                    ->placeholder('-')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Bidding' => 'primary',
                        'Planning' => 'primary',
                        'In progress' => 'info',
                        'On hold' => 'warning',
                        'Substantially complete' => 'success',
                        'Cancelled' => 'danger',
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
                SelectFilter::make('project_type')
                    ->options([
                        'New build' => 'New build',
                        'Renovation' => 'Renovation',
                        'Infrastructure' => 'Infrastructure',
                        'Maintenance' => 'Maintenance',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
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
                Group::make('project_type')
                    ->collapsible(),
                Group::make('status')
                    ->collapsible(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
