<?php

namespace App\Filament\Widgets;

use App\Models\Client;
use Filament\Widgets\ChartWidget;

class ClientsChart extends ChartWidget
{
    protected ?string $heading = 'Clients by Month';

    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $firstClient = Client::query()->oldest('created_at')->first();
        $lastClient = Client::query()->latest('created_at')->first();

        // No clients yet — return an empty chart instead of erroring out.
        if (! $firstClient || ! $lastClient) {
            return [
                'datasets' => [
                    ['label' => 'Clients', 'data' => []],
                ],
                'labels' => [],
            ];
        }

        // One bar per calendar month, starting on the first client's
        // created_at month and ending on the last client's created_at month.
        $cursor = $firstClient->created_at->copy()->startOfMonth();
        $end = $lastClient->created_at->copy()->startOfMonth();

        $counts = Client::query()
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as ym, count(*) as total")
            ->groupBy('ym')
            ->pluck('total', 'ym');

        $labels = [];
        $data = [];

        while ($cursor->lte($end)) {
            $labels[] = $cursor->format('M Y');
            $data[] = (int) ($counts->get($cursor->format('Y-m')) ?? 0);
            $cursor->addMonth();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Clients',
                    'data' => $data,
                    'backgroundColor' => '#3b82f6',
                    'borderRadius' => 4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
