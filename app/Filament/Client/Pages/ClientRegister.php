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
use Filament\Schemas\Components\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use SensitiveParameter;

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
                            TextInput::make('name')
                                ->label(__('filament-panels::auth/pages/register.form.name.label'))
                                ->required()
                                ->placeholder(__('Enter full name'))
                                ->maxLength(255)
                                ->autofocus(),
                            TextInput::make('contact_no')
                                ->label('Contact No.')
                                ->tel()
                                ->autofocus()
                                ->required()
                                ->placeholder('Enter contact no.')
                                ->maxLength(255),
                            TextInput::make('address')
                                ->required()
                                ->autofocus()
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

    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('email')
            ->label(__('filament-panels::auth/pages/register.form.email.label'))
            ->email()
            ->required()
            ->placeholder(__('juan_delacruz@gmail.com'))
            ->maxLength(255)
            ->unique($this->getUserModel());
    }

    protected function getPasswordFormComponent(): Component
    {
        return TextInput::make('password')
            ->label(__('filament-panels::auth/pages/register.form.password.label'))
            ->password()
            ->revealable(filament()->arePasswordsRevealable())
            ->required()
            ->placeholder('********')
            ->rules([
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
            ])
            ->validationMessages([
                'min' => 'Password must be at least 8 characters.',
            ])
            ->showAllValidationMessages()
            ->dehydrateStateUsing(fn(#[SensitiveParameter] $state) => Hash::make($state))
            ->same('passwordConfirmation')
            ->validationAttribute(__('filament-panels::auth/pages/register.form.password.validation_attribute'));
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
