<?php

namespace App\Filament\Resources\ScheduleTasks\Schemas;

use App\Models\Employee;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class ScheduleTaskForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('project_id')
                    ->relationship('project', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                TextInput::make('name')
                    ->required(),
                Select::make('task_type')
                    ->native(false)
                    ->options([
                        'Task' => 'Task',
                        'Milestone' => 'Milestone',
                        'Summary' => 'Summary'
                    ])
                    ->required(),
                DatePicker::make('planned_start_date')
                    ->required(),
                DatePicker::make('planned_end_date')
                    ->required(),
                TextInput::make('duration')
                    ->required(),
                Select::make('assigned_employee_id')
                    ->label('Assigned employee')
                    ->required()
                    ->relationship(
                        name: 'employee',
                        modifyQueryUsing: fn(Builder $query) => $query->with('user'),
                    )
                    ->getOptionLabelFromRecordUsing(fn(Employee $record) => $record->user?->name),
            ]);
    }
}
