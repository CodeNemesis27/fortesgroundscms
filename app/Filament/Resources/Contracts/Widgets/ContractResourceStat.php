<?php

namespace App\Filament\Resources\Contracts\Widgets;

use App\Models\Contract;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ContractResourceStat extends StatsOverviewWidget
{
    /**
     * Statuses that mean a contract has actually been signed/executed,
     * as opposed to still sitting in Draft.
     */
    private array $executedStatuses = ['Active', 'Completed', 'Terminated'];

    protected function getStats(): array
    {
        return [
            $this->contractsThisMonthStat(),
            $this->changeOrderValueStat(),
            $this->retentionHeldStat(),
        ];
    }

    /**
     * Count of contracts created this calendar month, compared to last month.
     */
    private function contractsThisMonthStat(): Stat
    {
        $thisMonth = Contract::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $lastMonth = Contract::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();

        [$description, $color, $icon] = $this->compareCounts($thisMonth, $lastMonth);

        return Stat::make('Contracts This Month', $thisMonth)
            ->description($description)
            ->descriptionIcon($icon, IconPosition::Before)
            ->color($color);
    }

    /**
     * Total scope growth (or shrinkage) across executed contracts:
     * SUM(current_value - original_value).
     */
    private function changeOrderValueStat(): Stat
    {
        $executedQuery = fn() => Contract::whereIn('status', $this->executedStatuses);

        $changeOrderValue = (float) ($executedQuery()->selectRaw('SUM(current_value - original_value) as diff')->value('diff') ?? 0);
        $revisedCount = $executedQuery()->whereColumn('current_value', '!=', 'original_value')->count();

        $description = $revisedCount > 0
            ? $revisedCount . ' contract' . ($revisedCount === 1 ? '' : 's') . ' revised from original scope'
            : 'No scope changes yet';

        $color = match (true) {
            $changeOrderValue > 0 => 'warning',
            $changeOrderValue < 0 => 'success',
            default => 'gray',
        };

        $icon = match (true) {
            $changeOrderValue > 0 => 'heroicon-m-arrow-trending-up',
            $changeOrderValue < 0 => 'heroicon-m-arrow-trending-down',
            default => 'heroicon-m-minus',
        };

        $formattedValue = ($changeOrderValue >= 0 ? '+' : '-') . '₱' . number_format(abs($changeOrderValue), 2);

        return Stat::make('Change Order Value', $formattedValue)
            ->description($description)
            ->descriptionIcon($icon, IconPosition::Before)
            ->color($color);
    }

    /**
     * Total ₱ currently withheld as retention on Active contracts:
     * SUM(current_value * retention_percentage / 100).
     */
    private function retentionHeldStat(): Stat
    {
        $activeQuery = fn() => Contract::where('status', 'Active');

        $retentionHeld = (float) ($activeQuery()->selectRaw('SUM(current_value * retention_percentage / 100) as retention')->value('retention') ?? 0);
        $activeCount = $activeQuery()->count();

        $description = $activeCount > 0
            ? 'Across ' . $activeCount . ' active contract' . ($activeCount === 1 ? '' : 's')
            : 'No active contracts';

        return Stat::make('Retention Held', '₱' . number_format($retentionHeld, 2))
            ->description($description)
            ->descriptionIcon('heroicon-m-lock-closed', IconPosition::Before)
            ->color($activeCount > 0 ? 'info' : 'gray');
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
