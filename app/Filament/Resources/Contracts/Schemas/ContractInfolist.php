<?php

namespace App\Filament\Resources\Contracts\Schemas;

use App\Models\Contract;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;

class ContractInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make([
                    Section::make('Contract details')
                        ->description('Contract information, financial terms, payment conditions, and effective dates')
                        ->schema([
                            TextEntry::make('contract_no')
                                ->label('Contract no.')
                                ->weight(FontWeight::SemiBold),
                            TextEntry::make('project.name')
                                ->label('Project reference')
                                ->weight(FontWeight::SemiBold),
                            TextEntry::make('contract_type')
                                ->badge(),
                            TextEntry::make('original_value')
                                ->money('PHP')
                                ->weight(FontWeight::SemiBold),
                            TextEntry::make('current_value')
                                ->money('PHP')
                                ->weight(FontWeight::SemiBold),
                            TextEntry::make('retention_percentage')
                                ->numeric(decimalPlaces: 2)
                                ->suffix('%')
                                ->weight(FontWeight::SemiBold),
                            TextEntry::make('payment_terms')
                                ->weight(FontWeight::SemiBold),
                            TextEntry::make('effective_date')
                                ->date()
                                ->weight(FontWeight::SemiBold),
                            TextEntry::make('status')
                                ->badge(),
                        ])->columns(2)
                ])->columnSpan(8),

                Group::make([
                    Section::make('Metadata')
                        ->description('Record audit trailing')
                        ->schema([
                            TextEntry::make('created_at')
                                ->dateTime('M d, Y h:i A')
                                ->weight(FontWeight::SemiBold)
                                ->placeholder('-'),
                            TextEntry::make('updated_at')
                                ->dateTime('M d, Y h:i A')
                                ->weight(FontWeight::SemiBold)
                                ->placeholder('-'),
                            TextEntry::make('deleted_at')
                                ->dateTime('M d, Y h:i A')
                                ->weight(FontWeight::SemiBold)
                                ->visible(fn(Contract $record): bool => $record->trashed()),
                        ])
                ])->columnSpan(4),

            ])->columns(12);
    }
}
