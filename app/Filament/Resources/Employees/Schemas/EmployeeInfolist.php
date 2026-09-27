<?php

namespace App\Filament\Resources\Employees\Schemas;

use App\Models\Employee;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;

class EmployeeInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make([
                    Section::make('Employee details')
                        ->description('Basic information of the employee')
                        ->schema([
                            TextEntry::make('employee_code')
                                ->weight(FontWeight::SemiBold),
                            TextEntry::make('first_name')
                                ->weight(FontWeight::SemiBold),
                            TextEntry::make('last_name')
                                ->weight(FontWeight::SemiBold),
                            TextEntry::make('position')
                                ->weight(FontWeight::SemiBold),
                            TextEntry::make('employee_type')
                                ->badge(),
                            TextEntry::make('date_hired')
                                ->weight(FontWeight::SemiBold)
                                ->date(),
                            TextEntry::make('date_terminated')
                                ->weight(FontWeight::SemiBold)
                                ->date()
                                ->placeholder('-'),
                            TextEntry::make('basic_rate')
                                ->weight(FontWeight::SemiBold)
                                ->money('PHP'),
                            TextEntry::make('rate_type')
                                ->badge(),
                            TextEntry::make('contact_no')
                                ->label('Contact no.')
                                ->weight(FontWeight::SemiBold),
                            TextEntry::make('status')
                                ->badge(),
                            TextEntry::make('address')
                                ->weight(FontWeight::SemiBold)
                                ->columnSpanFull(),

                        ])->columns(3)
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
                                ->weight(FontWeight::SemiBold)
                                ->dateTime('M d, Y h:i A')
                                ->visible(fn(Employee $record): bool => $record->trashed()),
                        ])
                ])->columnSpan(4)

            ])->columns(12);
    }
}
