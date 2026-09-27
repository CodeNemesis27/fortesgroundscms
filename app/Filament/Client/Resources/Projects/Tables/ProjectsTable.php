<?php

namespace App\Filament\Client\Resources\Projects\Tables;

use Filament\Actions\Action;
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
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ProjectsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $query->where('client_id', auth()->user()->client->id);
            })
            ->columns([
                TextColumn::make('project_code')
                    ->searchable(),
                TextColumn::make('name')
                    ->sortable()
                    ->weight(FontWeight::SemiBold)
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
                    ->sortable(),
                TextColumn::make('start_date')
                    ->date()
                    ->sortable()
                    ->icon(Heroicon::OutlinedCalendarDays),
            ])
            ->filters([
                //
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
