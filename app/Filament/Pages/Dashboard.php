<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\ClientsChart;
use App\Filament\Widgets\ContractValueChart;
use App\Filament\Widgets\LatestProjects;
use App\Filament\Widgets\ProjectChart;
use App\Filament\Widgets\ProjectTypeChart;
use App\Filament\Widgets\StatsOverview;
use BackedEnum;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class Dashboard extends BaseDashboard
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::RectangleGroup;

    protected static ?string $navigationLabel = 'Dashboard';

    protected static ?string $title = 'Welcome to FortesGrounds Dashboard';

    public function getWidgets(): array
    {
        return [
            StatsOverview::class,
            ProjectChart::class,
            ContractValueChart::class,
            ClientsChart::class,
            ProjectTypeChart::class,
            LatestProjects::class
        ];
    }
}
