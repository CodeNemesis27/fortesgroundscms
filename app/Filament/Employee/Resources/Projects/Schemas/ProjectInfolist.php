<?php

namespace App\Filament\Employee\Resources\Projects\Schemas;

use App\Models\Project;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;

class ProjectInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make([
                    Section::make('Project details')
                        ->description('Basic information identifying the project and its client')
                        ->schema([
                            TextEntry::make('project_code')
                                ->weight(FontWeight::SemiBold),
                            TextEntry::make('name')
                                ->weight(FontWeight::SemiBold),
                            TextEntry::make('description')
                                ->weight(FontWeight::SemiBold)
                                ->columnSpanFull(),
                            TextEntry::make('client.user.name')
                                ->weight(FontWeight::SemiBold)
                                ->label('Client'),
                            TextEntry::make('project_type')
                                ->badge(),

                        ])->columns(2),

                    Section::make('Location and schedule')
                        ->description('Where the project is located and its timeline')
                        ->schema([
                            TextEntry::make('site_address')
                                ->columnSpanFull()
                                ->weight(FontWeight::SemiBold),
                            TextEntry::make('start_date')
                                ->date()
                                ->weight(FontWeight::SemiBold),
                            TextEntry::make('expected_end_date')
                                ->date()
                                ->weight(FontWeight::SemiBold),
                            TextEntry::make('actual_end_date')
                                ->date()
                                ->weight(FontWeight::SemiBold)
                                ->placeholder('-'),

                        ])->columns(3)
                ])->columnSpan(8),

                Group::make([
                    Section::make('Status and financials')
                        ->description('Current progress and contract value')
                        ->schema([
                            TextEntry::make('contract_value')
                                ->money('PHP')
                                ->weight(FontWeight::SemiBold),
                            TextEntry::make('status')
                                ->weight(FontWeight::SemiBold)
                                ->badge(),
                        ]),

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
                                ->visible(fn(Project $record): bool => $record->trashed()),
                        ])
                ])->columnSpan(4),
            ])->columns(12);
    }
}
