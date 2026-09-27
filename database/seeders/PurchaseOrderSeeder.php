<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Material;
use App\Models\Project;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PurchaseOrderSeeder extends Seeder
{
    /**
     * Creates 20 purchase orders, each with 2-5 line items. Items are
     * seeded here (not in a separate seeder) because purchase_order_items
     * needs the parent purchase_order's real ID, and the parent's
     * grand_total is only known once its items exist.
     */
    public function run(): void
    {
        $projectAddresses = Project::query()->pluck('site_address', 'id');
        $materialIds = Material::query()->pluck('id')->all();
        $employeeIds = Employee::query()->pluck('id')->all();

        if ($projectAddresses->isEmpty()) {
            $this->command?->warn('No projects found — run ProjectSeeder before PurchaseOrderSeeder.');
            return;
        }

        if (empty($materialIds)) {
            $this->command?->warn('No materials found — run MaterialSeeder before PurchaseOrderSeeder.');
            return;
        }

        $projectIds = $projectAddresses->keys()->all();

        for ($i = 1; $i <= 20; $i++) {
            $projectId = $projectIds[array_rand($projectIds)];
            $deliveryAddress = $projectAddresses->get($projectId);

            // Spread expected delivery dates from 60 days ago to 45 days
            // ahead, so we get a realistic mix of delivered / still-pending orders.
            $expectedDeliveryDate = now()->copy()->addDays(mt_rand(-60, 45));

            [$actualDeliveryDate, $approvedBy, $approvedDate] = $this->deliveryAndApprovalDetails(
                $expectedDeliveryDate,
                $employeeIds
            );

            $purchaseOrder = PurchaseOrder::create([
                'project_id' => $projectId,
                'purchase_order_no' => 'PO-' . Carbon::now()->addMinutes($i)->format('mdy-Hi'),
                'expected_delivery_date' => $expectedDeliveryDate->toDateString(),
                'actual_delivery_date' => $actualDeliveryDate,
                'delivery_address' => $deliveryAddress,
                'grand_total' => 0, // placeholder, filled in below once items exist
                'approved_by' => $approvedBy,
                'approved_date' => $approvedDate,
            ]);

            $grandTotal = $this->seedItemsFor($purchaseOrder, $materialIds);

            $purchaseOrder->update(['grand_total' => $grandTotal]);
        }
    }

    /**
     * Work out delivery and approval fields for one purchase order.
     *
     * - actual_delivery_date is only set once expected_delivery_date has passed.
     * - approved_by/approved_date are set for ~75% of orders (some still awaiting sign-off).
     *
     * @return array{0: ?string, 1: ?int, 2: ?string}
     */
    private function deliveryAndApprovalDetails(Carbon $expectedDeliveryDate, array $employeeIds): array
    {
        $today = now();

        $actualDeliveryDate = $expectedDeliveryDate->lte($today)
            ? $expectedDeliveryDate->copy()->addDays(mt_rand(-2, 5))->toDateString()
            : null;

        if (empty($employeeIds) || mt_rand(1, 100) > 75) {
            return [$actualDeliveryDate, null, null];
        }

        $approvedBy = $employeeIds[array_rand($employeeIds)];
        $approvedDate = $expectedDeliveryDate->copy()->subDays(mt_rand(3, 15))->toDateString();

        return [$actualDeliveryDate, $approvedBy, $approvedDate];
    }

    /**
     * Create 2-5 line items for the given purchase order and return the
     * resulting grand total (sum of item subtotals).
     */
    private function seedItemsFor(PurchaseOrder $purchaseOrder, array $materialIds): float
    {
        $itemCount = min(count($materialIds), mt_rand(2, 5));
        $selectedMaterialIds = collect($materialIds)->random($itemCount)->all();

        $grandTotal = 0.0;

        foreach ($selectedMaterialIds as $materialId) {
            $quantity = mt_rand(5, 300);
            $unitCost = round(mt_rand(5000, 350000) / 100, 2); // ₱50.00 - ₱3,500.00 per unit
            $subtotal = round($quantity * $unitCost, 2);

            PurchaseOrderItem::create([
                'purchase_order_id' => $purchaseOrder->id,
                'material_id' => $materialId,
                'quantity' => $quantity,
                'unit_cost' => $unitCost,
                'subtotal' => $subtotal,
            ]);

            $grandTotal += $subtotal;
        }

        return round($grandTotal, 2);
    }
}
