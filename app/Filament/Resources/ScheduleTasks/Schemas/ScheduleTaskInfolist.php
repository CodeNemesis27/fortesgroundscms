<?php

namespace App\Filament\Resources\ScheduleTasks\Schemas;

use App\Models\ScheduleTask;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;

class ScheduleTaskInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make([
                    Section::make('Schedule task details')
                        ->description('Basic information identifying the task, its timeline, and the employee assigned to it')
                        ->schema([
                            TextEntry::make('project.name')
                                ->weight(FontWeight::SemiBold)
                                ->label('Project reference'),
                            TextEntry::make('name')
                                ->weight(FontWeight::SemiBold),
                            TextEntry::make('task_type')
                                ->badge(),
                            TextEntry::make('planned_start_date')
                                ->date()
                                ->weight(FontWeight::SemiBold),
                            TextEntry::make('planned_end_date')
                                ->date()
                                ->weight(FontWeight::SemiBold),
                            TextEntry::make('actual_start_date')
                                ->date()
                                ->placeholder('-')
                                ->weight(FontWeight::SemiBold),
                            TextEntry::make('actual_end_date')
                                ->date()
                                ->placeholder('-')
                                ->weight(FontWeight::SemiBold),
                            TextEntry::make('duration'),
                            TextEntry::make('employee.user.name')
                                ->label('Assigned employee')
                                ->weight(FontWeight::SemiBold),
                        ])->columns(2)
                ])->columnSpan(8),

                Group::make([
                    Section::make('Metadata')
                        ->description('Record audit trailing')
                        ->schema([
                            TextEntry::make('created_at')
                                ->dateTime('M d, Y h:i A')
                                ->placeholder('-'),
                            TextEntry::make('updated_at')
                                ->dateTime('M d, Y h:i A')
                                ->placeholder('-'),
                            TextEntry::make('deleted_at')
                                ->dateTime('M d, Y h:i A')
                                ->visible(fn(ScheduleTask $record): bool => $record->trashed()),
                        ])

                ])->columnSpan(4),

            ])->columns(12);
    }
}
