<?php

namespace App\Filament\Resources\Projects\Pages;

use App\Filament\Resources\Projects\ProjectResource;
use App\Filament\Resources\Projects\Widgets\ProjectResourceStat;
use App\Models\Project;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListProjects extends ListRecords
{
    protected static string $resource = ProjectResource::class;

    protected ?string $subheading = 'Manage project timelines, sites, and contract values across all clients';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'All' => Tab::make()
                ->badge(Project::query()->count()),
            'Bidding' => Tab::make()
                ->badge(Project::query()->where('status', 'Bidding')->count())
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'Bidding')),
            'Planning' => Tab::make()
                ->badge(Project::query()->where('status', 'Planning')->count())
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'Planning')),
            'In progress' => Tab::make()
                ->badge(Project::query()->where('status', 'In progress')->count())
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'In progress')),
            'On hold' => Tab::make()
                ->badge(Project::query()->where('status', 'On hold')->count())
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'On hold')),
            'Substantially complete' => Tab::make()
                ->badge(Project::query()->where('status', 'Substantially complete')->count())
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'Substantially complete')),
            'Cancelled' => Tab::make()
                ->badge(Project::query()->where('status', 'Cancelled')->count())
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'Cancelled')),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            ProjectResourceStat::class,
        ];
    }
}
