<?php

namespace App\Filament\Resources\Contracts\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ContractForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Contract details')
                    ->description('Contract information, financial terms, payment conditions, and effective dates')
                    ->schema([
                        Select::make('project_id')
                            ->required()
                            ->label('Project reference')
                            ->relationship('project', 'name')
                            ->searchable()
                            ->preload(),
                        TextInput::make('contract_no')
                            ->label('Contract no.')
                            ->default(fn() => 'CON-' . now()->format('dmy-Hi'))
                            ->dehydrated()
                            ->disabled(),
                        Select::make('contract_type')
                            ->native(false)
                            ->options([
                                'Lump sum' => 'Lump sum',
                                'Unit price' => 'Unit price',
                                'Cost plus' => 'Cost plus',
                                'Time and material' => 'Time and material',
                            ])
                            ->required(),
                        TextInput::make('original_value')
                            ->required()
                            ->placeholder('0')
                            ->prefix('PHP')
                            ->numeric(),
                        TextInput::make('current_value')
                            ->required()
                            ->placeholder('0')
                            ->prefix('PHP')
                            ->numeric(),
                        TextInput::make('retention_percentage')
                            ->required()
                            ->default(0.0)
                            ->placeholder('0.0')
                            ->prefix('%')
                            ->numeric(),
                        TextInput::make('payment_terms')
                            ->required()
                            ->placeholder('e.g. 30 days after billing'),
                        DatePicker::make('effective_date')
                            ->required(),
                        ToggleButtons::make('status')
                            ->options([
                                'Draft' => 'Draft',
                                'Active' => 'Active',
                                'Completed' => 'Completed',
                                'Terminated' => 'Terminated',
                            ])
                            ->inline()
                            ->required(),

                    ])->columns(2)

            ])->columns(1);
    }
}
