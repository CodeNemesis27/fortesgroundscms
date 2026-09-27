<?php

namespace App\Filament\Resources\PurchaseOrders\Widgets;

use App\Models\PurchaseOrder;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PurchaseOrderResourceStat extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            $this->purchaseOrdersThisMonthStat(),
            $this->overdueDeliveriesStat(),
            $this->onTimeDeliveryRateStat(),
        ];
    }

    /**
     * Count of purchase orders created this calendar month, compared to
     * last month.
     */
    private function purchaseOrdersThisMonthStat(): Stat
    {
        $thisMonth = PurchaseOrder::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $lastMonth = PurchaseOrder::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();

        [$description, $color, $icon] = $this->compareCounts($thisMonth, $lastMonth);

        return Stat::make('Purchase Orders This Month', $thisMonth)
            ->description($description)
            ->descriptionIcon($icon, IconPosition::Before)
            ->color($color);
    }

    /**
     * Orders whose expected_delivery_date has passed with no
     * actual_delivery_date recorded — i.e. materials that should have
     * arrived but haven't.
     */
    private function overdueDeliveriesStat(): Stat
    {
        $query = fn() => PurchaseOrder::whereDate('expected_delivery_date', '<', now())
            ->whereNull('actual_delivery_date');

        $overdueCount = $query()->count();
        $overdueValue = $query()->sum('grand_total');

        $description = $overdueCount > 0
            ? '₱' . number_format($overdueValue, 2) . ' in overdue orders'
            : 'No overdue deliveries';

        return Stat::make('Overdue Deliveries', $overdueCount)
            ->description($description)
            ->descriptionIcon(
                $overdueCount > 0 ? 'heroicon-m-exclamation-triangle' : 'heroicon-m-check-circle',
                IconPosition::Before
            )
            ->color($overdueCount > 0 ? 'danger' : 'success');
    }

    /**
     * Of orders that have actually been delivered, what percentage arrived
     * on or before their expected_delivery_date.
     */
    private function onTimeDeliveryRateStat(): Stat
    {
        $deliveredQuery = fn() => PurchaseOrder::whereNotNull('actual_delivery_date');

        $deliveredCount = $deliveredQuery()->count();

        if ($deliveredCount === 0) {
            return Stat::make('On-Time Delivery Rate', 'N/A')
                ->description('No deliveries recorded yet')
                ->descriptionIcon('heroicon-m-minus', IconPosition::Before)
                ->color('gray');
        }

        $onTimeCount = $deliveredQuery()
            ->whereColumn('actual_delivery_date', '<=', 'expected_delivery_date')
            ->count();

        $rate = round(($onTimeCount / $deliveredCount) * 100, 1);

        $color = match (true) {
            $rate >= 80 => 'success',
            $rate >= 50 => 'warning',
            default => 'danger',
        };

        $icon = match (true) {
            $rate >= 80 => 'heroicon-m-check-circle',
            $rate >= 50 => 'heroicon-m-exclamation-circle',
            default => 'heroicon-m-x-circle',
        };

        return Stat::make('On-Time Delivery Rate', $rate . '%')
            ->description($onTimeCount . ' of ' . $deliveredCount . ' delivered orders on time')
            ->descriptionIcon($icon, IconPosition::Before)
            ->color($color);
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
