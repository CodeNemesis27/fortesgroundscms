<?php

namespace App\Filament\Employee\Resources\Estimates\Pages;

use App\Filament\Employee\Resources\Estimates\EstimateResource;
use App\Models\Estimate;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListEstimates extends ListRecords
{
    protected static string $resource = EstimateResource::class;

    protected ?string $subheading = 'Manage and track project cost estimates and their approval status';

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
                ->badge(Estimate::query()->count()),
            'Draft' => Tab::make()
                ->badge(Estimate::query()->where('status', 'Draft')->count())
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'Draft')),
            'Submitted' => Tab::make()
                ->badge(Estimate::query()->where('status', 'Submitted')->count())
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'Submitted')),
            'Approved' => Tab::make()
                ->badge(Estimate::query()->where('status', 'Approved')->count())
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'Approved')),
            'Superseded' => Tab::make()
                ->badge(Estimate::query()->where('status', 'Superseded')->count())
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'Superseded')),
        ];
    }
}
