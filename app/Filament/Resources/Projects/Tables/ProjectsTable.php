<?php

namespace App\Filament\Resources\Projects\Tables;

use App\Models\Employee;
use App\Models\Project;
use App\Models\ScheduleTask;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Support\Enums\FontFamily;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\Summarizers\Average;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\ColumnManagerLayout;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

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
                TrashedFilter::make()
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                    Action::make('create_schedule_task')
                        ->label('Create schedule task')
                        ->icon('heroicon-o-clipboard-document-list')
                        ->modalHeading('Create new schedule task')

                        ->modalDescription('This will create a new schedule task for this project')
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
                        ])
                        ->action(function (Project $record, array $data): void {
                            DB::transaction(function () use ($record, $data) {

                                ScheduleTask::create([
                                    'name' => $data['name'],
                                    'project_id' => $record->id,
                                    'task_type' => $data['task_type'],
                                    'planned_start_date' => $data['planned_start_date'],
                                    'planned_end_date' => $data['planned_end_date'],
                                    'duration' => $data['duration'],
                                    'assigned_employee_id' => $data['assigned_employee_id'],
                                ]);
                            });

                            Notification::make()
                                ->title('New schedule task added!')
                                ->body("The task has now been added successfully")
                                ->success()
                                ->send();
                        }),
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
            ->groups([
                Group::make('project_type')
                    ->collapsible(),
                Group::make('status')
                    ->collapsible(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
