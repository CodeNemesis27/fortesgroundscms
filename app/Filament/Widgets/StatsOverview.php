<?php

namespace App\Filament\Widgets;

use App\Models\Client;
use App\Models\Contract;
use App\Models\Project;
use App\Models\PurchaseOrder;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Projects', Project::count())
                ->description('Total no. of projects')
                ->descriptionIcon('heroicon-s-building-office', IconPosition::Before)
                ->descriptionColor('success')
                ->url(route('filament.admin.resources.projects.index'))
                ->color('success'),
            Stat::make('Contracts', Contract::count())
                ->description('Total no. of contracts')
                ->descriptionIcon('heroicon-s-document-currency-dollar', IconPosition::Before)
                ->descriptionColor('info')
                ->color('info'),
            Stat::make('Purchase Orders', PurchaseOrder::count())
                ->description('Total no. of purchase orders')
                ->descriptionIcon('heroicon-s-shopping-cart', IconPosition::Before)
                ->descriptionColor('warning')
                ->color('warning'),
            Stat::make('Clients', Client::count())
                ->description('Total no. of clients')
                ->descriptionIcon('heroicon-s-user-group', IconPosition::Before)
                ->descriptionColor('danger')
                ->color('danger'),

        ];
    }
}
