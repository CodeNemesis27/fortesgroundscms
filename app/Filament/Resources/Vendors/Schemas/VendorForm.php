<?php

namespace App\Filament\Resources\Vendors\Schemas;

use App\Models\Vendor;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class VendorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Vendor details')
                    ->description('Vendor’s business and contact information')
                    ->schema([
                        Hidden::make('vendor_code')
                            ->dehydrated()
                            ->default(function () {
                                $lastVendor = Vendor::orderByDesc('id')->first();

                                if (!$lastVendor) {
                                    return 'V-001';
                                }

                                $lastNumber = (int) str_replace('V-', '', $lastVendor->vendor_code);

                                return 'V-' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
                            })
                            ->disabled(),
                        TextInput::make('name')
                            ->required()
                            ->placeholder('e.g. Wilcon Construction Supplies'),
                        TextInput::make('contact_person')
                            ->required()
                            ->placeholder('e.g. Juan Dela Cruz'),
                        TextInput::make('contact_no')
                            ->required()
                            ->placeholder('e.g. 09171234567'),
                        TextInput::make('email')
                            ->label('Email address')
                            ->email()
                            ->placeholder('wilcons_constructions@gmail.com')
                            ->required(),
                        TextInput::make('address')
                            ->required()
                            ->placeholder('e.g. J.P. Laurel Ave., Davao City'),
                        Select::make('vendor_type')
                            ->native(false)
                            ->options([
                                'Material supplier' => 'Material supplier',
                                'Service provider' => 'Service provider',
                                'Equipment rental' => 'Equipment rental',
                            ])
                            ->required(),

                    ])->columns(2)

            ])->columns(1);
    }
}
