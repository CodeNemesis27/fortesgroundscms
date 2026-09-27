<?php

namespace App\Filament\Resources\PurchaseOrders\Pages;

use App\Filament\Concerns\RegistersPdfFonts;
use App\Filament\Resources\PurchaseOrders\PurchaseOrderResource;
use App\Filament\Resources\PurchaseOrders\Widgets\PurchaseOrderResourceStat;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPurchaseOrders extends ListRecords
{
    use RegistersPdfFonts;

    protected static string $resource = PurchaseOrderResource::class;

    protected ?string $subheading = 'Track purchase orders, deliveries, and approval status';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            PurchaseOrderResourceStat::class,
        ];
    }
}
