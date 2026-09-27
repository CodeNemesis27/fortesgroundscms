<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class PrescriptiveAnalytics extends Page
{
    protected string $view = 'filament.pages.prescriptive-analytics';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;
}
