<?php

namespace App\Filament\Resources\Vendors\Schemas;

use App\Models\Vendor;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;

class VendorInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make([
                    Section::make('Vendor details')
                        ->description('Identification and contact information for this vendor')
                        ->schema([
                            TextEntry::make('vendor_code')
                                ->weight(FontWeight::SemiBold),
                            TextEntry::make('name')
                                ->weight(FontWeight::SemiBold),
                            TextEntry::make('contact_person')
                                ->weight(FontWeight::SemiBold),
                            TextEntry::make('contact_no')
                                ->label('Contact no.')
                                ->weight(FontWeight::SemiBold),
                            TextEntry::make('email')
                                ->weight(FontWeight::SemiBold)
                                ->label('Email address'),
                            TextEntry::make('vendor_type')
                                ->badge(),
                            TextEntry::make('address')
                                ->weight(FontWeight::SemiBold)
                                ->columnSpanFull(),

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
                                ->visible(fn(Vendor $record): bool => $record->trashed()),
                        ])
                ])->columnSpan(4),

            ])->columns(12);
    }
}
