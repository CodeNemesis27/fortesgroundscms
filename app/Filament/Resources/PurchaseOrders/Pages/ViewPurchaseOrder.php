<?php

namespace App\Filament\Resources\PurchaseOrders\Pages;

use App\Filament\Resources\PurchaseOrders\PurchaseOrderResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewPurchaseOrder extends ViewRecord
{
    protected static string $resource = PurchaseOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('setApproved')
                ->label('Approve')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->visible(fn($record) => is_null($record->approved_by) && is_null($record->approved_date))
                ->requiresConfirmation()
                ->action(function ($record) {
                    $record->update([
                        'approved_by' => auth()->id(),
                        'approved_date' => now(),
                    ]);

                    Notification::make()
                        ->success()
                        ->title('Purchase Order approved!')
                        ->body('The Purchase Order has been approved successfully')
                        ->send();
                }),
            EditAction::make(),
        ];
    }

    public function getHeading(): string
    {
        return 'View ' . $this->record->purchase_order_no;
    }
}
