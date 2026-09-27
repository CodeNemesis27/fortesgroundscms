<?php

namespace App\Filament\Resources\Clients\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;

class ClientInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make([
                    Section::make('Client information')
                        ->description('Name and contact information for this client')
                        ->schema([
                            TextEntry::make('user.name')
                                ->weight(FontWeight::SemiBold),
                            TextEntry::make('contact_no')
                                ->label('Contact no.')
                                ->weight(FontWeight::SemiBold),
                            TextEntry::make('user.email')
                                ->weight(FontWeight::SemiBold)
                                ->label('Email address'),
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
                                ->dateTime()
                                ->placeholder('-'),
                            TextEntry::make('updated_at')
                                ->weight(FontWeight::SemiBold)
                                ->dateTime()
                                ->placeholder('-'),
                            TextEntry::make('deleted_at')
                                ->weight(FontWeight::SemiBold)
                                ->dateTime()
                                ->placeholder('-'),
                        ])
                ])->columnSpan(4),

            ])->columns(12);
    }
}
