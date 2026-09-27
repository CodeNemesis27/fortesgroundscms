<?php

namespace App\Filament\Resources\Estimates\Schemas;

use App\Models\Estimate;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\RepeatableEntry\TableColumn;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;

class EstimateInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make([
                    Section::make('Estimate details')
                        ->description('Estimates information and status')
                        ->schema([
                            TextEntry::make('project.name')
                                ->label('Project reference')
                                ->weight(FontWeight::SemiBold),
                            TextEntry::make('estimate_no')
                                ->label('Estimate no.')
                                ->weight(FontWeight::SemiBold),
                            TextEntry::make('grand_total')
                                ->weight(FontWeight::SemiBold)
                                ->money('PHP'),
                            TextEntry::make('status')
                                ->badge(),
                            TextEntry::make('notes')
                                ->weight(FontWeight::SemiBold)
                                ->placeholder('-')
                                ->columnSpanFull(),

                        ])->columns(2)
                ])->columnSpan(8),

                Group::make([
                    Section::make('Metadata')
                        ->description('Record audit trailing')
                        ->schema([
                            TextEntry::make('preparedBy.name')
                                ->label('Prepared by')
                                ->weight(FontWeight::SemiBold),
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
                                ->visible(fn(Estimate $record): bool => $record->trashed()),
                        ])
                ])->columnSpan(4),

                Section::make('Materials')
                    ->schema([
                        RepeatableEntry::make('materials')
                            ->hiddenLabel()
                            ->schema([
                                TextEntry::make('material.name')
                                    ->label('Material')
                                    ->weight('bold'),
                                TextEntry::make('quantity'),
                                TextEntry::make('unit_cost')
                                    ->label('Unit cost')
                                    ->money('PHP'),
                                TextEntry::make('subtotal')
                                    ->money('PHP'),
                            ])
                            ->columns(4)
                            ->columnSpanFull(),
                    ])->columnSpanFull()


            ])->columns(12);
    }
}
