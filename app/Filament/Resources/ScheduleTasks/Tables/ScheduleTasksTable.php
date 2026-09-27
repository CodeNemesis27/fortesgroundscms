<?php

namespace App\Filament\Resources\ScheduleTasks\Tables;

use App\Models\Employee;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\ColumnManagerLayout;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ScheduleTasksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->weight(FontWeight::SemiBold)
                    ->searchable(),
                TextColumn::make('project.name')
                    ->searchable()
                    ->label('Project reference'),
                TextColumn::make('task_type')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Task' => 'info',
                        'Milestone' => 'success',
                        'Summary' => 'warning',
                    })
                    ->icon(fn(string $state): string => match ($state) {
                        'Task' => 'heroicon-s-document-text',
                        'Milestone' => 'heroicon-s-flag',
                        'Summary' => 'heroicon-s-list-bullet',
                    }),
                TextColumn::make('planned_start_date')
                    ->date()
                    ->icon(Heroicon::OutlinedCalendarDays)
                    ->sortable(),
                TextColumn::make('planned_end_date')
                    ->date()
                    ->icon(Heroicon::OutlinedCalendarDays)
                    ->sortable(),
                TextColumn::make('actual_start_date')
                    ->date()
                    ->icon(Heroicon::OutlinedCalendarDays)
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('actual_end_date')
                    ->date()
                    ->icon(Heroicon::OutlinedCalendarDays)
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('duration')
                    ->searchable(),
                TextColumn::make('employee.user.name')
                    ->label('Assigned employee'),
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
                TrashedFilter::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make()
                        ->modalHeading('Edit schedule task')
                        ->icon('heroicon-o-clipboard-document-list')
                        ->modalDescription('This will update the schedule task for this project')
                        ->modalWidth('xl')
                        ->schema([
                            Section::make()
                                ->schema([
                                    TextInput::make('name')
                                        ->placeholder('e.g. Interior Partition')
                                        ->required()
                                        ->columnSpanFull(),
                                    Select::make('task_type')
                                        ->native(false)
                                        ->columnSpanFull()
                                        ->options([
                                            'Task' => 'Task',
                                            'Milestone' => 'Milestone',
                                            'Summary' => 'Summary',
                                        ])
                                        ->required(),
                                    DatePicker::make('planned_start_date')
                                        ->required()
                                        ->columnSpan(1),
                                    DatePicker::make('planned_end_date')
                                        ->required()
                                        ->columnSpan(1),
                                    DatePicker::make('actual_start_date')
                                        ->required()
                                        ->columnSpan(1),
                                    DatePicker::make('actual_end_date')
                                        ->required()
                                        ->columnSpan(1),
                                    TextInput::make('duration')
                                        ->columnSpanFull()
                                        ->required()
                                        ->placeholder('10 days'),
                                    Select::make('assigned_employee_id')
                                        ->required()
                                        ->columnSpanFull()
                                        ->label('Assigned employee')
                                        ->relationship(
                                            name: 'employee',
                                            modifyQueryUsing: fn(Builder $query) => $query->with('user'),
                                        )
                                        ->getOptionLabelFromRecordUsing(fn(Employee $record) => $record->user?->name)
                                        ->searchable()
                                        ->preload()

                                ])->columns(2)
                        ]),
                    DeleteAction::make()
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
            ->defaultSort('created_at', 'desc');
    }
}
