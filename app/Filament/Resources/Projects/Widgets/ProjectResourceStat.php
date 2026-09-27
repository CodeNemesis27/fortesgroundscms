<?php

namespace App\Filament\Resources\Projects\Widgets;

use App\Models\Project;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ProjectResourceStat extends StatsOverviewWidget
{
    /**
     * Statuses that mean a project is done and should be excluded from
     * "at risk" style calculations like Overdue / Ending Soon.
     */
    private array $closedStatuses = ['Substantially complete', 'Cancelled'];

    protected function getStats(): array
    {
        return [
            $this->projectsThisMonthStat(),
            $this->overdueProjectsStat(),
            $this->endingSoonStat(),
        ];
    }

    /**
     * Count of projects created this calendar month, compared to last month.
     */
    private function projectsThisMonthStat(): Stat
    {
        $thisMonth = Project::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $lastMonth = Project::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();

        [$description, $color, $icon] = $this->compareCounts($thisMonth, $lastMonth);

        return Stat::make('Projects This Month', $thisMonth)
            ->description($description)
            ->descriptionIcon($icon, IconPosition::Before)
            ->color($color);
    }

    /**
     * Projects whose expected_end_date has passed with no actual_end_date
     * recorded and no closed status — i.e. genuinely running late.
     */
    private function overdueProjectsStat(): Stat
    {
        $query = fn() => Project::whereDate('expected_end_date', '<', now())
            ->whereNull('actual_end_date')
            ->whereNotIn('status', $this->closedStatuses);

        $overdueCount = $query()->count();
        $overdueValue = $query()->sum('contract_value');

        $description = $overdueCount > 0
            ? '₱' . number_format($overdueValue, 2) . ' in contract value at risk'
            : 'No overdue projects';

        return Stat::make('Overdue Projects', $overdueCount)
            ->description($description)
            ->descriptionIcon(
                $overdueCount > 0 ? 'heroicon-m-exclamation-triangle' : 'heroicon-m-check-circle',
                IconPosition::Before
            )
            ->color($overdueCount > 0 ? 'danger' : 'success');
    }

    /**
     * Projects expected to finish within the next 30 days that haven't
     * actually finished yet — an early warning before they become overdue.
     */
    private function endingSoonStat(): Stat
    {
        $baseQuery = fn(int $days) => Project::whereBetween('expected_end_date', [
            now()->toDateString(),
            now()->addDays($days)->toDateString(),
        ])
            ->whereNull('actual_end_date')
            ->whereNotIn('status', $this->closedStatuses);

        $next30Days = $baseQuery(30)->count();
        $next7Days = $baseQuery(7)->count();

        $description = ($next7Days > 0 ? $next7Days . ' within 7 days' : 'None within 7 days') . ' · next 30 days shown';

        return Stat::make('Ending Soon', $next30Days)
            ->description($description)
            ->descriptionIcon('heroicon-m-clock', IconPosition::Before)
            ->color($next7Days > 0 ? 'warning' : 'gray');
    }

    /**
     * Shared month-over-month comparison logic: description text, color,
     * and icon based on the direction of change.
     *
     * @return array{0: string, 1: string, 2: string}
     */
    private function compareCounts(int $current, int $previous): array
    {
        if ($current === 0 && $previous === 0) {
            return ['No activity this month', 'gray', 'heroicon-m-minus'];
        }

        if ($previous === 0) {
            return [$current . ' new this month', 'success', 'heroicon-m-arrow-trending-up'];
        }

        $change = (($current - $previous) / $previous) * 100;
        $isIncrease = $change >= 0;

        $description = number_format(abs($change), 1) . '% ' . ($isIncrease ? 'increase' : 'decrease') . ' vs last month';

        return [
            $description,
            $isIncrease ? 'success' : 'danger',
            $isIncrease ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down',
        ];
    }
}
