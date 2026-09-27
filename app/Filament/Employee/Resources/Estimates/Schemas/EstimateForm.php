<?php

namespace App\Filament\Employee\Resources\Estimates\Schemas;

use App\Models\Material;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Number;

class EstimateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Estimate details')
                    ->description('Estimates information and status')
                    ->schema([
                        Select::make('project_id')
                            ->required()
                            ->label('Project reference')
                            ->relationship('project', 'name')
                            ->searchable()
                            ->preload(),
                        TextInput::make('estimate_no')
                            ->label('Estimate no.')
                            ->default(fn() => 'ES-' . now()->format('dmy-Hi'))
                            ->dehydrated()
                            ->disabled(),
                        Textarea::make('notes')
                            ->columnSpanFull()
                            ->placeholder('Short notes about the estimates...'),
                        ToggleButtons::make('status')
                            ->inline()
                            ->options([
                                'Draft' => 'Draft',
                                'Submitted' => 'Submitted',
                                'Approved' => 'Approved',
                                'Superseded' => 'Superseded',
                            ])
                            ->required(),

                    ])->columns(2),

                Section::make('Material items')
                    ->description('Materials for the estimates')
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

                Hidden::make('prepared_by')
                    ->default(fn() => Auth::id()),
            ])->columns(1);
    }
}
