<?php

namespace App\Filament\Resources\Announcements\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AnnouncementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        TextInput::make('title')
                            ->placeholder('e.g. Board Meeting')
                            ->required(),
                        ColorPicker::make('background_color')
                            ->label('Banner background color')
                            ->default('#f59e0b'),
                        RichEditor::make('content')
                            ->columnSpanFull()
                            ->placeholder('Content of the announcement to be displayed in the topbar...')
                            ->required()
                            ->toolbarButtons([
                                ['bold', 'underline'],
                            ])
                            ->maxLength(250),
                        Toggle::make('is_featured')
                            ->label('Set as featured')
                            ->helperText('Turning this on will replace current featured announcement')
                            ->inline(false),
                    ])->columns(2)

            ])->columns(1);
    }
}
