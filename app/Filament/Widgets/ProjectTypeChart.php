<?php

namespace App\Filament\Widgets;

use App\Models\Project;
use Filament\Widgets\ChartWidget;

class ProjectTypeChart extends ChartWidget
{
    protected ?string $heading = 'Project Type Segment';

    protected static ?int $sort = 4;

    protected function getData(): array
    {
        // Keep this in the same order as the project_type enum so the
        // legend and slice colors stay consistent as new statuses appear.
        $types = ['New build', 'Renovation', 'Infrastructure', 'Maintenance'];

        $counts = Project::query()
            ->selectRaw('project_type, count(*) as total')
            ->groupBy('project_type')
            ->pluck('total', 'project_type');

        $data = collect($types)->map(fn(string $type) => $counts->get($type, 0));

        return [
            'datasets' => [
                [
                    'label' => 'Projects',
                    'data' => $data->values()->all(),
                    'backgroundColor' => [
                        '#3b82f6', // blue    — New build
                        '#f59e0b', // amber   — Renovation
                        '#10b981', // emerald — Infrastructure
                        '#8b5cf6', // violet  — Maintenance
                    ],
                    'borderWidth' => 0,
                ],
            ],
            'labels' => $types,
        ];
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
