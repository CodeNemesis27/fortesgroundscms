<?php

namespace App\Filament\Widgets;

use App\Models\Project;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Illuminate\Support\Carbon;

class ProjectChart extends ChartWidget
{
    protected ?string $heading = 'Project Contract Value Trend';

    protected static ?int $sort = 1;

    protected function getData(): array
    {
        $earliestDate = Project::min('created_at');
        $latestDate = Project::max('created_at');

        if (!$earliestDate || !$latestDate) {
            return [
                'datasets' => [
                    [
                        'label' => 'Contract Value',
                        'data' => [],
                        'fill' => true,
                    ],
                ],
                'labels' => [],
            ];
        }

        $startDate = Carbon::parse($earliestDate)->startOfMonth();
        $endDate = Carbon::parse($latestDate)->endOfMonth();

        $trend = Trend::query(Project::query())
            ->between(
                start: $startDate,
                end: $endDate
            )
            ->dateColumn('created_at')
            ->perMonth()
            ->sum('contract_value');

        return [
            'datasets' => [
                [
                    'label' => 'Contract Value',
                    'data' => $trend->map(
                        fn($value) => $value->aggregate
                    )->toArray(),

                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',

                    'fill' => true,
                    'tension' => 0.3,
                ],
            ],

            'labels' => $trend->map(
                fn($value) => Carbon::parse($value->date)->format('M Y')
            )->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
