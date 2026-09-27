<?php

namespace App\Filament\Resources\Documents\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class DocumentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Document details')
                    ->schema([
                        Hidden::make('owner_id')
                            ->default(fn() => Auth::id()),
                        TextInput::make('title')
                            ->unique()
                            ->required()
                            ->placeholder('e.g. Residential Contract')
                            ->maxLength(255),
                        Select::make('category')
                            ->required()
                            ->options([
                                'Architectural Plan' => 'Architectural Plan',
                                'Bill of Quantity' => 'Bill of Quantity',
                                'Contract' => 'Contract',
                                'Draft' => 'Draft',
                                'Invoice' => 'Invoice',
                                'Policy' => 'Policy',
                                'Report' => 'Report',
                                'Other' => 'Other',
                            ])
                            ->native(false),
                        Textarea::make('description')
                            ->placeholder('Short description about the document...')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('File')
                    ->description('The file is encrypted at rest immediately after upload. ' . 'Only the owner and users it has explicitly been shared with can decrypt and download it.')
                    ->schema([
                        FileUpload::make('document_upload')
                            ->label('Document file')
                            ->disk('r2')
                            ->preserveFilenames()
                            ->required(fn(string $operation) => $operation === 'create')
                            ->storeFiles(false)
                            ->disk('public')
                            ->acceptedFileTypes(config('dms.allowed_mime_types'))
                            ->maxSize(config('dms.max_upload_size_kb'))
                            ->helperText('Uploading a new file on an existing document creates a new version — the previous version is preserved, never overwritten.'),
                    ]),
            ]);
    }
}
