<?php

namespace App\Filament\Widgets;

use App\Models\Contract;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class ContractValueChart extends ChartWidget
{
    protected ?string $heading = 'Original vs. Current Contract Value Trend';

    protected static ?int $sort = 2;

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        /*
         * Get the earliest and latest contract creation dates.
         */
        $earliestDate = Contract::min('created_at');
        $latestDate = Contract::max('created_at');

        /*
         * Return an empty chart if there are no contracts.
         */
        if (!$earliestDate || !$latestDate) {
            return [
                'datasets' => [
                    [
                        'label' => 'Current Value',
                        'data' => [],
                        'borderColor' => '#3b82f6',
                        'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                        'fill' => 'start',
                    ],
                    [
                        'label' => 'Original Value',
                        'data' => [],
                        'borderColor' => '#9ca3af',
                        'backgroundColor' => 'rgba(156, 163, 175, 0.05)',
                    ],
                ],
                'labels' => [],
            ];
        }

        /*
         * Convert dates to the beginning/end of their months.
         */
        $startDate = Carbon::parse($earliestDate)->startOfMonth();
        $endDate = Carbon::parse($latestDate)->startOfMonth();

        /*
         * Generate every month between the earliest
         * and latest contract creation date.
         */
        $months = collect();

        $currentMonth = $startDate->copy();

        while ($currentMonth <= $endDate) {
            $months->push($currentMonth->copy());

            $currentMonth->addMonth();
        }

        /*
         * Get contracts within the date range.
         */
        $contracts = Contract::query()
            ->whereBetween('created_at', [
                $startDate,
                $endDate->copy()->endOfMonth(),
            ])
            ->get();

        /*
         * Group contracts by year-month.
         */
        $contractsByMonth = $contracts->groupBy(
            fn(Contract $contract): string =>
            $contract->created_at?->format('Y-m') ?? ''
        );

        $labels = [];
        $originalData = [];
        $currentData = [];

        /*
         * Build the chart data month by month.
         */
        foreach ($months as $month) {
            $monthKey = $month->format('Y-m');

            $monthContracts = $contractsByMonth->get(
                $monthKey,
                collect()
            );

            $labels[] = $month->format('M Y');

            /*
             * Total original contract value
             */
            $originalData[] = $monthContracts->sum(
                fn(Contract $contract) =>
                (float) $contract->original_value
            );

            /*
             * Total current contract value
             */
            $currentData[] = $monthContracts->sum(
                fn(Contract $contract) =>
                (float) $contract->current_value
            );
        }

        return [
            'datasets' => [
                [
                    'label' => 'Current Value',
                    'data' => $currentData,
                    'borderColor' => '#f59e0b',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.1)',
                    'fill' => 'start',
                    'tension' => 0.3,
                ],
                [
                    'label' => 'Original Value',
                    'data' => $originalData,
                    'borderColor' => '#9ca3af',
                    'backgroundColor' => 'rgba(156, 163, 175, 0.05)',
                    'borderDash' => [5, 5],
                    'fill' => false,
                    'tension' => 0.3,
                ],
            ],

            'labels' => $labels,
        ];
    }
}
