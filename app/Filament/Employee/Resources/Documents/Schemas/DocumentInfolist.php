<?php

namespace App\Filament\Employee\Resources\Documents\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;

class DocumentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Document details')
                    ->description('Title, category, and current status of the document')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('title')
                            ->weight(FontWeight::SemiBold),
                        TextEntry::make('category')
                            ->weight(FontWeight::SemiBold),
                        TextEntry::make('description')
                            ->placeholder('-')
                            ->weight(FontWeight::SemiBold)
                            ->columnSpanFull(),
                        TextEntry::make('status')
                            ->badge()
                            ->color(fn(string $state) => match ($state) {
                                'active' => 'success',
                                'archived' => 'gray',
                                default => 'info',
                            }),
                    ]),

                Section::make('File details')
                    ->description('Current version, file, and who owns it')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('currentVersion.original_filename')
                            ->label('File name')
                            ->columnSpanFull()
                            ->weight(FontWeight::SemiBold),
                        TextEntry::make('currentVersion.version_number')
                            ->label('Version')
                            ->weight(FontWeight::SemiBold)
                            ->formatStateUsing(fn($state) => $state ? "v{$state}" : '—'),
                        TextEntry::make('owner.name')
                            ->label('Owner')
                            ->weight(FontWeight::SemiBold),
                        TextEntry::make('updated_at')
                            ->weight(FontWeight::SemiBold)
                            ->label('Last modified')
                            ->dateTime(),
                    ]),
            ]);
    }
}
