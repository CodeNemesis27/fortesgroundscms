<?php

namespace App\Filament\Resources\ScheduleTasks\Pages;

use App\Filament\Resources\ScheduleTasks\ScheduleTaskResource;
use App\Models\ScheduleTask;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListScheduleTasks extends ListRecords
{
    protected static string $resource = ScheduleTaskResource::class;

    protected ?string $subheading = 'Manage task schedules, durations, and assigned members';

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make()
        ];
    }

    public function getTabs(): array
    {
        return [
            'All' => Tab::make()
                ->badge(ScheduleTask::query()->count()),
            'Task' => Tab::make()
                ->badge(ScheduleTask::query()->where('task_type', 'Task')->count())
                ->modifyQueryUsing(fn(Builder $query) => $query->where('task_type', 'Task')),
            'Milestone' => Tab::make()
                ->badge(ScheduleTask::query()->where('task_type', 'Milestone')->count())
                ->modifyQueryUsing(fn(Builder $query) => $query->where('task_type', 'Milestone')),
            'Summary' => Tab::make()
                ->badge(ScheduleTask::query()->where('task_type', 'Summary')->count())
                ->modifyQueryUsing(fn(Builder $query) => $query->where('task_type', 'Summary')),
        ];
    }
}
