<?php

namespace App\Filament\Client\Pages;

use Filament\Auth\Pages\Register as BaseRegister;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ClientRegister extends BaseRegister
{
    public function getMaxWidth(): Width|string|null
    {
        return Width::TwoExtraLarge;
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Wizard::make([
                    Step::make('Name and Contact')
                        ->description('Fullname and contact info')
                        ->schema([
                            $this->getNameFormComponent(),
                            TextInput::make('contact_no')
                                ->label('Contact No.')
                                ->tel()
                                ->required()
                                ->placeholder('09156119397')
                                ->maxLength(255),
                            TextInput::make('address')
                                ->required()
                                ->placeholder('Enter complete address')
                                ->columnSpanFull(),
                        ]),
                    Step::make('Credentials')
                        ->description('Login credentials')
                        ->schema([
                            $this->getEmailFormComponent(),
                            $this->getPasswordFormComponent(),
                            $this->getPasswordConfirmationFormComponent(),
                        ])
                ])

            ]);
    }

    protected function handleRegistration(array $data): Model
    {
        return DB::transaction(function () use ($data) {
            // Only pass user-fillable fields up to the parent,
            // otherwise contact_no/address will throw a MassAssignmentException.
            $user = parent::handleRegistration([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
            ]);

            $user->client()->create([
                'contact_no' => $data['contact_no'],
                'address' => $data['address'],
            ]);

            return $user;
        });
    }
}
