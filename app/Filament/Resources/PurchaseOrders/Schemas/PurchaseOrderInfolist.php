<?php

namespace App\Filament\Resources\PurchaseOrders\Schemas;

use App\Models\PurchaseOrder;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;

class PurchaseOrderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make([
                    Section::make('Purchase order details')
                        ->description('Purchase order details, timeline, and delivery address')
                        ->schema([
                            TextEntry::make('project.name')
                                ->label('Project reference')
                                ->weight(FontWeight::SemiBold),
                            TextEntry::make('purchase_order_no')
                                ->label('Purchase order no.')
                                ->weight(FontWeight::SemiBold),
                            TextEntry::make('expected_delivery_date')
                                ->date()
                                ->weight(FontWeight::SemiBold),
                            TextEntry::make('actual_delivery_date')
                                ->date()
                                ->weight(FontWeight::SemiBold)
                                ->placeholder('-'),
                            TextEntry::make('delivery_address')
                                ->columnSpanFull()
                                ->weight(FontWeight::SemiBold),
                            TextEntry::make('grand_total')
                                ->money('PHP')
                                ->weight(FontWeight::SemiBold),
                            TextEntry::make('approvedBy.name')
                                ->label('Approved by')
                                ->placeholder('-')
                                ->weight(FontWeight::SemiBold),
                            TextEntry::make('approved_date')
                                ->date()
                                ->placeholder('-')
                                ->weight(FontWeight::SemiBold),
                        ])->columns(2)
                ])->columnSpan(8),

                Group::make([
                    Section::make('Metadata')
                        ->description('Record audit trailing')
                        ->schema([
                            TextEntry::make('created_at')
                                ->weight(FontWeight::SemiBold)
                                ->dateTime('M d, Y h:i A')
                                ->placeholder('-'),
                            TextEntry::make('updated_at')
                                ->weight(FontWeight::SemiBold)
                                ->dateTime('M d, Y h:i A')
                                ->placeholder('-'),
                            TextEntry::make('deleted_at')
                                ->weight(FontWeight::SemiBold)
                                ->dateTime('M d, Y h:i A')
                                ->visible(fn(PurchaseOrder $record): bool => $record->trashed()),
                        ])
                ])->columnSpan(4),

            ])->columns(12);
    }
}
