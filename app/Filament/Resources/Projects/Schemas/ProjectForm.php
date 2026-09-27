<?php

namespace App\Filament\Resources\Projects\Schemas;

use App\Models\Client;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make()
                    ->schema([
                        Section::make('Project details')
                            ->description('Basic information identifying the project and its client')
                            ->columnSpanFull()
                            ->columns(2)
                            ->schema([
                                TextInput::make('project_code')
                                    ->dehydrated()
                                    ->columnSpan(4)
                                    ->default(fn() => 'PRJ-' . now()->format('dmy-Hi'))
                                    ->disabled(),
                                TextInput::make('name')
                                    ->required()
                                    ->placeholder('e.g. Riverside residential renovation')
                                    ->columnSpan(8),
                                Textarea::make('description')
                                    ->required()
                                    ->placeholder('Description about the project...')
                                    ->columnSpanFull(),
                                Select::make('client_id')
                                    ->required()
                                    ->columnSpan(6)
                                    ->relationship(
                                        name: 'client',
                                        modifyQueryUsing: fn(Builder $query) => $query->with('user'),
                                    )
                                    ->getOptionLabelFromRecordUsing(fn(Client $record) => $record->user?->name)
                                    ->searchable()
                                    ->preload(),
                                Select::make('project_type')
                                    ->columnSpan(6)
                                    ->options([
                                        'New build' => 'New build',
                                        'Renovation' => 'Renovation',
                                        'Infrastructure' => 'Infrastructure',
                                        'Maintenance' => 'Maintenance',
                                    ])
                                    ->native(false)
                                    ->required(),
                            ])->columns(12),

                        Section::make('Location and schedule')
                            ->description('Where the project is located and its timeline')
                            ->columnSpanFull()
                            ->columns(3)
                            ->schema([
                                TextInput::make('site_address')
                                    ->required()
                                    ->placeholder('Enter site address of the project')
                                    ->columnSpanFull(),
                                DatePicker::make('start_date')
                                    ->required()
                                    ->placeholder('Select date'),
                                DatePicker::make('expected_end_date')
                                    ->required()
                                    ->placeholder('Select date'),

                            ])->columns(2),

                    ])->columnSpan(3),

                Group::make()
                    ->schema([
                        Section::make('Status and financials')
                            ->description('Current progress and contract value')
                            ->columnSpanFull()
                            ->columns(2)
                            ->schema([
                                TextInput::make('contract_value')
                                    ->required()
                                    ->columnSpanFull()
                                    ->placeholder('0')
                                    ->numeric()
                                    ->prefix('₱'),
                                ToggleButtons::make('status')
                                    ->options([
                                        'Bidding' => 'Bidding',
                                        'Planning' => 'Planning',
                                        'In progress' => 'In progress',
                                        'On hold' => 'On hold',
                                        'Substantially complete' => 'Substantially complete',
                                        'Cancelled' => 'Cancelled',
                                    ])
                                    ->columnSpanFull()
                                    ->inline()
                                    ->required(),

                            ]),
                    ])->columnSpan(2),



            ])->columns(5);
    }
}
