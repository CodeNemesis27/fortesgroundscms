<?php

namespace App\Filament\Widgets;

use App\Models\Project;
use Filament\Actions\BulkActionGroup;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestProjects extends TableWidget
{
    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 5;

    public function table(Table $table): Table
    {
        return $table
            ->query(fn(): Builder => Project::query())
            ->defaultPaginationPageOption(5)
            ->columns([
                TextColumn::make('project_code'),
                TextColumn::make('name'),
                TextColumn::make('project_type')
                    ->badge(),
                TextColumn::make('start_date')
                    ->date()
                    ->icon(Heroicon::OutlinedCalendarDays),
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
                    ->date()
                    ->icon(Heroicon::OutlinedCalendarDays)
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                //
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
