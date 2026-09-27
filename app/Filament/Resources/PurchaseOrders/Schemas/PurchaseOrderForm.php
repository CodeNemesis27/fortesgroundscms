<?php

namespace App\Filament\Resources\PurchaseOrders\Schemas;

use App\Models\Material;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Number;

class PurchaseOrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Purchase order details')
                    ->description('Purchase order details, timeline, and delivery address')
                    ->schema([
                        Select::make('project_id')
                            ->required()
                            ->label('Project reference')
                            ->relationship('project', 'name')
                            ->searchable()
                            ->preload(),
                        TextInput::make('purchase_order_no')
                            ->label('Purchase order no.')
                            ->dehydrated()
                            ->default(fn() => 'PO-' . now()->format('dmy-Hi'))
                            ->disabled(),
                        DatePicker::make('expected_delivery_date')
                            ->required(),
                        DatePicker::make('actual_delivery_date'),
                        TextInput::make('delivery_address')
                            ->placeholder('Enter purchase order delivery address')
                            ->required()
                            ->columnSpanFull(),

                    ])->columns(2),


                Section::make('Material items')
                    ->description('Materials for the purchase order')
                    ->schema([
                        Repeater::make('materials')
                            ->hiddenLabel()
                            ->addActionLabel('Add material')
                            ->relationship()
                            ->schema([
                                Select::make('material_id')
                                    ->relationship('material', 'name')
                                    ->searchable()
                                    ->columnSpan(6)
                                    ->preload()
                                    ->required()
                                    ->distinct()
                                    ->placeholder('Select material')
                                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                    ->reactive()
                                    ->afterStateUpdated(fn($state, Set $set) => $set('unit_cost', Material::find($state)?->price ?? 0))
                                    ->afterStateUpdated(fn($state, Set $set) => $set('subtotal', Material::find($state)?->price ?? 0)),

                                TextInput::make('quantity')
                                    ->numeric()
                                    ->columnSpan(2)
                                    ->default(1)
                                    ->minValue(1)
                                    ->reactive()
                                    ->afterStateUpdated(fn($state, Set $set, Get $get) => $set('subtotal', $state * $get('unit_cost'))),

                                TextInput::make('unit_cost')
                                    ->numeric()
                                    ->placeholder('0.00')
                                    ->disabled()
                                    ->prefix('PHP')
                                    ->columnSpan(3)
                                    ->dehydrated()
                                    ->reactive()
                                    ->afterStateHydrated(function (Set $set, Get $get) {
                                        $materialId = $get('material_id');
                                        if ($materialId) {
                                            $material = Material::find($materialId);
                                            if ($material) {
                                                $set('unit_cost', $material->price);
                                            }
                                        }
                                    }),

                                TextInput::make('subtotal')
                                    ->numeric()
                                    ->prefix('PHP')
                                    ->disabled()
                                    ->dehydrated()
                                    ->columnSpan(4)

                            ])->columns(15)
                            ->reorderable(true),

                        TextEntry::make('grand_total_placeholder')
                            ->label('Grand total')
                            ->columnSpanFull()
                            ->state(function (Get $get, Set $set) {
                                $total = 0;
                                if (!$repeaters = $get('materials')) {
                                    return $total;
                                }

                                foreach ($repeaters as $key => $repeater) {
                                    $total += $get("materials.{$key}.subtotal");
                                }

                                $set('grand_total', $total);
                                return Number::currency($total, 'PHP');
                            }),

                        Hidden::make('grand_total')
                            ->default(0)

                    ])->columnSpanFull(),

            ])->columns(1);
    }
}
