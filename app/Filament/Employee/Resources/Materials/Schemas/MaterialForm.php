<?php

namespace App\Filament\Employee\Resources\Materials\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MaterialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make([
                    Section::make('Material details')
                        ->description('Material name, unit and pricing')
                        ->schema([
                            TextInput::make('name')
                                ->required()
                                ->columnSpanFull()
                                ->placeholder('e.g. Portland Cement'),
                            TextInput::make('unit')
                                ->required()
                                ->placeholder('e.g. bag, piece, meter, kg'),
                            TextInput::make('price')
                                ->required()
                                ->placeholder('0')
                                ->numeric()
                                ->prefix('PHP'),
                            Select::make('category')
                                ->required()
                                ->options([
                                    'Concrete and Cement Products' => 'Concrete and Cement Products',
                                    'Doors, Windows and Glazing' => 'Doors, Windows and Glazing',
                                    'Electrical Materials' => 'Electrical Materials',
                                    'Finishes' => 'Finishes',
                                    'Lumber and Carpentry' => 'Lumber and Carpentry',
                                    'Masonry and Aggregates' => 'Masonry and Aggregates',
                                    'Plumbing Materials' => 'Plumbing Materials',
                                    'Roofing and Waterproofing' => 'Roofing and Waterproofing',
                                    'Site and Earthworks'
                                ])
                                ->native(false),
                            Select::make('vendor_id')
                                ->required()
                                ->relationship('vendor', 'name')
                                ->searchable()
                                ->preload()
                        ])->columns(2)
                ])->columnSpan(7),

                Group::make([
                    Section::make('Image')
                        ->description('Material image')
                        ->schema([
                            FileUpload::make('image')
                                ->disk('r2')
                                ->preserveFilenames()
                        ]),
                ])->columnSpan(5),

            ])->columns(12);
    }
}
